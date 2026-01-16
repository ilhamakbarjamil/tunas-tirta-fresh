@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-dark mb-2">Produk Pilihan</h2>
        <p class="text-gray-500">Buah segar langsung dari kebun petani lokal</p>
        <div class="w-20 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach($products as $product)
        <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-xl transition duration-300 group border border-transparent hover:border-gray-100 relative flex flex-col justify-between">
            
            <div>
                <button class="absolute top-4 right-4 text-gray-300 hover:text-danger transition z-10">
                    <i class="far fa-heart text-xl"></i>
                </button>

                <a href="{{ route('products.show', $product->slug) }}" class="block mb-4 overflow-hidden rounded-lg">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-contain transform group-hover:scale-110 transition duration-500">
                </a>

                <div class="text-center mb-2">
                    <h3 class="text-lg font-medium text-dark group-hover:text-primary transition truncate">
                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                    </h3>
                    
                    <p class="text-xl font-bold text-dark" id="price-display-{{ $product->id }}">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                
                <div class="mb-3 px-2">
                    @if($product->variants->isNotEmpty())
                        <select name="variant_id" 
                                onchange="updatePrice(this, {{ $product->id }})" 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded px-3 py-2 focus:outline-none focus:border-primary cursor-pointer">
                            
                            <option value="" data-price="{{ $product->price }}">pilih varian</option>
                            
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}" data-price="{{ $variant->price }}">
                                    {{ $variant->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="h-[38px] flex items-center justify-center text-xs text-gray-300">
                            Satuan (Pack)
                        </div>
                    @endif
                </div>

                <div class="flex items-center space-x-2 px-2 pb-2">
                    <input type="number" name="quantity" value="1" min="1" class="border border-gray-200 rounded bg-gray-50 h-10 w-16 text-center text-sm focus:outline-none focus:border-primary">

                    @if($product->stock > 0)
                        <button type="submit" class="flex-1 bg-primary hover:bg-green-600 text-white font-bold h-10 rounded shadow-md transition flex items-center justify-center space-x-2">
                            <i class="fas fa-shopping-cart text-xs"></i>
                            <span class="text-sm uppercase tracking-wide">Add</span>
                        </button>
                    @else
                        <button type="button" disabled class="flex-1 bg-gray-300 text-white font-bold h-10 rounded cursor-not-allowed text-sm">
                            Habis
                        </button>
                    @endif
                </div>
            </form>

        </div>
        @endforeach
    </div>
</div>
@endsection

<script>
    function updatePrice(selectElement, productId) {
        // 1. Ambil harga dari opsi yang dipilih (data-price)
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var newPrice = selectedOption.getAttribute('data-price');

        // 2. Cari elemen Harga yang sesuai ID produk
        var priceDisplay = document.getElementById('price-display-' + productId);

        // 3. Ubah format angka jadi Rupiah (Contoh: 50000 -> Rp 50.000)
        if (newPrice) {
            var formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(newPrice);

            // 4. Update tampilan
            priceDisplay.innerText = formattedPrice;
        }
    }
</script>