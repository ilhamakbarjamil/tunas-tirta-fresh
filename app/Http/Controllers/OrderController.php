<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
// 1. GANTI IMPORTS
use Midtrans\Config;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function __construct()
    {
        // 2. SETUP CONFIG MIDTRANS (Bisa ditaruh di __construct biar rapi)
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        // Ambil Order User
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. LOGIKA CEK STATUS OTOMATIS (VERSI MIDTRANS)
        foreach ($orders as $order) {
            // Cek hanya yang statusnya masih 'pending'
            // Pastikan order punya ID (karena Midtrans butuh Order ID)
            if ($order->status == 'pending' && $order->id) {
                try {
                    // Minta status ke Midtrans berdasarkan Order ID (atau external_id kalau Mas pakai itu)
                    // Asumsi: Di Midtrans Mas pakai $order->id sebagai Order ID
                    $status = Transaction::status($order->id);

                    // Cek Status Transaksi dari respon Midtrans
                    $transactionStatus = $status->transaction_status;
                    $fraudStatus = $status->fraud_status;

                    $isPaid = false;

                    // Logika Status Midtrans
                    if ($transactionStatus == 'capture') {
                        if ($fraudStatus == 'challenge') {
                            // Masih ditahan (Challenge)
                        } else {
                            $isPaid = true; // Sukses CC
                        }
                    } else if ($transactionStatus == 'settlement') {
                        $isPaid = true; // Sukses Transfer/E-wallet
                    } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                        // Jika expired/gagal di Midtrans, update di DB kita juga
                        $order->update(['status' => 'failed']);
                    }

                    // JIKA LUNAS
                    if ($isPaid) {
                        // Update Status Database
                        $order->update(['status' => 'paid']);

                        // --- KIRIM WHATSAPP KE ADMIN ---
                        // Note: Invoice URL di Midtrans (Snap) biasanya tidak disimpan permanen seperti Xendit.
                        // Jadi kita kosongkan atau ganti link ke detail web kita.
                        $invoiceUrl = route('orders.show', $order->id); 
                        $this->sendWhatsAppNotification($order, $invoiceUrl);
                    }

                } catch (\Exception $e) {
                    // Jika error (misal Order ID belum ada di Midtrans karena user baru klik checkout tapi belum bayar)
                    // Lanjut ke order berikutnya (jangan error 500)
                    continue; 
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

    // --- FUNGSI KIRIM WA (Hampir sama, cuma edit pesan dikit) ---
    private function sendWhatsAppNotification($order, $invoiceUrl)
    {
        $adminPhone = env('WHATSAPP_ADMIN');
        $token = env('FONNTE_TOKEN');

        // Susun Pesan
        $message = "*LAPORAN ORDER LUNAS!* ✅\n\n";
        $message .= "No Order: #" . $order->id . "\n"; // Midtrans biasa pakai ID angka
        $message .= "Pembeli: " . Auth::user()->name . "\n";
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
            // Log::error($e->getMessage());
        }
    }
}