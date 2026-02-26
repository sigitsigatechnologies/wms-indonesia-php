@extends('layouts.app')

@section('title', 'Suppliers - WMS Indonesia')

@section('content')
<div class="mb-4 lg:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Suppliers</h1>
        <p class="text-gray-600 mt-1 lg:mt-2 text-sm lg:text-base">Kelola data supplier</p>
    </div>
    <a href="{{ route('admin.suppliers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Tambah Supplier
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
            <input type="text" name="search" placeholder="Cari supplier..." value="{{ request('search') }}" 
                class="border rounded-lg px-3 lg:px-4 py-2 w-full sm:w-64">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Telepon</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Alamat</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm font-medium">{{ $supplier->name }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm hidden md:table-cell">{{ $supplier->phone ?? '-' }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 text-sm hidden lg:table-cell">{{ $supplier->address ?? '-' }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="text-blue-600 hover:text-blue-900 mr-2 inline">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2 inline">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier->id) }}" class="inline">
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
                    <td colspan="4" class="px-3 lg:px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-3 lg:px-6 py-3 lg:py-4">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
