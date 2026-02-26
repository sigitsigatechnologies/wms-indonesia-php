<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    protected PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'supplier_id', 'date_from', 'date_to']);
        $purchases = $this->purchaseService->getAll($filters);
        $suppliers = Supplier::all();

        return view('admin.purchases.index', compact('purchases', 'suppliers'));
    }

    public function create(): View
    {
        $suppliers = Supplier::all();
        $products = Product::where('is_active', true)->get();

        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        $this->purchaseService->create($validated);

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Pembelian berhasil ditambahkan');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load('supplier', 'items.product');

        return view('admin.purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        $this->purchaseService->delete($purchase);

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Pembelian berhasil dihapus');
    }
}
