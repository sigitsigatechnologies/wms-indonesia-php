@extends('layouts.app')

@section('title', 'Sales - WMS Indonesia')

@section('content')
<div class="mb-4 lg:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Sales</h1>
        <p class="text-gray-600 mt-1 lg:mt-2 text-sm lg:text-base">Kelola data penjualan</p>
    </div>
    <a href="{{ route('admin.sales.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Penjualan Baru
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-4 lg:mb-6">
    <div class="bg-white rounded-lg shadow p-3 lg:p-4">
        <p class="text-gray-500 text-xs lg:text-sm">Hari Ini</p>
        <p class="text-lg lg:text-xl font-bold text-indigo-600">Rp {{ number_format($stats['today_sales'] ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-3 lg:p-4">
        <p class="text-gray-500 text-xs lg:text-sm">Keuntungan</p>
        <p class="text-lg lg:text-xl font-bold text-green-600">Rp {{ number_format($stats['today_profit'] ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-3 lg:p-4">
        <p class="text-gray-500 text-xs lg:text-sm">Transaksi</p>
        <p class="text-lg lg:text-xl font-bold text-blue-600">{{ $stats['today_transactions'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-3 lg:p-4">
        <p class="text-gray-500 text-xs lg:text-sm">Bulan Ini</p>
        <p class="text-lg lg:text-xl font-bold text-orange-600">Rp {{ number_format($stats['month_sales'] ?? 0, 0, ',', '.') }}</p>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-3 lg:p-4 border-b">
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" placeholder="Cari invoice..." value="{{ request('search') }}" 
                class="border rounded-lg px-3 lg:px-4 py-2 w-full sm:w-48">
            <select name="payment_method" class="border rounded-lg px-3 lg:px-4 py-2">
                <option value="">Semua Metode</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
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
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Total</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Profit</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Metode</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($sales as $sale)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">{{ $sale->invoice_number }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm hidden sm:table-cell">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm text-green-600 hidden lg:table-cell">Rp {{ number_format($sale->total_profit, 0, ',', '.') }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap hidden md:table-cell">
                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                            {{ ucfirst($sale->payment_method) }}
                        </span>
                    </td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm hidden sm:table-cell">{{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.sales.show', $sale->id) }}" class="text-blue-600 hover:text-blue-900 mr-2 inline">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.sales.destroy', $sale->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin hapus? Stok akan dikembalikan.')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-3 lg:px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-3 lg:px-6 py-3 lg:py-4">
        {{ $sales->links() }}
    </div>
</div>
@endsection
