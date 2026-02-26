@extends('layouts.app')

@section('title', 'Detail Supplier - WMS Indonesia')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.suppliers.index') }}" class="text-indigo-600 hover:text-indigo-900">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{ $supplier->name }}</h1>
    <p class="text-gray-600 mt-2">Detail supplier</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Informasi Supplier</h2>
    <dl class="space-y-3">
        <div class="flex justify-between">
            <dt class="text-gray-500">Nama</dt>
            <dd class="font-medium">{{ $supplier->name }}</dd>
        </div>
        <div class="flex justify-between">
            <dt class="text-gray-500">Telepon</dt>
            <dd class="font-medium">{{ $supplier->phone ?? '-' }}</dd>
        </div>
        <div class="flex justify-between">
            <dt class="text-gray-500">Alamat</dt>
            <dd class="font-medium">{{ $supplier->address ?? '-' }}</dd>
        </div>
    </dl>
</div>
@endsection
