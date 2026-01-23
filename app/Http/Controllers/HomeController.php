<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 

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
        if ($request->has('search') && $request->search != null) {
            $search = $request->search;
            
            // Cek apakah ada koma? (Misal: "Nanas,Pepaya")
            if (str_contains($search, ',')) {
                $keywords = explode(',', $search); // Pecah jadi array ['Nanas', 'Pepaya']
                
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        // Cari yang namanya mirip Nanas ATAU mirip Pepaya
                        $q->orWhere('name', 'like', '%' . trim($word) . '%');
                    }
                });
            } else {
                // Kalau cuma 1 kata, cari biasa saja
                $query->where('name', 'like', '%' . $search . '%');
            }
        }

        // Ambil data (urutkan terbaru)
        $products = $query->latest()->get();

        return view('home', compact('products'));
    }
}