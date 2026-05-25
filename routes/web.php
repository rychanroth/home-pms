<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\StockInController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Cashier\CartController;
use App\Http\Controllers\Cashier\PosController;
use App\Http\Controllers\Cashier\CheckoutController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('product-types', ProductTypeController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);

    Route::get('/stock-movements', [StockInController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/create', [StockInController::class, 'create'])->name('stock-movements.create');
    Route::post('/stock-movements', [StockInController::class, 'store'])->name('stock-movements.store');

    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
});


require __DIR__ . '/auth.php';
