<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Xendit; 
use Xendit\Invoice;

class OrderController extends Controller
{
    public function index()
    {
        // 1. Setup API Key
        Xendit::setApiKey(env('XENDIT_SECRET_KEY'));

        // 2. Ambil Order User
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Cek Status Otomatis ke Xendit
        foreach ($orders as $order) {
            // Kita cek yg pending ATAU cancelled (siapa tau user bayar telat)
            if (($order->status == 'pending' || $order->status == 'cancelled') && $order->external_id) {
                try {
                    // Cari invoice berdasarkan ID External kita
                    $listInvoices = Invoice::retrieveAll([
                        'external_id' => $order->external_id
                    ]);

                    if (!empty($listInvoices) && count($listInvoices) > 0) {
                        $invoice = $listInvoices[0]; // Ambil data terbaru

                        if (isset($invoice['status'])) {
                            // Update jadi Lunas
                            if ($invoice['status'] == 'PAID' || $invoice['status'] == 'SETTLED') {
                                $order->update(['status' => 'paid']);
                            } 
                            // Update jadi Expired (Hanya jika status DB masih pending)
                            // elseif ($invoice['status'] == 'EXPIRED' && $order->status == 'pending') {
                            //     $order->update(['status' => 'cancelled']);
                            // }
                        }
                    }
                } catch (\Exception $e) {
                    continue; // Lanjut kalau ada error koneksi
                }
            }
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }
}