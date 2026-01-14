<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunas Tirta Fresh - Buah Segar Bali</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tosca: {
                            DEFAULT: '#26A69A', // Hijau Tosca Segar
                            dark: '#00897B',
                            light: '#B2DFDB'
                        },
                        pink: {
                            DEFAULT: '#D81B60', // Pink Buah Naga
                            hover: '#AD1457'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-tosca text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-wide">🍏 Tunas Tirta Fresh</h1>
            <a href="https://wa.me/62812345678" class="bg-white text-tosca px-4 py-2 rounded-full font-semibold hover:bg-gray-100 transition">
                Hubungi Kami
            </a>
        </div>
    </nav>

    <header class="bg-tosca-light py-16 text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-tosca-dark mb-4">Kesegaran Alami dari Bali</h2>
            <p class="text-lg text-gray-700 mb-8">Supplier Buah Segar, Frozen, & Jus untuk Hotel, Restoran & Harian.</p>
            <a href="#katalog" class="bg-pink hover:bg-pink-hover text-white px-8 py-3 rounded-full text-lg font-bold shadow-md transition">
                Lihat Katalog
            </a>
        </div>
    </header>

    <main id="katalog" class="container mx-auto px-4 py-12">
        
        @foreach($categories as $category)
            <div class="mb-12">
                <h3 class="text-3xl font-bold text-tosca-dark border-l-4 border-pink pl-4 mb-6">
                    {{ $category->name }}
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($category->products as $product)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 group">
                        
                        <div class="h-48 overflow-hidden bg-gray-100 relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <span class="text-sm">No Image</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h4 class="text-lg font-bold text-gray-800 mb-1">{{ $product->name }}</h4>
                            
                            @if($product->price)
                                <p class="text-pink font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            @else
                                <p class="text-sm text-gray-500 italic">Hubungi Admin</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($category->products->count() == 0)
                    <p class="text-gray-400 italic text-center py-4">Belum ada produk di kategori ini.</p>
                @endif
            </div>
        @endforeach

    </main>

    <footer class="bg-tosca-dark text-white py-8 text-center">
        <p>&copy; {{ date('Y') }} Tunas Tirta Fresh. All rights reserved.</p>
    </footer>

</body>
</html>