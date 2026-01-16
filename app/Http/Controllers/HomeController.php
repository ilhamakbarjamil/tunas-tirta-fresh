<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // <--- PENTING: Panggil Model Produk

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) // <--- Tambahkan Request $request
    {
        // Mulai Query Produk + Varian
        $query = Product::with('variants');

        // 1. LOGIKA SEARCH
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            // Cari berdasarkan Nama Produk
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        // Ambil data (urutkan terbaru)
        $products = $query->latest()->get();

        return view('home', compact('products'));
    }
}