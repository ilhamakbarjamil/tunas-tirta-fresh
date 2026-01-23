<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Tunas Tirta Fresh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">

    <div class="bg-white p-8 rounded-none shadow-lg max-w-md w-full border border-gray-100 text-center">
        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h2 class="text-xl font-black uppercase tracking-widest text-gray-800 mb-2">Order Dibuat!</h2>
        <p class="text-xs text-gray-500 mb-6 font-bold uppercase tracking-wide">ID Pesanan: #{{ $order->id }}</p>

        <div class="bg-gray-50 p-4 border border-gray-100 mb-8">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Total Tagihan</p>
            <p class="text-2xl font-black text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>

        <button id="pay-button" class="w-full bg-gray-900 text-white py-4 font-bold uppercase tracking-[0.2em] text-[10px] hover:bg-green-600 transition-all shadow-lg hover:shadow-xl">
            Bayar Sekarang
        </button>

        <a href="{{ route('home') }}" class="block mt-6 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-gray-900">
            Kembali ke Beranda
        </a>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        
        // Fungsi Trigger Snap
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Redirect ke halaman sukses / riwayat order
                    window.location.href = "/orders"; 
                },
                onPending: function(result){
                    // Redirect ke halaman riwayat (status pending)
                    window.location.href = "/orders";
                },
                onError: function(result){
                    alert("Pembayaran Gagal!");
                },
                onClose: function(){
                    alert('Anda belum menyelesaikan pembayaran!');
                }
            });
        });

        // Opsional: Langsung klik otomatis biar popup muncul sendiri
        setTimeout(function() {
            payButton.click();
        }, 1000);
    </script>

</body>
</html>