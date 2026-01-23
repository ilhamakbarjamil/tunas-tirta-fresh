<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController; // <--- WAJIB DITAMBAHKAN
use App\Http\Controllers\PaymentCallbackController; // <--- WAJIB DITAMBAHKAN
use Illuminate\Support\Facades\Route;

// --- HALAMAN PUBLIK ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// Perbaikan: Pilih satu saja (Saya sarankan ProductController)
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::view('/about', 'about')->name('about');

// --- AUTHENTICATION ---
Route::view('/login', 'auth.login')->name('login');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// --- FITUR MEMBER (HARUS LOGIN) ---
Route::middleware(['auth'])->group(function () {
    // Cart
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease'); // Dipindah ke dalam auth
    
    // Checkout (Proses Pembayaran)
    Route::post('/checkout/process', [CartController::class, 'checkout'])->name('checkout.process');

    // Orders (PERBAIKAN DISINI)
    // 1. Ganti 'my-orders' jadi 'orders' biar sesuai error log 404 tadi
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    // 2. Tambahkan route detail order (ini yang bikin not found saat klik detail)
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// --- ADMIN REDIRECT ---
Route::get('/login-admin', function () {
    return redirect('/admin/login');
});

// --- WEBHOOK MIDTRANS (WAJIB DILUAR AUTH) ---
// Midtrans tidak perlu login, jadi taruh di luar middleware auth
Route::post('payments/midtrans-notification', [PaymentCallbackController::class, 'receive']);

// Route GET untuk testing/debugging (opsional, bisa dihapus di production)
Route::get('payments/midtrans-notification', [PaymentCallbackController::class, 'test']);