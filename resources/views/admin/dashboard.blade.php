@extends('layouts.app')

@section('title', 'Dashboard - WMS Indonesia')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600 mt-2">Selamat datang di WMS Indonesia</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Penjualan Hari Ini</p>
                <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($stats['today_sales'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-indigo-100 p-3 rounded-full">
                <i class="fas fa-shopping-bag text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Keuntungan Hari Ini</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['today_profit'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Transaksi Hari Ini</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['today_transactions'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-receipt text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Penjualan Bulan Ini</p>
                <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($stats['month_sales'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-calendar text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow p-4 lg:p-6">
    <h2 class="text-lg lg:text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 lg:gap-4">
        <a href="{{ route('admin.products.create') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 lg:p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
            <i class="fas fa-plus-circle text-indigo-600 mb-1 sm:mb-0 sm:mr-2"></i>
            <span class="text-indigo-600 font-medium text-sm lg:text-base">Produk</span>
        </a>
        <a href="{{ route('admin.suppliers.create') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 lg:p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
            <i class="fas fa-user-plus text-blue-600 mb-1 sm:mb-0 sm:mr-2"></i>
            <span class="text-blue-600 font-medium text-sm lg:text-base">Supplier</span>
        </a>
        <a href="{{ route('admin.purchases.create') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 lg:p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <i class="fas fa-shopping-cart text-green-600 mb-1 sm:mb-0 sm:mr-2"></i>
            <span class="text-green-600 font-medium text-sm lg:text-base">Pembelian</span>
        </a>
        <a href="{{ route('admin.sales.create') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 lg:p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
            <i class="fas fa-cash-register text-orange-600 mb-1 sm:mb-0 sm:mr-2"></i>
            <span class="text-orange-600 font-medium text-sm lg:text-base">Penjualan</span>
        </a>
    </div>
</div>
@endsection
