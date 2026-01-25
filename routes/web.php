<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.show');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/cart/decrease/{id}', [App\Http\Controllers\CartController::class, 'decrease'])->name('cart.decrease');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::view('/about', 'about')->name('about');

// Login Google
Route::view('/login', 'auth.login')->name('login');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// FITUR KERANJANG (Harus Login)
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/process', [CartController::class, 'checkout'])->name('checkout.process');
    // Route::post('/checkout/process', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout.process');
    // Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Admin Redirect
Route::get('/login-admin', function () {
    return redirect('/admin/login');
});

Route::get('/invoice/public/{external_id}', [App\Http\Controllers\OrderController::class, 'publicInvoice'])
    ->name('invoice.public');
