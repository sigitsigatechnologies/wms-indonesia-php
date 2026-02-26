@extends('layouts.app')

@section('title', 'Pembelian Baru - WMS Indonesia')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.purchases.index') }}" class="text-indigo-600 hover:text-indigo-900">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Pembelian Baru</h1>
    <p class="text-gray-600 mt-2">Buat transaksi pembelian baru</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.purchases.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <select name="supplier_id" required class="w-full border rounded-lg px-4 py-2">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembelian</label>
                <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full border rounded-lg px-4 py-2">
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-bold mb-4">Produk</h3>
            <div id="items-container">
                <div class="item-row flex gap-4 mb-4 items-end">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Produk</label>
                        <select name="items[0][product_id]" required class="w-full border rounded-lg px-4 py-2 product-select">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-cost="{{ $product->average_cost }}">
                                {{ $product->name }} - Stok: {{ number_format($product->current_stock, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-24">
                        <label class="block text-xs text-gray-500 mb-1">Qty</label>
                        <input type="number" name="items[0][quantity]" min="1" value="1" required class="w-full border rounded-lg px-4 py-2 quantity-input">
                    </div>
                    <div class="w-32">
                        <label class="block text-xs text-gray-500 mb-1">Harga</label>
                        <input type="number" name="items[0][cost_price]" min="0" step="0.01" required class="w-full border rounded-lg px-4 py-2 price-input">
                    </div>
                    <div class="w-32">
                        <label class="block text-xs text-gray-500 mb-1">Subtotal</label>
                        <input type="text" readonly class="w-full border rounded-lg px-4 py-2 bg-gray-50 subtotal-display">
                    </div>
                    <button type="button" class="text-red-600 hover:text-red-900 p-2 remove-item" style="display:none;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <button type="button" id="add-item" class="mt-2 text-indigo-600 hover:text-indigo-900">
                <i class="fas fa-plus mr-1"></i>Tambah Produk
            </button>
        </div>

        <div class="border-t pt-4">
            <div class="flex justify-end">
                <div class="text-right">
                    <p class="text-gray-500">Total:</p>
                    <p class="text-2xl font-bold text-indigo-600" id="grand-total">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i>Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<script>
let itemCount = 1;
const container = document.getElementById('items-container');
const addBtn = document.getElementById('add-item');

addBtn.addEventListener('click', () => {
    const row = document.createElement('div');
    row.className = 'item-row flex gap-4 mb-4 items-end';
    row.innerHTML = `
        <div class="flex-1">
            <select name="items[${itemCount}][product_id]" required class="w-full border rounded-lg px-4 py-2 product-select">
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-cost="{{ $product->average_cost }}">
                    {{ $product->name }} - Stok: {{ number_format($product->current_stock, 0, ',', '.') }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="w-24">
            <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" required class="w-full border rounded-lg px-4 py-2 quantity-input">
        </div>
        <div class="w-32">
            <input type="number" name="items[${itemCount}][cost_price]" min="0" step="0.01" required class="w-full border rounded-lg px-4 py-2 price-input">
        </div>
        <div class="w-32">
            <input type="text" readonly class="w-full border rounded-lg px-4 py-2 bg-gray-50 subtotal-display">
        </div>
        <button type="button" class="text-red-600 hover:text-red-900 p-2 remove-item">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(row);
    itemCount++;
    attachListeners(row);
    updateRemoveButtons();
});

function attachListeners(row) {
    const productSelect = row.querySelector('.product-select');
    const quantityInput = row.querySelector('.quantity-input');
    const priceInput = row.querySelector('.price-input');
    const subtotalDisplay = row.querySelector('.subtotal-display');
    const removeBtn = row.querySelector('.remove-item');

    productSelect.addEventListener('change', () => {
        const option = productSelect.options[productSelect.selectedIndex];
        if (option && option.dataset.cost) {
            priceInput.value = option.dataset.cost;
        }
        calculateSubtotal();
    });

    quantityInput.addEventListener('input', calculateSubtotal);
    priceInput.addEventListener('input', calculateSubtotal);
    removeBtn.addEventListener('click', () => {
        row.remove();
        calculateGrandTotal();
        updateRemoveButtons();
    });
}

function calculateSubtotal() {
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;
        row.querySelector('.subtotal-display').value = 'Rp ' + subtotal.toLocaleString('id-ID');
    });
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        total += qty * price;
    });
    document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, index) => {
        row.querySelector('.remove-item').style.display = rows.length > 1 ? 'block' : 'none';
    });
}

document.querySelectorAll('.item-row').forEach(row => attachListeners(row));
updateRemoveButtons();
</script>
@endsection
