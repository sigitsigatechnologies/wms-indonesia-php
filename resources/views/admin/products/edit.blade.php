@extends('layouts.app')

@section('title', 'Edit Produk - WMS Indonesia')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-900">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Produk</h1>
    <p class="text-gray-600 mt-2">Perbarui data produk</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Barcode</label>
                <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" required
                    class="w-full border rounded-lg px-4 py-2 @error('barcode') border-red-500 @enderror">
                @error('barcode')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" placeholder="pcs, kg, liter..."
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual</label>
                <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required min="0" step="0.01"
                    class="w-full border rounded-lg px-4 py-2 @error('selling_price') border-red-500 @enderror">
                @error('selling_price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Rata-rata (COGS)</label>
                <input type="number" name="average_cost" value="{{ old('average_cost', $product->average_cost) }}" min="0" step="0.01"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok Minimum</label>
                <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" step="0.01"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="is_active" class="w-full border rounded-lg px-4 py-2">
                    <option value="1" {{ old('is_active', $product->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $product->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category_id" class="w-full border rounded-lg px-4 py-2">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
