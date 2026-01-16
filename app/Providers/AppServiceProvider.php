<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Category;

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
    }
}
