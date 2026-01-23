<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // WAJIB ADA UNTUK FONNTE
use Midtrans\Config;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        // 1. Ambil Order User
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. CEK STATUS KE MIDTRANS (Active Check)
        foreach ($orders as $order) {
            // Hanya cek yang masih pending & punya external_id
            if ($order->status == 'pending' && $order->external_id) {
                try {
                    // Cek status ke Midtrans pakai external_id
                    $status = Transaction::status($order->external_id); 
                    
                    $transactionStatus = $status->transaction_status;
                    $fraudStatus = $status->fraud_status;

                    $newStatus = null;

                    // Logika Status Midtrans
                    if ($transactionStatus == 'capture') {
                        if ($fraudStatus == 'challenge') {
                            // Challenge
                        } else {
                            $newStatus = 'paid';
                        }
                    } else if ($transactionStatus == 'settlement') {
                        $newStatus = 'paid';
                    } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                        $newStatus = 'failed';
                    }

                    // --- UPDATE DB & KIRIM WA ---
                    if ($newStatus) {
                        $order->update(['status' => $newStatus]);

                        // 🔥 JIKA STATUS BERUBAH JADI PAID, KIRIM WA 🔥
                        if ($newStatus == 'paid') {
                            $invoiceUrl = route('orders.show', $order->id); // Buat Link Invoice
                            $this->sendWhatsAppNotification($order, $invoiceUrl);
                        }
                    }

                } catch (\Exception $e) {
                    continue; 
                }
            }
        }

        // Refresh data agar tampilan update
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Cek Pemilik
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }

    // --- FUNGSI KIRIM WA (FONNTE) ---
    private function sendWhatsAppNotification($order, $invoiceUrl)
    {
        $adminPhone = env('WHATSAPP_ADMIN'); // Pastikan ada di .env
        $token = env('FONNTE_TOKEN');        // Pastikan ada di .env

        // Susun Pesan
        $message = "*LAPORAN ORDER LUNAS!* ✅\n\n";
        $message .= "No Order: #" . $order->id . "\n";
        $message .= "Pembeli: " . Auth::user()->name . "\n"; // Aman pakai Auth karena ini dijalankan user login
        $message .= "Total Barang: Rp " . number_format($order->total_price, 0, ',', '.') . "\n";
        $message .= "Status: SUDAH DIBAYAR (via Midtrans)\n\n";

        $message .= "*Alamat Pengiriman:* \n" . $order->address . "\n\n";

        $message .= "🔗 *Link Detail:* \n" . $invoiceUrl . "\n\n";

        $message .= "⚠️ *PENTING:* \n";
        $message .= "Cek Dashboard Midtrans untuk verifikasi dana masuk.";

        // Kirim via Fonnte
        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $adminPhone,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Error handling diam-diam agar user tidak terganggu
        }
    }
}