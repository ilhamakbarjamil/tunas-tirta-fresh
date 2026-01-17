@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex items-center justify-between mb-8 border-b-2 border-gray-100 pb-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Produk Pilihan</h2>
            <p class="text-gray-600 font-medium mt-1">Langsung dari kebun petani lokal.</p>
        </div>
        <div class="hidden md:block w-16 h-16 bg-green-50 rounded-lg flex items-center justify-center border border-green-100">
            <i class="fas fa-leaf text-2xl text-primary"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg border border-gray-200 hover:border-primary transition-colors duration-200 flex flex-col justify-between group overflow-hidden relative">
            
            <div class="p-4 border-b border-gray-100 bg-white relative">
                <button class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors z-10">
                    <i class="far fa-heart text-lg"></i>
                </button>

                <a href="{{ route('products.show', $product->slug) }}" class="block overflow-hidden">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-contain transform group-hover:scale-105 transition duration-300 ease-out">
                </a>
            </div>

            <div class="p-4 flex-1 flex flex-col">
                <h3 class="text-base font-bold text-gray-900 leading-tight mb-1 group-hover:text-primary transition-colors line-clamp-2">
                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                </h3>
                
                <div class="mt-auto pt-2">
                    <p class="text-lg font-black text-primary" id="price-display-{{ $product->id }}">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        @if($product->variants->isNotEmpty())
                            <select name="variant_id" 
                                    onchange="updatePrice(this, {{ $product->id }})" 
                                    class="w-full bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md px-3 py-2.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-shadow cursor-pointer">
                                
                                <option value="" data-price="{{ $product->price }}">Pilih Varian</option>
                                
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}" data-price="{{ $variant->price }}">
                                        {{ $variant->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="h-[42px] w-full bg-gray-100 border border-dashed border-gray-300 rounded-md flex items-center justify-center text-xs font-semibold text-gray-400 uppercase tracking-wide">
                                Single Pack
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="number" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               class="w-14 h-10 text-center font-bold text-gray-800 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">

                        @if($product->stock > 0)
                            <button type="submit" class="flex-1 bg-gray-900 hover:bg-primary text-white font-bold h-10 rounded-md transition-colors duration-200 flex items-center justify-center gap-2">
                                <span class="text-sm">TAMBAH</span>
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        @else
                            <button type="button" disabled class="flex-1 bg-gray-200 text-gray-400 font-bold h-10 rounded-md cursor-not-allowed text-sm uppercase">
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
@endsection

<script>
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