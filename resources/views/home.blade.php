<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunas Tirta Fresh - Supplier Buah Bali</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        secondary: '#f97316',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, sans-serif; }
        
        .product-hover {
            transition: transform 0.2s ease;
        }
        
        .product-hover:hover {
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="bg-white">

    <!-- Top Info Bar -->
    <div class="bg-emerald-600 text-white text-sm py-2">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <span>📍 Denpasar, Bali • Pengiriman ke seluruh Indonesia</span>
            <div class="flex gap-4 items-center">
                @auth
                    <span>Hi, {{ Auth::user()->name }}</span>
                    <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-green-600 font-bold ml-2 underline">
                        Riwayat
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="underline">Login</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🥑</span>
                    </div>
                    <div>
                        <div class="font-bold text-lg text-gray-900">Tunas Tirta Fresh</div>
                        <div class="text-xs text-gray-500">Supplier Buah & Sayur</div>
                    </div>
                </a>

                <div class="hidden md:flex flex-1 max-w-md mx-12">
                    <input type="text" 
                           placeholder="Cari produk..." 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <div class="flex items-center gap-6">
                    <a href="{{ route('cart.index') }}" class="relative">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center text-[10px]">
                            {{ Auth::check() ? Auth::user()->carts->sum('quantity') : 0 }}
                        </span>
                    </a>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ url('/admin') }}" class="text-sm px-3 py-1.5 bg-gray-900 text-white rounded-md">Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-emerald-600">Login</a>
                    @endauth
                </div>
            </div>

            <!-- Navigation -->
            <nav class="border-t">
                <ul class="flex gap-8 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="py-4 inline-block text-gray-700 hover:text-emerald-600">Home</a></li>
                    @foreach($categories as $category)
                    <li class="relative group">
                        <a href="#{{ $category->slug }}" class="py-4 inline-block text-gray-700 hover:text-emerald-600">
                            {{ $category->name }}
                        </a>
                        
                        @if($category->products->count() > 0)
                        <div class="hidden group-hover:block absolute top-full left-0 bg-white border shadow-lg rounded-lg mt-0 w-80 p-4">
                            <div class="space-y-3">
                                @foreach($category->products->take(3) as $product)
                                <a href="{{ route('product.show', $product->slug) }}" class="flex gap-3 hover:bg-gray-50 p-2 rounded">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 object-cover rounded" alt="{{ $product->name }}">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded"></div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-orange-600 font-semibold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </li>
                    @endforeach
                    <li><a href="#about" class="py-4 inline-block text-gray-700 hover:text-emerald-600">Tentang</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="bg-gradient-to-b from-emerald-50 to-white">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-block bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full mb-4">
                        🌱 Fresh from Bali
                    </div>
                    <h1 class="text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Buah & Sayur Segar Setiap Hari
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Langsung dari petani lokal Bali ke rumah Anda. Kualitas terjamin, harga terjangkau.
                    </p>
                    <a href="#katalog" class="inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700">
                        Lihat Produk
                    </a>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=800" 
                         class="w-full rounded-2xl shadow-xl" 
                         alt="Fresh Fruits">
                </div>
            </div>
        </div>
    </section>

    <!-- Products -->
    <main id="katalog" class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            
            @foreach($categories as $category)
            <section id="{{ $category->slug }}" class="mb-16 scroll-mt-24">
                
                <div class="flex items-baseline justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                    <a href="#" class="text-sm text-emerald-600 hover:underline">Lihat semua</a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
                    @foreach($category->products as $product)
                    <a href="{{ route('product.show', $product->slug) }}" class="product-hover bg-white border rounded-lg overflow-hidden group">
                        
                        <div class="aspect-square bg-gray-50 p-4 relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    No image
                                </div>
                            @endif
                            
                            @if(!$product->is_available)
                                <div class="absolute top-2 left-2 bg-gray-900 text-white text-xs px-2 py-1 rounded">Habis</div>
                            @endif
                        </div>

                        <div class="p-3">
                            <h3 class="font-medium text-sm text-gray-900 mb-1 line-clamp-2">{{ $product->name }}</h3>
                            
                            @if($product->price)
                                <div class="text-orange-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @else
                                <div class="text-xs text-gray-500">Hubungi admin</div>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                @if($category->products->count() == 0)
                    <div class="bg-gray-50 border-2 border-dashed rounded-lg p-8 text-center text-gray-500">
                        Belum ada produk
                    </div>
                @endif

            </section>
            @endforeach

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="font-bold text-white text-lg mb-3">Tunas Tirta Fresh</div>
                    <p class="text-sm leading-relaxed">
                        Jl. Cargo Sari III, Ubung Kaja,<br>
                        Denpasar Utara, Bali<br><br>
                        WhatsApp: +62 812-3456-7890
                    </p>
                </div>
                <div>
                    <div class="font-semibold text-white mb-3">Kategori</div>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Fresh Fruits</a></li>
                        <li><a href="#" class="hover:text-white">Vegetables</a></li>
                        <li><a href="#" class="hover:text-white">Frozen</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-semibold text-white mb-3">Bantuan</div>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Cara Pesan</a></li>
                        <li><a href="#" class="hover:text-white">Pengiriman</a></li>
                        <li><a href="#" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-semibold text-white mb-3">Ikuti Kami</div>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/><path d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z"/><circle cx="18.406" cy="5.594" r="1.44"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-sm text-center text-gray-400">
                © {{ date('Y') }} PT. Alam Tunas Tirta. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>