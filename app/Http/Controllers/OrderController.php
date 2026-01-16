<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;        // <--- Wajib Import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <--- Wajib Import ini (Buat Transaksi)
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class OrderController extends Controller
{
    public function index()
    {
        // 1. Ambil Order User
        $orders = Order::where('user_id', Auth::id())
                        ->with(['items.product', 'items.variant'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 2. Cek Status Pembayaran ke Xendit
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
        $apiInstance = new InvoiceApi();

        foreach ($orders as $order) {
            // Hanya cek yang pending & punya external_id (Order Xendit)
            if ($order->status === 'pending' && !empty($order->external_id)) {
                
                try {
                    // Cari Invoice di Xendit berdasarkan ID Order kita
                    $list_invoices = $apiInstance->getInvoices(null, $order->external_id);

                    if (count($list_invoices) > 0) {
                        $invoice = $list_invoices[0];

                        // --- LOGIKA UTAMA DI SINI ---
                        if ($invoice['status'] === 'PAID' || $invoice['status'] === 'SETTLED') {
                            
                            // Mulai Transaksi Database (Biar Aman)
                            DB::transaction(function () use ($order) {
                                // A. Update Status jadi 'processed'
                                $order->update(['status' => 'processed']);

                                // B. Kurangi Stok Barang
                                foreach ($order->items as $item) {
                                    $product = Product::find($item->product_id);
                                    
                                    // Cek apakah produknya ada?
                                    if ($product) {
                                        // Kurangi stok sesuai jumlah beli
                                        // Pastikan di tabel products ada kolom 'stock'
                                        if ($product->stock >= $item->quantity) {
                                            $product->decrement('stock', $item->quantity);
                                        } else {
                                            // Opsional: Kalau stok minus, set jadi 0
                                            $product->update(['stock' => 0]);
                                        }
                                    }
                                }
                            });

                        } elseif ($invoice['status'] === 'EXPIRED') {
                            $order->update(['status' => 'cancelled']);
                        }
                    }

                } catch (\Exception $e) {
                    // Diamkan error koneksi, lanjut ke order berikutnya
                    continue;
                }
            }
        }

        return view('orders.index', compact('orders'));
    }
}