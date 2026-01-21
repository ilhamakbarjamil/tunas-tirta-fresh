<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // <--- Jangan lupa import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Xendit; 
use Xendit\Invoice; 

class CartController extends Controller
{
    // 1. Tampilkan Keranjang
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        return view('cart.index', compact('carts'));
    }

    // 2. Tambah Barang ke Keranjang (INI YANG TADI HILANG)
    public function add(Request $request, $productId)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id'); // Ambil ID varian jika ada

        // Cek apakah barang yang sama (dan varian sama) sudah ada?
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($existingCart) {
            // Kalau sudah ada, tambahkan jumlahnya saja
            $existingCart->increment('quantity', $quantity);
        } else {
            // Kalau belum, buat baru
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    // 3. Update Jumlah (Tombol +/- di Keranjang)
    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        
        if ($cart) {
            // Validasi stok minimal 1
            $qty = max(1, $request->input('quantity')); 
            $cart->update(['quantity' => $qty]);
        }
        
        return redirect()->back();
    }

    // 4. Hapus Barang
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        if ($cart) {
            $cart->delete();
        }
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
    }

    // 5. Checkout Xendit (Yang tadi sudah kita perbaiki)
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

        // Hitung Total
        $totalOrder = 0;
        foreach ($carts as $cart) {
            $price = $cart->variant ? $cart->variant->price : $cart->product->price;
            $totalOrder += $price * $cart->quantity;
        }

        // Setup Xendit
        Xendit::setApiKey(env('XENDIT_SECRET_KEY'));
        
        // Buat Nomor Unik
        $externalId = 'ORD-' . time() . '-' . rand(100, 999); 

        $params = [
            'external_id' => $externalId,
            'amount' => $totalOrder,
            'description' => 'Order Buah oleh ' . $user->name,
            'invoice_duration' => 86400,
            'customer' => [
                'given_names' => $user->name,
                'email' => $user->email,
            ],
            'success_redirect_url' => route('orders.index'),
            'failure_redirect_url' => route('orders.index'),
        ];

        try {
            $createInvoice = Invoice::create($params);
            $paymentUrl = $createInvoice['invoice_url'];

            // Simpan Order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => $totalOrder,
                'external_id' => $externalId,
                'payment_url' => $paymentUrl,
                'address' => $request->address,
                'note' => $request->note,
                'shipping_courier' => 'Menyesuaikan',
            ]);

            // Pindahkan Item
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

            // Hapus Keranjang
            Cart::where('user_id', $user->id)->delete();

            return redirect($paymentUrl);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}