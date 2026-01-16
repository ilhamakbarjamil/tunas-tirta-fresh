<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Ambil produk berdasarkan kategori ini
        $products = Product::where('category_id', $category->id)->latest()->get();

        return view('home', compact('products')); 
        // Kita pakai tampilan 'home' saja biar hemat, tapi isinya sudah difilter
    }
}