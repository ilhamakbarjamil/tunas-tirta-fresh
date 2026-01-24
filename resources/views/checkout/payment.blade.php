@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            
            <div class="bg-indigo-600 p-6 text-white text-center">
                <h2 class="text-2xl font-bold uppercase tracking-wider">Konfirmasi Pembayaran</h2>
                <p class="mt-2 text-indigo-100 text-sm">Order ID: #{{ $order->external_id }}</p>
            </div>

            <div class="p-8">
                <div class="mb-8 border-b border-gray-200 pb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rincian Belanja</h3>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Total Barang</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-500 mt-4">
                        <span>Penerima:</span>
                        <span class="text-right">{{ $order->user->name }} <br> ({{ $order->user->email }})</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        <span class="block">Alamat Pengiriman:</span>
                        <p class="italic mt-1">{{ $order->address }}</p>
                    </div>
                </div>

                <div class="text-center">
                    <button id="pay-button" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg transform transition hover:scale-105 duration-200 uppercase tracking-widest">
                        Bayar Sekarang 💳
                    </button>
                    
                    <p class="mt-4 text-xs text-gray-400">
                        Pembayaran diproses aman oleh Midtrans.
                    </p>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        // Memicu Popup Snap Midtrans
        window.snap.pay('{{ $snapToken }}', {
            // Jika SUKSES bayar
            onSuccess: function(result){
                // Arahkan ke Halaman Riwayat Order
                window.location.href = '/my-orders'; 
            },
            // Jika PENDING (misal pilih ATM tapi belum transfer)
            onPending: function(result){
                window.location.href = '/my-orders';
            },
            // Jika ERROR/GAGAL
            onError: function(result){
                alert("Pembayaran gagal!");
                location.reload();
            },
            // Jika DITUTUP (Close Popup)
            onClose: function(){
                alert('Anda menutup popup pembayaran sebelum menyelesaikan transaksi.');
            }
        });
    });
</script>
@endsection