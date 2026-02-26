<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Product::query();

        if (isset($filters['search']) && $filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('barcode', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            return Product::create([
                'barcode' => $data['barcode'],
                'name' => $data['name'],
                'unit' => $data['unit'] ?? null,
                'selling_price' => $data['selling_price'],
                'average_cost' => $data['average_cost'] ?? 0,
                'current_stock' => $data['current_stock'] ?? 0,
                'min_stock' => $data['min_stock'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
                'category_id' => $data['category_id'] ?? null,
            ]);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update([
                'barcode' => $data['barcode'],
                'name' => $data['name'],
                'unit' => $data['unit'] ?? null,
                'selling_price' => $data['selling_price'],
                'average_cost' => $data['average_cost'] ?? 0,
                'min_stock' => $data['min_stock'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
                'category_id' => $data['category_id'] ?? null,
            ]);

            return $product->fresh();
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $product->delete();
        });
    }

    public function updateStock(Product $product, float $quantity, string $type): void
    {
        DB::transaction(function () use ($product, $quantity, $type) {
            $currentStock = $product->current_stock;
            
            if ($type === 'in') {
                $newStock = $currentStock + $quantity;
            } else {
                $newStock = $currentStock - $quantity;
            }

            $product->update(['current_stock' => $newStock]);
        });
    }

    public function getLowStock(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::whereRaw('current_stock <= min_stock')
            ->where('is_active', true)
            ->get();
    }
}
