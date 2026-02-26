@extends('layouts.app')

@section('title', 'Detail Produk - WMS Indonesia')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-900">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
    <p class="text-gray-600 mt-2">Detail produk</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Informasi Produk</h2>
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-gray-500">Barcode</dt>
                <dd class="font-medium">{{ $product->barcode }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Nama Produk</dt>
                <dd class="font-medium">{{ $product->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Unit</dt>
                <dd class="font-medium">{{ $product->unit ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Kategori</dt>
                <dd class="font-medium">{{ $product->category->name ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Harga Jual</dt>
                <dd class="font-medium">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Harga Rata-rata (COGS)</dt>
                <dd class="font-medium">Rp {{ number_format($product->average_cost, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Stok Saat Ini</dt>
                <dd class="font-medium {{ $product->current_stock <= $product->min_stock ? 'text-red-600' : 'text-green-600' }}">
                    {{ number_format($product->current_stock, 0, ',', '.') }}
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Stok Minimum</dt>
                <dd class="font-medium">{{ number_format($product->min_stock, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Status</dt>
                <dd>
                    <span class="px-2 py-1 rounded text-xs {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Riwayat Pergerakan Stok</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-left">Jenis</th>
                        <th class="px-3 py-2 text-right">Qty</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($product->stockMovements()->latest()->take(10)->get() as $movement)
                    <tr>
                        <td class="px-3 py-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $movement->movement_type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $movement->movement_type == 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-right">{{ number_format($movement->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-3 py-4 text-center text-gray-500">Tidak ada riwayat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
