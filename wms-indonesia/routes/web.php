<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Purchases
    Route::resource('purchases', PurchaseController::class);

    // Sales
    Route::resource('sales', SaleController::class);

    // Categories
    Route::resource('categories', CategoryController::class);
});
