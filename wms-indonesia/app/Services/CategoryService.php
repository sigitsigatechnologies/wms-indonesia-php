<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Category::query();

        if (isset($filters['search']) && $filters['search']) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            return Category::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);
        });
    }

    public function update(Category $category, array $data): Category
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            return $category->fresh();
        });
    }

    public function delete(Category $category): void
    {
        DB::transaction(function () use ($category) {
            $category->delete();
        });
    }
}
