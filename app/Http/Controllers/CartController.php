<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
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
        // 1. Validasi Input
        $request->validate([
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:200',
            // shipping_courier tidak divalidasi ketat karena bisa hidden/null
        ]);

        $user = Auth::user();
        $carts = $user->carts()->with(['product', 'variant'])->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // 2. Buat Nomor Order & Hitung Total
        $externalId = 'ORD-' . time(); 
        $totalOrder = 0;
        $itemsListString = ""; 

        foreach ($carts as $index => $cart) {
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

            // Susun teks daftar barang untuk WA
            $no = $index + 1;
            $itemsListString .= "$no. $productName x $cart->quantity = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        // 3. Simpan ke Database (PENTING: Agar Admin punya data)
        // Kita set default kurir "Menyesuaikan" jika kosong
        $courier = $request->shipping_courier ?? 'Menyesuaikan (Hubungi Admin)';

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending', // Status awal Pending
            'total_price' => $totalOrder,
            'external_id' => $externalId,
            'address' => $request->address,
            'note' => $request->note,
            'shipping_courier' => $courier,
            'payment_url' => null, 
        ]);

        // 4. Simpan Item Detail
        foreach ($carts as $cart) {
            $price = $cart->variant ? $cart->variant->price : $cart->product->price;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'product_variant_id' => $cart->variant_id,
                'quantity' => $cart->quantity,
                'price' => $price,
            ]);
        }

        // 5. Hapus Keranjang User
        $user->carts()->delete();

        // 6. Redirect ke WhatsApp
        $waAdmin = env('WHATSAPP_ADMIN', '6281234567890'); // Pastikan no di .env ada
        
        $message = "Halo Kak, saya mau pesan buah dong! 🍎\n\n";
        $message .= "*No. Order:* $externalId\n";
        $message .= "*Nama:* $user->name\n";
        $message .= "*Alamat:* $request->address\n";
        $message .= "*Kurir:* $courier\n\n";
        
        $message .= "*Detail Pesanan:*\n";
        $message .= "----------------------------\n";
        $message .= $itemsListString;
        $message .= "----------------------------\n";
        $message .= "*Total Belanja: Rp " . number_format($totalOrder, 0, ',', '.') . "*\n\n";
        
        if ($request->note) {
            $message .= "*Catatan:* $request->note\n\n";
        }
        
        $message .= "Mohon info total ongkir dan nomor rekening ya kak. Terima kasih! 🙏";

        $urlWhatsApp = "https://wa.me/$waAdmin?text=" . urlencode($message);

        return redirect($urlWhatsApp);
    }
}