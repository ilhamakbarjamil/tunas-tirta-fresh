<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use App\Models\Category;
use App\Models\Announcement;
use App\Models\Product;

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

        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        // 1. PENANGANAN HTTPS (FIX UNTUK CLOUDFLARE/NGROK)
        if (str_contains(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Paksa Livewire mengikuti URL utama
        \Illuminate\Support\Facades\Config::set('livewire.asset_url', config('app.url'));

        // Paksa livewire menggunakan path yang benar agar dashboard & upload lancar
        Config::set('livewire.asset_url', config('app.url'));


        // 2. SHARING DATA KE SEMUA VIEW (GLOBAL DATA)
        try {
            // A. Share Semua Kategori
            $globalCategories = Category::all();
            View::share('globalCategories', $globalCategories);

            // B. Share Data Promo Aktif
            $activePromo = Announcement::where('is_active', true)->latest()->first();
            View::share('activePromo', $activePromo);

            // C. DATA MEGA MENU (Dioptimasi agar lebih ringan)
            $categoriesToShare = [
                'freshMenuProducts' => 'fresh-fruits',
                'frozenMenuProducts' => 'frozen-fruits',
                'drinkMenuProducts' => 'fresh-drinks',
                'packageMenuProducts' => 'packages',
            ];

            foreach ($categoriesToShare as $viewVar => $slug) {
                View::share($viewVar, Product::whereHas('category', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                })->limit(3)->inRandomOrder()->get());
            }

        } catch (\Exception $e) {
            // Jaga-jaga agar tidak error saat proses migrasi database
        }
    }
}