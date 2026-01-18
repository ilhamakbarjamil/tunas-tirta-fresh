@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">

        <h1 class="text-3xl font-light text-red-400 mb-8">Shopping cart</h1>

        @if($carts->isEmpty())
            <div class="text-center py-20 bg-white rounded-xl shadow-sm">
                <i class="fas fa-shopping-basket text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-500 text-lg">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 text-primary font-bold hover:underline">
                    Mulai Belanja &rarr;
                </a>
            </div>
        @else

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">

                        @foreach($carts as $cart)
                            <div class="flex items-center p-6 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition">

                                <div
                                    class="w-24 h-24 flex-shrink-0 bg-gray-50 rounded-md border border-gray-200 overflow-hidden p-2">
                                    <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}"
                                        class="w-full h-full object-contain">
                                </div>

                                <div class="ml-6 flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-800">
                                                {{ $cart->product->name }}
                                            </h3>
                                            @if($cart->variant)
                                                <p class="text-sm text-gray-400 mt-1">Weight: {{ $cart->variant->name }}</p>
                                            @else
                                                <p class="text-sm text-gray-400 mt-1">Satuan: Pack</p>
                                            @endif
                                        </div>

                                        <div class="text-right">
                                            @php
                                                $price = $cart->variant ? $cart->variant->price : $cart->product->price;
                                            @endphp
                                            <p class="text-lg font-bold text-gray-800">Rp {{ number_format($price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-end mt-4">

                                        <div class="flex items-center bg-gray-100 rounded-lg h-10">
                                            <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 text-gray-500 hover:text-red-500 transition h-full font-bold text-lg">-</button>
                                            </form>

                                            <input type="text" value="{{ $cart->quantity }}" readonly
                                                class="w-10 bg-transparent text-center text-sm font-bold text-gray-700 focus:outline-none">

                                            <form action="{{ route('cart.add', $cart->product_id) }}" method="POST">
                                                @csrf
                                                @if($cart->product_variant_id)
                                                    <input type="hidden" name="variant_id" value="{{ $cart->product_variant_id }}">
                                                @endif
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="px-3 text-gray-500 hover:text-green-600 transition h-full font-bold text-lg">+</button>
                                            </form>
                                        </div>

                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-red-500 transition text-xl p-2"
                                                title="Hapus Barang">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <a href="{{ route('home') }}"
                        class="inline-block mt-6 px-6 py-3 bg-gray-200 text-gray-600 font-semibold rounded-full hover:bg-gray-300 transition">
                        &larr; Continue shopping
                    </a>
                </div>


                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-xl p-6 sticky top-24 border border-gray-100">

                        <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4">Order Summary</h3>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>{{ $carts->count() }} items</span>
                                @php
                                    $total = $carts->sum(function ($item) {
                                        $p = $item->variant ? $item->variant->price : $item->product->price;
                                        return $p * $item->quantity;
                                    });
                                @endphp
                                <span class="font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span class="border-b border-dotted border-gray-400 cursor-pointer">Delivery</span>
                                <span class="text-green-600 font-bold">Free</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Taxes</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="pt-4 border-t flex justify-between items-center text-xl font-bold text-gray-800">
                                <span>TOTAL</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <a href="#" class="text-sm text-gray-500 underline hover:text-primary">Have a promo code?</a>
                        </div>


                        <form action="{{ route('checkout.process') }}" method="POST"
                            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            @csrf

                            <h3 class="font-bold text-lg mb-4 text-dark border-b pb-2">Data Pengiriman</h3>

                            <div class="mb-4">
                                <label class="block font-bold text-sm text-gray-600 mb-2">Alamat Lengkap</label>
                                <textarea name="address" required
                                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                    rows="3" placeholder="Jalan, No Rumah, Patokan..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block font-bold text-sm text-gray-600 mb-2">Metode Pengiriman</label>
                                <div class="relative">
                                    <select name="shipping_courier"
                                        class="w-full border border-gray-300 p-3 rounded-lg appearance-none bg-white focus:ring-2 focus:ring-primary focus:border-transparent cursor-pointer">
                                        <option value="Kurir Toko (Bayar Ongkir di Tempat)">🛵 Kurir Toko (Ongkir Bayar di
                                            Tempat)</option>
                                        <option value="GoSend/Grab (Bayar di Tempat)">🟢 GoSend/Grab Instant (Ongkir Bayar di
                                            Tempat)</option>
                                        <option value="Ambil Sendiri (Gratis)">🏪 Ambil Sendiri di Toko (Gratis)</option>
                                        <option value="Ekspedisi (Menyesuaikan)">📦 Ekspedisi Luar Kota (Ongkir Transfer Manual)
                                        </option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-2 bg-gray-50 p-2 rounded border border-gray-200">
                                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                    Total bayar di Xendit <b>HANYA harga produk</b>. Ongkos kirim ditanggung pembeli saat barang
                                    sampai.
                                </p>
                            </div>

                            <div class="mb-6">
                                <label class="block font-bold text-sm text-gray-600 mb-2">Catatan (Opsional)</label>
                                <input type="text" name="note"
                                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Pagar hitam, jangan dibanting...">
                            </div>

                            <button type="submit"
                                class="w-full bg-primary hover:bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-500/30 transition transform hover:scale-[1.02] flex justify-center items-center gap-2">
                                <span>LANJUT KE PEMBAYARAN</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>

                        <div class="mt-8 space-y-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-tree text-green-600 text-xl mt-1"></i>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-800">Locally Sourced</h4>
                                    <p class="text-xs text-gray-500">Supporting local farmers & producers.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-truck text-green-600 text-xl mt-1"></i>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-800">Express Delivery</h4>
                                    <p class="text-xs text-gray-500">Convenient delivery to your door.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection