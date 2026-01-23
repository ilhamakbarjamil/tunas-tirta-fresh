<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant; // <--- INI YANG PENTING (Tambahkan ini) 
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

        $product = Product::findOrFail($productId);
        $variantIdInput = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);

        // VALIDASI: Harus pilih varian (tidak ada lagi opsi "normal")
        if (empty($variantIdInput) || $variantIdInput === 'normal') {
            return redirect()->back()->with('error', '❌ Silakan pilih varian/satuan terlebih dahulu.');
        }

        // AMBIL STOK DARI TABEL VARIAN SAJA
        $variant = ProductVariant::where('id', $variantIdInput)
            ->where('product_id', $productId)
            ->firstOrFail();
        
        $maxStock = $variant->stock;
        $displayName = $product->name . " (" . $variant->name . ")";

        // VALIDASI STOK
        if ($maxStock <= 0) {
            return redirect()->back()->with('error', "❌ Stok untuk $displayName saat ini habis.");
        }

        if ($quantity > $maxStock) {
            return redirect()->back()->with('error', "❌ Stok tidak cukup. Hanya tersedia $maxStock pcs.");
        }

        // SIMPAN KE KERANJANG (Logika Upsert: Jika sudah ada, tambah quantity)
        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantIdInput)
            ->first();

        if ($cart) {
            $totalQuantity = $cart->quantity + $quantity;
            if ($totalQuantity > $maxStock) {
                return redirect()->back()->with('error', "❌ Gagal! Total di keranjang melebihi stok tersedia.");
            }
            $cart->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'product_variant_id' => $variantIdInput,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', "✅ $displayName berhasil masuk keranjang!");
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        if ($cart) {
            $qty = max(1, $request->input('quantity'));
            
            // Validasi stock - HANYA DARI VARIAN
            if (!$cart->product_variant_id) {
                return redirect()->back()->with('error', "❌ Item ini tidak memiliki varian. Silakan hapus dan tambah ulang.");
            }
            
            $maxStock = $cart->variant->stock;
            $displayName = $cart->product->name . " (" . $cart->variant->name . ")";
            
            if ($maxStock <= 0) {
                return redirect()->back()->with('error', "❌ Stok untuk $displayName saat ini habis.");
            }
            
            if ($qty > $maxStock) {
                return redirect()->back()->with('error', "❌ Stok tidak cukup. Hanya tersedia $maxStock pcs.");
            }
            
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

    public function decrease($id)
    {
        // Cari item keranjang berdasarkan ID
        $cartItem = \App\Models\Cart::where('id', $id)
            ->where('user_id', auth()->id()) // Pastikan milik user yang login
            ->firstOrFail();

        if ($cartItem->quantity > 1) {
            // Jika jumlah lebih dari 1, kurangi 1
            $cartItem->decrement('quantity');
            return redirect()->back()->with('success', 'Jumlah produk berhasil dikurangi.');
        } else {
            // Jika jumlah sudah 1 dan ditekan kurang, biasanya dihapus atau biarkan saja
            // Pilih salah satu:

            // Opsi A: Hapus item jika dikurangi saat jumlahnya 1
            $cartItem->delete();
            return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');

            /* 
            Opsi B: Jangan lakukan apa-apa (tetap 1)
            return redirect()->back()->with('info', 'Minimal pembelian adalah 1.'); 
            */
        }
    }

    public function increase($id)
    {
        $cartItem = \App\Models\Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Cek stok - HANYA DARI VARIAN
        if (!$cartItem->product_variant_id) {
            return redirect()->back()->with('error', '❌ Item ini tidak memiliki varian.');
        }
        
        $maxStock = $cartItem->variant->stock;

        if ($cartItem->quantity < $maxStock) {
            $cartItem->increment('quantity');
            return redirect()->back();
        }

        return redirect()->back()->with('error', 'Stok sudah maksimal.');
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

        // Validasi Stock sebelum checkout - HANYA DARI VARIAN
        foreach ($carts as $cart) {
            if (!$cart->product_variant_id) {
                return redirect()->back()->with('error', "❌ Item '{$cart->product->name}' tidak memiliki varian. Silakan hapus dan tambah ulang dengan varian.");
            }
            
            $variant = ProductVariant::find($cart->product_variant_id);
            if (!$variant || $variant->stock < $cart->quantity) {
                $displayName = $cart->product->name . " (" . ($variant ? $variant->name : 'Varian') . ")";
                $availableStock = $variant ? $variant->stock : 0;
                return redirect()->back()->with('error', "❌ Stok tidak cukup untuk $displayName. Tersedia: $availableStock pcs, dibutuhkan: {$cart->quantity} pcs.");
            }
        }

        // Hitung Total - HANYA DARI VARIAN
        $totalOrder = 0;
        foreach ($carts as $cart) {
            if (!$cart->variant) {
                return redirect()->back()->with('error', "❌ Item '{$cart->product->name}' tidak memiliki varian yang valid.");
            }
            $totalOrder += $cart->variant->price * $cart->quantity;
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

            // B. Pindahkan Item ke OrderItem - HANYA DARI VARIAN
            foreach ($carts as $cart) {
                if (!$cart->variant) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "❌ Item '{$cart->product->name}' tidak memiliki varian yang valid.");
                }
                
                $price = $cart->variant->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_variant_id' => $cart->product_variant_id,
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