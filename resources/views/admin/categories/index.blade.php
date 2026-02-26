@extends('layouts.app')

@section('title', 'Kategori - WMS Indonesia')

@section('content')
<div class="mb-4 lg:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Kategori</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Tambah Kategori
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kategori</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Deskripsi</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Jumlah Produk</th>
                    <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap">{{ $category->name }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 hidden md:table-cell">{{ $category->description ?? '-' }}</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap hidden sm:table-cell">{{ $category->products->count() }} produk</td>
                    <td class="px-3 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.categories.show', $category->id) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-3 lg:px-6 py-4 text-center text-gray-500">Tidak ada kategori</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-3 lg:px-6 py-3 lg:py-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
