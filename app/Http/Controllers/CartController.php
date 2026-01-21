<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $carts = Auth::user()->carts()->with(['product', 'variant'])->get();
        return view('cart.index', compact('carts'));
        // return view('orders.index', compact('orders'));
    }

    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();
        $variantId = $request->input('variant_id');

        // ... (LOGIKA CEK CART LAMA TETAP SAMA, TIDAK BERUBAH) ...
        $existingCart = \App\Models\Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity', $request->input('quantity', 1));
        } else {
            \App\Models\Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $request->input('quantity', 1)
            ]);
        }

        // --- UPDATE DI SINI ---
        // Kita kirim sinyal 'show_cart' => true agar Sidebar otomatis terbuka
        // return redirect()->back()->with('show_cart', true)->with('success', 'Produk berhasil ditambahkan!');
        return redirect()->back()->with('show_cart', true)->with('success', 'Produk berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        // Cart::find($id)->delete();
        // return redirect()->back()->with('success', 'Produk dihapus.');
        Cart::find($id)->delete();
        return redirect()->back()->with('show_cart', true)->with('success', 'Produk dihapus.');
    }

    // --- FITUR UTAMA: CHECKOUT XENDIT ---
    // PERBAIKAN: Tambahkan (Request $request) di dalam kurung
    // GANTI METHOD CHECKOUT YANG LAMA DENGAN INI:
    public function checkout(Request $request)
    {

        // dd($request->all());

        // 1. Validasi Input
        $request->validate([
            'address' => 'required|string|max:500',
            'shipping_courier' => 'required|string', // Wajib pilih kurir
            'note' => 'nullable|string|max:200',
        ]);

        $user = Auth::user();
        $carts = $user->carts()->with(['product', 'variant'])->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // 2. Buat Nomor Order Unik
        $externalId = 'ORD-' . time(); // Contoh: ORD-17682938

        // 3. Hitung Total & Siapkan Data Barang
        $totalOrder = 0;
        $itemsListString = ""; // Variabel untuk menampung teks daftar barang

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

            // Susun Teks Barang untuk WA (Contoh: 1. Jus Jambu x 2 = Rp 30.000)
            $no = $index + 1;
            $itemsListString .= "$no. $productName x $cart->quantity = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        // 4. Simpan Order ke Database (PENTING: Biar Admin tetap punya data)
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'status' => 'pending', // Masuk sebagai Pending
            'total_price' => $totalOrder,
            'external_id' => $externalId,
            'address' => $request->address,
            'note' => $request->note,
            'shipping_courier' => $request->shipping_courier,
            // payment_url kita kosongkan atau isi dummy karena transaksi di WA
            'payment_url' => null, 
        ]);

        // 5. Pindahkan Item ke Tabel OrderItem
        foreach ($carts as $cart) {
            $price = $cart->variant ? $cart->variant->price : $cart->product->price;
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'product_variant_id' => $cart->variant_id,
                'quantity' => $cart->quantity,
                'price' => $price,
            ]);
        }

        // 6. Hapus Keranjang
        $user->carts()->delete();

        // ---------------------------------------------------------
        // 7. SUSUN PESAN WHATSAPP (MAGIC HAPPENS HERE) ✨
        // ---------------------------------------------------------
        $waAdmin = env('WHATSAPP_ADMIN', '6281234567890'); // Ambil no dari .env
        
        $message = "Halo Kak, saya mau pesan buah dong! 🍎\n\n";
        $message .= "*No. Order:* $externalId\n";
        $message .= "*Nama:* $user->name\n";
        $message .= "*Alamat:* $request->address\n";
        $message .= "*Kurir:* $request->shipping_courier\n\n";
        
        $message .= "*Detail Pesanan:*\n";
        $message .= "----------------------------\n";
        $message .= $itemsListString; // Masukkan daftar barang tadi
        $message .= "----------------------------\n";
        $message .= "*Total Belanja: Rp " . number_format($totalOrder, 0, ',', '.') . "*\n\n";
        
        if ($request->note) {
            $message .= "*Catatan:* $request->note\n\n";
        }
        
        $message .= "Mohon info total ongkir dan nomor rekening ya kak. Terima kasih! 🙏";

        // Encode pesan agar bisa jadi URL (Spasi jadi %20, Enter jadi %0A)
        $urlWhatsApp = "https://wa.me/$waAdmin?text=" . urlencode($message);

        // 8. Redirect Langsung ke WhatsApp
        return redirect($urlWhatsApp);
    }

    public function decrease($id)
    {
        $cart = \App\Models\Cart::find($id);

        if ($cart) {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity'); // Kurangi 1
            } else {
                $cart->delete(); // Kalau sisa 1 dikurangi, jadi hapus
            }
        }

        // return redirect()->back();
        return redirect()->back()->with('show_cart', true);
    }
}