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
        // 1. Validasi tetap sama
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
        ]);

        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)->with('product')->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        // 2. Hitung Total
        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        // 3. Simpan ke Database Order (Status langsung 'pending' atau 'waiting')
        $order = Order::create([
            'user_id' => $user->id,
            'external_id' => 'TRX-' . time(), // Bikin ID unik
            'total_price' => $totalPrice,
            'status' => 'pending',
            'address' => $request->address,
            'phone' => $request->phone,
            'note' => $request->note,
        ]);

        // 4. Masukkan Item Keranjang ke OrderItems
        $pesananText = "Halo Admin Tunas Tirta Fresh! 👋\n";
        $pesananText .= "Saya ingin memesan produk berikut:\n\n";

        foreach ($carts as $cart) {
            $order->items()->create([
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ]);

            $pesananText .= "- " . $cart->product->name . " (x" . $cart->quantity . ")\n";
        }

        $pesananText .= "\n💰 *Total Bayar: Rp " . number_format($totalPrice, 0, ',', '.') . "*\n";
        $pesananText .= "📍 *Alamat:* " . $request->address . "\n";
        $pesananText .= "📄 *Invoice:* " . route('invoice.public', $order->external_id);

        // 5. Hapus Keranjang
        Cart::where('user_id', $user->id)->delete();

        // 6. REDIRECT KE WHATSAPP
        $adminWA = env('WHATSAPP_ADMIN', '628xxxxxxx'); // Ambil dari .env
        $waUrl = "https://api.whatsapp.com/send?phone=" . $adminWA . "&text=" . urlencode($pesananText);

        return redirect()->away($waUrl);
    }
}