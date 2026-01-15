<?php

namespace App\Http\Controllers;

// --- BAGIAN INI SANGAT PENTING (JANGAN DIHAPUS) ---
use App\Models\Cart;
use App\Models\Order;      // <--- Agar kenal tabel Order
use App\Models\OrderItem;  // <--- Agar kenal tabel Rincian Barang
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// --------------------------------------------------

class CartController extends Controller
{
    // 1. Lihat Keranjang
    public function index()
    {
        $carts = Auth::user()->carts()->with(['product', 'variant'])->get();
        return view('cart.index', compact('carts'));
    }

    // 2. Tambah Barang
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
            $existingCart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
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

    // 4. CHECKOUT CANGGIH (Simpan ke Admin + Kirim WA)
    public function checkout()
    {
        $user = Auth::user();
        // Ambil keranjang user
        $carts = $user->carts()->with(['product', 'variant'])->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // A. Buat Order Baru di Database (Status: Pending)
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending', 
            'total_price' => 0, // Nanti diupdate
        ]);

        // B. Siapkan Teks WhatsApp
        $waText = "Halo Tunas Tirta Fresh, saya *" . $user->name . "* ingin memesan:\n\n";
        $totalOrder = 0;

        // C. Pindahkan Isi Keranjang ke 'Order Items'
        foreach ($carts as $cart) {
            // Tentukan Harga & Nama (Pakai Varian atau Normal?)
            if ($cart->variant) {
                $price = $cart->variant->price;
                $productName = $cart->product->name . " (" . $cart->variant->name . ")";
                $variantId = $cart->variant->id;
            } else {
                $price = $cart->product->price;
                $productName = $cart->product->name;
                $variantId = null;
            }

            $subtotal = $price * $cart->quantity;
            $totalOrder += $subtotal;

            // Simpan detail barang ke database
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'product_variant_id' => $variantId,
                'quantity' => $cart->quantity,
                'price' => $price,
            ]);

            // Tambah ke teks WA
            $waText .= "📦 " . $productName . " (" . $cart->quantity . "x) = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        // D. Update Total Harga di Database Order
        $order->update(['total_price' => $totalOrder]);

        // E. Lengkapi Teks WA
        $waText .= "\n💰 *Total Belanja: Rp " . number_format($totalOrder, 0, ',', '.') . "*";
        $waText .= "\n🧾 *No. Order: #" . $order->id . "*"; 
        $waText .= "\n\nMohon diproses, terima kasih!";

        // F. Kosongkan Keranjang User
        $user->carts()->delete();

        // G. Kirim ke WhatsApp
        return redirect("https://wa.me/6281331921019?text=" . urlencode($waText));
    }
}