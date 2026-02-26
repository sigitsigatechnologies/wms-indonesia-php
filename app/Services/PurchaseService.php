<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseService
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Purchase::with('supplier');

        if (isset($filters['search']) && $filters['search']) {
            $query->where('invoice_number', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['supplier_id']) && $filters['supplier_id']) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('purchase_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('purchase_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            $invoiceNumber = $this->generateInvoiceNumber();

            $purchase = Purchase::create([
                'invoice_number' => $invoiceNumber,
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'] ?? now(),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = floatval($item['quantity']);
                $costPrice = floatval($item['cost_price']);
                $subtotal = $quantity * $costPrice;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'cost_price' => $costPrice,
                    'subtotal' => $subtotal,
                ]);

                // Update stock
                $this->productService->updateStock($product, $quantity, 'in');

                // Record stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'reference_type' => 'purchase',
                    'reference_id' => $purchase->id,
                    'movement_type' => 'in',
                    'quantity' => $quantity,
                    'stock_before' => $product->current_stock - $quantity,
                    'stock_after' => $product->current_stock,
                ]);

                // Update average cost
                $newAverageCost = (($product->average_cost * $product->current_stock) + ($costPrice * $quantity)) / ($product->current_stock + $quantity);
                $product->update(['average_cost' => $newAverageCost]);

                $totalAmount += $subtotal;
            }

            $purchase->update(['total_amount' => $totalAmount]);

            return $purchase->fresh(['supplier', 'items.product']);
        });
    }

    public function delete(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $item) {
                $product = $item->product;
                
                // Reverse stock
                $this->productService->updateStock($product, $item->quantity, 'out');

                // Record stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'reference_type' => 'purchase_delete',
                    'reference_id' => $purchase->id,
                    'movement_type' => 'out',
                    'quantity' => $item->quantity,
                    'stock_before' => $product->current_stock + $item->quantity,
                    'stock_after' => $product->current_stock,
                ]);

                $item->delete();
            }

            $purchase->delete();
        });
    }

    protected function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $lastPurchase = Purchase::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = intval(substr($lastPurchase->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "PO-{$date}-{$newNumber}";
    }
}
