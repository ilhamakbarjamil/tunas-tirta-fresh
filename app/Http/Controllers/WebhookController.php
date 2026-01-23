<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    // ==========================================
    // 1. WEBHOOK XENDIT (UTAMA)
    // ==========================================
    public function xenditHandler(Request $request)
    {
        // Ambil Callback Token dari Xendit (Untuk keamanan)
        // Pastikan Anda set "X-CALLBACK-TOKEN" di Dashboard Xendit jika mau strict
        $xenditXCallbackToken = 'TOKEN_VERIFIKASI_ANDA'; // Opsional
        $reqHeaders = $request->header('x-callback-token');

        // Ambil Data yang dikirim Xendit
        $data = $request->all();

        // Cari Order berdasarkan External ID
        $external_id = $data['external_id'] ?? null;
        $status = $data['status'] ?? null; // PAID, SETTLED, EXPIRED

        $order = Order::where('external_id', $external_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Cek apakah order sudah diproses sebelumnya? (Biar stok gak berkurang 2x)
        if ($order->status == 'processed' || $order->status == 'completed') {
            return response()->json(['message' => 'Order already processed'], 200);
        }

        // LOGIKA UPDATE STATUS
        DB::beginTransaction();
        try {
            if ($status == 'PAID' || $status == 'SETTLED') {
                $order->update(['status' => 'processed']);
                $this->reduceStock($order); // Panggil fungsi kurangi stok
            } else if ($status == 'EXPIRED') {
                $order->update(['status' => 'cancelled']);
            }

            DB::commit();
            return response()->json(['message' => 'Webhook success'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Xendit Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    // ==========================================
    // 2. WEBHOOK MIDTRANS (CADANGAN)
    // ==========================================
    public function midtransHandler(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        
        // Verifikasi Signature (Keamanan Midtrans)
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        
        // Di database kita external_id simpan order_id nya midtrans
        $order = Order::where('external_id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status == 'processed' || $order->status == 'completed') {
            return response()->json(['message' => 'Order already processed'], 200);
        }

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $order->update(['status' => 'processed']);
                $this->reduceStock($order);
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $order->update(['status' => 'cancelled']);
            } 
            // Kalau 'pending', kita diamkan saja

            DB::commit();
            return response()->json(['message' => 'Webhook success'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error'], 500);
        }
    }

    // ==========================================
    // FUNGSI BANTUAN: KURANGI STOK - HANYA DARI VARIAN
    // ==========================================
    private function reduceStock($order)
    {
        foreach ($order->items as $item) {
            if (!$item->product_variant_id) {
                Log::warning('OrderItem without variant found in webhook', [
                    'order_item_id' => $item->id,
                    'product_id' => $item->product_id
                ]);
                continue; // Skip jika tidak ada varian
            }
            
            // Kurangi stok di tabel Varian
            $variant = ProductVariant::find($item->product_variant_id);
            if ($variant && $variant->stock >= $item->quantity) {
                $variant->decrement('stock', $item->quantity);
                Log::info('Stock reduced via webhook (Variant)', [
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->name,
                    'quantity' => $item->quantity,
                    'new_stock' => $variant->fresh()->stock
                ]);
            } else {
                Log::warning('Stock reduction failed in webhook', [
                    'variant_id' => $item->product_variant_id,
                    'variant_exists' => $variant ? true : false,
                    'stock_available' => $variant ? $variant->stock : 0,
                    'quantity_needed' => $item->quantity
                ]);
            }
        }
    }
}