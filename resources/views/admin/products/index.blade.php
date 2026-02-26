@extends('layouts.app')

@section('title', 'Products - WMS Indonesia')

@section('content')
<div class="mb-4 lg:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Products</h1>
        <p class="text-gray-600 mt-1 lg:mt-2 text-sm lg:text-base">Kelola data produk</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Tambah Produk
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-3 lg:p-4 border-b">
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}" 
                class="border rounded-lg px-3 lg:px-4 py-2 w-full sm:w-64">
            <select name="is_active" class="border rounded-lg px-3 lg:px-4 py-2">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barcode</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Unit</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Harga Jual</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Status</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm">{{ $product->barcode }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">{{ $product->name }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm hidden md:table-cell">{{ $product->unit ?? '-' }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm hidden lg:table-cell">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm">
                        <span class="{{ $product->current_stock <= $product->min_stock ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($product->current_stock, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap hidden sm:table-cell">
                        <span class="px-2 py-1 rounded text-xs {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-blue-600 hover:text-blue-900 mr-2 inline">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2 inline">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-3 lg:px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-3 lg:px-6 py-3 lg:py-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
