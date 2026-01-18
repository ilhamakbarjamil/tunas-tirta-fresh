<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Category;
use App\Models\Announcement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            View::share('globalCategories', Category::all());
        } catch (\Exception $e) {
            // Jaga-jaga kalau tabel belum ada (pas migrate fresh), biar gak error
        }

        try {
            $activePromo = \App\Models\Announcement::where('is_active', true)->latest()->first();
            View::share('activePromo', $activePromo);
        } catch (\Exception $e) {
            // Biar gak error pas migrate fresh
        }

        try {
            // 1. Share Semua Kategori (Untuk List Menu Kiri)
            View::share('globalCategories', \App\Models\Category::all());

            // 2. Share Data Promo
            $activePromo = \App\Models\Announcement::where('is_active', true)->latest()->first();
            View::share('activePromo', $activePromo);

            // --- DATA MEGA MENU (AMBIL 3 PRODUK ACAK PER KATEGORI) ---

            // A. Buah Segar
            $freshMenuProducts = \App\Models\Product::whereHas('category', function($q) {
                $q->where('slug', 'fresh-fruits');
            })->limit(3)->inRandomOrder()->get();
            View::share('freshMenuProducts', $freshMenuProducts);

            // B. Buah Beku (Frozen)
            $frozenMenuProducts = \App\Models\Product::whereHas('category', function($q) {
                $q->where('slug', 'frozen-fruits');
            })->limit(3)->inRandomOrder()->get();
            View::share('frozenMenuProducts', $frozenMenuProducts);

            // C. Jus Segar (Drinks)
            $drinkMenuProducts = \App\Models\Product::whereHas('category', function($q) {
                $q->where('slug', 'fresh-drinks');
            })->limit(3)->inRandomOrder()->get();
            View::share('drinkMenuProducts', $drinkMenuProducts);

            // D. Paket Hemat (Packages)
            $packageMenuProducts = \App\Models\Product::whereHas('category', function($q) {
                $q->where('slug', 'packages');
            })->limit(3)->inRandomOrder()->get();
            View::share('packageMenuProducts', $packageMenuProducts);

        } catch (\Exception $e) {
            // Error handling diam-diam
        }
    }
}
