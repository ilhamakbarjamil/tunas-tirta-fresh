<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // WAJIB ADA: Untuk kirim request ke Fonnte
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // 1. Konfigurasi
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response(['message' => 'Invalid Notification'], 400);
        }

        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Cari Order beserta data User-nya (Penting buat ambil Nama/NoHP)
        $order = Order::with('user')->find($orderId);

        if (!$order) {
            return response(['message' => 'Order not found'], 404);
        }

        $isPaid = false;

        // 2. Cek Status Midtrans
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->update(['status' => 'pending']);
            } else {
                $isPaid = true; // Kartu Kredit Lunas
            }
        } else if ($transaction == 'settlement') {
            $isPaid = true; // Transfer/GoPay Lunas
        } else if ($transaction == 'pending') {
            $order->update(['status' => 'pending']);
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->update(['status' => 'failed']);
        }

        // 3. JIKA LUNAS -> Update DB & KIRIM WA
        if ($isPaid) {
            $order->update(['status' => 'paid']);
            
            // Panggil Fungsi Kirim WA
            $this->sendWhatsAppNotification($order);
        }

        return response(['message' => 'Payment status updated']);
    }

    // --- FUNGSI KIRIM WA (Versi Midtrans) ---
    private function sendWhatsAppNotification($order)
    {
        $adminPhone = env('WHATSAPP_ADMIN'); // Pastikan ini ada di .env
        $token = env('FONNTE_TOKEN');        // Pastikan ini ada di .env

        // Validasi data user (karena webhook berjalan di background)
        $buyerName = $order->user ? $order->user->name : 'Guest';
        
        // Buat Link Detail Order (bukan link payment lagi, karena sudah lunas)
        // Pastikan Mas punya route 'orders.show'. Kalau error, ganti jadi URL biasa.
        $detailUrl = url('/orders'); 

        // Susun Pesan
        $message = "*LAPORAN ORDER LUNAS!* ✅\n\n";
        $message .= "No Order: #" . $order->id . "\n";
        $message .= "Pembeli: " . $buyerName . "\n";
        $message .= "Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n";
        $message .= "Status: LUNAS (via Midtrans)\n\n";
        
        $message .= "*Alamat Kirim:* \n" . $order->address . "\n\n";
        $message .= "*Catatan:* \n" . ($order->note ?? '-') . "\n\n";
        
        $message .= "🔗 *Lihat Detail:* \n" . $detailUrl . "\n\n";
        $message .= "Mohon Admin segera proses pengiriman.";

        // Kirim via Fonnte
        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $adminPhone,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Biarkan saja jika gagal kirim WA, yang penting status order sudah berubah
            // Log::error("Gagal kirim WA: " . $e->getMessage());
        }
    }
}