<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Halaman Depan (Frontend)
Route::get('/', [HomeController::class, 'index'])->name('home');

// (Biarkan route lainnya seperti default/admin tetap ada)