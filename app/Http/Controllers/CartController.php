<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
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

        $useVariant = !empty($variantIdInput) && $variantIdInput !== 'normal';

        if ($useVariant) {
            $variant = ProductVariant::where('id', $variantIdInput)->where('product_id', $productId)->firstOrFail();
            $maxStock = $variant->stock;
            $displayName = $product->name . " (" . $variant->name . ")";
        } else {
            $maxStock = $product->stock;
            $displayName = $product->name;
            $variantIdInput = null;
        }

        if ($maxStock <= 0)
            return redirect()->back()->with('error', "❌ Stok $displayName habis.");
        if ($quantity > $maxStock)
            return redirect()->back()->with('error', "❌ Stok sisa $maxStock.");

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantIdInput)
            ->first();

        if ($cart) {
            if (($cart->quantity + $quantity) > $maxStock)
                return redirect()->back()->with('error', "❌ Total melebihi stok.");
            $cart->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'product_variant_id' => $variantIdInput,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', "✅ $displayName masuk keranjang!");
    }

    public function decrease($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } else {
            $cartItem->delete();
        }
        return redirect()->back();
    }

    public function increase($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->with(['product', 'variant'])->firstOrFail();
        $maxStock = $cartItem->product_variant_id ? $cartItem->variant->stock : $cartItem->product->stock;

        if ($cartItem->quantity < $maxStock) {
            $cartItem->increment('quantity');
            return redirect()->back();
        }
        return redirect()->back()->with('error', 'Stok maksimal.');
    }

    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Produk dihapus.');
    }

    public function checkout(Request $request)
    {
        $request->validate(['address' => 'required|string|max:500']);
        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)->with(['product', 'variant'])->get();

        if ($carts->isEmpty())
            return redirect()->back()->with('error', 'Keranjang kosong!');

        $totalOrder = 0;
        foreach ($carts as $cart) {
            $price = $cart->product_variant_id ? $cart->variant->price : $cart->product->price;
            $totalOrder += $price * $cart->quantity;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => $totalOrder,
                'external_id' => 'ORD-' . time(),
                'address' => $request->address,
                'note' => $request->note,
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_variant_id' => $cart->product_variant_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product_variant_id ? $cart->variant->price : $cart->product->price,
                ]);
            }

            // --- CONFIGURASI SNAP ---
            $params = [
                'transaction_details' => [
                    'order_id' => $order->external_id,
                    'gross_amount' => (int) $totalOrder
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
            ];

            // Minta Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Update order dengan token
            $order->update(['snap_token' => $snapToken]);

            Cart::where('user_id', $user->id)->delete();
            DB::commit();

            return view('checkout.payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack();
            // Kembalikan pesan error yang lebih jelas jika gagal
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}