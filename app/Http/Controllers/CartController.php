<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambah DB Transaction biar aman
// 1. GANTI LIBRARY XENDIT JADI MIDTRANS
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    // --- KONFIGURASI MIDTRANS DI CONSTRUCTOR ---
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        return view('cart.index', compact('carts'));
    }

    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();
        $variantId = $request->input('variant_id');

        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity', $request->input('quantity', 1));
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $request->input('quantity', 1)
            ]);
        }

        return redirect()->back()->with('show_cart', true)->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        if ($cart) {
            $qty = max(1, $request->input('quantity'));
            $cart->update(['quantity' => $qty]);
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        if ($cart) {
            $cart->delete();
        }
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
    }

    // --- 2. FUNGSI CHECKOUT (UBAHAN TOTAL MIDTRANS) ---
    public function checkout(Request $request)
    {
        // Validasi
        $request->validate([
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:200',
        ]);

        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // Hitung Total
        $totalOrder = 0;
        foreach ($carts as $cart) {
            $price = $cart->variant ? $cart->variant->price : $cart->product->price;
            $totalOrder += $price * $cart->quantity;
        }

        // --- MULAI TRANSAKSI DATABASE ---
        DB::beginTransaction();
        try {
            // A. Buat Order Dulu (Status: Pending)
            // Kita butuh ID Order untuk dikirim ke Midtrans
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => $totalOrder,
                'external_id' => 'ORD-' . time(), // ID Unik
                'address' => $request->address,
                'note' => $request->note,
                'shipping_courier' => 'Menyesuaikan', // Bisa diupdate nanti
                // 'snap_token' => null, // Nanti diisi di bawah
            ]);

            // B. Pindahkan Item ke OrderItem

            foreach ($carts as $cart) {
                $price = $cart->variant ? $cart->variant->price : $cart->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    // GANTI DI BAWAH INI:
                    'product_variant_id' => $cart->product_variant_id, // Sesuaikan dengan nama kolom di tabel carts
                    'quantity' => $cart->quantity,
                    'price' => $price,
                ]);
            }

            // C. Siapkan Parameter Midtrans SNAP
            $params = [
                'transaction_details' => [
                    'order_id' => $order->external_id, // Gunakan ID dari Database
                    'gross_amount' => (int) $totalOrder, // Wajib Integer
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    // 'phone' => $user->phone, // Tambahkan jika ada
                ],
                'item_details' => [
                    [
                        'id' => 'TOTAL',
                        'price' => (int) $totalOrder,
                        'quantity' => 1,
                        'name' => 'Total Pembelian Buah'
                    ]
                ]
            ];

            if (env('APP_ENV') === 'local') {
                \Midtrans\Config::$curlOptions = [
                    CURLOPT_SSL_VERIFYPEER => false, // Matikan SSL biar CURL lancar di laptop
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_CONNECTTIMEOUT => 30,
                    CURLOPT_HTTPHEADER => [],
                ];
            }

            // D. Minta Snap Token ke Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan Token ke Order (Opsional, tapi bagus buat log)
            // Pastikan di database tabel orders ada kolom 'snap_token' atau 'payment_url'
            // Kalau tidak ada kolom snap_token, pakai kolom payment_url saja sementara
            $order->payment_url = $snapToken;
            $order->snap_token = $snapToken;
            $order->save();

            // E. Hapus Keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // F. LEMPAR KE HALAMAN PEMBAYARAN (VIEW BARU)
            // Kita bawa variabel $snapToken dan $order ke view
            return view('checkout.payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}