@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-5xl">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-dark uppercase tracking-tight">Riwayat Pesanan</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau status pembayaran dan pengiriman Anda.</p>
            </div>
            <a href="{{ route('home') }}" class="text-sm font-bold text-primary hover:underline">
                &larr; Kembali Belanja
            </a>
        </div>

        <div class="flex overflow-x-auto border-b border-gray-200 mb-6 gap-6 hide-scrollbar">
            <button class="pb-3 text-sm font-bold text-primary border-b-2 border-primary whitespace-nowrap">Semua Pesanan</button>
            <button class="pb-3 text-sm font-bold text-gray-400 hover:text-dark whitespace-nowrap">Belum Bayar</button>
            <button class="pb-3 text-sm font-bold text-gray-400 hover:text-dark whitespace-nowrap">Diproses</button>
        </div>

        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                    
                    <div class="px-6 py-4 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50/50">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-shopping-bag text-gray-400"></i>
                            <span class="text-sm font-bold text-dark">
                                Order #{{ $order->external_id ?? $order->id }}
                            </span>
                            <span class="text-xs text-gray-400 border-l border-gray-300 pl-3">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        
                        <div>
                            @if($order->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide flex items-center gap-1">
                                    <i class="fas fa-clock"></i> MENUNGGU PEMBAYARAN
                                </span>
                            @elseif($order->status == 'processed')
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide flex items-center gap-1">
                                    <i class="fas fa-box"></i> DIPROSES
                                </span>
                            @elseif($order->status == 'completed')
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> SELESAI
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide flex items-center gap-1">
                                    <i class="fas fa-times"></i> DIBATALKAN
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @foreach($order->items as $item)
                            <div class="flex items-start gap-4 mb-4 last:mb-0">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                
                                <div class="flex-1">
                                    <h4 class="font-bold text-dark text-sm">{{ $item->product->name }}</h4>
                                    @if($item->variant)
                                        <p class="text-xs text-gray-500 mb-1">Varian: {{ $item->variant->name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-left w-full sm:w-auto">
                            <p class="text-xs text-gray-500 font-bold uppercase">Total Tagihan</p>
                            <p class="text-lg font-black text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex gap-3 w-full sm:w-auto">
                            
                            <a href="https://wa.me/6281234567890" target="_blank" class="flex-1 sm:flex-none px-4 py-2 bg-white border border-gray-300 text-gray-600 text-sm font-bold rounded hover:bg-gray-50 transition-colors text-center">
                                Bantuan
                            </a>

                            @if($order->status == 'pending' && $order->payment_url)
                                <a href="{{ $order->payment_url }}" class="flex-1 sm:flex-none px-6 py-2 bg-primary text-white text-sm font-bold rounded hover:bg-green-600 shadow-md transition-all text-center flex items-center justify-center gap-2">
                                    <span>Bayar Sekarang</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif

                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                    <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-bold text-dark">Belum ada pesanan</h3>
                    <a href="{{ route('home') }}" class="text-primary font-bold text-sm mt-2 hover:underline">Mulai Belanja &rarr;</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection