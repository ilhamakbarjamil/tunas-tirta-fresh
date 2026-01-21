<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Tambahan untuk kirim request API
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

        // 3. Cek Status Otomatis
        foreach ($orders as $order) {
            // Cek jika status pending atau cancelled (siapa tau user bayar telat)
            if (($order->status == 'pending' || $order->status == 'cancelled') && $order->external_id) {
                try {
                    $listInvoices = Invoice::retrieveAll([
                        'external_id' => $order->external_id
                    ]);

                    if (!empty($listInvoices) && count($listInvoices) > 0) {
                        $invoice = $listInvoices[0];

                        if (isset($invoice['status'])) {
                            // JIKA LUNAS
                            if ($invoice['status'] == 'PAID' || $invoice['status'] == 'SETTLED') {
                                
                                // Update Status Database
                                $order->update(['status' => 'paid']);

                                // --- KIRIM WHATSAPP KE ADMIN ---
                                // Kita cek dulu, apakah notifikasi sudah pernah dikirim? (Opsional, biar gak spam)
                                // Tapi untuk sekarang kita kirim langsung.
                                $this->sendWhatsAppNotification($order, $invoice['invoice_url']);
                            } 
                        }
                    }
                } catch (\Exception $e) {
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

    // --- FUNGSI TAMBAHAN KIRIM WA ---
    private function sendWhatsAppNotification($order, $invoiceUrl)
    {
        $adminPhone = env('WHATSAPP_ADMIN');
        $token = env('FONNTE_TOKEN');

        // Susun Pesan
        $message = "*LAPORAN ORDER LUNAS!* ✅\n\n";
        $message .= "No Order: " . $order->external_id . "\n";
        $message .= "Pembeli: " . Auth::user()->name . "\n";
        $message .= "Total Barang: Rp " . number_format($order->total_price, 0, ',', '.') . "\n";
        $message .= "Status: SUDAH DIBAYAR (via Xendit)\n\n";
        
        $message .= "*Alamat Pengiriman:* \n" . $order->address . "\n\n";
        
        $message .= "🔗 *Link Invoice:* \n" . $invoiceUrl . "\n\n";
        
        $message .= "⚠️ *PENTING:* \n";
        $message .= "Ongkos kirim belum termasuk. Mohon Admin segera cek alamat dan hubungi pembeli untuk info biaya ongkir.";

        // Kirim via Fonnte
        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $adminPhone,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Jika gagal kirim WA, biarkan saja (jangan bikin error di web)
            // Log::error($e->getMessage());
        }
    }
}