<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <div class="bg-white shadow p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="font-bold text-gray-600 hover:text-green-600">← Kembali Belanja</a>
            <h1 class="text-xl font-bold text-green-600">Riwayat Pesanan</h1>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-3xl">
        @if($orders->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-400 text-xl">Belum ada riwayat pesanan.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-full">Mulai
                    Belanja</a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 p-4 flex justify-between items-center border-b">
                            <div>
                                <span class="text-xs text-gray-500">No. Order #{{ $order->id }}</span>
                                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>

                            @php
                                $statusColor = match ($order->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processed' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };

                                $statusLabel = match ($order->status) {
                                    'pending' => 'Menunggu Konfirmasi',
                                    'processed' => 'Sedang Diproses',
                                    'completed' => 'Selesai / Dikirim',
                                    'cancelled' => 'Dibatalkan',
                                    default => $order->status
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="p-4">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center mb-2 last:mb-0">
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-700 text-sm">
                                            {{ $item->product->name }}
                                            @if($item->variant)
                                                <span class="text-gray-400 text-xs">({{ $item->variant->name }})</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-400">x{{ $item->quantity }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-600">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-4 border-t flex justify-between items-center bg-gray-50">
                            <div>
                                <span class="text-sm text-gray-500 block">Total Pembayaran</span>
                                <span class="text-lg font-bold text-green-600">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            @if($order->status == 'pending' && $order->payment_url)
                                <a href="{{ $order->payment_url }}" target="_blank"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full text-sm shadow-lg transition transform hover:scale-105">
                                    Bayar Sekarang →
                                </a>
                            @elseif($order->status == 'processed')
                                <button disabled class="bg-gray-200 text-gray-500 font-bold py-2 px-6 rounded-full text-sm">
                                    Sedang Dikemas
                                </button>
                            @elseif($order->status == 'completed')
                                <a href="https://wa.me/62812345678?text=Halo%20kak,%20terima%20kasih%20paketnya%20sudah%20sampai!"
                                    target="_blank"
                                    class="bg-green-100 text-green-700 border border-green-200 font-bold py-2 px-6 rounded-full text-sm">
                                    Konfirmasi Terima
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>

</html>