<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil kategori beserta produknya
        $categories = Category::with(['products' => function($query) {
            $query->where('is_available', true);
        }])->get();

        return view('home', compact('categories'));
    }
}