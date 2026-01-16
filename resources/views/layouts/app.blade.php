<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunas Tirta Fresh - Toko Buah Segar</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#00b894', // Hijau Emerald (Mirip Referensi)
                        secondary: '#ffeaa7', // Kuning/Cream lembut
                        dark: '#2d3436', // Hitam teks
                        danger: '#ff7675', // Merah muda/Pink (untuk harga/diskon)
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-dark">

    <div class="bg-primary text-white text-xs py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex space-x-4">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="flex space-x-4">
                <a href="#" class="hover:underline">My Wishlist (0)</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="hover:underline">Riwayat Pesanan</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Sign In</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="bg-white py-6 shadow-sm relative z-10">
        <div
            class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">

            <div class="w-full md:w-1/3 flex items-center">

                <form action="{{ route('home') }}" method="GET" class="w-full flex items-center">

                    <i class="fas fa-search text-gray-400 mr-2 text-lg"></i>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari buah apa hari ini..."
                        class="w-full border-b border-gray-300 focus:border-primary outline-none py-1 transition">

                </form>

            </div>

            <div class="w-full md:w-1/3 text-center">
                <a href="{{ route('home') }}" class="text-3xl md:text-4xl font-bold text-danger">
                    Tunas<span class="text-primary">Tirta</span>Fresh
                    <span class="block text-xs font-normal text-gray-400 tracking-widest mt-1 uppercase">Fresh from
                        Farm</span>
                </a>
            </div>

            <div class="w-full md:w-1/3 flex justify-end items-center">
                <div class="w-full md:w-1/3 flex justify-end items-center">
                    <button onclick="toggleCart()"
                        class="flex items-center text-dark hover:text-primary transition focus:outline-none">
                        <i class="fas fa-shopping-bag text-2xl mr-2"></i>
                        <span class="font-medium">
                            Shopping Cart ({{ Auth::check() ? Auth::user()->carts()->count() : 0 }})
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-100 py-4 hidden md:block sticky top-0 z-40 shadow-sm transition-all duration-300" id="navbar">
        <div class="container mx-auto px-4 flex justify-center space-x-10 text-[13px] font-bold text-gray-600 uppercase tracking-widest">
            
            <a href="{{ route('home') }}" class="hover:text-primary transition relative group {{ request()->routeIs('home') ? 'text-primary' : '' }}">
                Home
            </a>

            <a href="{{ url('/category/fresh-fruits') }}" class="hover:text-primary transition relative group">
                Fresh Fruits
                <span class="absolute -top-3 -right-3 bg-green-100 text-green-700 text-[9px] px-1.5 py-0.5 rounded font-bold normal-case tracking-normal">Fresh</span>
            </a>

            <a href="{{ url('/category/frozen-fruits') }}" class="hover:text-primary transition relative group">
                Frozen Fruits
                <span class="absolute -top-3 -right-3 bg-blue-100 text-blue-600 text-[9px] px-1.5 py-0.5 rounded font-bold normal-case tracking-normal">Cold</span>
            </a>

            <a href="{{ url('/category/fresh-drinks') }}" class="hover:text-primary transition relative group">
                Fresh Juice
            </a>

             <a href="{{ url('/category/packages') }}" class="hover:text-primary transition relative group">
                Packages
            </a>

            <a href="{{ route('about') }}" class="hover:text-primary transition relative group {{ request()->routeIs('about') ? 'text-primary' : '' }}">
                About Us
            </a>
            
        </div>
    </div>

    <main class="min-h-screen py-8">
       @if(session('success') || session('error'))
    <div id="toast-notification" 
         class="fixed top-24 right-5 z-[100] transform transition-all duration-500 ease-out translate-x-full opacity-0">
        
        <div class="bg-white border-l-4 {{ session('error') ? 'border-red-500' : 'border-primary' }} rounded-r-xl shadow-2xl p-4 flex items-center gap-4 min-w-[320px] max-w-[400px]">
            
            <div class="{{ session('error') ? 'bg-red-50 text-red-500' : 'bg-green-50 text-primary' }} p-3 rounded-full flex-shrink-0">
                <i class="fas {{ session('error') ? 'fa-exclamation-triangle' : 'fa-check' }} text-lg"></i>
            </div>

            <div class="flex-1">
                <h4 class="font-bold text-gray-800 text-sm">
                    {{ session('error') ? 'Ada Masalah' : 'Berhasil!' }}
                </h4>
                <p class="text-sm text-gray-500 leading-tight mt-1">
                    {{ session('success') ?? session('error') }}
                </p>
            </div>

            <button onclick="closeToast()" class="text-gray-300 hover:text-gray-500 transition p-2">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toast = document.getElementById('toast-notification');
            
            if(toast) {
                // 1. Animasi Masuk (Tunggu 100ms biar smooth)
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                }, 100);

                // 2. Hilang Otomatis setelah 4 detik
                setTimeout(() => {
                    closeToast();
                }, 4000);
            }
        });

        function closeToast() {
            const toast = document.getElementById('toast-notification');
            if(toast) {
                // Geser ke kanan lagi sebelum hilang
                toast.classList.add('translate-x-full', 'opacity-0');
                
                // Hapus dari HTML setelah animasi selesai
                setTimeout(() => { toast.remove(); }, 500);
            }
        }
    </script>
    @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t mt-16 pt-12 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                <div class="col-span-1 md:col-span-1">
                    <h4 class="text-xl font-bold text-gray-800 mb-4">Tunas<span class="text-primary">Tirta</span>Fresh</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Supplier buah segar terpercaya di Bali. Melayani hotel, restoran, villa, dan kebutuhan rumah tangga.
                    </p>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-widest">Contact Us</h4>
                    <ul class="text-sm text-gray-500 space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 mr-3 w-4"></i>
                            <span>Jl. Cargo Sari III, Ubung Kaja, Kec. Denpasar Utara,<br>Kota Denpasar, Bali 80116</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-primary mr-3 w-4"></i>
                            <span>(Email coming soon)</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-primary mr-3 w-4"></i>
                            <span>(Phone coming soon)</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-widest">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary hover:text-white transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} PT. Alam Tunas Tirta. All rights reserved.
            </div>
        </div>
    </footer>

    <div id="cart-overlay" onclick="toggleCart()"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300">
    </div>

    <div id="cart-sidebar"
        class="fixed inset-y-0 right-0 w-full md:w-[400px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50 flex flex-col">

        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wide">Shopping Cart</h2>
            <button onclick="toggleCart()" class="text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            @auth
                @php
                    // Ambil data keranjang langsung di View (Biar Praktis)
                    $sideCarts = Auth::user()
                        ->carts()
                        ->with(['product', 'variant'])
                        ->latest()
                        ->get();
                    $sideTotal = 0;
                @endphp

                @if ($sideCarts->count() > 0)
                    @foreach ($sideCarts as $item)
                        @php
                            $price = $item->variant ? $item->variant->price : $item->product->price;
                            $name = $item->product->name . ($item->variant ? ' (' . $item->variant->name . ')' : '');
                            $subtotal = $price * $item->quantity;
                            $sideTotal += $subtotal;
                        @endphp

                        <div class="flex gap-4">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden border">
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                    class="w-full h-full object-contain">
                            </div>

                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-800 line-clamp-2">{{ $name }}</h3>
                                <p class="text-primary font-bold text-sm mt-1">Rp {{ number_format($price, 0, ',', '.') }}
                                </p>
                                <p class="text-gray-400 text-xs mt-1">Qty: {{ $item->quantity }}</p>
                            </div>

                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-500 transition">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-400 mt-10">
                        <i class="fas fa-shopping-basket text-4xl mb-3"></i>
                        <p>Keranjang masih kosong.</p>
                    </div>
                @endif
            @else
                <div class="text-center text-gray-400 mt-10">
                    <p>Silakan login untuk melihat keranjang.</p>
                </div>
            @endauth
        </div>

        <div class="border-t p-6 bg-gray-50">
            @auth
                <div class="flex justify-between items-center mb-4 text-lg font-bold text-gray-800">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($sideTotal ?? 0, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('cart.index') }}"
                    class="block w-full bg-primary hover:bg-green-600 text-white text-center font-bold py-3 rounded-full shadow-lg transition transform hover:scale-105">
                    View Cart & Checkout
                </a>
            @endauth
        </div>
    </div>

    <script>
        function toggleCart() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');

            // Cek apakah sedang terbuka (tidak ada class translate-x-full)
            const isOpen = !sidebar.classList.contains('translate-x-full');

            if (isOpen) {
                // Tutup
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            } else {
                // Buka
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
            }
        }

        // AUTO OPEN: Jika Controller mengirim sinyal 'show_cart', otomatis buka!
        @if (session('show_cart'))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(toggleCart, 100); // Kasih jeda dikit biar animasinya kelihatan
            });
        @endif
    </script>

</body>

</html>
