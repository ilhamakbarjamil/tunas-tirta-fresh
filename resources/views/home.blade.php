@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-6 md:py-8">
    
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6 md:mb-8 border-b-2 border-gray-100 pb-4">
        <div>
            <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Produk Pilihan</h2>
            <p class="text-xs md:text-gray-600 font-medium mt-1">Langsung dari kebun petani lokal.</p>
        </div>
    </div>

    <!-- Product Grid: Mobile 2 Kolom, Tablet 3, Desktop 4 -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-xl border border-gray-200 hover:border-primary transition-all duration-200 flex flex-col justify-between group overflow-hidden relative shadow-sm hover:shadow-md">
            
            <!-- Wishlist Button & Image -->
            <div class="p-2 md:p-4 border-b border-gray-100 bg-white relative">
                <button class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors z-10">
                    <i class="far fa-heart text-sm md:text-lg"></i>
                </button>

                <a href="{{ route('products.show', $product->slug) }}" class="block overflow-hidden">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-32 md:h-48 object-contain transform group-hover:scale-105 transition duration-300 ease-out">
                </a>
            </div>

            <!-- Product Info -->
            <div class="p-3 md:p-4 flex-1 flex flex-col">
                <h3 class="text-sm md:text-base font-bold text-gray-900 leading-tight mb-1 group-hover:text-primary transition-colors line-clamp-2 min-h-[2.5rem] md:min-h-0">
                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                </h3>
                
                <div class="mt-auto pt-2">
                    <p class="text-sm md:text-lg font-black text-primary" id="price-display-{{ $product->id }}">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Footer Card: Variant & Buy Button -->
            <div class="p-3 md:p-4 bg-gray-50 border-t border-gray-100">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        @if($product->variants->isNotEmpty())
                            <select name="variant_id" 
                                    onchange="updatePrice(this, {{ $product->id }})" 
                                    class="w-full bg-white border border-gray-300 text-gray-700 text-xs md:text-sm font-medium rounded-md px-2 md:px-3 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-shadow cursor-pointer">
                                
                                <option value="" data-price="{{ $product->price }}">Pilih Varian</option>
                                
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}" data-price="{{ $variant->price }}">
                                        {{ $variant->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="h-[34px] md:h-[42px] w-full bg-gray-100 border border-dashed border-gray-300 rounded-md flex items-center justify-center text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wide">
                                Single Pack
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-1.5 md:gap-2">
                        <input type="number" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               class="w-10 md:w-14 h-8 md:h-10 text-center text-xs md:text-base font-bold text-gray-800 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">

                        @if($product->stock > 0)
                            <button type="submit" class="flex-1 bg-gray-900 hover:bg-primary text-white font-bold h-8 md:h-10 rounded-md transition-colors duration-200 flex items-center justify-center gap-1 md:gap-2">
                                <span class="text-[10px] md:text-sm">TAMBAH</span>
                                <i class="fas fa-plus text-[10px]"></i>
                            </button>
                        @else
                            <button type="button" disabled class="flex-1 bg-gray-200 text-gray-400 font-bold h-8 md:h-10 rounded-md cursor-not-allowed text-[10px] md:text-sm uppercase">
                                Habis
                            </button>
                        @endif
                    </div>
                </form>
            </div>

        </div>
        @endforeach
    </div>
</div>

<!-- Modal Promo: Responsive Optimization -->
@if($activePromo)
<div id="promo-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900 bg-opacity-80 transition-opacity backdrop-blur-sm" onclick="closePromo()"></div>

    <div class="relative bg-white w-full max-w-4xl shadow-2xl overflow-hidden flex flex-col md:flex-row transform scale-95 transition-all duration-300 max-h-[90vh] overflow-y-auto md:overflow-visible" id="promo-content">
        
        <button onclick="closePromo()" class="absolute top-2 right-2 md:top-4 md:right-4 z-20 bg-white text-gray-800 hover:bg-red-500 hover:text-white w-8 h-8 md:w-10 md:h-10 flex items-center justify-center transition border border-gray-200 shadow-sm">
            <i class="fas fa-times text-sm md:text-lg"></i>
        </button>

        <!-- Promo Image -->
        <div class="w-full md:w-1/2 relative bg-gray-100 min-h-[200px] md:min-h-[400px]">
            @if($activePromo->image)
                <img src="{{ asset('storage/' . $activePromo->image) }}" 
                     alt="{{ $activePromo->title }}" 
                     class="absolute inset-0 w-full h-full object-cover">
                
                <div class="absolute top-0 left-0 bg-primary text-white font-bold px-4 py-1.5 md:px-6 md:py-2 text-[10px] md:text-xs uppercase tracking-widest shadow-lg">
                    Limited Offer
                </div>
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-gray-200 text-gray-400">
                    <i class="fas fa-image text-4xl"></i>
                </div>
            @endif
        </div>

        <!-- Promo Text -->
        <div class="w-full md:w-1/2 p-6 md:p-12 flex flex-col justify-center text-left bg-white relative">
            <span class="text-primary font-bold tracking-[0.2em] uppercase text-[10px] md:text-xs mb-2 md:mb-3 block">
                Official Announcement
            </span>

            <h2 class="text-2xl md:text-4xl font-black text-gray-900 leading-tight mb-3 md:mb-4 uppercase">
                {{ $activePromo->title }}
            </h2>

            <div class="w-16 md:w-20 h-1 md:h-1.5 bg-primary mb-4 md:mb-6"></div>

            <p class="text-gray-600 text-xs md:text-base leading-relaxed mb-6 md:mb-8">
                {{ $activePromo->description }}
            </p>

            <div class="mt-auto">
                @if($activePromo->button_link)
                    <a href="{{ url($activePromo->button_link) }}" class="block w-full bg-gray-900 hover:bg-primary text-white text-center font-bold py-3 md:py-4 text-sm md:text-base uppercase tracking-wider transition-colors duration-300">
                        {{ $activePromo->button_text }} &nbsp; <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                @else
                    <button onclick="closePromo()" class="block w-full bg-gray-900 hover:bg-primary text-white text-center font-bold py-3 md:py-4 text-sm md:text-base uppercase tracking-wider transition-colors duration-300">
                        {{ $activePromo->button_text }}
                    </button>
                @endif
                
                <button onclick="closePromo()" class="block w-full text-center text-gray-400 text-[10px] md:text-xs font-medium mt-4 hover:text-gray-600 underline">
                    Tutup / Abaikan
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentPromoId = "{{ $activePromo ? $activePromo->id : 'none' }}";
        const lastSeenId = sessionStorage.getItem('last_seen_promo_id');

        if (currentPromoId !== 'none' && lastSeenId != currentPromoId) {
            setTimeout(() => {
                const modal = document.getElementById('promo-modal');
                const content = document.getElementById('promo-content');
                if(modal) {
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        content.classList.remove('scale-95');
                        content.classList.add('scale-100');
                    }, 50);
                }
            }, 1000);
        }
    });

    function closePromo() {
        const modal = document.getElementById('promo-modal');
        if(modal) {
            modal.classList.add('hidden');
            const currentPromoId = "{{ $activePromo ? $activePromo->id : 'none' }}";
            sessionStorage.setItem('last_seen_promo_id', currentPromoId);
        }
    }

    function updatePrice(selectElement, productId) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var newPrice = selectedOption.getAttribute('data-price');
        var priceDisplay = document.getElementById('price-display-' + productId);

        if (newPrice) {
            var formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(newPrice);
            priceDisplay.innerText = formattedPrice;
        }
    }
</script>
@endsection