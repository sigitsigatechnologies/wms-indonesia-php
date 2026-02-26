@extends('layouts.app')

@section('title', 'Detail Penjualan - WMS Indonesia')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sales.index') }}" class="text-indigo-600 hover:text-indigo-900">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Invoice: {{ $sale->invoice_number }}</h1>
    <p class="text-gray-600 mt-2">Detail penjualan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Item Produk</h2>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Produk</th>
                        <th class="px-4 py-2 text-right">Harga</th>
                        <th class="px-4 py-2 text-right">Qty</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($sale->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->product->name }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada item</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-bold">Total</td>
                        <td class="px-4 py-3 text-right font-bold text-indigo-600">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Informasi</h2>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Invoice</dt>
                    <dd class="font-medium">{{ $sale->invoice_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Total</dt>
                    <dd class="font-medium">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Keuntungan</dt>
                    <dd class="font-medium text-green-600">Rp {{ number_format($sale->total_profit, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Metode</dt>
                    <dd class="font-medium">{{ ucfirst($sale->payment_method) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Tanggal</dt>
                    <dd class="font-medium">{{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i') : '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
