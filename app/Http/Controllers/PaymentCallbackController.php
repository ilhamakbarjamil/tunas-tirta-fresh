<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
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
            // 2. Terima Notifikasi dari Midtrans
            $notif = new Notification();
        } catch (\Exception $e) {
            return response(['message' => 'Invalid Notification'], 400);
        }

        // 3. Ambil Data Penting
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // 4. Cari Order di Database Kita
        $order = Order::find($orderId);

        if (!$order) {
            return response(['message' => 'Order not found'], 404);
        }

        // 5. Logika Status Midtrans -> Database Kita
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->update(['status' => 'pending']);
            } else {
                $order->update(['status' => 'paid']); // Kartu Kredit Sukses
            }
        } else if ($transaction == 'settlement') {
            $order->update(['status' => 'paid']); // VA, GoPay, dll Lunas
        } else if ($transaction == 'pending') {
            $order->update(['status' => 'pending']);
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->update(['status' => 'failed']); // Gagal/Kadaluarsa
        }

        return response(['message' => 'Payment status updated']);
    }
}