<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product; // Pastikan ini ada!
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil kategori dan produknya untuk halaman depan
        $categories = Category::with(['products' => function($query) {
            $query->where('is_available', true);
        }])->get();

        return view('home', compact('categories'));
    }

    // FUNGSI BARU: Menampilkan Detail Produk
    public function show($slug)
    {
        // 1. Cari produk berdasarkan slug, kalau tidak ketemu tampilkan 404
        $product = Product::where('slug', $slug)
                          ->where('is_available', true)
                          ->firstOrFail();

        // 2. Ambil produk lain di kategori yang sama (untuk rekomendasi)
        // Kecuali produk yang sedang dibuka
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->take(4)
                                  ->get();

        return view('product.show', compact('product', 'relatedProducts'));
    }
}