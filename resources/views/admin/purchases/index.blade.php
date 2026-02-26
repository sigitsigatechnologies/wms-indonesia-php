@extends('layouts.app')

@section('title', 'Purchases - WMS Indonesia')

@section('content')
<div class="mb-4 lg:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Purchases</h1>
        <p class="text-gray-600 mt-1 lg:mt-2 text-sm lg:text-base">Kelola data pembelian</p>
    </div>
    <a href="{{ route('admin.purchases.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Pembelian Baru
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
            <input type="text" name="search" placeholder="Cari invoice..." value="{{ request('search') }}" 
                class="border rounded-lg px-3 lg:px-4 py-2 w-full sm:w-48">
            <select name="supplier_id" class="border rounded-lg px-3 lg:px-4 py-2">
                <option value="">Semua Supplier</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
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
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Supplier</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($purchases as $purchase)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">{{ $purchase->invoice_number }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm hidden md:table-cell">{{ $purchase->supplier->name ?? '-' }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm hidden sm:table-cell">{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.purchases.show', $purchase->id) }}" class="text-blue-600 hover:text-blue-900 mr-2 inline">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.purchases.destroy', $purchase->id) }}" class="inline">
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
                    <td colspan="5" class="px-3 lg:px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-3 lg:px-6 py-3 lg:py-4">
        {{ $purchases->links() }}
    </div>
</div>
@endsection
