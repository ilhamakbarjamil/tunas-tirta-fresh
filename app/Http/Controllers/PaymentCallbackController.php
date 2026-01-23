<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // 1. Konfigurasi Midtrans
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

        // Ambil data Order beserta Item dan User-nya
        $order = Order::with(['user', 'items'])->find($orderId);

        if (!$order) {
            return response(['message' => 'Order not found'], 404);
        }

        // Jangan proses jika order sudah berstatus 'paid' (mencegah stok berkurang 2x)
        if ($order->status === 'paid') {
            return response(['message' => 'Order already processed']);
        }

        $isPaid = false;

        // 2. Cek Status Transaksi Midtrans
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->update(['status' => 'pending']);
            } else {
                $isPaid = true; 
            }
        } else if ($transaction == 'settlement') {
            $isPaid = true; 
        } else if ($transaction == 'pending') {
            $order->update(['status' => 'pending']);
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->update(['status' => 'failed']);
        }

        // 3. JIKA PEMBAYARAN LUNAS
        if ($isPaid) {
            // A. Update status order jadi paid
            $order->update(['status' => 'paid']);

            // B. LOGIKA PENGURANGAN STOK
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    // Kurangi stok di tabel Varian
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->decrement('stock', $item->quantity);
                    }
                } else {
                    // Kurangi stok di tabel Produk Utama
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }
            
            // C. Kirim Notifikasi WhatsApp
            $this->sendWhatsAppNotification($order);
        }

        return response(['message' => 'Payment status updated & stock reduced']);
    }

    // --- FUNGSI KIRIM WA ---
    private function sendWhatsAppNotification($order)
    {
        $adminPhone = env('WHATSAPP_ADMIN'); 
        $token = env('FONNTE_TOKEN');        

        $buyerName = $order->user ? $order->user->name : 'Guest';
        $detailUrl = url('/orders'); 

        $message = "*LAPORAN ORDER LUNAS!* ✅\n\n";
        $message .= "No Order: #" . $order->id . "\n";
        $message .= "Pembeli: " . $buyerName . "\n";
        $message .= "Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n";
        $message .= "Status: LUNAS (via Midtrans)\n\n";
        
        $message .= "*Alamat Kirim:* \n" . $order->address . "\n\n";
        $message .= "*Catatan:* \n" . ($order->note ?? '-') . "\n\n";
        
        $message .= "🔗 *Lihat Detail:* \n" . $detailUrl . "\n\n";
        $message .= "Mohon Admin segera proses pengiriman.";

        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $adminPhone,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Gagal kirim WA tidak membatalkan proses lunas
        }
    }
}