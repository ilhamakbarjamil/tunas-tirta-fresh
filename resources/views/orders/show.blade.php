@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <a href="{{ route('orders.index') }}" class="mb-6 inline-flex items-center text-sm font-bold text-gray-500 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
        </a>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg relative">
            
            <div class="px-6 py-6 bg-gray-900 text-white flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-bold tracking-widest uppercase">INVOICE</h1>
                    <p class="text-xs text-gray-400 mt-1">Order ID: #{{ $order->external_id ?? $order->id }}</p>
                    <p class="text-xs text-gray-400">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                     @if($order->status == 'paid' || $order->status == 'settlement')
                        <span class="bg-green-500 text-white px-3 py-1 rounded text-xs font-black uppercase tracking-widest">LUNAS</span>
                    @elseif($order->status == 'pending')
                        <span class="bg-yellow-500 text-black px-3 py-1 rounded text-xs font-black uppercase tracking-widest">BELUM BAYAR</span>
                    @elseif($order->status == 'failed' || $order->status == 'expire')
                        <span class="bg-red-500 text-white px-3 py-1 rounded text-xs font-black uppercase tracking-widest">GAGAL</span>
                    @endif
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-b border-gray-100 pb-8">
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Penerima</h3>
                        <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alamat Pengiriman</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $order->address }}</p>
                        @if($order->note)
                            <div class="mt-3 bg-yellow-50 p-2 rounded border border-yellow-100">
                                <p class="text-xs text-yellow-800 italic"><span class="font-bold">Catatan:</span> {{ $order->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Rincian Barang</h3>
                    <table class="w-full">
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-b border-gray-50 last:border-0">
                                <td class="py-3 pr-4">
                                    <span class="font-bold text-gray-900 block text-sm">{{ $item->product->name }}</span>
                                    @if($item->product_variant_id) <span class="text-xs text-gray-400">Varian: {{ $item->variant->name ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="text-center py-3 px-2 text-sm text-gray-500">x{{ $item->quantity }}</td>
                                <td class="text-right py-3 pl-4 text-sm font-bold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 flex justify-between items-center mb-8">
                    <span class="font-bold text-gray-600 text-sm uppercase tracking-wider">Total Tagihan</span>
                    <span class="text-2xl font-black text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>

                <div class="text-right space-y-3">
                    @if($order->status == 'pending' && $order->snap_token)
                        <button id="pay-button" class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white px-8 py-4 rounded-lg shadow-lg font-bold text-sm uppercase tracking-widest transition-transform transform hover:scale-105">
                            Bayar Sekarang 💳
                        </button>
                        <p class="text-xs text-gray-400 mt-2">Klik tombol di atas untuk menyelesaikan pembayaran.</p>
                    @elseif($order->status == 'paid')
                        <a href="https://wa.me/{{ env('WHATSAPP_ADMIN') }}?text=Halo%20Admin,%20saya%20sudah%20bayar%20Order%20%23{{ $order->external_id }}%20mohon%20diproses" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold text-sm uppercase tracking-widest">
                            <i class="fab fa-whatsapp mr-2"></i> Konfirmasi ke Admin
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@if($order->status == 'pending' && $order->snap_token)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result){ window.location.reload(); },
                onPending: function(result){ window.location.reload(); },
                onError: function(result){ window.location.reload(); },
                onClose: function(){
                    // Opsional: alert('Anda menutup popup tanpa membayar');
                }
            });
        });
    </script>
@endif

@endsection