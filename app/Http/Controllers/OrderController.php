<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil semua order milik user yang sedang login
        // Urutkan dari yang terbaru
        $orders = Order::where('user_id', Auth::id())
                        ->with(['items.product', 'items.variant']) // Load rincian barangnya
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('orders.index', compact('orders'));
    }
}