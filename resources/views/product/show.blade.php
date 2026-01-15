<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-green-600 font-bold">← Kembali</a>
            <a href="{{ route('cart.index') }}" class="relative p-2">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                    {{ Auth::check() ? Auth::user()->carts->sum('quantity') : 0 }}
                </span>
            </a>
        </div>
    </div>

    <main class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col md:flex-row">
            <div class="md:w-1/2 bg-gray-50 p-8 flex items-center justify-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="max-h-[400px] object-contain">
                @else
                    <span class="text-gray-400">No Image</span>
                @endif
            </div>
            <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>
                <div class="mb-8 text-3xl font-bold text-red-500">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <p class="text-gray-600 mb-10">{{ $product->description ?? 'Deskripsi produk belum tersedia.' }}</p>

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">Success! Masuk keranjang.</div>
                @endif

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl text-lg shadow-lg">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>