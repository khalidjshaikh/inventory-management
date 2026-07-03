<?php

use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\DashboardController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\PurchaseOrderController;
use App\Http\Controllers\Inventory\ReportController;
use App\Http\Controllers\Inventory\SaleController;
use App\Http\Controllers\Inventory\StockHistoryController;
use App\Http\Controllers\Inventory\SupplierController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::get('products/{product}/barcode', [ProductController::class, 'showBarcode'])->name('products.barcode');

    // Purchase Orders
    Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::get('purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
    Route::put('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    Route::delete('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');

    // Sales
    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

    // Stock History
    Route::get('stock-history', [StockHistoryController::class, 'index'])->name('stock-history.index');

    // Reports
    Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
