@extends('layouts.app')

@section('content')
<div class="border-b border-gray-200 bg-white">
    <div class="container mx-auto px-4 py-4">
        <nav class="flex text-xs font-bold uppercase tracking-widest text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-dark transition-colors">Home</a> 
            <span class="mx-3 text-gray-300">/</span> 
            <a href="#" class="hover:text-dark transition-colors">{{ $product->category->name ?? 'Product' }}</a>
            <span class="mx-3 text-gray-300">/</span> 
            <span class="text-dark">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">
        
        <div class="w-full lg:w-7/12">
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-10 flex justify-center items-center relative overflow-hidden group">
                @if($product->stock <= 5 && $product->stock > 0)
                    <div class="absolute top-4 left-4 bg-red-600 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-wider">
                        Stok Menipis
                    </div>
                @elseif($product->stock > 5)
                    <div class="absolute top-4 left-4 bg-primary text-white text-[10px] font-bold px-3 py-1 uppercase tracking-wider">
                        Ready Stock
                    </div>
                @endif

                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="max-h-[500px] w-auto object-contain drop-shadow-md transform transition duration-700 group-hover:scale-105">
            </div>

            <div class="grid grid-cols-3 gap-4 mt-6">
                <div class="border border-gray-200 p-4 text-center rounded hover:border-dark transition-colors">
                    <i class="fas fa-truck-fast text-2xl text-dark mb-2"></i>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Kirim Cepat</p>
                </div>
                <div class="border border-gray-200 p-4 text-center rounded hover:border-dark transition-colors">
                    <i class="fas fa-leaf text-2xl text-dark mb-2"></i>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">100% Segar</p>
                </div>
                <div class="border border-gray-200 p-4 text-center rounded hover:border-dark transition-colors">
                    <i class="fas fa-shield-alt text-2xl text-dark mb-2"></i>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Garansi</p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-5/12 flex flex-col">
            
            <h1 class="text-4xl md:text-5xl font-black text-dark uppercase leading-none mb-4 tracking-tight">
                {{ $product->name }}
            </h1>

            <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-6">
                <div class="flex text-yellow-500 text-sm">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Terjual 150+ Unit</p>
            </div>

            <div class="mb-8">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Harga Terbaik</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-primary">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm font-bold text-gray-400">/ pack</span>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-xs font-black text-dark uppercase tracking-widest mb-3">Deskripsi Produk</h3>
                <p class="text-gray-600 text-sm leading-relaxed text-justify">
                    {{ $product->description }}
                </p>
            </div>

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                @csrf
                
                @if($product->variants && $product->variants->count() > 0)
                    <div class="mb-6">
                        <label class="block text-xs font-black text-dark uppercase tracking-widest mb-2">Pilih Varian</label>
                        <div class="relative">
                            <select name="variant_id" class="w-full appearance-none bg-white border-2 border-gray-200 text-dark font-bold py-3 px-4 rounded-none focus:outline-none focus:border-dark transition-colors">
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->name }} (+ Rp {{ number_format($variant->price - $product->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-dark">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-3">
                    @if($product->stock > 0)
                        <button type="submit" class="w-full bg-dark hover:bg-primary text-white font-bold text-sm uppercase tracking-[0.15em] py-4 px-6 transition-all duration-300 border border-transparent hover:shadow-lg flex items-center justify-center gap-3 group">
                            <span>Masukkan Keranjang</span>
                            <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    @else
                         <button type="button" disabled class="w-full bg-gray-200 text-gray-400 font-bold text-sm uppercase tracking-widest py-4 cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                    
                    </div>
            </form>
            
            <div class="mt-6 flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-wide">
                <i class="fas fa-lock"></i> Transaksi Aman & Terenkripsi
            </div>

        </div>
    </div>
</div>
@endsection