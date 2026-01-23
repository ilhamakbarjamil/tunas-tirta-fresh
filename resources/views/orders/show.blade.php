@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <a href="{{ route('orders.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-dark mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
    </a>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm max-w-3xl mx-auto">
        
        <div class="bg-gray-900 p-6 text-white flex justify-between items-start">
            <div>
                <h1 class="text-xl font-bold uppercase tracking-widest">Invoice</h1>
                <p class="text-xs text-gray-400 mt-1">Order ID #{{ $order->id }}</p>
                <p class="text-xs text-gray-400">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="text-right">
                @if($order->status == 'paid')
                    <span class="bg-green-500 text-white text-[10px] font-black px-3 py-1 rounded uppercase tracking-widest">Lunas</span>
                @elseif($order->status == 'pending')
                    <span class="bg-yellow-500 text-black text-[10px] font-black px-3 py-1 rounded uppercase tracking-widest">Belum Bayar</span>
                @else
                    <span class="bg-red-500 text-white text-[10px] font-black px-3 py-1 rounded uppercase tracking-widest">{{ $order->status }}</span>
                @endif
            </div>
        </div>

        <div class="p-6 border-b border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Penerima</h3>
                <p class="font-bold text-dark">{{ $order->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
            </div>
            <div>
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Alamat Pengiriman</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $order->address }}</p>
                @if($order->note)
                    <p class="text-xs text-gray-500 mt-2 italic">Catatan: "{{ $order->note }}"</p>
                @endif
            </div>
        </div>

        <div class="p-6">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Rincian Pesanan</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center border-b border-gray-50 pb-4 last:border-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-50 rounded border border-gray-100 overflow-hidden">
                             @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-dark">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                @if($item->variant) <span class="text-gray-400">({{ $item->variant->name }})</span> @endif
                            </p>
                        </div>
                    </div>
                    <p class="font-bold text-dark">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-50 p-6 flex justify-between items-center">
            <span class="font-black uppercase text-xs tracking-widest text-gray-500">Total Tagihan</span>
            <span class="text-2xl font-black text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        
        @if($order->status == 'pending' && $order->snap_token)
        <div class="p-4 bg-white border-t border-gray-100 text-right">
             <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
             <button onclick="snap.pay('{{ $order->snap_token }}')" class="bg-dark text-white px-6 py-3 rounded font-bold text-xs uppercase tracking-widest hover:bg-primary transition-all">
                Bayar Sekarang
             </button>
        </div>
        @endif

    </div>
</div>
@endsection