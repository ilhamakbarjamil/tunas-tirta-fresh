<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tunas Tirta Fresh') }} - Premium Quality</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#00A859',         // Hijau Brand
                        primaryDark: '#008F4C',     // Hijau Tua (Hover)
                        secondary: '#D32F2F',       // Merah Aksen
                        dark: '#111827',            // Hitam Pekat (Tegas)
                        medium: '#4B5563',          // Abu-abu Gelap
                        light: '#F3F4F6',           // Background Abu Sangat Muda
                        border: '#E5E7EB',          // Garis Batas
                        highlight: '#F0FDF4',       // Hijau Sangat Muda
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05)',
                        'mega': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'card': '0 1px 3px rgba(0,0,0,0.1)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animasi Mega Menu */
        .group:hover .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .mega-menu {
            transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
            transform: translateY(10px);
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>

<body class="bg-white text-dark flex flex-col min-h-screen">

    <div class="bg-dark text-white text-xs font-medium py-2.5">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fas fa-check-circle text-primary mr-1"></i> Jaminan Kualitas 100%</span>
                <span class="hidden sm:inline text-gray-400">|</span>
                <span class="hidden sm:inline"><i class="fas fa-truck text-primary mr-1"></i> Pengiriman Cepat Bali</span>
            </div>
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('orders.index') }}" class="hover:text-primary transition-colors">Pesanan Saya</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-primary transition-colors">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-primary transition-colors font-semibold">Masuk / Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    <header class="bg-white border-b border-border py-6 relative z-30">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between gap-6 md:gap-10">

                <a href="{{ route('home') }}" class="flex-shrink-0 group">
                    <div class="flex items-center gap-4">
                        <div class="w-24 h-auto flex-shrink-0 flex items-center justify-start">
                            <img src="{{ asset('images/logo.png') }}" alt="Tunas Tirta Fresh" 
                                 class="w-full h-full object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="hidden md:block">
                            <h1 class="text-xl font-black text-dark tracking-tighter uppercase leading-none">PT. Alam <br>Tunas Tirta</h1>
                        </div>
                    </div>
                </a>

                <div class="flex-1 max-w-3xl">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <div class="relative flex items-center">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Cari apel, mangga, atau jus..."
                                class="w-full pl-4 pr-12 py-3 border-2 border-gray-200 bg-gray-50 rounded text-sm font-semibold text-dark focus:bg-white focus:border-dark focus:ring-0 transition-all outline-none placeholder-gray-400">
                            <button type="submit" class="absolute right-0 top-0 bottom-0 px-4 text-gray-500 hover:text-dark transition-colors">
                                <i class="fas fa-search text-lg"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <button onclick="toggleCart()" class="flex items-center gap-3 group">
                    <div class="relative w-10 h-10 bg-gray-50 rounded border border-gray-200 flex items-center justify-center group-hover:border-dark transition-colors">
                        <i class="fas fa-shopping-bag text-lg text-dark"></i>
                        @auth
                            @if(Auth::user()->carts()->count() > 0)
                                <span class="absolute -top-1.5 -right-1.5 bg-secondary text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                    {{ Auth::user()->carts()->count() }}
                                </span>
                            @endif
                        @endauth
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Keranjang</p>
                        <p class="text-sm font-bold text-dark group-hover:text-primary transition-colors">Lihat Item</p>
                    </div>
                </button>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b border-border sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4 relative">
            <ul class="flex items-center space-x-1">
                
                <li class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="block px-6 py-5 text-sm font-bold uppercase tracking-wide text-dark hover:text-primary border-b-2 {{ request()->routeIs('home') ? 'border-primary' : 'border-transparent' }} transition-colors">
                        Beranda
                    </a>
                </li>

                <li class="group flex-shrink-0"> 
                    <a href="{{ url('/category/fresh-fruits') }}" class="block px-6 py-5 text-sm font-bold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Fruits
                        <i class="fas fa-chevron-down text-[10px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>

                    <div class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-8 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-10">
                            
                            <div class="col-span-3 border-r border-gray-100 pr-4">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5">Pencarian Cepat</h3>
                                <ul class="space-y-3">
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Mangga']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Mangga
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Apel']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Apel
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Jeruk']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jeruk
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Pisang']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Pisang
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Anggur']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Anggur
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'Alpukat']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Alupkat
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'Pir']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Pir
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'Semangka']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Semangka
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'Melon']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Melon
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'Nanas, Pepaya, Naga, Jambu, Sirsak, Markisa, Nangka, Kelapa']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Buah Tropis Lainnya
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'Manggis, Rambutan, Salak']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Buah Musiman
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'Kiwi, Delima']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Buah Import Premium
                                        </a>
                                    </li>
                                    
                                    <li class="pt-2">
                                        <a href="{{ url('/category/fresh-fruits') }}" class="text-xs font-bold text-secondary uppercase tracking-wide hover:underline">
                                            Lihat Semua Buah &rarr;
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-span-9">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5 border-b border-gray-100 pb-3">Pilihan Segar Minggu Ini</h3>
                                <div class="grid grid-cols-3 gap-6">
                                    @foreach($freshMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}" class="group/item relative bg-white rounded-lg p-2 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-3 overflow-hidden">
                                                @if($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                        <i class="fas fa-leaf text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-dark text-sm truncate">{{ $product->name }}</h4>
                                            <p class="text-xs text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

               <li class="group flex-shrink-0">
                    <a href="{{ url('/category/frozen-fruits') }}" class="block px-6 py-5 text-sm font-bold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Frozen Fruits
                        <i class="fas fa-chevron-down text-[10px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>
                    <div class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-8 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-10">
                            <div class="col-span-3 border-r border-gray-100 pr-4">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5">Pencarian Cepat</h3>
                                <ul class="space-y-3">
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Mangga']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Frozen Buah Tropis
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Apel']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Frozen Berries
                                        </a>
                                    </li>
                                                                        
                                    <li class="pt-2">
                                        <a href="{{ url('/category/fresh-fruits') }}" class="text-xs font-bold text-secondary uppercase tracking-wide hover:underline">
                                            Lihat Semua Buah &rarr;
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-span-9">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5 border-b border-gray-100 pb-3">Penyegar Dahaga</h3>
                                <div class="grid grid-cols-3 gap-6">
                                    @forelse($frozenMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}" class="group/item relative bg-white rounded-lg p-2 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-3 overflow-hidden">
                                                @if($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400"><i class="fas fa-wine-glass"></i></div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-dark text-sm truncate">{{ $product->name }}</h4>
                                            <p class="text-xs text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @empty
                                        <p class="col-span-3 text-sm text-gray-400 italic">Belum ada menu jus.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="group flex-shrink-0">
                    <a href="{{ url('/category/fresh-drinks') }}" class="block px-6 py-5 text-sm font-bold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Jus Segar
                        <i class="fas fa-chevron-down text-[10px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>
                    <div class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-8 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-10">
                            <div class="col-span-3 border-r border-gray-100 pr-4">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5">Pencarian Cepat</h3>
                                <ul class="space-y-3">
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus mangga']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Mangga
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus alpukat']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Alpukat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus jambu']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Jambu
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus melon']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Melon
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus semangka']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Semangka
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'jus stroberi']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Stroberi
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'jus pisang']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Pisang
                                        </a>
                                    </li>
                                     <li>
                                        <a href="{{ route('home', ['search' => 'jus sirsak']) }}" class="text-sm font-semibold text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Sirsak
                                        </a>
                                    </li>
                                    
                                    <li class="pt-2">
                                        <a href="{{ url('/category/fresh-fruits') }}" class="text-xs font-bold text-secondary uppercase tracking-wide hover:underline">
                                            Lihat Semua Jus&rarr;
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-span-9">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5 border-b border-gray-100 pb-3">Penyegar Dahaga</h3>
                                <div class="grid grid-cols-3 gap-6">
                                    @forelse($drinkMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}" class="group/item relative bg-white rounded-lg p-2 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-3 overflow-hidden">
                                                @if($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400"><i class="fas fa-wine-glass"></i></div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-dark text-sm truncate">{{ $product->name }}</h4>
                                            <p class="text-xs text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @empty
                                        <p class="col-span-3 text-sm text-gray-400 italic">Belum ada menu jus.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="group flex-shrink-0">
                    <a href="{{ url('/category/packages') }}" class="block px-6 py-5 text-sm font-bold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Paket Hemat
                        <i class="fas fa-chevron-down text-[10px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>
                    <div class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-8 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-10">
                            <div class="col-span-3 border-r border-gray-100 pr-4">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5">Solusi Hemat</h3>
                                <p class="text-xs text-medium leading-relaxed mb-4">
                                    Paket kombinasi buah, parsel, dan hampers untuk hadiah atau stok mingguan keluarga.
                                </p>
                                <a href="{{ url('/category/packages') }}" class="text-xs font-bold text-secondary uppercase tracking-wide hover:underline">Lihat Semua Paket &rarr;</a>
                            </div>
                            <div class="col-span-9">
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5 border-b border-gray-100 pb-3">Paket Best Seller</h3>
                                <div class="grid grid-cols-3 gap-6">
                                    @forelse($packageMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}" class="group/item relative bg-white rounded-lg p-2 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-3 overflow-hidden">
                                                @if($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400"><i class="fas fa-box-open"></i></div>
                                                @endif
                                                <div class="absolute top-2 left-2 bg-secondary text-white text-[9px] font-bold px-1.5 py-0.5 rounded">HEMAT</div>
                                            </div>
                                            <h4 class="font-bold text-dark text-sm truncate">{{ $product->name }}</h4>
                                            <p class="text-xs text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @empty
                                        <p class="col-span-3 text-sm text-gray-400 italic">Belum ada paket tersedia.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="flex-shrink-0">
                    <a href="{{ route('about') }}" class="block px-6 py-5 text-sm font-bold uppercase tracking-wide text-dark hover:text-primary border-b-2 border-transparent hover:border-primary transition-colors">
                        Tentang
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="flex-1 py-8 bg-white fade-in relative">
        
        @if(session('success') || session('error'))
            <div id="toast-notification" class="fixed top-24 right-5 z-[100] transform transition-all duration-500 translate-x-full opacity-0">
                <div class="bg-white border-l-4 {{ session('error') ? 'border-red-500' : 'border-primary' }} shadow-mega rounded-r-lg p-4 flex items-start gap-3 min-w-[320px] max-w-[400px]">
                    <div class="{{ session('error') ? 'text-red-500' : 'text-primary' }} mt-0.5">
                        <i class="fas {{ session('error') ? 'fa-exclamation-circle' : 'fa-check-circle' }} text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-dark text-sm uppercase tracking-wide mb-1">
                            {{ session('error') ? 'Perhatian' : 'Berhasil' }}
                        </h4>
                        <p class="text-medium text-sm leading-snug">
                            {{ session('success') ?? session('error') }}
                        </p>
                    </div>
                    <button onclick="closeToast()" class="text-gray-400 hover:text-dark transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const toast = document.getElementById('toast-notification');
                    if (toast) {
                        setTimeout(() => {
                            toast.classList.remove('translate-x-full', 'opacity-0');
                            toast.classList.add('translate-x-0', 'opacity-100');
                        }, 100);
                        setTimeout(() => closeToast(), 4000);
                    }
                });
                function closeToast() {
                    const toast = document.getElementById('toast-notification');
                    if (toast) {
                        toast.classList.remove('translate-x-0', 'opacity-100');
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 500);
                    }
                }
            </script>
        @endif

        @yield('content')
    </main>

    <footer class="bg-light border-t border-border pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
                
                <div class="pr-4">
                    <div class="flex items-center gap-2 mb-5">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain">
                        <span class="text-lg font-black text-dark uppercase tracking-tight">Tunas Tirta</span>
                    </div>
                    <p class="text-medium text-sm leading-relaxed mb-6">
                        Penyedia buah segar dan kebutuhan pangan berkualitas tinggi di Bali. Melayani hotel, restoran, dan rumah tangga dengan standar terbaik.
                    </p>
                </div>

                <div>
                    <h4 class="font-black text-dark text-sm uppercase tracking-widest mb-6">Hubungi Kami</h4>
                    <ul class="space-y-4 text-medium text-sm font-medium">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-primary mt-1"></i>
                            <span>Jl. Cargo Sari III, Ubung Kaja, Denpasar Utara, Bali</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-primary"></i>
                            <span>(0361) 123-4567</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-black text-dark text-sm uppercase tracking-widest mb-6">Ikuti Kami</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-white border border-gray-200 rounded flex items-center justify-center text-gray-500 hover:bg-dark hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 bg-white border border-gray-200 rounded flex items-center justify-center text-gray-500 hover:bg-blue-600 hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="font-black text-dark text-sm uppercase tracking-widest mb-6">Pembayaran</h4>
                    <div class="flex flex-wrap gap-2">
                        <div class="h-8 w-14 bg-white border border-gray-200 rounded flex items-center justify-center"><span class="text-[10px] font-bold">BCA</span></div>
                        <div class="h-8 w-14 bg-white border border-gray-200 rounded flex items-center justify-center"><span class="text-[10px] font-bold">QRIS</span></div>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-semibold text-green-600 bg-green-50 px-3 py-2 rounded w-fit">
                        <i class="fas fa-lock mr-2"></i> Transaksi Aman
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-xs font-medium">
                    &copy; {{ date('Y') }} PT. Alam Tunas Tirta. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <div id="cart-overlay" onclick="toggleCart()" class="fixed inset-0 bg-black/60 z-50 hidden transition-opacity duration-300 backdrop-blur-sm"></div>

    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full md:w-[400px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-[60] flex flex-col border-l border-gray-100">
        
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-dark font-black text-lg uppercase tracking-tight">Keranjang Belanja</h2>
            <button onclick="toggleCart()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors">
                <i class="fas fa-times text-medium"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 bg-white">
            @auth
                @php
                    $sideCarts = Auth::user()->carts()->with(['product', 'variant'])->latest()->get();
                    $sideTotal = 0;
                @endphp

                @if ($sideCarts->count() > 0)
                    <div class="space-y-4">
                        @foreach ($sideCarts as $item)
                            @php
                                $price = $item->variant ? $item->variant->price : $item->product->price;
                                $name = $item->product->name . ($item->variant ? ' (' . $item->variant->name . ')' : '');
                                $subtotal = $price * $item->quantity;
                                $sideTotal += $subtotal;
                            @endphp

                            <div class="flex gap-4 p-3 border border-gray-100 rounded-lg hover:border-gray-300 transition-colors">
                                <div class="w-16 h-16 bg-gray-50 rounded-md flex-shrink-0 overflow-hidden relative">
                                    @if($item->product->image && $item->product->image != 'pending')
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">No Img</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-dark font-bold text-sm truncate pr-2">{{ $name }}</h3>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-primary font-bold text-sm mt-0.5">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center bg-gray-100 rounded">
                                            <span class="text-xs px-2 py-1 font-bold">{{ $item->quantity }} x</span>
                                        </div>
                                        <p class="font-bold text-dark text-sm">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-shopping-basket text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-dark font-bold text-lg">Keranjang Kosong</p>
                        <p class="text-medium text-sm mt-1">Belum ada produk yang dipilih.</p>
                    </div>
                @endif
            @else
                <div class="h-full flex flex-col items-center justify-center text-center px-6">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-lock text-3xl text-gray-300"></i>
                    </div>
                    <p class="text-dark font-bold text-lg">Silakan Masuk</p>
                    <p class="text-medium text-sm mt-1 mb-6">Login untuk melihat isi keranjang Anda.</p>
                    <a href="{{ route('login') }}" class="bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-primaryDark w-full transition-colors shadow-lg shadow-primary/30">
                        Masuk Sekarang
                    </a>
                </div>
            @endauth
        </div>

        @auth
            @if (isset($sideCarts) && $sideCarts->count() > 0)
                <div class="border-t border-gray-100 p-6 bg-gray-50">
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-medium text-sm font-medium">Total Tagihan</span>
                        <span class="text-dark font-black text-xl">Rp {{ number_format($sideTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="grid gap-3">
                        <a href="{{ route('cart.index') }}" class="block w-full bg-dark text-white text-center font-bold text-sm py-3.5 rounded-lg hover:opacity-90 transition-opacity">
                            LIHAT KERANJANG
                        </a>
                        <button onclick="toggleCart()" class="block w-full bg-white border border-gray-300 text-dark text-center font-bold text-sm py-3.5 rounded-lg hover:bg-gray-50 transition-colors">
                            LANJUT BELANJA
                        </button>
                    </div>
                </div>
            @endif
        @endauth
    </div>

    <script>
        function toggleCart() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            const isOpen = !sidebar.classList.contains('translate-x-full');

            if (isOpen) {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto'; 
            } else {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; 
            }
        }

        @if (session('show_cart'))
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(toggleCart, 300);
            });
        @endif

        // Close on Escape Key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('cart-sidebar');
                if (!sidebar.classList.contains('translate-x-full')) {
                    toggleCart();
                }
            }
        });
    </script>
</body>
</html>