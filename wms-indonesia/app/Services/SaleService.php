<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleService
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Sale::query();

        if (isset($filters['search']) && $filters['search']) {
            $query->where('invoice_number', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['payment_method']) && $filters['payment_method']) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $invoiceNumber = $this->generateInvoiceNumber();

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'total_amount' => 0,
                'total_profit' => 0,
                'payment_method' => $data['payment_method'] ?? 'cash',
            ]);

            $totalAmount = 0;
            $totalProfit = 0;

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = floatval($item['quantity']);
                $sellingPrice = floatval($item['selling_price']);
                $costPriceSnapshot = floatval($product->average_cost);
                $profit = ($sellingPrice - $costPriceSnapshot) * $quantity;
                $subtotal = $sellingPrice * $quantity;

                // Check stock
                if ($product->current_stock < $quantity) {
                    throw new \Exception("Stock tidak cukup untuk produk {$product->name}. Stok tersedia: {$product->current_stock}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'selling_price' => $sellingPrice,
                    'cost_price_snapshot' => $costPriceSnapshot,
                    'profit' => $profit,
                    'subtotal' => $subtotal,
                ]);

                // Update stock
                $this->productService->updateStock($product, $quantity, 'out');

                // Record stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                    'movement_type' => 'out',
                    'quantity' => $quantity,
                    'stock_before' => $product->current_stock + $quantity,
                    'stock_after' => $product->current_stock,
                ]);

                $totalAmount += $subtotal;
                $totalProfit += $profit;
            }

            $sale->update([
                'total_amount' => $totalAmount,
                'total_profit' => $totalProfit,
            ]);

            return $sale->fresh(['items.product']);
        });
    }

    public function delete(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = $item->product;
                
                // Reverse stock
                $this->productService->updateStock($product, $item->quantity, 'in');

                // Record stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'reference_type' => 'sale_delete',
                    'reference_id' => $sale->id,
                    'movement_type' => 'in',
                    'quantity' => $item->quantity,
                    'stock_before' => $product->current_stock - $item->quantity,
                    'stock_after' => $product->current_stock,
                ]);

                $item->delete();
            }

            $sale->delete();
        });
    }

    public function getStats(): array
    {
        $today = today();
        
        $todaySales = Sale::whereDate('created_at', $today)->sum('total_amount');
        $todayProfit = Sale::whereDate('created_at', $today)->sum('total_profit');
        $todayTransactions = Sale::whereDate('created_at', $today)->count();

        $monthSales = Sale::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->sum('total_amount');
        $monthProfit = Sale::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->sum('total_profit');

        return [
            'today_sales' => $todaySales,
            'today_profit' => $todayProfit,
            'today_transactions' => $todayTransactions,
            'month_sales' => $monthSales,
            'month_profit' => $monthProfit,
        ];
    }

    protected function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $time = now()->format('His');
        
        // Use unique ID + random number to ensure uniqueness
        $uniqueId = strtoupper(Str::random(4));
        $random = rand(1000, 9999);
        
        return "SO-{$date}-{$time}-{$uniqueId}{$random}";
    }
}
