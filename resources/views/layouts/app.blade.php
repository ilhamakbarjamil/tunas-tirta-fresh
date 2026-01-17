<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunas Tirta Fresh - Toko Buah Segar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Palet dari gambar Instagram
                        primary: '#00A859',        // Hijau buah (dari toko)
                        secondary: '#ED4956',      // Merah Instagram (like)
                        accent: '#0095F6',         // Biru Instagram (link)
                        dark: '#262626',           // Hitam Instagram (text utama)
                        medium: '#8E8E8E',         // Abu-abu Instagram (text sekunder)
                        light: '#FAFAFA',          // Background Instagram
                        border: '#DBDBDB',         // Border Instagram
                        highlight: '#F0F9F5',      // Hijau muda untuk highlight
                    },
                    fontFamily: {
                        // GANTI KONFIGURASI TAILWIND KE POPPINS
                        sans: ['Poppins', 'sans-serif'],
                    },
                    fontSize: {
                        'xs': ['11px', '14px'],     // Lebih kecil dari default
                        'sm': ['13px', '16px'],     // Ukuran Instagram
                        'base': ['14px', '18px'],   // Base size Instagram
                        'lg': ['16px', '20px'],
                        'xl': ['18px', '22px'],
                        '2xl': ['22px', '26px'],
                    },
                    boxShadow: {
                        'strong': '0 2px 8px rgba(0, 0, 0, 0.15)',
                        'card': '0 1px 3px rgba(0, 0, 0, 0.08)',
                        'button': '0 1px 2px rgba(0, 0, 0, 0.08)',
                    },
                    borderRadius: {
                        'md': '6px',
                        'lg': '8px',
                        'xl': '12px',
                    },
                    spacing: {
                        '18': '4.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        /* GANTI CSS STYLE KE POPPINS */
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Instagram-like scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Instagram-style animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-light text-dark text-base">

    <div class="bg-white border-b border-border py-2.5">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium text-medium">Tunas Tirta Fresh</span>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('orders.index') }}" class="text-sm text-medium hover:text-dark font-medium">Pesanan</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-medium hover:text-dark font-medium">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-accent font-medium hover:text-dark">Masuk</a>
                @endauth
            </div>
        </div>
    </div>

    <header class="bg-white border-b border-border py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">TTF</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-dark leading-tight">Tunas Tirta Fresh</h1>
                        <p class="text-xs text-medium">Buah Segar Bali</p>
                    </div>
                </div>

                <div class="flex-1 max-w-md mx-4">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-medium text-sm"></i>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Cari buah..."
                                class="w-full pl-10 pr-4 py-2 border border-border bg-light rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
                            >
                        </div>
                    </form>
                </div>

                <div>
                    <button onclick="toggleCart()"
                        class="relative flex items-center space-x-2 bg-white border border-border rounded-lg px-3 py-2 hover:border-primary transition-colors group">
                        <i class="fas fa-shopping-bag text-medium group-hover:text-primary"></i>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs text-medium">Keranjang</p>
                            <p class="font-bold text-dark text-sm">
                                {{ Auth::check() ? Auth::user()->carts()->count() : 0 }}
                            </p>
                        </div>
                        @auth
                            @if(Auth::user()->carts()->count() > 0)
                                <span class="absolute -top-2 -right-2 bg-secondary text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                    {{ Auth::user()->carts()->count() }}
                                </span>
                            @endif
                        @endauth
                    </button>
                </div>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b border-border sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <div class="flex overflow-x-auto hide-scrollbar">
                <div class="flex space-x-1 py-2">
                    <a href="{{ route('home') }}" 
                       class="font-medium text-sm px-4 py-2 rounded-lg transition-colors whitespace-nowrap {{ request()->routeIs('home') ? 'text-primary bg-highlight' : 'text-medium hover:text-dark' }}">
                        Beranda
                    </a>
                    
                    <a href="{{ url('/category/fresh-fruits') }}" 
                       class="font-medium text-sm px-4 py-2 rounded-lg transition-colors whitespace-nowrap hover:text-dark">
                        Buah Segar
                    </a>
                    
                    <a href="{{ url('/category/frozen-fruits') }}" 
                       class="font-medium text-sm px-4 py-2 rounded-lg transition-colors whitespace-nowrap hover:text-dark">
                        Buah Beku
                    </a>
                    
                    <a href="{{ url('/category/fresh-drinks') }}" 
                       class="font-medium text-sm px-4 py-2 rounded-lg transition-colors whitespace-nowrap hover:text-dark">
                        Jus Segar
                    </a>
                    
                    <a href="{{ url('/category/packages') }}" 
                       class="font-medium text-sm px-4 py-2 rounded-lg transition-colors whitespace-nowrap hover:text-dark">
                        Paket
                    </a>
                    
                    <a href="{{ route('about') }}" 
                       class="font-medium text-sm px-4 py-2 rounded-lg transition-colors whitespace-nowrap hover:text-dark">
                        Tentang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-screen py-4 fade-in">
        @if(session('success') || session('error'))
            <div id="toast-notification" 
                 class="fixed top-20 right-4 z-[100] transform transition-all duration-300 translate-x-full opacity-0">
                
                <div class="bg-white border border-border rounded-lg shadow-strong p-3 flex items-center gap-3 min-w-[300px] max-w-[350px]">
                    
                    <div class="{{ session('error') ? 'bg-red-50 text-red-500' : 'bg-highlight text-primary' }} p-2 rounded-md">
                        <i class="fas {{ session('error') ? 'fa-exclamation-circle' : 'fa-check' }} text-sm"></i>
                    </div>

                    <div class="flex-1">
                        <h4 class="font-bold text-dark text-sm">
                            {{ session('error') ? 'Perhatian' : 'Berhasil' }}
                        </h4>
                        <p class="text-medium text-sm leading-tight mt-0.5">
                            {{ session('success') ?? session('error') }}
                        </p>
                    </div>

                    <button onclick="closeToast()" 
                            class="text-medium hover:text-dark transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const toast = document.getElementById('toast-notification');
                    
                    if(toast) {
                        setTimeout(() => {
                            toast.classList.remove('translate-x-full', 'opacity-0');
                            toast.classList.add('translate-x-0', 'opacity-100');
                        }, 100);

                        setTimeout(() => {
                            closeToast();
                        }, 4000);
                    }
                });

                function closeToast() {
                    const toast = document.getElementById('toast-notification');
                    if(toast) {
                        toast.classList.remove('translate-x-0', 'opacity-100');
                        toast.classList.add('translate-x-full', 'opacity-0');
                        
                        setTimeout(() => { 
                            if(toast.parentNode) {
                                toast.remove(); 
                            }
                        }, 300);
                    }
                }
            </script>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t border-border py-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">TTF</span>
                        </div>
                        <h4 class="text-dark font-bold text-sm">Tunas Tirta Fresh</h4>
                    </div>
                    <p class="text-medium text-xs leading-relaxed">
                        Supplier buah segar terpercaya di Bali sejak 2010.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-dark text-sm mb-3">Kontak</h4>
                    <ul class="space-y-2 text-medium text-xs">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-0.5 mr-2 text-xs"></i>
                            <span>Jl. Cargo Sari III, Denpasar, Bali</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-primary mr-2 text-xs"></i>
                            <span>(0361) 123-4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-primary mr-2 text-xs"></i>
                            <span>info@tunastirtafresh.com</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-dark text-sm mb-3">Sosial Media</h4>
                    <div class="flex space-x-2">
                        <a href="#" class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-medium hover:text-primary hover:border-primary transition-colors">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-medium hover:text-primary hover:border-primary transition-colors">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-medium hover:text-primary hover:border-border transition-colors">
                            <i class="fab fa-whatsapp text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-border pt-4">
                <div class="text-center">
                    <p class="text-medium text-xs">
                        &copy; {{ date('Y') }} PT. Alam Tunas Tirta
                    </p>
                    <div class="flex justify-center space-x-3 mt-2 text-xs text-medium">
                        <a href="#" class="hover:text-dark">Syarat</a>
                        <span>•</span>
                        <a href="#" class="hover:text-dark">Privasi</a>
                        <span>•</span>
                        <a href="#" class="hover:text-dark">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="cart-overlay" onclick="toggleCart()"
        class="fixed inset-0 bg-black/40 z-40 hidden transition-opacity duration-200">
    </div>

    <div id="cart-sidebar"
        class="fixed inset-y-0 right-0 w-full md:w-[380px] bg-white shadow-strong transform translate-x-full transition-transform duration-300 ease-out z-50 flex flex-col">

        <div class="px-4 py-3 border-b border-border flex justify-between items-center">
            <h2 class="text-dark font-bold text-sm">Keranjang Belanja</h2>
            <button onclick="toggleCart()" class="text-medium hover:text-dark">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4">
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
                                $name = $item->product->name . ($item->variant ? ' (' . $item->variant->name . ')' : '');
                                $subtotal = $price * $item->quantity;
                                $sideTotal += $subtotal;
                            @endphp

                            <div class="flex gap-3 p-3 border border-border rounded-lg">
                                <div class="w-14 h-14 bg-light rounded-md flex-shrink-0 overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                        class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-dark font-medium text-sm truncate">{{ $name }}</h3>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-medium hover:text-secondary">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-primary font-bold text-sm mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center space-x-2">
                                            <button class="w-6 h-6 border border-border rounded text-xs flex items-center justify-center hover:bg-light">−</button>
                                            <span class="font-medium text-sm">{{ $item->quantity }}</span>
                                            <button class="w-6 h-6 border border-border rounded text-xs flex items-center justify-center hover:bg-light">+</button>
                                        </div>
                                        <p class="font-bold text-dark text-sm">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-light rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shopping-bag text-xl text-medium"></i>
                        </div>
                        <p class="text-medium font-medium text-sm">Keranjang kosong</p>
                        <p class="text-medium text-xs mt-1">Tambahkan produk untuk mulai belanja</p>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-light rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user text-xl text-medium"></i>
                    </div>
                    <p class="text-medium font-medium text-sm">Login diperlukan</p>
                    <p class="text-medium text-xs mt-1">Silakan login untuk melihat keranjang</p>
                    <a href="{{ route('login') }}" class="inline-block mt-3 bg-primary text-white text-sm px-4 py-2 rounded-lg font-medium hover:bg-green-600">
                        Masuk
                    </a>
                </div>
            @endauth
        </div>

        @auth
            @if ($sideCarts && $sideCarts->count() > 0)
                <div class="border-t border-border p-4">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-medium text-sm">
                            <span>Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($sideTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-dark font-bold text-sm border-t border-border pt-2">
                            <span>Total</span>
                            <span>Rp {{ number_format($sideTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('cart.index') }}"
                            class="block w-full bg-primary text-white text-center text-sm font-medium py-2.5 rounded-lg hover:bg-green-600">
                            Lihat Keranjang
                        </a>
                        <button onclick="toggleCart()"
                            class="block w-full bg-white border border-border text-dark text-center text-sm font-medium py-2.5 rounded-lg hover:bg-light">
                            Lanjut Belanja
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
            } else {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
            }
        }

        @if (session('show_cart'))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(toggleCart, 300);
            });
        @endif
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('cart-sidebar');
                const overlay = document.getElementById('cart-overlay');
                
                if (!sidebar.classList.contains('translate-x-full')) {
                    toggleCart();
                }
            }
        });
    </script>

</body>

</html>