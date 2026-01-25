<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $carts = Auth::user()->carts()->with(['product', 'variant'])->get();
        return view('cart.index', compact('carts'));
    }

    // --- FUNGSI TAMBAH KE KERANJANG ---
    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();
        $variantId = $request->input('variant_id');

        // Cek apakah produk dengan varian yang sama sudah ada di keranjang
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($existingCart) {
            // Kalau ada, tambahkan jumlahnya
            $existingCart->increment('quantity', $request->input('quantity', 1));
        } else {
            // Kalau belum ada, buat baru
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $request->input('quantity', 1),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambah ke keranjang.');
    }

    // --- FUNGSI KURANGI JUMLAH (DI HALAMAN KERANJANG) ---
    public function decrease($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($cart->quantity > 1) {
            $cart->decrement('quantity');
        } else {
            $cart->delete(); // Kalau sisa 1 dikurangi, hapus dari keranjang
        }

        return redirect()->back();
    }

    // --- FUNGSI HAPUS ITEM ---
    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Produk dihapus.');
    }

    public function checkout(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
        ]);

        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)->with(['product', 'variant'])->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        // 2. Hitung Total
        $totalPrice = $carts->sum(function ($cart) {
            $price = $cart->variant ? $cart->variant->price : $cart->product->price;
            return $price * $cart->quantity;
        });

        return DB::transaction(function () use ($user, $carts, $totalPrice, $request) {
            // 3. Simpan ke Database Order
            $order = Order::create([
                'user_id' => $user->id,
                'external_id' => 'TRX-' . time(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'address' => $request->address,
                'phone' => $request->phone,
                'note' => $request->note,
            ]);

            // 4. Susun Pesanan & Masukkan ke OrderItems
            $pesananText = "*PESANAN BARU - TUNAS TIRTA FRESH* 🍎\n";
            $pesananText .= "----------------------------------\n";
            $pesananText .= "ID Order: #" . $order->external_id . "\n\n";

            foreach ($carts as $cart) {
                $price = $cart->variant ? $cart->variant->price : $cart->product->price;

                $order->items()->create([
                    'product_id' => $cart->product_id,
                    'product_variant_id' => $cart->product_variant_id,
                    'quantity' => $cart->quantity,
                    'price' => $price,
                ]);

                $variantName = $cart->variant ? " (" . $cart->variant->name . ")" : "";
                $pesananText .= "- " . $cart->product->name . $variantName . " (x" . $cart->quantity . ")\n";
            }

            $pesananText .= "\n💰 *Total: Rp " . number_format($totalPrice, 0, ',', '.') . "*\n";
            $pesananText .= "📍 *Alamat:* " . $request->address . "\n";
            $pesananText .= "📄 *Invoice:* " . route('invoice.public', $order->external_id);

            // 5. Hapus Keranjang
            Cart::where('user_id', $user->id)->delete();

            // 6. Redirect ke WhatsApp
            $adminWA = env('WHATSAPP_ADMIN', '6281553450941');
            $waUrl = "https://api.whatsapp.com/send?phone=" . $adminWA . "&text=" . urlencode($pesananText);

            return redirect()->away($waUrl);
        });
    }
}