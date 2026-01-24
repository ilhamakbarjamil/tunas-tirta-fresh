<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
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
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        return view('cart.index', compact('carts'));
    }

    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $product = Product::findOrFail($productId);
        $variantIdInput = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);

        $useVariant = !empty($variantIdInput) && $variantIdInput !== 'normal';

        if ($useVariant) {
            $variant = ProductVariant::where('id', $variantIdInput)->where('product_id', $productId)->firstOrFail();
            $maxStock = $variant->stock;
            $displayName = $product->name . " (" . $variant->name . ")";
        } else {
            $maxStock = $product->stock;
            $displayName = $product->name;
            $variantIdInput = null;
        }

        if ($maxStock <= 0)
            return redirect()->back()->with('error', "❌ Stok $displayName habis.");
        if ($quantity > $maxStock)
            return redirect()->back()->with('error', "❌ Stok sisa $maxStock.");

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantIdInput)
            ->first();

        if ($cart) {
            if (($cart->quantity + $quantity) > $maxStock)
                return redirect()->back()->with('error', "❌ Total melebihi stok.");
            $cart->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'product_variant_id' => $variantIdInput,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', "✅ $displayName masuk keranjang!");
    }

    public function decrease($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } else {
            $cartItem->delete();
        }
        return redirect()->back();
    }

    public function increase($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                $cart->delete();
            }
        }
        return redirect()->back()->with('error', 'Stok maksimal.');
    }

    public function destroy($id)
    {
        // 1. Validasi: User wajib isi alamat
        $request->validate([
            'phone'   => 'required|numeric',
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:200',
        ]);

    public function checkout(Request $request)
    {
        $request->validate(['address' => 'required|string|max:500']);
        $user = Auth::user();

        // 2. Cek Keranjang
        // (Pastikan Mas punya relasi 'carts' di model User, atau sesuaikan query ini)
        $carts = \App\Models\Cart::where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong, mau beli angin?');
        }

        foreach ($carts as $cart) {
            if (!$cart->product->is_available) {
                // KICK USER BALIK KE KERANJANG
                return redirect()->route('cart.index')->with('error', 
                    'Maaf, produk "' . $cart->product->name . '" baru saja habis/tidak tersedia!'
                );
            }
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
                'phone'   => $request->phone,
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