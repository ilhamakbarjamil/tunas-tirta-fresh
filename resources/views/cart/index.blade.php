@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 border-b-2 border-gray-100 pb-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Keranjang Belanja</h2>
            <p class="text-gray-600 font-medium mt-1">Tinjau kembali pesanan Anda sebelum melakukan pembayaran.</p>
        </div>
    </div>

    @if($carts->isEmpty())
        <div class="text-center py-20 bg-white border border-gray-200 rounded-lg shadow-sm">
            <i class="fas fa-shopping-basket text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800">Keranjang Anda Kosong</h3>
            <p class="text-gray-500 mb-6">Sepertinya Anda belum menambahkan produk apapun.</p>
            <a href="{{ route('home') }}" class="inline-block bg-gray-900 hover:bg-primary text-white font-bold px-8 py-3 rounded-md transition-colors">
                Mulai Belanja
            </a>
        </div>
    @else

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Daftar Produk -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    @foreach($carts as $cart)
                        <div class="group flex flex-col sm:flex-row items-center p-6 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                            
                            <!-- Image (Style mirip thumbnail produk) -->
                            <div class="w-24 h-24 flex-shrink-0 bg-white border border-gray-100 rounded-md p-2 overflow-hidden">
                                <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}"
                                    class="w-full h-full object-contain transform group-hover:scale-105 transition duration-300">
                            </div>

                            <!-- Details -->
                            <div class="sm:ml-6 flex-1 w-full mt-4 sm:mt-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors leading-tight">
                                            {{ $cart->product->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1 font-medium italic">
                                            {{ $cart->variant ? 'Weight: ' . $cart->variant->name : 'Satuan: Pack' }}
                                        </p>
                                    </div>
                                    @php $price = $cart->variant ? $cart->variant->price : $cart->product->price; @endphp
                                    <div class="text-right">
                                        <p class="text-lg font-black text-primary">
                                            Rp {{ number_format($price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center mt-6">
                                    <!-- Quantity Selector (Style sama dengan product list) -->
                                    <div class="flex items-center gap-1">
                                        <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 font-bold text-gray-600">-</button>
                                        </form>

                                        <input type="text" value="{{ $cart->quantity }}" readonly
                                            class="w-12 h-8 text-center font-bold text-gray-800 bg-transparent border-0 focus:outline-none">

                                        <form action="{{ route('cart.add', $cart->product_id) }}" method="POST">
                                            @csrf
                                            @if($cart->product_variant_id)
                                                <input type="hidden" name="variant_id" value="{{ $cart->product_variant_id }}">
                                            @endif
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 font-bold text-gray-600">+</button>
                                        </form>
                                    </div>

                                    <!-- Remove Button -->
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1 text-sm font-medium">
                                            <i class="far fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 font-bold hover:text-primary transition-colors">
                    <i class="fas fa-arrow-left mr-2 text-xs"></i> Lanjut Belanja Produk Lain
                </a>
            </div>


            <!-- Right Side: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden sticky top-8">
                    
                    <div class="p-5 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-extrabold text-gray-900 uppercase tracking-wider">Ringkasan Pesanan</h3>
                    </div>

                    <div class="p-6">
                        @php
                            $total = $carts->sum(function ($item) {
                                $p = $item->variant ? $item->variant->price : $item->product->price;
                                return $p * $item->quantity;
                            });
                        @endphp

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600 font-medium">
                                <span>Total ({{ $carts->count() }} Produk)</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600 font-medium italic text-sm">
                                <span>Pengiriman</span>
                                <span class="text-primary font-bold">Bayar di Tujuan</span>
                            </div>
                            <div class="pt-4 border-t-2 border-dashed border-gray-100 flex justify-between items-center">
                                <span class="text-gray-900 font-bold">ESTIMASI TOTAL</span>
                                <span class="text-2xl font-black text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Checkout Form (Style Input sama dengan product list) -->
                        <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Alamat Lengkap</label>
                                <textarea name="address" required
                                    class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm min-h-[80px]"
                                    placeholder="Jalan, No Rumah, Patokan..."></textarea>
                            </div>

                            <!-- <div>
                                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Metode Pengiriman</label>
                                <div class="relative">
                                    <select name="shipping_courier"
                                        class="w-full border border-gray-300 p-3 rounded-md appearance-none bg-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm cursor-pointer">
                                        <option value="Kurir Toko (Bayar Ongkir di Tempat)">🛵 Kurir Toko (COD Ongkir)</option>
                                        <option value="GoSend/Grab (Bayar di Tempat)">🟢 GoSend/Grab (COD Ongkir)</option>
                                        <option value="Ambil Sendiri (Gratis)">🏪 Ambil Sendiri di Toko</option>
                                        <option value="Ekspedisi (Menyesuaikan)">📦 Ekspedisi Luar Kota</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div> -->

                            <div>
                                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Catatan Pesanan</label>
                                <input type="text" name="note"
                                    class="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm"
                                    placeholder="Contoh: Pagar hitam...">
                            </div>

                            <button type="submit"
                                class="w-full bg-gray-900 hover:bg-primary text-white font-black py-4 rounded-md transition-colors duration-200 flex justify-center items-center gap-2 uppercase tracking-widest text-sm">
                                <span>Bayar Sekarang</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </form>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex items-center gap-3 text-gray-400">
                                <i class="fas fa-lock text-sm"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Secure Payment via Xendit</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    @endif
</div>
@endsection