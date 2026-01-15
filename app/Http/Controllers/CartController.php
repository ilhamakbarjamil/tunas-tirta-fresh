<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Lihat Keranjang
    public function index()
    {
        $carts = Auth::user()->carts()->with('product')->get();
        return view('cart.index', compact('carts'));
    }

    // 2. Tambah Barang
    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();
        $existingCart = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($existingCart) {
            $existingCart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Produk masuk keranjang!');
    }

    // 3. Hapus Barang
    public function destroy($id)
    {
        Cart::find($id)->delete();
        return redirect()->back()->with('success', 'Produk dihapus.');
    }

    // 4. Checkout WA
    public function checkout()
    {
        $user = Auth::user();
        $carts = $user->carts()->with('product')->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        $text = "Halo Tunas Tirta Fresh, saya *" . $user->name . "* ingin pesan:\n\n";
        $total = 0;

        foreach ($carts as $cart) {
            $subtotal = $cart->product->price * $cart->quantity;
            $text .= "📦 " . $cart->product->name . " (" . $cart->quantity . "x) = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            $total += $subtotal;
        }

        $text .= "\n💰 *Total: Rp " . number_format($total, 0, ',', '.') . "*";
        $text .= "\n\nMohon diproses!";

        // Hapus keranjang setelah checkout (Opsional, aktifkan jika mau)
        // Cart::where('user_id', $user->id)->delete();

        return redirect("https://wa.me/62812345678?text=" . urlencode($text));
    }
}