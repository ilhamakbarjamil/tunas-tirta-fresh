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
    public function checkout(Request $request)
    {
        // 1. Validasi Input (Wajib isi Alamat)
        $request->validate([
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:200',
        ], [
            'address.required' => 'Mohon isi alamat pengiriman dulu ya kak!',
        ]);

        $user = Auth::user();
        $carts = $user->carts()->with(['product', 'variant'])->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // 2. Buat ID Unik Xendit
        $externalId = 'ORDER-' . time() . '-' . Str::random(5);

        // 3. Simpan Order ke Database (Plus Alamat & Catatan)
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 0,
            'external_id' => $externalId,

            // DATA DARI FORM
            'address' => $request->address,
            'note' => $request->note,
            'shipping_courier' => $request->shipping_courier, // <--- INI PENTING
        ]);

        $totalOrder = 0;
        $itemsForXendit = [];

        // 4. Pindahkan Keranjang ke Order Items
        foreach ($carts as $cart) {
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

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'product_variant_id' => $variantId,
                'quantity' => $cart->quantity,
                'price' => $price,
            ]);

            $itemsForXendit[] = [
                'name' => $productName,
                'quantity' => $cart->quantity,
                'price' => $price,
                'category' => 'Buah Segar',
            ];
        }

        // Update Total Harga
        $order->update(['total_price' => $totalOrder]);

        // 5. KIRIM KE XENDIT
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
        $apiInstance = new InvoiceApi();

        $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
            'external_id' => $externalId,
            'amount' => $totalOrder,
            'description' => 'Tagihan Order #' . $order->id,
            'invoice_duration' => 86400,
            'customer' => [
                'given_names' => $user->name,
                'email' => $user->email,
                // Kita bisa kirim alamat ke Xendit juga (Opsional)
                'addresses' => [
                    [
                        'city' => 'Indonesia',
                        'country' => 'ID',
                        'street_line1' => $request->address
                    ]
                ]
            ],
            'success_redirect_url' => route('orders.index'),
            'failure_redirect_url' => route('home'),
            'currency' => 'IDR',
            'items' => $itemsForXendit
        ]);

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);

            $order->update(['payment_url' => $result['invoice_url']]);
            $user->carts()->delete();

            return redirect($result['invoice_url']);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
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