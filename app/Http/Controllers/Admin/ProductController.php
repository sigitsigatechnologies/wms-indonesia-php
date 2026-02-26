<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'is_active']);
        $products = $this->productService->getAll($filters);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'selling_price' => 'required|numeric|min:0',
            'average_cost' => 'nullable|numeric|min:0',
            'current_stock' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'category_id' => 'nullable|uuid|exists:categories,id',
        ]);

        $this->productService->create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product): View
    {
        $product->load('purchaseItems', 'saleItems', 'stockMovements', 'category');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'selling_price' => 'required|numeric|min:0',
            'average_cost' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'category_id' => 'nullable|uuid|exists:categories,id',
        ]);

        $this->productService->update($product, $validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
