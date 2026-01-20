@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header (Konsisten dengan Produk Pilihan) -->
    <div class="flex items-center justify-between mb-8 border-b-2 border-gray-100 pb-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase">Riwayat Pesanan</h2>
            <p class="text-gray-600 font-medium mt-1">Pantau status pesanan dan pembayaran Anda.</p>
        </div>
    </div>

    <!-- Filter Status (Konsisten dengan screenshot tombol yang Anda kirim) -->
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('orders.index') }}" 
           class="px-5 py-2.5 text-xs font-bold rounded-md uppercase tracking-wide border transition-all
           {{ !request('status') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-primary' }}">
            Semua
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
           class="px-5 py-2.5 text-xs font-bold rounded-md uppercase tracking-wide border transition-all
           {{ request('status') == 'pending' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-primary' }}">
            Belum Bayar
        </a>
        <a href="{{ route('orders.index', ['status' => 'processed']) }}" 
           class="px-5 py-2.5 text-xs font-bold rounded-md uppercase tracking-wide border transition-all
           {{ request('status') == 'processed' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-primary' }}">
            Diproses
        </a>
        <a href="{{ route('orders.index', ['status' => 'completed']) }}" 
           class="px-5 py-2.5 text-xs font-bold rounded-md uppercase tracking-wide border transition-all
           {{ request('status') == 'completed' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-primary' }}">
            Selesai
        </a>
    </div>

    <div class="space-y-8">
        @forelse($orders as $order)
            <!-- Order Card (Konsisten dengan Card Produk) -->
            <div class="bg-white rounded-lg border border-gray-200 hover:border-primary transition-colors duration-200 overflow-hidden flex flex-col group">
                
                <!-- Header Card (Info Order) -->
                <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4 bg-white">
                    <div class="flex items-center gap-4">
                        <div class="bg-gray-100 px-3 py-1 rounded text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                            #{{ $order->external_id ?? $order->id }}
                        </div>
                        <span class="text-sm text-gray-400 font-medium">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <!-- Status Label (Style Persegi Panjang Solid) -->
                    @if($order->status == 'pending')
                        <span class="bg-yellow-500 text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest">
                            MENUNGGU PEMBAYARAN
                        </span>
                    @elseif($order->status == 'processed')
                        <span class="bg-blue-600 text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest">
                            SEDANG DIPROSES
                        </span>
                    @elseif($order->status == 'completed')
                        <span class="bg-primary text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest">
                            PESANAN SELESAI
                        </span>
                    @else
                        <span class="bg-red-500 text-white text-[10px] font-black px-3 py-1.5 uppercase tracking-widest">
                            DIBATALKAN
                        </span>
                    @endif
                </div>

                <!-- Body Card (Daftar Produk) -->
                <div class="p-6 space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4">
                            <!-- Thumbnail (Konsisten dengan Thumbnail Produk) -->
                            <div class="w-16 h-16 bg-white border border-gray-100 rounded-md p-1 flex-shrink-0">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-200"><i class="fas fa-image"></i></div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 leading-tight uppercase tracking-tight">{{ $item->product->name }}</h4>
                                <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase">
                                    {{ $item->quantity }}x @if($item->variant) | {{ $item->variant->name }} @endif
                                </p>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer Card (Total & Action) -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pembayaran</p>
                        <p class="text-xl font-black text-primary">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a href="https://wa.me/6281234567890" target="_blank" 
                           class="flex-1 sm:flex-none border border-gray-300 bg-white hover:border-gray-900 text-gray-900 font-bold h-10 px-6 rounded-md transition-colors text-[11px] uppercase tracking-widest flex items-center justify-center">
                            Bantuan
                        </a>

                        @if($order->status == 'pending' && $order->payment_url)
                            <a href="{{ $order->payment_url }}" 
                               class="flex-1 sm:flex-none bg-gray-900 hover:bg-primary text-white font-bold h-10 px-8 rounded-md transition-colors flex items-center justify-center gap-2 text-[11px] uppercase tracking-widest shadow-lg shadow-gray-200">
                                <span>BAYAR SEKARANG</span>
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-20 bg-white border border-gray-200 rounded-lg shadow-sm">
                <i class="fas fa-receipt text-6xl text-gray-100 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 uppercase">Belum Ada Pesanan</h3>
                <p class="text-gray-500 font-medium mb-6">Anda belum memiliki riwayat transaksi.</p>
                <a href="{{ route('home') }}" class="inline-block bg-gray-900 hover:bg-primary text-white font-bold px-8 py-3 rounded-md transition-colors uppercase text-xs tracking-widest">
                    Mulai Belanja
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection