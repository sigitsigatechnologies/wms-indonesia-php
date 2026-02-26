@extends('layouts.app')

@section('title', 'Edit Supplier - WMS Indonesia')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.suppliers.index') }}" class="text-indigo-600 hover:text-indigo-900">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Supplier</h1>
    <p class="text-gray-600 mt-2">Perbarui data supplier</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.suppliers.update', $supplier->id) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Supplier</label>
                <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                    class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="address" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('address', $supplier->address) }}</textarea>
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
