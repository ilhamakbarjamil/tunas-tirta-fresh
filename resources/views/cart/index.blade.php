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
        @if($carts->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-400 text-xl">Keranjang Kosong.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-full">Belanja Dulu</a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow overflow-hidden">
                @php $grandTotal = 0; @endphp
                @foreach($carts as $cart)
                @php $sub = $cart->product->price * $cart->quantity; $grandTotal += $sub; @endphp
                <div class="flex items-center justify-between p-4 border-b">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                            @if($cart->product->image) <img src="{{ asset('storage/'.$cart->product->image) }}" class="h-full object-contain"> @else <span>No IMG</span> @endif
                        </div>
                        <div>
                            <h3 class="font-bold">{{ $cart->product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $cart->quantity }} x Rp {{ number_format($cart->product->price,0,',','.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-red-500">Rp {{ number_format($sub,0,',','.') }}</span>
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
                        <h2 class="text-3xl font-bold">Rp {{ number_format($grandTotal,0,',','.') }}</h2>
                    </div>
                    <a href="{{ route('cart.checkout') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-lg">Checkout WhatsApp</a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>