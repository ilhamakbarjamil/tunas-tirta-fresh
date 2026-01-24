<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response(['message' => 'Invalid Notification'], 400);
        }

        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id; // Ini adalah external_id
        $fraud = $notif->fraud_status;

        $order = Order::with(['items'])->where('external_id', $orderId)->first();

        if (!$order) {
            Log::warning("Order $orderId tidak ditemukan di database.");
            return response(['message' => 'Order not found'], 404);
        }

        if ($order->status === 'paid') {
            return response(['message' => 'Sudah diproses']);
        }

        $isPaid = false;

        if ($transaction == 'capture' && $fraud !== 'challenge') $isPaid = true;
        else if ($transaction == 'settlement') $isPaid = true;
        else if (in_array($transaction, ['deny', 'expire', 'cancel'])) $order->update(['status' => 'failed']);

        if ($isPaid) {
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'paid']);

                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) $variant->decrement('stock', $item->quantity);
                    } else {
                        $product = Product::find($item->product_id);
                        if ($product) $product->decrement('stock', $item->quantity);
                    }
                }
            });

            Log::info("Order $orderId Berhasil Lunas & Stok Berkurang.");
            $this->sendWhatsAppNotification($order);
        }

        return response(['message' => 'OK']);
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