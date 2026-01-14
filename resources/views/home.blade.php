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
                        primary: {
                            DEFAULT: '#00A859', // Hijau Khas Bali Food Store
                            dark: '#008F4C',
                            light: '#E6F7EE'
                        },
                        accent: {
                            DEFAULT: '#FF6B6B', // Merah muda untuk harga/sale
                        },
                        dark: {
                            DEFAULT: '#333333',
                            light: '#777777'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans text-dark">

    <div class="bg-primary text-white text-xs py-2">
        <div class="container mx-auto px-4 flex justify-between">
            <span>Welcome to Tunas Tirta Fresh Supplier!</span>
            <div class="space-x-4">
                <a href="#" class="hover:underline">My Wishlist (0)</a>
                <a href="#" class="hover:underline">Sign In</a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between gap-8">
            <a href="#" class="flex items-center gap-2 flex-shrink-0">
                <span class="text-3xl">🍏</span>
                <div>
                    <h1 class="text-2xl font-bold text-primary tracking-tight">TunasTirta<span class="text-accent">Fresh</span></h1>
                </div>
            </a>

            <div class="flex-grow max-w-2xl hidden md:block relative">
                <input type="text" placeholder="Cari buah, jus, atau frozen food..." class="w-full border border-gray-300 rounded-full py-2.5 px-6 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition">
                <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 rounded-full hover:bg-primary-dark">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </div>

            <div class="flex items-center gap-4">
                <a href="https://wa.me/62812345678" target="_blank" class="flex items-center gap-2 text-dark hover:text-primary font-semibold">
                    <div class="relative">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="absolute -top-1 -right-1 bg-accent text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
                    </div>
                    <span class="hidden md:inline">Cart</span>
                </a>
            </div>
        </div>

        <div class="border-t border-gray-100 bg-white relative">
            <div class="container mx-auto px-4">
                <ul class="flex justify-center gap-8 text-sm font-bold uppercase tracking-wide text-dark-light">
                    
                    <li><a href="#" class="py-4 block hover:text-primary transition">Home</a></li>

                    @foreach($categories as $category)
                    <li class="group static"> <a href="#{{ $category->slug }}" class="py-4 block hover:text-primary cursor-pointer border-b-2 border-transparent group-hover:border-primary transition relative">
                            {{ $category->name }}
                            @if($category->products->count() > 0)
                                <span class="ml-1 text-[10px] opacity-50">▼</span>
                            @endif
                        </a>

                        @if($category->products->count() > 0)
                        <div class="absolute left-0 top-full w-full bg-white shadow-xl border-t border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform translate-y-2 group-hover:translate-y-0">
                            <div class="container mx-auto px-4 py-8">
                                <div class="flex gap-8">
                                    
                                    <div class="w-1/4 border-r border-gray-100 pr-8">
                                        <h3 class="text-xl font-bold text-primary mb-4">{{ $category->name }}</h3>
                                        <ul class="space-y-2 text-gray-500 font-normal capitalize">
                                            <li class="hover:text-primary cursor-pointer">Semua {{ $category->name }}</li>
                                            <li class="hover:text-primary cursor-pointer">Terlaris</li>
                                            <li class="hover:text-primary cursor-pointer">Promo Hari Ini</li>
                                            <li class="hover:text-primary cursor-pointer">New Arrival</li>
                                        </ul>
                                        <a href="#{{ $category->slug }}" class="mt-6 inline-block text-xs font-bold text-primary border border-primary px-4 py-2 rounded hover:bg-primary hover:text-white transition">
                                            Lihat Semua Produk →
                                        </a>
                                    </div>

                                    <div class="w-3/4">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Highlight Produk</h4>
                                        <div class="grid grid-cols-4 gap-6">
                                            @foreach($category->products->take(4) as $product)
                                            <div class="group/item text-center cursor-pointer">
                                                <div class="bg-gray-50 rounded-lg p-4 mb-3 hover:shadow-md transition duration-300 relative overflow-hidden h-32 flex items-center justify-center">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-full object-contain transform group-hover/item:scale-110 transition duration-500">
                                                    @else
                                                        <span class="text-xs text-gray-400">No Image</span>
                                                    @endif
                                                </div>
                                                <h5 class="text-sm font-semibold text-gray-800 group-hover/item:text-primary transition">{{ $product->name }}</h5>
                                                @if($product->price)
                                                    <p class="text-accent font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                @else
                                                    <p class="text-xs text-gray-400">Hubungi Admin</p>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                        </li>
                    @endforeach

                    <li><a href="#about" class="py-4 block hover:text-primary transition">About Us</a></li>
                </ul>
            </div>
        </div>
    </div>

    <header class="relative bg-primary-light h-[400px] md:h-[500px] flex items-center overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-primary/10 rounded-l-full hidden md:block"></div>
        <div class="container mx-auto px-4 relative z-10 flex items-center">
            <div class="max-w-xl">
                <span class="bg-white text-primary px-3 py-1 rounded text-xs font-bold shadow-sm mb-4 inline-block">FRESH FROM BALI</span>
                <h2 class="text-4xl md:text-6xl font-extrabold text-dark mb-6 leading-tight">
                    Healthy Life with <br> <span class="text-primary">Fresh Products</span>
                </h2>
                <p class="text-lg text-gray-600 mb-8">
                    Temukan buah segar, sayuran organik, dan produk frozen berkualitas tinggi langsung dari petani lokal Bali.
                </p>
                <a href="#katalog" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-full font-bold shadow-lg transition transform hover:-translate-y-1 inline-flex items-center gap-2">
                    Belanja Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
        <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=2070&auto=format&fit=crop" class="absolute right-10 md:right-20 top-1/2 transform -translate-y-1/2 w-64 md:w-96 h-64 md:h-96 object-cover rounded-full shadow-2xl border-4 border-white hidden md:block" alt="Banner Fruit">
    </header>

    <main id="katalog" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            
            @foreach($categories as $category)
            <div id="{{ $category->slug }}" class="mb-20 scroll-mt-32"> <div class="flex items-end justify-between mb-8 border-b border-gray-100 pb-4">
                    <div>
                        <span class="text-primary font-bold text-sm tracking-widest uppercase mb-1 block">Fresh Collection</span>
                        <h3 class="text-3xl font-bold text-dark">{{ $category->name }}</h3>
                    </div>
                    <a href="#" class="text-sm font-semibold text-primary hover:text-primary-dark transition hidden md:block">Lihat Semua →</a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($category->products as $product)
                    <div class="bg-white border border-gray-100 rounded-xl hover:shadow-xl transition-all duration-300 group overflow-hidden">
                        
                        <div class="h-48 p-4 bg-gray-50 relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <span class="text-xs">No Image</span>
                                </div>
                            @endif
                            
                            @if(!$product->is_available)
                                <span class="absolute top-2 left-2 bg-gray-800 text-white text-[10px] px-2 py-1 rounded font-bold">SOLD OUT</span>
                            @else
                                <span class="absolute top-2 left-2 bg-green-100 text-primary text-[10px] px-2 py-1 rounded font-bold">FRESH</span>
                            @endif

                            <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">
                                <button class="bg-primary text-white p-2 rounded-full shadow-lg hover:bg-primary-dark" title="Add to Cart">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-4 text-center">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 group-hover:text-primary transition">{{ $product->name }}</h4>
                            <p class="text-xs text-gray-500 mb-2 line-clamp-1">{{ $product->description ?? 'Premium Quality' }}</p>
                            
                            @if($product->price)
                                <span class="block text-accent font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @else
                                <span class="text-xs italic text-gray-400">Hubungi Admin</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($category->products->count() == 0)
                    <div class="bg-gray-50 rounded-lg p-8 text-center border-2 border-dashed border-gray-200">
                        <p class="text-gray-400">Belum ada produk di kategori ini.</p>
                    </div>
                @endif

            </div>
            @endforeach

        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <h4 class="font-bold text-lg mb-4">Tunas Tirta Fresh</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Jl. Cargo Sari III, Ubung Kaja,<br>Denpasar Utara, Bali.<br>
                        <br>
                        WhatsApp: +62 812-3456-7890
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Kategori</h4>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><a href="#" class="hover:text-primary">Fresh Fruits</a></li>
                        <li><a href="#" class="hover:text-primary">Vegetables</a></li>
                        <li><a href="#" class="hover:text-primary">Frozen Food</a></li>
                        <li><a href="#" class="hover:text-primary">Juice & Drinks</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Bantuan</h4>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li><a href="#" class="hover:text-primary">Cara Pemesanan</a></li>
                        <li><a href="#" class="hover:text-primary">Info Pengiriman</a></li>
                        <li><a href="#" class="hover:text-primary">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-primary">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Metode Pembayaran</h4>
                    <div class="flex gap-2">
                        <div class="w-10 h-6 bg-gray-200 rounded"></div>
                        <div class="w-10 h-6 bg-gray-200 rounded"></div>
                        <div class="w-10 h-6 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>
            <div class="text-center text-xs text-gray-400 pt-8 border-t border-gray-100">
                &copy; {{ date('Y') }} PT. Alam Tunas Tirta. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>