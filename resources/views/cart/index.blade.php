@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 sm:pb-12">
    
    <!-- Header: Consistent with layout.txt -->
    <div class="py-8 sm:py-12 mb-8">
        <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 tracking-tight">
            Keranjang <span class="text-primary">({{ $carts->count() }})</span>
        </h2>
    </div>

    @if($carts->isEmpty())
        <div class="text-center py-24 border border-dashed border-gray-200 rounded-lg">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 mx-auto">
                <i class="fas fa-shopping-basket text-3xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-400 uppercase tracking-wide mb-4">Keranjang Anda Kosong</h3>
            <a href="{{ route('home') }}" class="inline-block bg-dark text-white px-8 py-3 font-bold uppercase text-sm hover:bg-primary transition-colors rounded-lg shadow-soft">
                Mulai Belanja
            </a>
        </div>
    @else

        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Daftar Produk -->
            <div class="flex-1">
                <div class="space-y-0 border-t border-gray-100">
                    @foreach($carts as $cart)
                        <div class="flex items-center gap-4 sm:gap-6 py-6 border-b border-gray-100 group">
                            <!-- Image: Consistent styling -->
                            <div class="w-24 h-24 sm:w-32 sm:h-32 flex-shrink-0 bg-gray-50 border border-gray-100 rounded-lg p-2 overflow-hidden relative">
                                <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}" 
                                     class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col h-24 sm:h-32 justify-between py-1">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-sm sm:text-base font-bold uppercase leading-none tracking-tight text-dark group-hover:text-primary transition-colors">
                                            {{ $cart->product->name }}
                                        </h3>
                                        <!-- Hapus Button -->
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="ml-2">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <span class="inline-block mt-1.5 px-2.5 py-0.5 bg-gray-50 text-[10px] font-bold uppercase tracking-widest text-medium rounded">
                                        {{ $cart->variant ? $cart->variant->name : 'Standard Pack' }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-end">
                                    <!-- Stepper: Consistent with layout design -->
                                    <div class="flex items-center border border-gray-200 rounded h-8 sm:h-10 overflow-hidden bg-white">
                                        <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-8 sm:w-10 h-full flex items-center justify-center font-bold hover:bg-gray-50 transition-colors text-medium">-</button>
                                        </form>
                                        <div class="w-8 sm:w-10 text-center font-black text-xs sm:text-sm border-x border-gray-200 h-full flex items-center justify-center bg-gray-50">
                                            {{ $cart->quantity }}
                                        </div>
                                        <form action="{{ route('cart.add', $cart->product_id) }}" method="POST">
                                            @csrf
                                            @if($cart->product_variant_id) <input type="hidden" name="variant_id" value="{{ $cart->product_variant_id }}"> @endif
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-8 sm:w-10 h-full flex items-center justify-center font-bold hover:bg-gray-50 transition-colors text-medium">+</button>
                                        </form>
                                    </div>
                                    
                                    @php $price = $cart->variant ? $cart->variant->price : $cart->product->price; @endphp
                                    <p class="text-sm sm:text-lg font-black text-dark">
                                        Rp{{ number_format($price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar: Checkout (Desktop) -->
            <div class="lg:w-96 hidden lg:block">
                <div class="border border-gray-200 rounded-xl p-6 sticky top-8 shadow-card bg-white">
                    <h3 class="text-xl font-black uppercase mb-6 tracking-tight text-dark">Ringkasan Belanja</h3>
                    
                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-medium block mb-2">Alamat Pengiriman</label>
                                <textarea name="address" required class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:border-primary outline-none min-h-[100px] transition-colors" placeholder="Masukkan alamat lengkap..."></textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-medium block mb-2">Catatan</label>
                                <input type="text" name="note" class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:border-primary outline-none transition-colors" placeholder="Contoh: Pagar hitam">
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-bold uppercase text-medium">Total</span>
                                @php $total = $carts->sum(fn($i) => ($i->variant ? $i->variant->price : $i->product->price) * $i->quantity); @endphp
                                <span class="text-2xl font-black text-primary">Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <button type="submit" class="w-full bg-dark text-white py-4 rounded-lg font-black uppercase tracking-wide hover:bg-primary transition-all active:scale-[0.98] shadow-soft">
                                Bayar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    @endif
</div>

<!-- Mobile Sticky Bottom Bar: Consistent Design -->
@if(!$carts->isEmpty())
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 lg:hidden z-50 shadow-mega">
    <div class="max-w-xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-[10px] font-black uppercase text-medium tracking-wider">Total Pembayaran</p>
                <p class="text-xl font-black text-primary leading-none">Rp{{ number_format($total, 0, ',', '.') }}</p>
            </div>
            <button onclick="document.getElementById('mobile-drawer').classList.toggle('translate-y-0')" class="text-[10px] font-black uppercase border-b border-dark text-dark hover:text-primary transition-colors">
                Detail <i class="fas fa-chevron-up ml-1"></i>
            </button>
        </div>
        
        <!-- Form Checkout Tersembunyi di Mobile Drawer -->
        <div id="mobile-drawer" class="fixed inset-0 bg-white z-[60] transform translate-y-full transition-transform duration-300 p-6 flex flex-col">
            <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                <h3 class="font-black uppercase text-xl text-dark tracking-tight">Lengkapi Data</h3>
                <button onclick="document.getElementById('mobile-drawer').classList.toggle('translate-y-0')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times text-medium"></i>
                </button>
            </div>
            
            <form action="{{ route('checkout.process') }}" method="POST" class="flex flex-col h-full">
                @csrf
                <div class="flex-1 space-y-6">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-medium block mb-2">Alamat Lengkap</label>
                        <textarea name="address" required class="w-full border border-gray-200 rounded-lg p-4 text-sm focus:border-primary outline-none h-32 transition-colors" placeholder="Jalan, No Rumah, Kelurahan..."></textarea>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-medium block mb-2">Catatan Pesanan</label>
                        <input type="text" name="note" class="w-full border border-gray-200 rounded-lg p-4 text-sm focus:border-primary outline-none transition-colors" placeholder="Warna kemasan, posisi rumah, dll">
                    </div>
                </div>
                
                <div class="pb-8 border-t border-gray-100 pt-6">
                    <div class="flex justify-between mb-4 items-end">
                        <span class="font-bold uppercase text-medium text-sm">Total</span>
                        <span class="font-black text-2xl text-primary">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" class="w-full bg-dark text-white py-5 font-black uppercase tracking-wide text-sm rounded-lg shadow-soft hover:bg-primary active:scale-[0.98] transition-all">
                        Konfirmasi & Bayar
                    </button>
                </div>
            </form>
        </div>

        <button onclick="document.getElementById('mobile-drawer').classList.add('translate-y-0')" class="w-full bg-dark text-white py-4 rounded-lg font-black uppercase tracking-wide text-sm hover:bg-primary active:scale-95 transition-all shadow-soft">
            Checkout Sekarang
        </button>
    </div>
</div>
@endif
@endsection