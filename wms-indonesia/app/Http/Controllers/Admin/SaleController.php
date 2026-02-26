<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'payment_method', 'date_from', 'date_to']);
        $sales = $this->saleService->getAll($filters);
        $stats = $this->saleService->getStats();

        return view('admin.sales.index', compact('sales', 'stats'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)
            ->where('current_stock', '>', 0)
            ->get();

        return view('admin.sales.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:cash,transfer,qris',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.selling_price' => 'required|numeric|min:0',
        ]);

        try {
            $this->saleService->create($validated);

            return redirect()->route('admin.sales.index')
                ->with('success', 'Penjualan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function show(Sale $sale): View
    {
        $sale->load('items.product');

        return view('admin.sales.show', compact('sale'));
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $this->saleService->delete($sale);

        return redirect()->route('admin.sales.index')
            ->with('success', 'Penjualan berhasil dihapus');
    }
}
