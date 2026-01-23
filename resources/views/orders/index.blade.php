@extends('layouts.app')

@section('content')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<div class="container mx-auto px-3 md:px-4 py-6 md:py-8">
    
    <div class="flex items-center justify-between mb-6 md:mb-8 border-b-2 border-gray-100 pb-4">
        <div>
            <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Pesanan</h2>
            <p class="text-xs md:text-sm text-gray-600 font-medium mt-1">Pantau status pesanan dan pembayaran Anda.</p>
        </div>
    </div>

    <div class="flex flex-nowrap overflow-x-auto pb-2 md:pb-0 gap-2 mb-6 md:mb-8 no-scrollbar">
        <a href="{{ route('orders.index') }}" class="whitespace-nowrap px-4 py-2 bg-gray-900 text-white text-[10px] md:text-xs font-bold rounded-full uppercase tracking-wide">Semua</a>
        </div>

    <div class="space-y-4 md:space-y-6">
        @forelse($orders as $order)
        <div class="bg-white rounded-xl border border-gray-200 hover:border-green-500 transition-all duration-200 flex flex-col group overflow-hidden shadow-sm">
            
            <div class="p-3 md:p-4 border-b border-gray-100 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 px-2 py-1 rounded text-[10px] md:text-[11px] font-black text-gray-600 uppercase tracking-widest">
                        #{{ $order->id }}
                    </div>
                    <span class="text-[11px] md:text-sm text-gray-400 font-medium">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                <div class="w-full sm:w-auto text-left">
                    @if($order->status == 'pending')
                        <span class="inline-block bg-yellow-500 text-white text-[9px] md:text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm rounded">
                            Belum Dibayar
                        </span>
                    @elseif($order->status == 'paid')
                        <span class="inline-block bg-green-600 text-white text-[9px] md:text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm rounded">
                            Lunas / Diproses
                        </span>
                    @elseif($order->status == 'completed')
                        <span class="inline-block bg-blue-600 text-white text-[9px] md:text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm rounded">
                            Selesai
                        </span>
                    @else
                        <span class="inline-block bg-red-500 text-white text-[9px] md:text-[10px] font-black px-3 py-1.5 uppercase tracking-widest shadow-sm rounded">
                            Dibatalkan
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-4 md:p-6 space-y-4 md:space-y-5">
                @foreach($order->items as $item)
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-white border border-gray-100 rounded-lg p-1 flex-shrink-0">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-50"><i class="fas fa-image text-gray-200"></i></div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs md:text-sm font-bold text-gray-900 leading-tight truncate">{{ $item->product->name }}</h4>
                        <p class="text-[10px] md:text-xs text-gray-500 mt-1">
                            {{ $item->quantity }}x @if($item->variant) ({{ $item->variant->name }}) @endif
                        </p>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-xs md:text-sm font-black text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="w-full md:w-auto text-center md:text-left">
                    <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Bayar</p>
                    <p class="text-lg md:text-xl font-black text-primary">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <a href="https://wa.me/628xxxxxx" target="_blank" 
                       class="flex-1 md:flex-none border border-gray-300 bg-white hover:border-gray-900 text-gray-900 font-bold h-10 px-4 md:px-6 rounded-lg transition-colors duration-200 flex items-center justify-center text-[10px] md:text-xs uppercase tracking-wider">
                        Bantuan
                    </a>

                    @if($order->status == 'pending' && $order->snap_token)
                        <button onclick="pay('{{ $order->snap_token }}')" 
                            class="flex-1 md:flex-none bg-gray-900 hover:bg-green-600 text-white font-bold h-10 px-6 md:px-8 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 text-[10px] md:text-xs uppercase tracking-wider shadow-lg">
                            <span>BAYAR SEKARANG</span>
                            <i class="fas fa-chevron-right text-[9px] md:text-[10px]"></i>
                        </button>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="text-center py-16 md:py-24 bg-white border border-gray-200 rounded-xl shadow-sm px-6">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-receipt text-3xl md:text-4xl text-gray-200"></i>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900">Belum Ada Pesanan</h3>
            <p class="text-xs md:text-sm text-gray-500 mb-8 max-w-xs mx-auto">Riwayat pesanan Anda akan muncul di sini.</p>
            <a href="{{ route('home') }}" class="inline-block bg-gray-900 hover:bg-primary text-white font-bold px-8 py-3 rounded-full md:rounded-md transition-colors uppercase text-[11px] md:text-xs tracking-widest shadow-lg">
                Mulai Belanja
            </a>
        </div>
        @endforelse
    </div>
</div>

<script type="text/javascript">
    // Fungsi Sakti untuk melanjutkan pembayaran
    function pay(snapToken) {
        window.snap.pay(snapToken, {
            onSuccess: function(result){
                location.reload(); // Refresh halaman biar status jadi 'Paid'
            },
            onPending: function(result){
                location.reload(); // Refresh biar user tau masih pending
            },
            onError: function(result){
                alert("Pembayaran Gagal!");
            },
            onClose: function(){
                // Tidak melakukan apa-apa, user bisa klik bayar lagi nanti
            }
        });
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection