@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Kategori</h1>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Kategori</label>
                <p class="text-lg font-semibold text-gray-800">{{ $category->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-500 mb-1">Deskripsi</label>
                <p class="text-gray-700">{{ $category->description ?? '-' }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Produk</label>
                <p class="text-gray-700">{{ $category->products->count() }} produk</p>
            </div>
            <div class="flex gap-2 mt-6">
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold text-gray-800 mb-4">Produk dalam Kategori</h2>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($category->products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->current_stock }} {{ $product->unit }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada produk dalam kategori ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
