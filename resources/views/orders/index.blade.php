@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header (Persis Produk Pilihan) -->
    <div class="flex items-center justify-between mb-8 border-b-2 border-gray-100 pb-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Pesanan</h2>
            <p class="text-gray-600 font-medium mt-1">Pantau status pesanan dan pembayaran Anda.</p>
        </div>
    </div>

    <!-- Filter/Tabs (Style Button Minimalis) -->
    <div class="flex flex-wrap gap-2 mb-8">
        <button class="px-5 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-md uppercase tracking-wide">Semua</button>
        <button class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-md uppercase tracking-wide hover:border-primary transition-colors">Belum Bayar</button>
        <button class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-md uppercase tracking-wide hover:border-primary transition-colors">Diproses</button>
        <button class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-md uppercase tracking-wide hover:border-primary transition-colors">Selesai</button>
    </div>

    <div class="space-y-8">
        @forelse($orders as $order)
        <div class="bg-white rounded-lg border border-gray-200 hover:border-primary transition-colors duration-200 flex flex-col group overflow-hidden">
            
            <!-- Card Header (Info Order & Status) -->
            <div class="p-4 border-b border-gray-100 bg-white flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-gray-100 px-3 py-1 rounded text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                        #{{ $order->external_id ?? $order->id }}
                    </div>
                    <span class="text-sm text-gray-400 font-medium">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                <!-- Status Badge (Style mirip label promo) -->
                @if($order->status == 'pending')
                    <span class="bg-yellow-500 text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm">
                        Menunggu Pembayaran
                    </span>
                @elseif($order->status == 'processed')
                    <span class="bg-blue-600 text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm">
                        Diproses
                    </span>
                @elseif($order->status == 'completed')
                    <span class="bg-primary text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm">
                        Selesai
                    </span>
                @else
                    <span class="bg-red-500 text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm">
                        Dibatalkan
                    </span>
                @endif
            </div>

            <!-- List Produk (Mirip Cart Style) -->
            <div class="p-6 space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4">
                    <!-- Thumbnail (Style Box Produk) -->
                    <div class="w-16 h-16 bg-white border border-gray-100 rounded-md p-1 flex-shrink-0">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-50"><i class="fas fa-image text-gray-200"></i></div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-900 leading-tight">{{ $item->product->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $item->quantity }}x @if($item->variant) ({{ $item->variant->name }}) @endif
                        </p>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Card Footer (Total & Action - Persis footer kartu produk) -->
            <div class="p-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Tagihan</p>
                    <p class="text-xl font-black text-primary">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <!-- Tombol Bantuan (Style Border Putih) -->
                    <a href="https://wa.me/6281234567890" target="_blank" 
                       class="flex-1 sm:flex-none border border-gray-300 bg-white hover:border-gray-900 text-gray-900 font-bold h-10 px-6 rounded-md transition-colors duration-200 flex items-center justify-center text-xs uppercase tracking-wider">
                        Bantuan
                    </a>

                    @if($order->status == 'pending' && $order->payment_url)
                        <!-- Tombol Bayar (Style Hitam Hitam/Primary) -->
                        <a href="{{ $order->payment_url }}" 
                           class="flex-1 sm:flex-none bg-gray-900 hover:bg-primary text-white font-bold h-10 px-8 rounded-md transition-colors duration-200 flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                            <span>BAYAR</span>
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </a>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <!-- Empty State (Style konsisten) -->
        <div class="text-center py-20 bg-white border border-gray-200 rounded-lg shadow-sm">
            <i class="fas fa-receipt text-6xl text-gray-100 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900">Belum Ada Pesanan</h3>
            <p class="text-gray-500 mb-6">Mulai belanja produk pilihan Anda sekarang.</p>
            <a href="{{ route('home') }}" class="inline-block bg-gray-900 hover:bg-primary text-white font-bold px-8 py-3 rounded-md transition-colors uppercase text-sm tracking-widest">
                Mulai Belanja
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection