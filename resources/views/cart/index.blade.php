<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">
    <div class="bg-white shadow p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="font-bold text-gray-600">← Kembali</a>
            <h1 class="text-xl font-bold text-green-600">Keranjang Saya</h1>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        @if ($carts->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-400 text-xl">Keranjang Kosong.</p>
                <a href="{{ route('home') }}"
                    class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-full">Belanja Dulu</a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow overflow-hidden">
                @php $grandTotal = 0; @endphp

                @foreach ($carts as $cart)
                    @php
                        // LOGIKA PINTAR: Cek apakah item ini punya varian?
                        if ($cart->variant) {
                            $price = $cart->variant->price;
                            $variantName = ' (' . $cart->variant->name . ')';
                        } else {
                            $price = $cart->product->price;
                            $variantName = ''; // Kosong kalau tidak ada varian
                        }

                        $subtotal = $price * $cart->quantity;
                        $grandTotal += $subtotal;
                    @endphp

                    <div class="flex items-center justify-between p-4 border-b">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                @if ($cart->product->image)
                                    <img src="{{ asset('storage/' . $cart->product->image) }}"
                                        class="h-full object-contain">
                                @else
                                    <span class="text-xs text-gray-400">No IMG</span>
                                @endif
                            </div>

                            <div>
                                <h3 class="font-bold text-gray-800">
                                    {{ $cart->product->name }} <span
                                        class="text-green-600 text-sm">{{ $variantName }}</span>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $cart->quantity }} x Rp {{ number_format($price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="font-bold text-red-500">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>

                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-gray-300 hover:text-red-500 font-bold text-xl">&times;</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="p-6 bg-green-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Pembayaran</p>
                        <h2 class="text-3xl font-bold text-gray-800">Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </h2>
                    </div>
                    <form action="{{ route('cart.checkout') }}" method="GET"
                        class="mt-6 bg-white p-4 rounded-lg shadow-sm border">

                        <h3 class="font-bold text-gray-700 mb-3">Info Pengiriman</h3>

                        <div class="mb-4">
                            <label class="block text-sm text-gray-600 mb-1">Alamat Lengkap <span
                                    class="text-red-500">*</span></label>
                            <textarea name="address" rows="2" required placeholder="Jalan, Nomor Rumah, RT/RW, Kecamatan..."
                                class="w-full border rounded-lg p-2"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-gray-600 mb-1">Catatan (Opsional)</label>
                            <input type="text" name="note"
                                placeholder="Contoh: Pagar warna hitam / Titip di pos satpam"
                                class="w-full border rounded-lg p-2">
                        </div>

                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition transform hover:scale-105">
                            Lanjut ke Pembayaran →
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</body>

</html>
