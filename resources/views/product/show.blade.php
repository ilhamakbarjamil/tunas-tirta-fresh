@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-4 border-b">
    <div class="container mx-auto px-4 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a> 
        <span class="mx-2">/</span> 
        <span class="text-gray-800 font-medium">{{ $product->name }}</span>
    </div>
</div>

<div class="container mx-auto px-4 py-10">
    <div class="bg-white rounded-xl shadow-sm border p-6 md:p-10 flex flex-col md:flex-row gap-10">
        
        <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-50 rounded-xl p-8">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="max-h-[400px] object-contain drop-shadow-lg hover:scale-105 transition duration-500">
        </div>

        <div class="w-full md:w-1/2">
            <h1 class="text-3xl md:text-4xl font-bold text-dark mb-2">{{ $product->name }}</h1>
            
            <div class="flex items-center text-sm mb-4">
                <div class="text-yellow-400 flex mr-2">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <span class="text-gray-400">(15 Reviews)</span>
            </div>

            <div class="text-3xl font-bold text-primary mb-6">
                Rp {{ number_format($product->price, 0, ',', '.') }}
                <span class="text-sm font-normal text-gray-400">/ pack</span>
            </div>

            <p class="text-gray-500 leading-relaxed mb-8 border-b pb-8">
                {{ $product->description }}
            </p>

            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                
                @if($product->variants && $product->variants->count() > 0)
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Pilih Ukuran/Berat:</label>
                        <select name="variant_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-primary bg-white">
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}">
                                    {{ $variant->name }} - Rp {{ number_format($variant->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mb-6">
                     @if($product->stock > 5)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                            ✅ Tersedia (Ready Stock)
                        </span>
                    @elseif($product->stock > 0)
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm font-semibold">
                            🔥 Stok Menipis (Sisa {{ $product->stock }})
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                            ❌ Stok Habis
                        </span>
                    @endif
                </div>

                <div class="flex space-x-4">
                    @if($product->stock > 0)
                        <button type="submit" class="flex-1 bg-primary hover:bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:scale-105 flex justify-center items-center gap-2">
                            <i class="fas fa-shopping-cart"></i>
                            Masukkan Keranjang
                        </button>
                    @else
                        <button type="button" disabled class="flex-1 bg-gray-300 text-gray-500 font-bold py-4 rounded-xl cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif

                    <button type="button" class="w-14 h-14 border border-gray-300 rounded-xl flex items-center justify-center text-gray-400 hover:text-danger hover:border-danger transition">
                        <i class="far fa-heart text-xl"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-sm text-gray-500 space-y-2">
                <p><i class="fas fa-truck mr-2 text-primary"></i> Pengiriman Cepat (1-2 Jam Sampai)</p>
                <p><i class="fas fa-shield-alt mr-2 text-primary"></i> Jaminan Buah Segar</p>
            </div>
        </div>
    </div>
</div>
@endsection