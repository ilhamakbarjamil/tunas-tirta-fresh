@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12">

        <!-- Header Section - Gaya Minimalis & Rapat -->
        <div class="flex flex-col mb-10 border-b border-gray-100 pb-6">
            <h2 class="text-2xl md:text-3xl font-black text-dark uppercase tracking-tighter leading-none">
                Produk <span class="text-primary">Pilihan.</span>
            </h2>
            <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-2">
                Langsung dari kebun petani lokal bali
            </p>
        </div>

        <!-- Product Grid: Sudut Tegas & Tanpa Rounded -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
            @foreach($products as $product)
                <div
                    class="bg-white border border-gray-100 flex flex-col group transition-all duration-300 hover:shadow-xl relative">

                    <!-- Image Area -->
                    <div class="aspect-square bg-gray-50 p-4 relative overflow-hidden">
                        @if($product->stock <= 0)
                            <div
                                class="absolute top-0 left-0 bg-dark text-white text-[8px] font-black px-2 py-1 z-10 uppercase tracking-tighter">
                                Habis
                            </div>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-contain mix-blend-multiply transform group-hover:scale-110 transition duration-700">
                        </a>
                    </div>

                    <!-- Info Area -->
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="text-[11px] font-black text-dark uppercase tracking-tighter leading-tight mb-2 min-h-[2rem]">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-primary transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <div class="mt-auto">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Harga</p>
                            <p class="text-base font-black text-primary tracking-tighter" id="price-display-{{ $product->id }}">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Area - Kontrol Boxy -->
                    <div class="p-4 bg-white border-t border-gray-50">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                @if($product->variants->isNotEmpty())
                                    <div class="relative">
                                        <select name="variant_id" onchange="updatePrice(this, {{ $product->id }})"
                                            class="w-full bg-gray-50 border-none text-dark text-[10px] font-bold uppercase tracking-tighter px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-dark cursor-pointer rounded-none appearance-none" required>

                                            <!-- WAJIB PILIH VARIAN (Tidak ada lagi opsi STANDAR) -->
                                            <option value="" disabled selected>Pilih Varian</option>

                                            @foreach($product->variants as $variant)
                                                <option value="{{ $variant->id }}" data-price="{{ $variant->price }}">
                                                    {{ $variant->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- Icon panah kecil agar user tahu ini dropdown -->
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                            <i class="fas fa-chevron-down text-[8px]"></i>
                                        </div>
                                    </div>
                                @else
                                    <!-- Jika tidak ada varian, tampilkan pesan -->
                                    <div class="text-[9px] font-bold text-gray-300 uppercase tracking-widest py-2 text-center">
                                        Tidak Tersedia
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="number" name="quantity" value="1" min="1"
                                    class="w-12 bg-gray-50 border-none text-center text-xs font-black text-dark py-2.5 focus:outline-none focus:ring-1 focus:ring-dark rounded-none">

                                @if($product->variants->isNotEmpty())
                                    <button type="submit"
                                        class="flex-1 bg-dark hover:bg-primary text-white font-black py-2.5 transition-colors duration-300 flex items-center justify-center gap-2 rounded-none">
                                        <span class="text-[10px] uppercase tracking-widest">Tambah</span>
                                        <i class="fas fa-plus text-[8px]"></i>
                                    </button>
                                @else
                                    <button type="button" disabled
                                        class="flex-1 bg-gray-100 text-gray-300 font-black py-2.5 text-[10px] uppercase tracking-widest rounded-none cursor-not-allowed">
                                        Tidak Tersedia
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Promo Modal - Rombak Total Gaya Minimalis -->
    <!-- Promo Modal - SINGLE LAYER BOXY -->
    @if($activePromo)
        <div id="promo-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
            <!-- Overlay Gelap (Backdrop) -->
            <div class="absolute inset-0 bg-dark/80 backdrop-blur-sm transition-opacity" onclick="closePromo()"></div>

            <!-- Kontainer Modal - Satu Layer Solid -->
            <div class="relative bg-white w-full max-w-5xl flex flex-col md:flex-row shadow-2xl rounded-none overflow-hidden border-none transform scale-95 transition-all duration-500"
                id="promo-content">

                <!-- Tombol Tutup - Menyatu dengan Kontainer -->
                <button onclick="closePromo()"
                    class="absolute top-0 right-0 z-50 bg-dark text-white w-10 h-10 flex items-center justify-center hover:bg-primary transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>

                <!-- AREA GAMBAR -->
                <div class="w-full md:w-1/2 bg-gray-100 relative min-h-[250px] md:min-h-[500px]">
                    @if($activePromo->image)
                        <img src="{{ asset('storage/' . $activePromo->image) }}" class="absolute inset-0 w-full h-full object-cover"
                            alt="{{ $activePromo->title }}">

                        <!-- Tag Announcement -->
                        <div
                            class="absolute top-4 left-4 bg-primary text-white font-black px-3 py-1 text-[8px] uppercase tracking-[0.2em]">
                            Announcement
                        </div>
                    @endif
                </div>

                <!-- AREA TEKS -->
                <div class="w-full md:w-1/2 p-8 md:p-14 flex flex-col justify-center bg-white">
                    <div class="space-y-6">
                        <div>
                            <span class="text-primary font-black tracking-[0.3em] uppercase text-[9px] mb-2 block">
                                Official Info
                            </span>
                            <h2 class="text-3xl md:text-4xl font-black text-dark leading-[0.9] uppercase tracking-tighter">
                                {{ $activePromo->title }}
                            </h2>
                        </div>

                        <div class="w-10 h-[2px] bg-primary"></div>

                        <p class="text-gray-500 text-[12px] font-bold leading-relaxed uppercase tracking-tight text-justify">
                            {{ $activePromo->description }}
                        </p>

                        <div class="pt-4 space-y-4">
                            @if($activePromo->button_link)
                                <a href="{{ url($activePromo->button_link) }}"
                                    class="block w-full bg-dark hover:bg-primary text-white text-center font-black py-4 text-[10px] uppercase tracking-[0.2em] transition-all group">
                                    {{ $activePromo->button_text }}
                                    <i
                                        class="fas fa-arrow-right ml-2 text-[8px] transform group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            @else
                                <button onclick="closePromo()"
                                    class="block w-full bg-dark hover:bg-primary text-white text-center font-black py-4 text-[10px] uppercase tracking-[0.2em] transition-all">
                                    {{ $activePromo->button_text }}
                                </button>
                            @endif

                            <button onclick="closePromo()"
                                class="block w-full text-center text-gray-300 text-[9px] font-black uppercase tracking-widest hover:text-dark transition-colors">
                                Abaikan Pesan Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Logic modal & update price tetap sama, hanya menyesuaikan ID jika perlu
        document.addEventListener("DOMContentLoaded", function () {
            const currentPromoId = "{{ $activePromo ? $activePromo->id : 'none' }}";
            const lastSeenId = sessionStorage.getItem('last_seen_promo_id');

            if (currentPromoId !== 'none' && lastSeenId != currentPromoId) {
                setTimeout(() => {
                    const modal = document.getElementById('promo-modal');
                    const content = document.getElementById('promo-content');
                    if (modal) {
                        modal.classList.remove('hidden');
                        setTimeout(() => {
                            content.classList.remove('scale-95');
                            content.classList.add('scale-100');
                        }, 50);
                    }
                }, 800);
            }
        });

        function closePromo() {
            const modal = document.getElementById('promo-modal');
            if (modal) {
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