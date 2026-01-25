@extends('layouts.app')

@section('content')
    <div class="border-b border-gray-200 bg-white">
        <div class="container mx-auto px-4 py-6 md:py-8">
            <h1 class="text-xl md:text-2xl font-black text-dark uppercase tracking-tight">
                Keranjang <span class="text-primary">[{{ $carts->count() }}]</span>
            </h1>
            <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">
                Ringkasan pesanan anda
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 md:py-10 pb-32 sm:pb-12">
        @if($carts->isEmpty())
            <div class="text-center py-20 border border-dashed border-gray-200 rounded-xl">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i class="fas fa-shopping-basket text-xl text-gray-200"></i>
                </div>
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Keranjang Kosong</h3>
                <a href="{{ route('home') }}"
                    class="inline-block bg-dark text-white px-8 py-3 font-bold uppercase text-[10px] tracking-widest hover:bg-primary transition-all">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-10">

                <div class="flex-1">
                    <div class="space-y-0 border-t border-gray-100">
                        @foreach($carts as $cart)
                            <div class="flex items-center gap-4 sm:gap-6 py-6 border-b border-gray-100 group">
                                <div class="w-20 h-20 sm:w-28 sm:h-28 flex-shrink-0 bg-gray-50 border border-gray-100 rounded p-2 overflow-hidden relative">
                                    @if($cart->product->image)
                                        <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}"
                                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>

                                <div class="flex-1 flex flex-col h-20 sm:h-28 justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-xs sm:text-sm font-bold uppercase leading-tight tracking-wide text-dark group-hover:text-primary transition-colors">
                                                {{ $cart->product->name }}
                                            </h3>
                                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="ml-4">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-300 hover:text-red-600 transition-colors">
                                                    <i class="fas fa-times text-[10px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <span class="inline-block mt-1 text-[8px] font-bold uppercase tracking-widest text-gray-400">
                                            {{ $cart->variant ? $cart->variant->name : 'Standard Pack' }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-end">
                                        <div class="flex items-center border border-gray-200 rounded-none bg-white">
                                            <form action="{{ route('cart.decrease', $cart->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-7 h-7 flex items-center justify-center text-xs hover:bg-gray-50 text-dark">-</button>
                                            </form>
                                            <div class="w-8 text-center font-bold text-[10px] border-x border-gray-200 h-7 flex items-center justify-center bg-white">
                                                {{ $cart->quantity }}
                                            </div>
                                            <form action="{{ route('cart.add', $cart->product_id) }}" method="POST">
                                                @csrf
                                                @if($cart->product_variant_id) <input type="hidden" name="variant_id" value="{{ $cart->product_variant_id }}"> @endif
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="w-7 h-7 flex items-center justify-center text-xs hover:bg-gray-50 text-dark">+</button>
                                            </form>
                                        </div>

                                        @php $price = $cart->variant ? $cart->variant->price : $cart->product->price; @endphp
                                        <p class="text-sm sm:text-base font-black text-dark">
                                            Rp{{ number_format($price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:w-80 hidden lg:block">
                    <form action="{{ route('checkout.process') }}" method="POST"
                        class="border border-gray-100 p-8 sticky top-8 bg-white shadow-xl rounded-none">
                        @csrf
                        <h3 class="text-[10px] font-black uppercase mb-8 tracking-[0.2em] text-dark border-b border-gray-50 pb-4">
                            Ringkasan <span class="text-primary">Belanja</span>
                        </h3>

                        <div class="space-y-6">
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-3">WhatsApp / HP</label>
                                <input type="number" name="phone" required
                                    class="w-full bg-gray-50 border-none p-4 text-[11px] font-bold focus:outline-none focus:ring-1 focus:ring-primary placeholder-gray-300 rounded-none"
                                    placeholder="CONTOH: 08123456789">
                            </div>

                            <div>
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-3">Alamat Pengiriman</label>
                                <textarea name="address" required
                                    class="w-full bg-gray-50 border-none p-4 text-[11px] font-bold focus:outline-none focus:ring-1 focus:ring-primary min-h-[100px] placeholder-gray-300 uppercase tracking-tighter rounded-none"
                                    placeholder="ALAMAT LENGKAP..."></textarea>
                            </div>

                            <div>
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-3">Catatan</label>
                                <input type="text" name="note"
                                    class="w-full bg-gray-50 border-none p-4 text-[11px] font-bold focus:outline-none focus:ring-1 focus:ring-primary placeholder-gray-300 uppercase tracking-tighter rounded-none"
                                    placeholder="OPSIONAL">
                            </div>

                            <div class="pt-4 border-t border-gray-50">
                                <div class="flex justify-between items-end mb-6">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Total Tagihan</span>
                                    @php $total = $carts->sum(fn($i) => ($i->variant ? $i->variant->price : $i->product->price) * $i->quantity); @endphp
                                    <span class="text-xl font-black text-primary leading-none tracking-tighter">
                                        Rp{{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>

                                <button type="submit"
                                    class="w-full bg-dark hover:bg-primary text-white py-5 font-black uppercase tracking-[0.2em] text-[10px] transition-all flex justify-center gap-3 items-center group rounded-none">
                                    <span>Lanjut ke WhatsApp</span>
                                    <i class="fab fa-whatsapp text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    @if(!$carts->isEmpty())
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 lg:hidden z-50 shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
            <div class="max-w-xl mx-auto flex justify-between items-center mb-3">
                <div>
                    <p class="text-[8px] font-bold uppercase text-gray-400 tracking-widest mb-0.5">Total Bayar</p>
                    <p class="text-lg font-black text-primary leading-none">Rp{{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <button onclick="document.getElementById('mobile-drawer').classList.toggle('translate-y-0')"
                    class="w-1/2 bg-dark text-white py-3 font-bold uppercase tracking-widest text-[10px] hover:bg-primary">
                    ISI DATA & BELI
                </button>
            </div>

            <div id="mobile-drawer" class="fixed inset-0 bg-white z-[60] transform translate-y-full transition-transform duration-500 p-6 flex flex-col">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="font-black uppercase text-sm text-dark tracking-widest">Data Pengiriman</h3>
                    <button type="button" onclick="document.getElementById('mobile-drawer').classList.remove('translate-y-0')">
                        <i class="fas fa-times text-xs text-dark"></i>
                    </button>
                </div>

                <form action="{{ route('checkout.process') }}" method="POST" class="flex flex-col h-full">
                    @csrf
                    <div class="flex-1 space-y-4">
                        <div>
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-2 text-left">WhatsApp</label>
                            <input type="number" name="phone" required class="w-full border border-gray-100 p-4 text-xs font-medium focus:outline-none focus:border-dark" placeholder="08xxxx">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-2 text-left">Alamat Lengkap</label>
                            <textarea name="address" required class="w-full border border-gray-100 p-4 text-xs font-medium focus:outline-none focus:border-dark h-24" placeholder="Jalan, No Rumah, Desa..."></textarea>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-2 text-left">Catatan</label>
                            <input type="text" name="note" class="w-full border border-gray-100 p-4 text-xs font-medium focus:outline-none focus:border-dark" placeholder="Opsional">
                        </div>
                    </div>

                    <div class="pb-6 pt-6 border-t">
                        <div class="flex justify-between mb-4">
                            <span class="font-bold uppercase text-gray-400 text-[10px]">Total</span>
                            <span class="font-black text-xl text-primary">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <button type="submit" class="w-full bg-dark text-white py-4 font-bold uppercase text-[10px] flex justify-center gap-2 items-center">
                            <span>Lanjut ke WhatsApp</span>
                            <i class="fab fa-whatsapp text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection