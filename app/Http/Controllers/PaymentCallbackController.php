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
    /**
     * Handle GET request untuk testing/debugging
     * Webhook sebenarnya hanya menerima POST dari Midtrans
     */
    public function test()
    {
        return response()->json([
            'message' => 'Webhook endpoint aktif',
            'note' => 'Endpoint ini hanya menerima POST request dari Midtrans',
            'method' => 'POST',
            'url' => url('payments/midtrans-notification'),
            'instructions' => [
                '1. Konfigurasi webhook URL di dashboard Midtrans',
                '2. URL harus bisa diakses dari internet (gunakan ngrok untuk development)',
                '3. Midtrans akan mengirim POST request ke endpoint ini saat ada update pembayaran'
            ]
        ], 200);
    }

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
        $orderId = $notif->order_id; // Ini adalah external_id (contoh: 'ORD-1234567890')
        $fraud = $notif->fraud_status;

        // Ambil data Order beserta Item dan User-nya
        // PENTING: order_id dari Midtrans adalah external_id, bukan id database
        $order = Order::with(['user', 'items'])->where('external_id', $orderId)->first();

        if (!$order) {
            Log::error('PaymentCallback: Order not found', ['external_id' => $orderId]);
            return response(['message' => 'Order not found'], 404);
        }
        
        Log::info('PaymentCallback: Order found', [
            'order_id' => $order->id,
            'external_id' => $order->external_id,
            'current_status' => $order->status,
            'transaction_status' => $transaction
        ]);

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

            // B. LOGIKA PENGURANGAN STOK - DARI VARIAN ATAU PRODUK
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    // Kurangi stok di tabel Varian
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $oldStock = $variant->stock;
                        $variant->decrement('stock', $item->quantity);
                        Log::info('Stock reduced (Variant)', [
                            'variant_id' => $variant->id,
                            'variant_name' => $variant->name,
                            'old_stock' => $oldStock,
                            'quantity' => $item->quantity,
                            'new_stock' => $variant->fresh()->stock
                        ]);
                    } else {
                        Log::error('Variant not found for stock reduction', [
                            'variant_id' => $item->product_variant_id,
                            'order_item_id' => $item->id
                        ]);
                    }
                } else {
                    // Kurangi stok di tabel Produk (satuan)
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $oldStock = $product->stock;
                        $product->decrement('stock', $item->quantity);
                        Log::info('Stock reduced (Product)', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'old_stock' => $oldStock,
                            'quantity' => $item->quantity,
                            'new_stock' => $product->fresh()->stock
                        ]);
                    } else {
                        Log::error('Product not found for stock reduction', [
                            'product_id' => $item->product_id,
                            'order_item_id' => $item->id
                        ]);
                    }
                }
            }
            
            // C. Kirim Notifikasi WhatsApp
            $this->sendWhatsAppNotification($order);
            
            Log::info('Payment processed successfully', [
                'order_id' => $order->id,
                'external_id' => $order->external_id,
                'status' => 'paid'
            ]);
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