<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    public function index()
    {
        $carts = Auth::user()->carts()->with(['product', 'variant'])->get();
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

    public function destroy($id)
    {
        Cart::find($id)->delete();
        return redirect()->back()->with('show_cart', true)->with('success', 'Produk dihapus.');
    }

    public function decrease($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                $cart->delete();
            }
        }
        return redirect()->back()->with('show_cart', true);
    }

    // --- FITUR UTAMA: CHECKOUT VIA WHATSAPP ---
    public function checkout(Request $request)
    {
        // 1. Validasi: User wajib isi alamat
        $request->validate([
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:200',
        ]);

        $user = Auth::user();

        // 2. Cek Keranjang
        // (Pastikan Mas punya relasi 'carts' di model User, atau sesuaikan query ini)
        $carts = \App\Models\Cart::where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong, mau beli angin?');
        }

        // 3. Mulai Transaksi Database (Biar aman)
        DB::beginTransaction();
        try {
            // Hitung Total
            $totalOrder = 0;
            foreach ($carts as $cart) {
                // Logika harga: Cek apakah produk punya varian harga atau harga normal
                $price = $cart->variant ? $cart->variant->price : $cart->product->price;
                $totalOrder += $price * $cart->quantity;
            }

            // A. Buat Order Baru
            // PENTING: Kita bikin external_id unik (ORD-Jam-Acak) biar Midtrans gak error "Duplicate Order"
            $externalId = 'ORD-' . time() . '-' . rand(100, 999);

            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => $totalOrder,
                'external_id' => $externalId, // Ini kolom baru yang tadi kita cek
                'address' => $request->address,
                'note' => $request->note,
                'payment_url' => null,
                'snap_token' => null,
            ]);

            // B. Pindahkan Barang dari Cart ke OrderItem
            foreach ($carts as $cart) {
                $price = $cart->variant ? $cart->variant->price : $cart->product->price;

                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_variant_id' => $cart->product_variant_id ?? null, // Pakai null coalescing kalau kolom beda
                    'quantity' => $cart->quantity,
                    'price' => $price,
                ]);
            }

            // 4. SETTING MIDTRANS & MINTA TOKEN 
            $params = [
                'transaction_details' => [
                    'order_id' => $externalId, // Pakai ID Unik tadi
                    'gross_amount' => (int) $totalOrder,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                // Opsional: Detail Item biar tampil di email Midtrans
                'item_details' => [
                    [
                        'id' => 'TOTAL',
                        'price' => (int) $totalOrder,
                        'quantity' => 1,
                        'name' => 'Total Belanja di Tunas Tirta'
                    ]
                ]
            ];

            // --- JURUS ANTI ERROR CURL (Localhost Only) ---
            if (env('APP_ENV') === 'local') {
                \Midtrans\Config::$curlOptions = [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_CONNECTTIMEOUT => 30,
                    CURLOPT_HTTPHEADER => [], // <--- INI BARIS YANG HILANG (WAJIB ADA)
                ];
            }
            // ----------------------------------------------

            // Minta Token ke Server Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan Token ke Database kita
            $order->snap_token = $snapToken;
            $order->save();

            // 5. Kosongkan Keranjang
            \App\Models\Cart::where('user_id', $user->id)->delete();

            DB::commit(); // Simpan permanen

            // 6. Tampilkan Halaman Pembayaran (View Baru)
            // Kita belum buat view ini, nanti habis ini kita buat.
            return view('checkout.payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua kalau ada error
            return redirect()->back()->with('error', 'Gagal memproses order: ' . $e->getMessage());
        }
    }
}