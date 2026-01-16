<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        // 1. Cari produk berdasarkan SLUG
        // firstOrFail artinya: Kalau ketemu ambil, kalau tidak ketemu tampilkan "404 Not Found"
        $product = Product::where('slug', $slug)->firstOrFail();

        // 2. Tampilkan halaman detail
        return view('products.show', compact('product'));
    }
}