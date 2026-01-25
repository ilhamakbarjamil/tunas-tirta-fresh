<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->external_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4 flex justify-center items-start md:items-center">

    <div class="bg-white w-full max-w-xl shadow-lg rounded-none p-6 md:p-10 border-t-4 border-black">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-xl font-black uppercase tracking-tighter text-dark">TUNAS TIRTA FRESH</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Official Invoice</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black uppercase bg-black text-white px-2 py-1">
                    {{ strtoupper($order->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8 text-[11px] font-medium uppercase tracking-tight text-gray-600">
            <div>
                <p class="text-gray-400 mb-1">Dipesan Oleh:</p>
                <p class="text-dark font-black">{{ $order->user->name }}</p>
                <p>{{ $order->phone }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-400 mb-1">Tanggal:</p>
                <p class="text-dark font-black">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="border-t border-b border-gray-100 py-4 mb-6">
            <table class="w-full text-[11px] uppercase font-bold text-dark">
                <thead>
                    <tr class="text-gray-400">
                        <th class="text-left pb-4">Produk</th>
                        <th class="text-center pb-4">Qty</th>
                        <th class="text-right pb-4">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-3">
                            {{ $item->product->name }}
                            @if($item->variant) <br><span class="text-[9px] text-gray-400">{{ $item->variant->name }}</span> @endif
                        </td>
                        <td class="py-3 text-center">x{{ $item->quantity }}</td>
                        <td class="py-3 text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-end mb-10">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Pembayaran</span>
            <span class="text-2xl font-black text-primary tracking-tighter">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </span>
        </div>

        <div class="text-center">
            <p class="text-[9px] font-bold text-gray-300 uppercase tracking-[0.2em] mb-4">Terima kasih telah berbelanja</p>
            <button onclick="window.print()" class="text-[10px] font-black uppercase border-b-2 border-black pb-1 hover:text-primary hover:border-primary transition-all">
                Cetak Invoice
            </button>
        </div>
    </div>

</body>
</html>