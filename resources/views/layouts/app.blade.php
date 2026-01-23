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
                        primary: '#59A6A1', // Pantone 16-5127 TCX Ceramic
                        primaryDark: '#3F7A76', // Darker Ceramic for hover
                        secondary: '#D32F2F', 
                        dark: '#111827', 
                        medium: '#4B5563', 
                        light: '#F3F4F6', 
                        border: '#E5E7EB', 
                        highlight: '#E9F3F2', // Light Ceramic tint
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05)',
                        'mega': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'card': '0 1px 3px rgba(0,0,0,0.1)',
                    },
                    screens: {
                        'xs': '480px',
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

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* LOGIKA MEGA MENU: Hanya muncul di Desktop (min-width: 1024px) */
        @media (min-width: 1024px) {
            .group:hover .mega-menu {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateX(-50%) translateY(0) !important;
            }
            .mega-menu {
                display: block !important; /* Pastikan terlihat di desktop */
                transition: all 0.2s ease-out;
                opacity: 0;
                visibility: hidden;
                transform: translateX(-50%) translateY(10px);
            }
        }

        /* SEMBUNYIKAN MEGA MENU DI MOBILE & TABLET */
        @media (max-width: 1023px) {
            .mega-menu {
                display: none !important; /* Paksa hilang total di bawah 1024px */
            }
        }
    </style>
</head>

<body class="bg-white text-dark flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <!-- Top Bar -->
        <div class="bg-primary text-white text-[10px] md:text-xs font-medium py-1.5">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="flex items-center">
                        <i class="fas fa-check-circle mr-1"></i> <span class="hidden xs:inline">Jaminan</span> Kualitas 100%
                    </span>
                    <span class="hidden sm:inline text-white/70">|</span>
                    <span class="hidden sm:inline flex items-center">
                        <i class="fas fa-truck mr-1"></i> Pengiriman Cepat Bali
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('orders.index') }}" class="hover:text-white transition-colors">Pesanan Saya</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                            @csrf
                            <button type="submit" class="hover:text-white transition-colors">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-white transition-colors font-semibold">Masuk / Daftar</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="container mx-auto px-4 py-2.5">
            <div class="flex items-center justify-between">
                <!-- Hamburger Mobile -->
                <button onclick="toggleMobileMenu()" class="lg:hidden p-2 -ml-2 text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 md:w-10 md:h-10 object-contain">
                    <span class="text-gray-800 font-bold text-base md:text-lg hidden xs:block">Tunas Tirta Fresh</span>
                </a>

                <!-- Search Bar Desktop -->
                <div class="hidden lg:block w-full max-w-xl mx-8">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <div class="flex items-center border border-gray-200 rounded-md overflow-hidden bg-gray-50 focus-within:bg-white focus-within:border-primary transition-all">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari buah segar..." class="w-full px-3 py-1.5 text-sm bg-transparent focus:outline-none">
                            <button type="submit" class="px-4 py-1.5 border-l border-gray-200 text-gray-500 hover:text-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Icons (Search Mobile & Cart) -->
                <div class="flex items-center space-x-1 md:space-x-3">
                    <button onclick="toggleMobileSearch()" class="lg:hidden p-2 text-gray-700">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                    
                    <button onclick="toggleCart()" class="relative p-2 hover:bg-gray-100 rounded transition-colors">
                        <i class="fas fa-shopping-bag text-gray-800 text-lg md:text-xl"></i>
                         <span class="text-gray-800 font-medium text-base md:text-lg hidden xs:block">Cart</span>
                        @auth
                            @if (Auth::user()->carts()->count() > 0)
                                <span class="absolute top-0 right-0 bg-red-500 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center border-2 border-white">
                                    {{ Auth::user()->carts()->count() }}
                                </span>
                            @endif
                        @endauth
                    </button>
                </div>
            </div>

            <!-- Mobile Search Input (Dropdown) -->
            <div id="mobile-search-bar" class="hidden lg:none mt-2 pb-2">
                <form action="{{ route('home') }}" method="GET">
                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                        <input type="text" name="search" placeholder="Cari produk..." class="w-full px-4 py-2 text-sm focus:outline-none">
                        <button type="submit" class="px-4 bg-primary text-white"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Navigation Desktop -->
        <nav class="border-t border-gray-100 hidden lg:block">
            <div class="container mx-auto px-4">
                <ul class="flex items-center justify-center space-x-7">
                    <li>
                        <a href="{{ route('home') }}" class="block py-4 text-sm text-gray-700 hover:text-primary font-medium relative group">
                            Beranda
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all group-hover:w-full"></span>
                        </a>
                    </li>

                    <!-- FRESH FRUITS MEGA MENU -->
                    <li class="group relative">
                        <a href="{{ url('/category/fresh-fruits') }}" class="block py-4 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary transition-colors flex items-center gap-1">
                            Fresh Fruits <i class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:rotate-180 transition-all duration-300"></i>
                        </a>
                        <!-- Mega Menu Container -->
                        <div class="mega-menu absolute left-1/2 top-full w-[95vw] max-w-7xl bg-white shadow-mega border-t border-gray-100 rounded-b-xl z-50">
                            <div class="grid grid-cols-12 gap-8 p-8">
                                <div class="col-span-3">
                                    <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-4">Kategori Buah</h3>
                                    <ul class="space-y-2.5">
                                        <li><a href="{{ url('/category/fresh-fruits?subcategory=buah-lokal') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Buah Lokal</a></li>
                                        <li><a href="{{ url('/category/fresh-fruits?subcategory=buah-import') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Buah Import Premium</a></li>
                                        <li><a href="{{ url('/category/fresh-fruits?subcategory=buah-tropis') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Buah Tropis</a></li>
                                        <li class="pt-4 mt-2 border-t border-gray-200">
                                            <a href="{{ url('/category/fresh-fruits') }}" class="inline-flex items-center text-sm font-bold text-secondary">Lihat Semua Buah <span class="ml-1.5">→</span></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-span-9">
                                    <div class="flex items-center justify-between mb-5">
                                        <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest">Buah Terbaik Minggu Ini</h3>
                                    </div>
                                    <div class="grid grid-cols-4 gap-5">
                                        @foreach ($freshMenuProducts->take(5) as $product)
                                            <a href="{{ route('products.show', $product->slug) }}" class="group/item bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all">
                                                <div class="aspect-[4/3] overflow-hidden bg-gray-50">
                                                    <img src="{{ $product->image && $product->image != 'pending' ? asset('storage/' . $product->image) : asset('images/logo.png') }}" class="w-full h-full object-cover transition-transform group-hover/item:scale-110">
                                                </div>
                                                <div class="p-3">
                                                    <h4 class="text-sm font-semibold text-dark truncate">{{ $product->name }}</h4>
                                                    <p class="text-primary font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- FROZEN FRUITS MEGA MENU -->
                    <li class="group relative">
                        <a href="{{ url('/category/frozen-fruits') }}" class="block py-4 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary transition-colors flex items-center gap-1">
                            Frozen Fruit <i class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:rotate-180 transition-all duration-300"></i>
                        </a>
                        <div class="mega-menu absolute left-1/2 top-full w-[95vw] max-w-7xl bg-white shadow-mega border-t border-gray-100 rounded-b-xl z-50">
                            <div class="grid grid-cols-12 gap-8 p-8">
                                <div class="col-span-3">
                                    <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-4">Kategori Beku</h3>
                                    <ul class="space-y-2.5">
                                        <li><a href="{{ url('/category/frozen-fruits?subcategory=buah-lokal') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Frozen Tropis</a></li>
                                        <li><a href="{{ url('/category/frozen-fruits?subcategory=buah-import') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Frozen Berries</a></li>
                                    </ul>
                                </div>
                                <div class="col-span-9">
                                    <div class="grid grid-cols-4 gap-5">
                                        @foreach ($frozenMenuProducts->take(4) as $product)
                                            <a href="{{ route('products.show', $product->slug) }}" class="group/item bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all">
                                                <div class="aspect-[4/3]"><img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover"></div>
                                                <div class="p-3"><h4 class="text-sm font-semibold truncate">{{ $product->name }}</h4><p class="text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p></div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- FRUITS JUICE MEGA MENU -->
                    <li class="group relative">
                        <a href="{{ url('/category/fresh-drinks') }}" class="block py-4 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary transition-colors flex items-center gap-1">
                            Fruit Juice <i class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:rotate-180 transition-all duration-300"></i>
                        </a>
                        <div class="mega-menu absolute left-1/2 top-full w-[95vw] max-w-7xl bg-white shadow-mega border-t border-gray-100 rounded-b-xl z-50">
                            <div class="grid grid-cols-12 gap-8 p-8">
                                <div class="col-span-3">
                                    <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-4">Kategori Beku</h3>
                                    <ul class="space-y-2.5">
                                        <li><a href="{{ url('/category/fresh-drinks?subcategory=buah-lokal') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Juice Mangga</a></li>
                                        <li><a href="{{ url('/category/fresh-drinks?subcategory=buah-import') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Juice Alpukat</a></li>
                                    </ul>
                                </div>
                                <div class="col-span-9">
                                    <div class="grid grid-cols-4 gap-5">
                                        @foreach ($drinkMenuProducts->take(4) as $product)
                                            <a href="{{ route('products.show', $product->slug) }}" class="group/item bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all">
                                                <div class="aspect-[4/3]"><img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover"></div>
                                                <div class="p-3"><h4 class="text-sm font-semibold truncate">{{ $product->name }}</h4><p class="text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p></div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- PACKAGE MEGA MENU -->
                    <li class="group relative">
                        <a href="{{ url('/category/packages') }}" class="block py-4 text-xs font-semibold uppercase tracking-wide text-dark group-hover:text-primary transition-colors flex items-center gap-1">
                            Package <i class="fas fa-chevron-down text-[8px] ml-1 opacity-40 group-hover:rotate-180 transition-all duration-300"></i>
                        </a>
                        <div class="mega-menu absolute left-1/2 top-full w-[95vw] max-w-7xl bg-white shadow-mega border-t border-gray-100 rounded-b-xl z-50">
                            <div class="grid grid-cols-12 gap-8 p-8">
                                <div class="col-span-3">
                                    <h3 class="text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-4">Kategori Beku</h3>
                                    <ul class="space-y-2.5">
                                        <li><a href="{{ url('/category/packages?subcategory=buah-lokal') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Paket Buah Tropis</a></li>
                                        <!-- <li><a href="{{ url('/category/packages?subcategory=buah-import') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition-all block py-1">Juice Alpukat</a></li> -->
                                    </ul>
                                </div>
                                <div class="col-span-9">
                                    <div class="grid grid-cols-4 gap-5">
                                        @foreach ($packageMenuProducts->take(4) as $product)
                                            <a href="{{ route('products.show', $product->slug) }}" class="group/item bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all">
                                                <div class="aspect-[4/3]"><img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover"></div>
                                                <div class="p-3"><h4 class="text-sm font-semibold truncate">{{ $product->name }}</h4><p class="text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p></div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="{{ route('about') }}" class="block py-4 text-sm text-gray-700 hover:text-primary font-medium">Tentang Kami</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- MOBILE MENU SIDEBAR (Drawer) -->
    <div id="mobile-menu-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 z-[60] hidden transition-opacity duration-300 backdrop-blur-sm"></div>
    <div id="mobile-menu-sidebar" class="fixed inset-y-0 left-0 w-[280px] bg-white z-[70] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col">
        <div class="p-5 border-b flex justify-between items-center bg-gray-50">
            <span class="font-bold text-dark">Menu Navigasi</span>
            <button onclick="toggleMobileMenu()"><i class="fas fa-times text-xl text-gray-400"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto">
            <ul class="p-4 space-y-4">
                <li><a href="{{ route('home') }}" class="flex items-center text-sm font-semibold text-gray-700 py-2 border-b border-gray-50"><i class="fas fa-home w-8 text-primary"></i> Beranda</a></li>
                <li><a href="{{ url('/category/fresh-fruits') }}" class="flex items-center text-sm font-semibold text-gray-700 py-2 border-b border-gray-50"><i class="fas fa-apple-alt w-8 text-primary"></i> Fresh Fruits</a></li>
                <li><a href="{{ url('/category/frozen-fruits') }}" class="flex items-center text-sm font-semibold text-gray-700 py-2 border-b border-gray-50"><i class="fas fa-snowflake w-8 text-primary"></i> Frozen Fruit</a></li>
                <li><a href="{{ url('/category/fresh-drinks') }}" class="flex items-center text-sm font-semibold text-gray-700 py-2 border-b border-gray-50"><i class="fas fa-glass-whiskey w-8 text-primary"></i> Fresh Juice</a></li>
                <li><a href="{{ url('/category/packages') }}" class="flex items-center text-sm font-semibold text-gray-700 py-2 border-b border-gray-50"><i class="fas fa-box-open w-8 text-primary"></i> Package</a></li>
                <li><a href="{{ route('about') }}" class="flex items-center text-sm font-semibold text-gray-700 py-2 border-b border-gray-50"><i class="fas fa-info-circle w-8 text-primary"></i> Tentang Kami</a></li>
            </ul>
        </div>
        <div class="p-5 border-t bg-highlight">
            <p class="text-[10px] font-bold text-dark uppercase mb-2">Hubungi Kami</p>
            <a href="https://wa.me/6285701797522" class="flex items-center gap-2 text-xs font-bold text-gray-700"><i class="fab fa-whatsapp text-green-500 text-base"></i> +62 8570-1797-522</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 py-6 bg-white fade-in relative">
        <!-- Toast Notification (Tetap Sesuai Kode Asli) -->
        @if (session('success') || session('error'))
            <div id="toast-notification" class="fixed top-24 left-5 z-[100] transform transition-all duration-500 -translate-x-full opacity-0">
                <div class="bg-white border-l-4 {{ session('error') ? 'border-red-500' : 'border-primary' }} shadow-mega rounded-r-lg p-3 flex items-start gap-2 min-w-[300px] max-w-[380px]">
                    <div class="{{ session('error') ? 'text-red-500' : 'text-primary' }} mt-0.5">
                        <i class="fas {{ session('error') ? 'fa-exclamation-circle' : 'fa-check-circle' }} text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-dark text-xs uppercase tracking-wide mb-0.5">{{ session('error') ? 'Perhatian' : 'Berhasil' }}</h4>
                        <p class="text-medium text-xs leading-snug">{{ session('success') ?? session('error') }}</p>
                    </div>
                    <button onclick="closeToast()" class="text-gray-400 hover:text-dark transition-colors"><i class="fas fa-times text-sm"></i></button>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const toast = document.getElementById('toast-notification');
                    if (toast) {
                        setTimeout(() => {
                            toast.classList.remove('-translate-x-full', 'opacity-0');
                            toast.classList.add('translate-x-0', 'opacity-100');
                        }, 100);
                        setTimeout(() => closeToast(), 3000);
                    }
                });
                function closeToast() {
                    const toast = document.getElementById('toast-notification');
                    if (toast) {
                        toast.classList.remove('translate-x-0', 'opacity-100');
                        toast.classList.add('-translate-x-full', 'opacity-0');
                        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 500);
                    }
                }
            </script>
        @endif
        @yield('content')
    </main>

    <!-- FOOTER (Sederhana & Responsif) -->
    <footer class="bg-white border-t border-border py-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="pr-3">
                    <div class="flex items-center space-x-1.5 mb-3">
                        <div class="w-10 h-auto flex-shrink-0 flex items-center justify-start">
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
                                <i class="fab fa-whatsapp text-primary text-xs"></i>
                            </div>
                            <span>+62 8570-1797-522</span>
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

    <!-- CART SIDEBAR (Tetap Sesuai Kode Asli) -->
    <div id="cart-overlay" onclick="toggleCart()" class="fixed inset-0 bg-black/60 z-[80] hidden backdrop-blur-sm"></div>
    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-[380px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-[90] flex flex-col">
        <div class="px-5 py-4 border-b flex justify-between items-center bg-gray-50">
            <h2 class="text-dark font-black text-sm uppercase">Keranjang Belanja</h2>
            <button onclick="toggleCart()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200"><i class="fas fa-times text-medium"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            @auth
                @php $sideCarts = Auth::user()->carts()->with(['product', 'variant'])->latest()->get(); $sideTotal = 0; @endphp
                @if ($sideCarts->count() > 0)
                    <div class="space-y-4">
                        @foreach ($sideCarts as $item)
                            @php $price = $item->variant ? $item->variant->price : $item->product->price; $subtotal = $price * $item->quantity; $sideTotal += $subtotal; @endphp
                            <div class="flex gap-3 p-2 border border-gray-100 rounded-lg">
                                <div class="w-14 h-14 bg-gray-50 rounded overflow-hidden">
                                    <img src="{{ $item->product->image && $item->product->image != 'pending' ? asset('storage/'.$item->product->image) : asset('images/logo.png') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-xs font-bold truncate pr-2">{{ $item->product->name }}</h3>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-500"><i class="fas fa-trash-alt text-[10px]"></i></button></form>
                                    </div>
                                    <p class="text-primary font-bold text-xs mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-[10px] text-gray-400">{{ $item->quantity }} x</span>
                                        <span class="text-xs font-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center opacity-30"><i class="fas fa-shopping-basket text-4xl mb-2"></i><p class="text-xs font-bold">Keranjang Kosong</p></div>
                @endif
            @else
                <div class="h-full flex flex-col items-center justify-center text-center p-6">
                    <i class="fas fa-user-lock text-3xl mb-4 text-gray-200"></i>
                    <p class="text-sm font-bold mb-4">Silakan login untuk belanja</p>
                    <a href="{{ route('login') }}" class="w-full bg-primary text-white py-2 rounded-lg font-bold text-xs">MASUK</a>
                </div>
            @endauth
        </div>
        @auth
            @if (isset($sideCarts) && $sideCarts->count() > 0)
                <div class="p-5 border-t bg-gray-50">
                    <div class="flex justify-between items-center mb-4"><span class="text-xs font-medium text-gray-500">Total Tagihan</span><span class="text-lg font-black text-dark">Rp {{ number_format($sideTotal, 0, ',', '.') }}</span></div>
                    <div class="grid gap-2">
                        <a href="{{ route('cart.index') }}" class="w-full bg-dark text-white text-center py-3 rounded-lg font-bold text-xs uppercase tracking-wider">Checkout</a>
                        <button onclick="toggleCart()" class="w-full bg-white border border-gray-200 py-3 rounded-lg font-bold text-xs uppercase text-gray-500">Kembali</button>
                    </div>
                </div>
            @endif
        @endauth
    </div>

    <script>
        // Toggle Cart Sidebar
        function toggleCart(forceClose = false) {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            const isOpen = !sidebar.classList.contains('translate-x-full');
            if (forceClose || isOpen) {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            } else {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        // Toggle Mobile Navigation Menu (Hamburger)
        function toggleMobileMenu() {
            const sidebar = document.getElementById('mobile-menu-sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Toggle Mobile Search Bar
        function toggleMobileSearch() {
            const bar = document.getElementById('mobile-search-bar');
            bar.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('show_cart')) toggleCart(); @endif
        });
    </script>
</body>
</html>