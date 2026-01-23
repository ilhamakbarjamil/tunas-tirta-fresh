@extends('layouts.app')

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-gray-200 bg-white">
    <div class="container mx-auto px-4 py-3">
        <nav class="flex text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-gray-500 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <a href="{{ route('home') }}" class="hover:text-dark transition-colors">Home</a> 
            <span class="mx-2 text-gray-300">/</span> 
            <a href="#" class="hover:text-dark transition-colors">{{ $product->category->name ?? 'Product' }}</a>
            <span class="mx-2 text-gray-300">/</span> 
            <span class="text-dark">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-6 md:py-10 pb-24 lg:pb-10">
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-16">
        
        <!-- Kolom Gambar -->
        <div class="w-full lg:w-7/12">
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 sm:p-10 flex justify-center items-center relative overflow-hidden group min-h-[300px] md:min-h-[450px]">
                @if($product->stock <= 5 && $product->stock > 0)
                    <div class="absolute top-4 left-4 bg-red-600 text-white text-[8px] md:text-[9px] font-bold px-2 py-1 uppercase tracking-wider z-10">
                        Stok Menipis
                    </div>
                @elseif($product->stock > 5)
                    <div class="absolute top-4 left-4 bg-primary text-white text-[8px] md:text-[9px] font-bold px-2 py-1 uppercase tracking-wider z-10">
                        Ready Stock
                    </div>
                @endif

                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="max-h-[280px] md:max-h-[400px] w-auto object-contain drop-shadow-md transform transition duration-700 group-hover:scale-105">
            </div>
        </div>

        <!-- Kolom Informasi Produk -->
        <div class="w-full lg:w-5/12 flex flex-col">
            
            <h1 class="text-xl md:text-2xl lg:text-3xl font-black text-dark uppercase leading-tight mb-3 tracking-tight">
                {{ $product->name }}
            </h1>

            <div class="flex items-center gap-4 mb-5 border-b border-gray-100 pb-5">
                <div class="flex text-yellow-500 text-[10px]">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-wide text-gray-400">Terjual 150+</p>
            </div>

            <!-- Bagian Harga (Akan diupdate oleh JS) -->
            <div class="mb-6 md:mb-8">
                <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Harga Terpilih</p>
                <div class="flex items-baseline gap-2">
                    <span id="display-price" class="text-xl md:text-2xl font-black text-primary" data-base-price="{{ $product->price }}">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span id="display-unit" class="text-[10px] font-bold text-gray-400 uppercase">/ pack</span>
                </div>
            </div>

            <div class="mb-6 md:mb-8">
                <h3 class="text-[9px] md:text-[10px] font-black text-dark uppercase tracking-widest mb-2">Deskripsi Produk</h3>
                <p class="text-gray-600 text-[13px] leading-relaxed text-justify">
                    {{ $product->description }}
                </p>
            </div>

            <!-- Bagian Form di dalam product.blade.php -->
<form action="{{ route('cart.add', $product->id) }}" method="POST" id="add-to-cart-form" class="mt-auto">
    @csrf
    
    <div class="mb-5">
        <label class="block text-[9px] md:text-[10px] font-black text-dark uppercase tracking-widest mb-2">
            Pilih Satuan / Varian <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select name="variant_id" id="variant-select" required class="w-full appearance-none bg-white border border-gray-200 text-dark font-bold py-3 px-4 rounded-none focus:outline-none focus:border-dark transition-colors text-[11px] uppercase tracking-wider">
                
                <!-- 1. Placeholder (Value kosong agar 'required' bekerja) -->
                <option value="" disabled selected>--- PILIH VARIAN ---</option>

                <!-- 2. Pilihan Standar (Sekarang diberi value "normal" agar valid) -->
                <option value="normal" data-price="{{ $product->price }}" data-unit="pack">
                    STANDAR (Rp {{ number_format($product->price, 0, ',', '.') }})
                </option>
                
                <!-- 3. Pilihan Varian Lainnya -->
                @if($product->variants && $product->variants->count() > 0)
                    @foreach($product->variants as $variant)
                        <option value="{{ $variant->id }}" data-price="{{ $variant->price }}" data-unit="{{ strtolower($variant->name) }}">
                            {{ strtoupper($variant->name) }} (Rp {{ number_format($variant->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                @endif
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-dark">
                <i class="fas fa-chevron-down text-[10px]"></i>
            </div>
        </div>
    </div>

    <!-- Tombol Submit (Hanya aktif jika sudah pilih) -->
    <div class="hidden lg:block space-y-3">
        @if($product->stock > 0)
            <button type="submit" class="w-full bg-dark hover:bg-primary text-white font-bold text-[10px] md:text-[11px] uppercase tracking-[0.2em] py-4 px-6 transition-all duration-300 flex items-center justify-center gap-3 group">
                <span>Masukkan Keranjang</span>
                <i class="fas fa-arrow-right text-[9px] transform group-hover:translate-x-1 transition-transform"></i>
            </button>
        @else
            <button type="button" disabled class="w-full bg-gray-200 text-gray-400 font-bold text-[10px] uppercase tracking-widest py-4 cursor-not-allowed">
                Stok Habis
            </button>
        @endif
    </div>
    <!-- ... bagian sticky mobile juga menyesuaikan ... -->
</form>
            
            <div class="mt-6 flex items-center gap-2 text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest justify-center lg:justify-start">
                <i class="fas fa-lock"></i> Transaksi Aman & Terenkripsi
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Update Harga secara Real-time -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantSelect = document.getElementById('variant-select');
        const displayPrice = document.getElementById('display-price');
        const mobileDisplayPrice = document.getElementById('mobile-display-price');
        const displayUnit = document.getElementById('display-unit');

        variantSelect.addEventListener('change', function() {
            // Ambil data dari atribut data- di option yang dipilih
            const selectedOption = this.options[this.selectedIndex];
            const price = parseInt(selectedOption.getAttribute('data-price'));
            const unit = selectedOption.getAttribute('data-unit');

            // Format Rupiah
            const formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price).replace('Rp', 'Rp ');

            // Update UI
            displayPrice.innerText = formattedPrice;
            if(mobileDisplayPrice) mobileDisplayPrice.innerText = formattedPrice;
            displayUnit.innerText = '/ ' + unit;
        });
    });
</script>
@endsection