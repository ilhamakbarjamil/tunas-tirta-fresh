<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // WAJIB ADA: Buat kirim request ke Fonnte
use Midtrans\Config;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function __construct()
    {
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

        // 2. CEK STATUS (Auto-Check)
        foreach ($orders as $order) {
            if ($order->status == 'pending' && $order->external_id) {
                try {
                    $status = Transaction::status($order->external_id); 
                    
                    $newStatus = null;
                    if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                        $newStatus = 'paid';
                    } else if ($status->transaction_status == 'expire' || $status->transaction_status == 'cancel') {
                        $newStatus = 'failed';
                    }

                    // JIKA STATUS BERUBAH
                    if ($newStatus && $newStatus != $order->status) {
                        $order->update(['status' => $newStatus]);

                        // 🔥 FITUR WA: Jika LUNAS, Kirim Notif 🔥
                        if ($newStatus == 'paid') {
                            $this->sendWhatsAppNotification($order);
                        }
                    }

                } catch (\Exception $e) {
                    continue; 
                }
            }
        }

        // Refresh data setelah update
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }

    // --- FUNGSI KIRIM WA ---
    private function sendWhatsAppNotification($order)
    {
        $token = env('FONNTE_TOKEN');
        $adminPhone = env('WHATSAPP_ADMIN');
        
        // Buat Link Invoice
        $invoiceLink = route('orders.show', $order->id);

        // Pesan untuk Admin
        $message  = "*LAPORAN ORDER LUNAS!* ✅\n\n";
        $message .= "No Order: #{$order->external_id}\n";
        $message .= "Pembeli: " . Auth::user()->name . "\n";
        $message .= "Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n\n";
        $message .= "Segera proses pesanan ini!\n";
        $message .= "🔗 Link: $invoiceLink\n\n";
        $message .= "tolong, di infokan untuk biaya ongkir nya";

        try {
            // Kirim ke Admin
            Http::withHeaders(['Authorization' => $token])->post('https://api.fonnte.com/send', [
                'target' => $adminPhone,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Error diam-diam saja biar user tidak terganggu
        }
    }
}