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
        $variantId = $request->input('variant_id'); // Ambil data dari dropdown

        // Cek apakah produk + varian ini sudah ada di keranjang?
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId) // Cek variannya juga
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId, // Simpan varian ID
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
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
        // Ambil keranjang beserta data produk DAN variannya
        $carts = $user->carts()->with(['product', 'variant'])->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // Header Pesan WA
        $text = "Halo Tunas Tirta Fresh, saya *" . $user->name . "* ingin memesan:\n\n";
        $total = 0;

        foreach ($carts as $cart) {
            // LOGIKA PENENTUAN HARGA & NAMA
            if ($cart->variant) {
                // Jika user pilih varian (Misal: 1 Kg)
                $price = $cart->variant->price;
                $productName = $cart->product->name . " (" . $cart->variant->name . ")";
            } else {
                // Jika produk standar (tanpa varian)
                $price = $cart->product->price;
                $productName = $cart->product->name;
            }

            $subtotal = $price * $cart->quantity;
            
            // Format Baris: 📦 Apel Fuji (1 Kg) (2x) = Rp 70.000
            $text .= "📦 " . $productName . " (" . $cart->quantity . "x) = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            
            $total += $subtotal;
        }

        // Footer Pesan WA
        $text .= "\n💰 *Total Belanja: Rp " . number_format($total, 0, ',', '.') . "*";
        $text .= "\n\nMohon diproses, terima kasih!";

        // Kirim ke WhatsApp
        return redirect("https://wa.me/62812345678?text=" . urlencode($text));
    }
}