<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Landing page
Route::get('/home', function () {
    return view('home');
})->name('home');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{product}/archive', [AdminProductController::class, 'archive'])->name('products.archive');
    Route::get('/orders', [OrderController::class, 'adminOrders'])->name('orders.all');
    Route::get('/upload', [AdminProductController::class, 'uploadForm'])->name('upload');
    Route::post('/upload', [AdminProductController::class, 'uploadStore'])->name('upload.store');


});
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart/{productId}/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/{itemId}/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/orders', [OrderController::class, 'showUserOrders'])->name('orders.view');
Route::put('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
