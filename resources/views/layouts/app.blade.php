<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tunas Tirta Fresh') }} - Premium Quality</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#00A859', // Hijau Brand
                        primaryDark: '#008F4C', // Hijau Tua (Hover)
                        secondary: '#D32F2F', // Merah Aksen
                        dark: '#111827', // Hitam Pekat (Tegas)
                        medium: '#4B5563', // Abu-abu Gelap
                        light: '#F3F4F6', // Background Abu Sangat Muda
                        border: '#E5E7EB', // Garis Batas
                        highlight: '#F0FDF4', // Hijau Sangat Muda
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
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

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

    <header class="bg-white border-b border-gray-100 shadow-sm relative z-50">
        <!-- Top Bar - Dengan imperfection yang natural -->
        <div class="bg-primary text-white text-xs font-medium py-1.5">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="flex items-center">
                        <i class="fas fa-check-circle mr-1"></i> Jaminan Kualitas 100%
                    </span>
                    <span class="hidden sm:inline text-white/70">|</span>
                    <span class="hidden sm:inline flex items-center">
                        <i class="fas fa-truck mr-1"></i> Pengiriman Cepat Bali
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('orders.index') }}" class="hover:text-white transition-colors">
                            Pesanan Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                            @csrf
                            <button type="submit" class="hover:text-white transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-white transition-colors font-semibold">
                            Masuk / Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Main Header - Lebih natural dan tidak terlalu simetris -->
        <div class="container mx-auto px-4 py-2.5">
            <div class="flex items-center justify-between">
                <!-- Logo - Dengan penyesuaian natural -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-10 h-10">
                        <img src="{{ asset('images/logo.png') }}" alt="Tunas Tirta Fresh"
                            class="w-full h-full object-contain">
                    </div>
                    <span class="text-gray-800 font-bold text-lg">
                        Tunas Tirta Fresh
                    </span>
                </a>

                <!-- Search Bar - Lebih terintegrasi -->
                <div class="w-full max-w-2xl mx-8">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <div class="flex items-center border border-gray-200 rounded-md overflow-hidden">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari apel, mangga, atau jus..."
                                class="w-full px-3 py-1.5 text-sm focus:outline-none">
                            <button type="submit"
                                class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 border-l border-gray-200">
                                <i class="fas fa-search text-gray-500"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Cart - Tanpa teks berlebihan -->
                <div class="flex items-center space-x-3">
                    <button onclick="toggleCart()" class="relative p-2 hover:bg-gray-100 rounded transition-colors">
                        <i class="fas fa-shopping-bag text-gray-800 text-lg"></i>
                        @auth
                            @if (Auth::user()->carts()->count() > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center">
                                    {{ Auth::user()->carts()->count() }}
                                </span>
                            @endif
                        @endauth
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Bar - Dengan spacing yang natural -->
        <nav class="border-t border-gray-100">
            <div class="container mx-auto px-4">
                <ul class="flex items-center justify-center space-x-7 py-2">
                    <li>
                        <a href="{{ route('home') }}"
                            class="text-sm text-gray-700 hover:text-primary font-medium relative group">
                            Beranda
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                    <li class="group flex-shrink-0 relative">
                        <a href="{{ url('/category/fresh-fruits') }}"
                            class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                            Fresh Fruits
                            <i
                                class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                        </a>

                        <div
                            class="mega-menu absolute left-1/2 -translate-x-1/2 top-full w-[95vw] max-w-7xl bg-white shadow-mega border-t border-gray-100 rounded-b-xl overflow-hidden z-50 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 ease-out pointer-events-none group-hover:pointer-events-auto">
                            <div class="grid grid-cols-12 gap-8 p-8">
                                <!-- Kolom Kiri: Kategori -->
                                <div class="col-span-3">
                                    <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-4">
                                        Kategori Buah
                                    </h3>
                                    <ul class="space-y-2.5">
                                        <li>
                                            <a href="{{ url('/category/fresh-fruits?subcategory=buah-lokal') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Buah Lokal
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/category/fresh-fruits?subcategory=buah-import') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Buah Import Premium
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/category/fresh-fruits?subcategory=buah-tropis') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Buah Tropis
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/category/fresh-fruits?subcategory=buah-musiman') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Buah Musiman
                                            </a>
                                        </li>
                                        <li class="pt-4 mt-2 border-t border-gray-200">
                                            <a href="{{ url('/category/fresh-fruits') }}"
                                                class="inline-flex items-center text-sm font-bold text-secondary hover:text-red-700 transition-colors">
                                                Lihat Semua Buah <span class="ml-1.5">→</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Kolom Kanan: Featured Products -->
                                <div class="col-span-9">
                                    <div class="flex items-center justify-between mb-5">
                                        <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest">
                                            Buah Terbaik Minggu Ini
                                        </h3>
                                        <a href="{{ url('/category/fresh-fruits') }}"
                                            class="text-sm text-primary hover:text-primaryDark font-medium flex items-center gap-1.5 group/link">
                                            Lihat Koleksi Lengkap
                                            <i
                                                class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
                                        </a>
                                    </div>

                                    <div class="grid grid-cols-4 gap-5">
                                        @foreach ($freshMenuProducts->take(8) as $product)
                                            <!-- ambil 8 produk agar lebih penuh -->
                                            <a href="{{ route('products.show', $product->slug) }}"
                                                class="group/item relative bg-white rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-gray-200">
                                                <div class="relative aspect-[4/3] overflow-hidden bg-gray-50">
                                                    @if ($product->image && $product->image != 'pending')
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            class="w-full h-full object-cover transition-transform duration-700 group-hover/item:scale-110"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                                            <i class="fas fa-leaf text-4xl opacity-30"></i>
                                                        </div>
                                                    @endif

                                                    <!-- Badge -->
                                                    <span
                                                        class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                                        NEW
                                                    </span>
                                                </div>

                                                <div class="p-3">
                                                    <h4 class="text-sm font-semibold text-dark truncate mb-1">
                                                        {{ $product->name }}
                                                    </h4>
                                                    <p class="text-primary font-bold text-base">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Banner / Info -->
                            <div class="bg-highlight px-8 py-4 border-t border-gray-100">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-truck-fast text-2xl text-primary"></i>
                                        <div>
                                            <p class="font-medium text-dark">Pengiriman Hari Ini</p>
                                            <p class="text-xs text-gray-600">Pesan sebelum jam 14:00 WIB • Gratis ongkir
                                                area tertentu</p>
                                        </div>
                                    </div>
                                    <a href="#"
                                        class="text-primary hover:text-primaryDark font-medium flex items-center gap-1.5">
                                        Syarat & Ketentuan <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="group flex-shrink-0 relative">
                        <a href="{{ url('/category/frozen-fruits') }}"
                            class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                            Frozen Fruit
                            <i
                                class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                        </a>

                        <div
                            class="mega-menu absolute left-1/2 -translate-x-1/2 top-full w-[95vw] max-w-7xl bg-white shadow-mega border-t border-gray-100 rounded-b-xl overflow-hidden z-50 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 ease-out pointer-events-none group-hover:pointer-events-auto">
                            <div class="grid grid-cols-12 gap-8 p-8">
                                <!-- Kolom Kiri: Kategori -->
                                <div class="col-span-3">
                                    <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-4">
                                        Kategori Buah
                                    </h3>
                                    <ul class="space-y-2.5">
                                        <li>
                                            <a href="{{ url('/category/frozen-fruits?subcategory=buah-lokal') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Frozen Buah Tropis
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/category/frozen-fruits?subcategory=buah-import') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Frozen Berries
                                            </a>
                                        </li>
                                        <li class="pt-4 mt-2 border-t border-gray-200">
                                            <a href="{{ url('/category/frozen-fruits') }}"
                                                class="inline-flex items-center text-sm font-bold text-secondary hover:text-red-700 transition-colors">
                                                Lihat Semua Buah <span class="ml-1.5">→</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Kolom Kanan: Featured Products -->
                                <div class="col-span-9">
                                    <div class="flex items-center justify-between mb-5">
                                        <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest">
                                            Buah Terbaik Minggu Ini
                                        </h3>
                                        <a href="{{ url('/category/frozen-fruits') }}"
                                            class="text-sm text-primary hover:text-primaryDark font-medium flex items-center gap-1.5 group/link">
                                            Lihat Koleksi Lengkap
                                            <i
                                                class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
                                        </a>
                                    </div>

                                    <div class="grid grid-cols-4 gap-5">
                                        @foreach ($frozenMenuProducts->take(8) as $product)
                                            <!-- ambil 8 produk agar lebih penuh -->
                                            <a href="{{ route('products.show', $product->slug) }}"
                                                class="group/item relative bg-white rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-gray-200">
                                                <div class="relative aspect-[4/3] overflow-hidden bg-gray-50">
                                                    @if ($product->image && $product->image != 'pending')
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            class="w-full h-full object-cover transition-transform duration-700 group-hover/item:scale-110"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                                            <i class="fas fa-leaf text-4xl opacity-30"></i>
                                                        </div>
                                                    @endif

                                                    <!-- Badge -->
                                                    <span
                                                        class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                                        Fresh
                                                    </span>
                                                </div>

                                                <div class="p-3">
                                                    <h4 class="text-sm font-semibold text-dark truncate mb-1">
                                                        {{ $product->name }}
                                                    </h4>
                                                    <p class="text-primary font-bold text-base">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Banner / Info -->
                            <div class="bg-highlight px-8 py-4 border-t border-gray-100">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-truck-fast text-2xl text-primary"></i>
                                        <div>
                                            <p class="font-medium text-dark">Pengiriman Hari Ini</p>
                                            <p class="text-xs text-gray-600">Pesan sebelum jam 14:00 WIB • Gratis ongkir
                                                area tertentu</p>
                                        </div>
                                    </div>
                                    <a href="#"
                                        class="text-primary hover:text-primaryDark font-medium flex items-center gap-1.5">
                                        Syarat & Ketentuan <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="group flex-shrink-0 relative">
                        <a href="{{ url('/category/fresh-drinks') }}"
                            class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                            Fresh Juice
                            <i
                                class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                        </a>

                        <div
                            class="mega-menu absolute left-1/2 -translate-x-1/2 top-full w-[95vw] max-w-7xl bg-white shadow-mega border-t border-gray-100 rounded-b-xl overflow-hidden z-50 opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 ease-out pointer-events-none group-hover:pointer-events-auto">
                            <div class="grid grid-cols-12 gap-8 p-8">
                                <!-- Kolom Kiri: Kategori -->
                                <div class="col-span-3">
                                    <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-4">
                                        Kategori Jus
                                    </h3>
                                    <ul class="space-y-2.5">
                                        <li>
                                            <a href="{{ url('/category/fresh-drinks?subcategory=buah-lokal') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Jus Mangga
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/category/fresh-drinks?subcategory=buah-import') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Jus Alpukat
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/category/fresh-drinks?subcategory=buah-tropis') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Jus Jambu
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/category/fresh-drinks?subcategory=buah-musiman') }}"
                                                class="text-sm font-medium text-gray-700 hover:text-primary hover:translate-x-1.5 transition-all duration-200 block py-1">
                                                Jus Melon
                                            </a>
                                        </li>
                                        <li class="pt-4 mt-2 border-t border-gray-200">
                                            <a href="{{ url('/category/fresh-drinks') }}"
                                                class="inline-flex items-center text-sm font-bold text-secondary hover:text-red-700 transition-colors">
                                                Lihat Semua Buah <span class="ml-1.5">→</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Kolom Kanan: Featured Products -->
                                <div class="col-span-9">
                                    <div class="flex items-center justify-between mb-5">
                                        <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest">
                                            Buah Terbaik Minggu Ini
                                        </h3>
                                        <a href="{{ url('/category/fresh-drinks') }}"
                                            class="text-sm text-primary hover:text-primaryDark font-medium flex items-center gap-1.5 group/link">
                                            Lihat Koleksi Lengkap
                                            <i
                                                class="fas fa-arrow-right text-xs group-hover/link:translate-x-1 transition-transform"></i>
                                        </a>
                                    </div>

                                    <div class="grid grid-cols-4 gap-5">
                                        @foreach ($drinkMenuProducts->take(8) as $product)
                                            <!-- ambil 8 produk agar lebih penuh -->
                                            <a href="{{ route('products.show', $product->slug) }}"
                                                class="group/item relative bg-white rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-gray-200">
                                                <div class="relative aspect-[4/3] overflow-hidden bg-gray-50">
                                                    @if ($product->image && $product->image != 'pending')
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            class="w-full h-full object-cover transition-transform duration-700 group-hover/item:scale-110"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                                            <i class="fas fa-leaf text-4xl opacity-30"></i>
                                                        </div>
                                                    @endif

                                                    <!-- Badge -->
                                                    <span
                                                        class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                                        NEW
                                                    </span>
                                                </div>

                                                <div class="p-3">
                                                    <h4 class="text-sm font-semibold text-dark truncate mb-1">
                                                        {{ $product->name }}
                                                    </h4>
                                                    <p class="text-primary font-bold text-base">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Banner / Info -->
                            <div class="bg-highlight px-8 py-4 border-t border-gray-100">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-truck-fast text-2xl text-primary"></i>
                                        <div>
                                            <p class="font-medium text-dark">Pengiriman Hari Ini</p>
                                            <p class="text-xs text-gray-600">Pesan sebelum jam 14:00 WIB • Gratis ongkir
                                                area tertentu</p>
                                        </div>
                                    </div>
                                    <a href="#"
                                        class="text-primary hover:text-primaryDark font-medium flex items-center gap-1.5">
                                        Syarat & Ketentuan <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="text-sm text-gray-700 hover:text-primary font-medium relative group">
                            Tentang Kami
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- <nav class="bg-white border-b border-border sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4 relative">
            <ul class="flex items-center space-x-1">
                <li class="flex-shrink-0">
                    <a href="{{ route('home') }}"
                        class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark hover:text-primary border-b-2 {{ request()->routeIs('home') ? 'border-primary' : 'border-transparent' }} transition-colors">
                        Beranda
                    </a>
                </li>
                <li class="group flex-shrink-0">
                    <a href="{{ url('/category/fresh-fruits') }}"
                        class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Fruits
                        <i
                            class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>
                    <div
                        class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-6 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-8">
                            <div class="col-span-3 border-r border-gray-100 pr-3">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                    Pencarian Cepat</h3>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Mangga']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Mangga
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Apel']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Apel
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Jeruk']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jeruk
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Pisang']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Pisang
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Anggur']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Anggur
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Alpukat']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Alupkat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Pir']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Pir
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Semangka']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Semangka
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Melon']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Melon
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Nanas, Pepaya, Naga, Jambu, Sirsak, Markisa, Nangka, Kelapa']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Buah Tropis Lainnya
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Manggis, Rambutan, Salak']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Buah Musiman
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Kiwi, Delima']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Buah Import Premium
                                        </a>
                                    </li>
                                    <li class="pt-1">
                                        <a href="{{ url('/category/fresh-fruits') }}"
                                            class="text-[10px] font-bold text-secondary uppercase tracking-wide hover:underline">
                                            Lihat Semua Buah &rarr;
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-span-9">
                                <h3
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">
                                    Pilihan Segar Minggu Ini</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    @foreach ($freshMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            class="group/item relative bg-white rounded-lg p-1 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-2 overflow-hidden">
                                                @if ($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                        <i class="fas fa-leaf text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="font-medium text-dark text-xs truncate">{{ $product->name }}
                                            </h4>
                                            <p class="text-[10px] text-primary font-bold">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="group flex-shrink-0">
                    <a href="{{ url('/category/frozen-fruits') }}"
                        class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Frozen Fruits
                        <i
                            class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>
                    <div
                        class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-6 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-8">
                            <div class="col-span-3 border-r border-gray-100 pr-3">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                    Pencarian Cepat</h3>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Mangga']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Frozen Buah Tropis
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'Apel']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Frozen Berries
                                        </a>
                                    </li>
                                    <li class="pt-1">
                                        <a href="{{ url('/category/fresh-fruits') }}"
                                            class="text-[10px] font-bold text-secondary uppercase tracking-wide hover:underline">
                                            Lihat Semua Buah &rarr;
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-span-9">
                                <h3
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">
                                    Penyegar Dahaga</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    @forelse($frozenMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            class="group/item relative bg-white rounded-lg p-1 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-2 overflow-hidden">
                                                @if ($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                        <i class="fas fa-wine-glass"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="font-medium text-dark text-xs truncate">{{ $product->name }}
                                            </h4>
                                            <p class="text-[10px] text-primary font-bold">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @empty
                                        <p class="col-span-3 text-xs text-gray-400 italic">Belum ada menu jus.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="group flex-shrink-0">
                    <a href="{{ url('/category/fresh-drinks') }}"
                        class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Jus Segar
                        <i
                            class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>
                    <div
                        class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-6 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-8">
                            <div class="col-span-3 border-r border-gray-100 pr-3">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                    Pencarian Cepat</h3>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus mangga']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Mangga
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus alpukat']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Alpukat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus jambu']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Jambu
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus melon']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Melon
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus semangka']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Semangka
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus stroberi']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Stroberi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus pisang']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Pisang
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home', ['search' => 'jus sirsak']) }}"
                                            class="text-xs font-medium text-dark hover:text-primary block transition-transform hover:translate-x-1">
                                            Jus Sirsak
                                        </a>
                                    </li>
                                    <li class="pt-1">
                                        <a href="{{ url('/category/fresh-fruits') }}"
                                            class="text-[10px] font-bold text-secondary uppercase tracking-wide hover:underline">
                                            Lihat Semua Jus&rarr;
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-span-9">
                                <h3
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">
                                    Penyegar Dahaga</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    @forelse($drinkMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            class="group/item relative bg-white rounded-lg p-1 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-2 overflow-hidden">
                                                @if ($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                        <i class="fas fa-wine-glass"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="font-medium text-dark text-xs truncate">{{ $product->name }}
                                            </h4>
                                            <p class="text-[10px] text-primary font-bold">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @empty
                                        <p class="col-span-3 text-xs text-gray-400 italic">Belum ada menu jus.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="group flex-shrink-0">
                    <a href="{{ url('/category/packages') }}"
                        class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary border-b-2 border-transparent group-hover:border-primary transition-colors flex items-center gap-1 cursor-pointer">
                        Paket Hemat
                        <i
                            class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:opacity-100 group-hover:rotate-180 transition-all duration-300"></i>
                    </a>
                    <div
                        class="mega-menu absolute left-0 top-[100%] w-full bg-white shadow-mega border-t border-gray-100 p-6 z-50 cursor-default">
                        <div class="grid grid-cols-12 gap-8">
                            <div class="col-span-3 border-r border-gray-100 pr-3">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Solusi
                                    Hemat</h3>
                                <p class="text-[10px] text-medium leading-relaxed mb-3">
                                    Paket kombinasi buah, parsel, dan hampers untuk hadiah atau stok mingguan keluarga.
                                </p>
                                <a href="{{ url('/category/packages') }}"
                                    class="text-[10px] font-bold text-secondary uppercase tracking-wide hover:underline">Lihat
                                    Semua Paket &rarr;</a>
                            </div>
                            <div class="col-span-9">
                                <h3
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">
                                    Paket Best Seller</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    @forelse($packageMenuProducts as $product)
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            class="group/item relative bg-white rounded-lg p-1 hover:shadow-lg transition-all block text-left border border-transparent hover:border-gray-100">
                                            <div class="relative aspect-[4/3] bg-gray-50 rounded mb-2 overflow-hidden">
                                                @if ($product->image && $product->image != 'pending')
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                        <i class="fas fa-box-open"></i>
                                                    </div>
                                                @endif
                                                <div
                                                    class="absolute top-1.5 left-1.5 bg-secondary text-white text-[7px] font-bold px-1 py-0.5 rounded">
                                                    HEMAT</div>
                                            </div>
                                            <h4 class="font-medium text-dark text-xs truncate">{{ $product->name }}
                                            </h4>
                                            <p class="text-[10px] text-primary font-bold">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </a>
                                    @empty
                                        <p class="col-span-3 text-xs text-gray-400 italic">Belum ada paket tersedia.
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="flex-shrink-0">
                    <a href="{{ route('about') }}"
                        class="block px-4 py-3 text-xs font-semibold uppercase tracking-wide text-dark hover:text-primary border-b-2 border-transparent hover:border-primary transition-colors">
                        Tentang
                    </a>
                </li>
            </ul>
        </div>
    </nav> -->
    <main class="flex-1 py-6 bg-white fade-in relative">
        @if (session('success') || session('error'))
            <div id="toast-notification"
                class="fixed top-24 right-5 z-[100] transform transition-all duration-500 translate-x-full opacity-0">
                <div
                    class="bg-white border-l-4 {{ session('error') ? 'border-red-500' : 'border-primary' }} shadow-mega rounded-r-lg p-3 flex items-start gap-2 min-w-[300px] max-w-[380px]">
                    <div class="{{ session('error') ? 'text-red-500' : 'text-primary' }} mt-0.5">
                        <i class="fas {{ session('error') ? 'fa-exclamation-circle' : 'fa-check-circle' }} text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-dark text-xs uppercase tracking-wide mb-0.5">
                            {{ session('error') ? 'Perhatian' : 'Berhasil' }}
                        </h4>
                        <p class="text-medium text-xs leading-snug">
                            {{ session('success') ?? session('error') }}
                        </p>
                    </div>
                    <button onclick="closeToast()" class="text-gray-400 hover:text-dark transition-colors">
                        <i class="fas fa-times text-sm"></i>
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
                        setTimeout(() => closeToast(), 2000); // Durasi dikurangi dari 4000ms ke 2000ms
                    }
                });

                function closeToast() {
                    const toast = document.getElementById('toast-notification');
                    if (toast) {
                        toast.classList.remove('translate-x-0', 'opacity-100');
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => {
                            if (toast.parentNode) toast.remove();
                        }, 500);
                    }
                }
            </script>
        @endif
        @yield('content')
    </main>
    <footer class="bg-white border-t border-border py-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="pr-3">
                    <div class="flex items-center space-x-1.5 mb-3">
                        <div class="w-20 h-auto flex-shrink-0 flex items-center justify-start">
                            <img src="{{ asset('images/logo.png') }}" alt="Tunas Tirta Fresh"
                                class="w-full h-full object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h4 class="text-dark font-bold text-xs">Tunas Tirta Fresh</h4>
                    </div>
                    <p class="text-medium text-[10px] leading-relaxed text-justify">
                        Mitra terpercaya suplai buah segar dan kebutuhan pangan di Bali. Kami menjamin kualitas dan
                        kesegaran produk hingga ke tangan Anda.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-dark text-xs mb-3 uppercase tracking-wider">Kontak Kami</h4>
                    <ul class="space-y-2 text-medium text-[10px]">
                        <li class="flex items-start">
                            <div class="w-4 flex-shrink-0 text-center">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <span class="leading-tight">Jl. Cargo Sari III, Ubung Kaja, Denpasar Utara, Bali</span>
                        </li>
                        <li class="flex items-center">
                            <div class="w-4 flex-shrink-0 text-center">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <span>(0361) 123-4567</span>
                        </li>
                        <li class="flex items-center">
                            <div class="w-4 flex-shrink-0 text-center">
                                <i class="fab fa-whatsapp text-primary text-xs"></i>
                            </div>
                            <span>+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-dark text-xs mb-3 uppercase tracking-wider">Ikuti Kami</h4>
                    <div class="flex gap-2">
                        <a href="#"
                            class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-medium hover:bg-primary hover:text-white hover:border-primary transition-all duration-300">
                            <i class="fab fa-instagram text-xs"></i>
                        </a>
                        <a href="#"
                            class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-medium hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300">
                            <i class="fab fa-facebook-f text-xs"></i>
                        </a>
                        <a href="#"
                            class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-medium hover:bg-green-500 hover:text-white hover:border-green-500 transition-all duration-300">
                            <i class="fab fa-tiktok text-xs"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-dark text-xs mb-3 uppercase tracking-wider">Metode Pembayaran</h4>
                    <div class="flex flex-wrap gap-1.5">
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="BCA">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png"
                                alt="BCA" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="Mandiri">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/2560px-Bank_Mandiri_logo_2016.svg.png"
                                alt="Mandiri" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="BRI">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/2560px-BANK_BRI_logo.svg.png"
                                alt="BRI" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="BSI">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/2560px-Bank_Syariah_Indonesia.svg.png"
                                alt="BSI" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="QRIS">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/2560px-Logo_QRIS.svg.png"
                                alt="QRIS" class="h-3.5 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="GoPay">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/2560px-Gopay_logo.svg.png"
                                alt="GoPay" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="DANA">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/2560px-Logo_dana_blue.svg.png"
                                alt="DANA" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="LinkAja">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/LinkAja.svg/2560px-LinkAja.svg.png"
                                alt="LinkAja" class="h-3.5 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="Indomaret">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Logo_Indomaret.png/1200px-Logo_Indomaret.png"
                                alt="Indomaret" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center"
                            title="Alfamart">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Alfamart_logo.svg/2560px-Alfamart_logo.svg.png"
                                alt="Alfamart" class="h-3 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-border rounded p-0.5 h-7 w-[48px] flex items-center justify-center text-medium"
                            title="Bank Lainnya">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-university text-[8px] mb-[1px]"></i>
                                <span class="text-[7px] font-bold leading-none">Other</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center text-medium text-[9px]">
                        <i class="fas fa-shield-alt mr-1 text-primary"></i>
                        <span>Pembayaran 100% Aman & Terenkripsi</span>
                    </div>
                </div>
            </div>
            <div class="border-t border-border pt-4 mt-1">
                <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                    <p class="text-medium text-[10px] text-center md:text-left">
                        &copy; 2026 <strong>PT. Alam Tunas Tirta</strong>. All rights reserved.
                    </p>
                    <div class="flex space-x-3 text-[10px] text-medium">
                        <a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a>
                        <a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-primary transition-colors">Pusat Bantuan</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div id="cart-overlay" onclick="toggleCart()"
        class="fixed inset-0 bg-black/60 z-50 hidden transition-opacity duration-300 backdrop-blur-sm"></div>
    <div id="cart-sidebar"
        class="fixed inset-y-0 right-0 w-full md:w-[400px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-[60] flex flex-col border-l border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-dark font-black text-base uppercase tracking-tight">Keranjang Belanja</h2>
            <button onclick="toggleCart()"
                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors">
                <i class="fas fa-times text-medium"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5 bg-white">
            @auth
                @php
                    $sideCarts = Auth::user()
                        ->carts()
                        ->with(['product', 'variant'])
                        ->latest()
                        ->get();
                    $sideTotal = 0;
                @endphp
                @if ($sideCarts->count() > 0)
                    <div class="space-y-3">
                        @foreach ($sideCarts as $item)
                            @php
                                $price = $item->variant ? $item->variant->price : $item->product->price;
                                $name =
                                    $item->product->name . ($item->variant ? ' (' . $item->variant->name . ')' : '');
                                $subtotal = $price * $item->quantity;
                                $sideTotal += $subtotal;
                            @endphp
                            <div class="flex gap-3 p-2 border border-gray-100 rounded-lg hover:border-gray-300 transition-colors">
                                <div class="w-14 h-14 bg-gray-50 rounded-md flex-shrink-0 overflow-hidden relative">
                                    @if ($item->product->image && $item->product->image != 'pending')
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                                            No
                                            Img</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-dark font-medium text-xs truncate pr-2">{{ $name }}</h3>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-primary font-medium text-xs mt-0.5">Rp
                                        {{ number_format($price, 0, ',', '.') }}
                                    </p>
                                    <div class="flex justify-between items-center mt-1.5">
                                        <div class="flex items-center bg-gray-100 rounded">
                                            <span class="text-[10px] px-1.5 py-0.5 font-bold">{{ $item->quantity }}
                                                x</span>
                                        </div>
                                        <p class="font-medium text-dark text-xs">Rp
                                            {{ number_format($subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-shopping-basket text-xl text-gray-300"></i>
                        </div>
                        <p class="text-dark font-bold text-base">Keranjang Kosong</p>
                        <p class="text-medium text-xs mt-0.5">Belum ada produk yang dipilih.</p>
                    </div>
                @endif
            @else
                <div class="h-full flex flex-col items-center justify-center text-center px-5">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-user-lock text-xl text-gray-300"></i>
                    </div>
                    <p class="text-dark font-bold text-base">Silakan Masuk</p>
                    <p class="text-medium text-xs mt-0.5 mb-5">Login untuk melihat isi keranjang Anda.</p>
                    <a href="{{ route('login') }}"
                        class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primaryDark w-full transition-colors shadow-lg shadow-primary/30">
                        Masuk Sekarang
                    </a>
                </div>
            @endauth
        </div>
        @auth
            @if (isset($sideCarts) && $sideCarts->count() > 0)
                <div class="border-t border-gray-100 p-5 bg-gray-50">
                    <div class="flex justify-between items-end mb-3">
                        <span class="text-medium text-xs font-medium">Total Tagihan</span>
                        <span class="text-dark font-black text-lg">Rp {{ number_format($sideTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="grid gap-2">
                        <a href="{{ route('cart.index') }}"
                            class="block w-full bg-dark text-white text-center font-bold text-xs py-2.5 rounded-lg hover:opacity-90 transition-opacity">
                            LIHAT KERANJANG
                        </a>
                        <button onclick="toggleCart()"
                            class="block w-full bg-white border border-gray-300 text-dark text-center font-bold text-xs py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                            LANJUT BELANJA
                        </button>
                    </div>
                </div>
            @endif
        @endauth
    </div>
    <script>
        function toggleCart(forceClose = false) {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            const isOpen = !sidebar.classList.contains('translate-x-full');

            // Jika forceClose true, tutup paksa tanpa memeriksa isi keranjang
            if (forceClose || isOpen) {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
                // Simpan status tertutup di localStorage
                localStorage.setItem('cartSidebarOpen', 'false');
            } else {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Simpan status terbuka di localStorage
                localStorage.setItem('cartSidebarOpen', 'true');
            }
        }

        // Cek status keranjang dari localStorage saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            const cartEmpty = sidebar.querySelector('.h-full.flex-col.items-center.justify-center.text-center');

            // Jika keranjang tidak kosong dan sebelumnya slider dibuka, buka kembali slider
            if (!cartEmpty && localStorage.getItem('cartSidebarOpen') === 'true') {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Event listener untuk menutup saat klik overlay
            overlay.addEventListener('click', function () {
                toggleCart(true);
            });

            // Tutup saat tombol escape ditekan
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    const sidebar = document.getElementById('cart-sidebar');
                    if (!sidebar.classList.contains('translate-x-full')) {
                        toggleCart(true);
                    }
                }
            });
        });

        // Jangan tutup slider ketika item dihapus selama masih ada item lain
        document.addEventListener('DOMContentLoaded', function () {
            // Cegah penutupan otomatis setelah menghapus item
            const deleteButtons = document.querySelectorAll('form[action*="/cart/"][method="POST"]');
            deleteButtons.forEach(form => {
                form.addEventListener('submit', function (e) {
                    // Simpan status sebelum submit
                    const sidebar = document.getElementById('cart-sidebar');
                    const wasOpen = !sidebar.classList.contains('translate-x-full');

                    // Jika slider terbuka sebelum submit, pastikan tetap terbuka setelah submit
                    if (wasOpen) {
                        // Tunggu sedikit sebelum reload untuk menyimpan status
                        setTimeout(() => {
                            localStorage.setItem('cartSidebarOpen', 'true');
                        }, 100);
                    }
                });
            });
        });
    </script>
</body>

</html>