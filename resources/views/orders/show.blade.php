<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->external_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .invoice-box { box-shadow: none; border: none; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="invoice-box bg-white w-full max-w-2xl p-8 rounded-lg shadow-lg">
        
        <div class="flex justify-between items-start border-b pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">TUNAS TIRTA FRESH</h1>
                <p class="text-xs text-gray-500 mt-1">Invoice Pesanan Buah Segar</p>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-bold text-gray-600">#{{ $order->external_id }}</h2>
                <div class="mt-2">
                    @if($order->status == 'paid' || $order->status == 'shipped')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-green-200">LUNAS</span>
                    @elseif($order->status == 'pending')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-yellow-200">MENUNGGU BAYAR</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-red-200">{{ $order->status }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
            <div>
                <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mb-1">Kepada:</p>
                <p class="font-bold text-gray-800">{{ $order->user->name }}</p>
                <p class="text-gray-600">{{ $order->phone ?? '-' }}</p>
                <p class="text-gray-600 mt-2">{{ $order->address }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mb-1">Tanggal Order:</p>
                <p class="font-bold text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</p>
                
                @if($order->resi)
                <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mb-1 mt-3">Nomor Resi:</p>
                <p class="font-bold text-blue-600 text-lg">{{ $order->resi }}</p>
                @endif
            </div>
        </div>

        <div class="border rounded-lg overflow-hidden mb-8">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="p-4 font-bold uppercase text-[10px] tracking-widest">Produk</th>
                        <th class="p-4 text-center font-bold uppercase text-[10px] tracking-widest">Qty</th>
                        <th class="p-4 text-right font-bold uppercase text-[10px] tracking-widest">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="p-4">
                            <p class="font-bold text-gray-800">{{ $item->product->name }}</p>
                            @if($item->product_variant_id)
                                <p class="text-xs text-gray-500">Varian: {{ $item->productVariant->name ?? '-' }}</p>
                            @endif
                        </td>
                        <td class="p-4 text-center text-gray-600">x{{ $item->quantity }}</td>
                        <td class="p-4 text-right font-bold text-gray-800">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mb-8">
            <div class="w-1/2">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-4">
                    <span class="text-lg font-black text-gray-800 uppercase">Total Bayar</span>
                    <span class="text-lg font-black text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="text-center border-t pt-8 no-print">
            <button onclick="window.print()" class="bg-black text-white px-6 py-3 rounded font-bold text-sm uppercase tracking-widest hover:bg-gray-800 transition">
                Cetak Invoice / Simpan PDF 🖨️
            </button>
            <p class="mt-4 text-xs text-gray-400">Tunas Tirta Fresh - Bali</p>
        </div>

    </div>

</body>
</html>