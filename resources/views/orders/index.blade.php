<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Tunas Tirta Fresh</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#00A859',
                        secondary: '#ED4956',
                        accent: '#0095F6',
                        dark: '#262626',
                        medium: '#8E8E8E',
                        light: '#FAFAFA',
                        border: '#DBDBDB',
                        highlight: '#F0F9F5',
                    },
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
                    },
                    fontSize: {
                        'xs': ['11px', '14px'],
                        'sm': ['13px', '16px'],
                        'base': ['14px', '18px'],
                        'lg': ['16px', '20px'],
                        'xl': ['18px', '22px'],
                    },
                    boxShadow: {
                        'card': '0 1px 3px rgba(0, 0, 0, 0.08)',
                        'strong': '0 2px 8px rgba(0, 0, 0, 0.15)',
                    },
                    borderRadius: {
                        'md': '6px',
                        'lg': '8px',
                        'xl': '12px',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .order-item {
            transition: all 0.2s ease;
        }
        
        .order-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
    </style>
</head>

<body class="bg-light text-dark text-sm">

    <!-- Header -->
    <header class="bg-white border-b border-border sticky top-0 z-40">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="text-medium hover:text-dark transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-dark font-bold text-base">Riwayat Pesanan</h1>
                        <p class="text-medium text-xs">Semua transaksi pembelian Anda</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-medium text-xs">{{ $orders->count() }} pesanan</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        <div class="container mx-auto px-4 py-6">
            <!-- Filter Tabs -->
            <div class="mb-6 overflow-x-auto">
                <div class="flex space-x-1 border-b border-border pb-1">
                    <button class="px-4 py-2 text-sm font-medium text-dark border-b-2 border-primary whitespace-nowrap">
                        Semua
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-medium hover:text-dark whitespace-nowrap">
                        Belum Bayar
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-medium hover:text-dark whitespace-nowrap">
                        Diproses
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-medium hover:text-dark whitespace-nowrap">
                        Selesai
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-medium hover:text-dark whitespace-nowrap">
                        Dibatalkan
                    </button>
                </div>
            </div>

            @if($orders->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-light rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-bag text-2xl text-medium"></i>
                    </div>
                    <h3 class="text-dark font-medium text-base mb-2">Belum ada riwayat pesanan</h3>
                    <p class="text-medium text-sm max-w-sm mx-auto mb-6">
                        Mulai belanja buah segar favorit Anda dan pesanan akan muncul di sini
                    </p>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg font-medium text-sm hover:bg-green-600 transition-colors">
                        <i class="fas fa-store"></i>
                        Mulai Belanja
                    </a>
                </div>
            @else
                <!-- Order List -->
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white border border-border rounded-xl overflow-hidden order-item">
                            <!-- Order Header -->
                            <div class="px-4 py-3 border-b border-border bg-light">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-dark font-medium text-sm">Order #{{ $order->id }}</span>
                                            <span class="text-xs text-medium">•</span>
                                            <span class="text-medium text-xs">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                    
                                    @php
                                        $statusConfig = match($order->status) {
                                            'pending' => [
                                                'bg' => 'bg-yellow-50',
                                                'text' => 'text-yellow-700',
                                                'border' => 'border-yellow-200',
                                                'label' => 'Menunggu Pembayaran',
                                                'icon' => 'fa-clock'
                                            ],
                                            'processed' => [
                                                'bg' => 'bg-blue-50',
                                                'text' => 'text-blue-700',
                                                'border' => 'border-blue-200',
                                                'label' => 'Sedang Diproses',
                                                'icon' => 'fa-cog'
                                            ],
                                            'completed' => [
                                                'bg' => 'bg-green-50',
                                                'text' => 'text-green-700',
                                                'border' => 'border-green-200',
                                                'label' => 'Selesai',
                                                'icon' => 'fa-check-circle'
                                            ],
                                            'cancelled' => [
                                                'bg' => 'bg-red-50',
                                                'text' => 'text-red-700',
                                                'border' => 'border-red-200',
                                                'label' => 'Dibatalkan',
                                                'icon' => 'fa-times-circle'
                                            ],
                                            default => [
                                                'bg' => 'bg-gray-50',
                                                'text' => 'text-gray-700',
                                                'border' => 'border-gray-200',
                                                'label' => $order->status,
                                                'icon' => 'fa-info-circle'
                                            ]
                                        };
                                    @endphp
                                    
                                    <div class="flex items-center gap-2">
                                        <div class="{{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }} px-3 py-1 rounded-lg flex items-center gap-1.5">
                                            <i class="fas {{ $statusConfig['icon'] }} text-xs"></i>
                                            <span class="text-xs font-semibold">{{ $statusConfig['label'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-4">
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-12 h-12 bg-light rounded-md flex-shrink-0 overflow-hidden border">
                                                        @if($item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                                 alt="{{ $item->product->name }}"
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-medium">
                                                                <i class="fas fa-image text-sm"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-dark font-medium text-sm truncate">
                                                            {{ $item->product->name }}
                                                        </h4>
                                                        @if($item->variant)
                                                            <p class="text-medium text-xs mt-0.5">
                                                                Varian: {{ $item->variant->name }}
                                                            </p>
                                                        @endif
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span class="text-primary font-semibold text-sm">
                                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                                            </span>
                                                            <span class="text-medium text-xs">× {{ $item->quantity }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-dark font-semibold text-sm">
                                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Footer -->
                            <div class="px-4 py-3 border-t border-border bg-light">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-medium text-xs">Total Pembayaran</p>
                                        <p class="text-dark font-bold text-base">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        @if($order->status == 'pending' && $order->payment_url)
                                            <a href="{{ $order->payment_url }}" 
                                               target="_blank"
                                               class="bg-accent hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors flex items-center gap-2">
                                                <i class="fas fa-credit-card text-xs"></i>
                                                Bayar Sekarang
                                            </a>
                                        @elseif($order->status == 'processed')
                                            <button class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium text-sm flex items-center gap-2">
                                                <i class="fas fa-box text-xs"></i>
                                                Sedang Dikemas
                                            </button>
                                        @elseif($order->status == 'completed')
                                            <a href="https://wa.me/62812345678?text=Halo%20kak,%20pesanan%20{{ $order->id }}%20sudah%20sampai.%20Terima%20kasih!"
                                               target="_blank"
                                               class="bg-green-50 text-green-700 border border-green-200 px-4 py-2 rounded-lg font-medium text-sm hover:bg-green-100 transition-colors flex items-center gap-2">
                                                <i class="fab fa-whatsapp text-xs"></i>
                                                Konfirmasi
                                            </a>
                                        @endif
                                        
                                        <button onclick="toggleOrderDetails({{ $order->id }})"
                                                class="text-medium hover:text-dark px-3 py-2 rounded-lg text-sm transition-colors">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Details (Optional) -->
                            <div id="order-details-{{ $order->id }}" class="hidden px-4 py-3 border-t border-border bg-white">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-medium">Metode Pembayaran</span>
                                        <span class="text-dark font-medium">Transfer Bank</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-medium">Estimasi Pengiriman</span>
                                        <span class="text-dark font-medium">1-2 hari kerja</span>
                                    </div>
                                    @if($order->tracking_number)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-medium">No. Resi</span>
                                            <span class="text-dark font-medium">{{ $order->tracking_number }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination (if using pagination) -->
                @if(method_exists($orders, 'hasPages') && $orders->hasPages())
                    <div class="mt-8 flex justify-center">
                        <div class="flex items-center gap-1">
                            @if($orders->onFirstPage())
                                <span class="px-3 py-1.5 text-medium text-sm rounded-md border border-border bg-gray-50">← Sebelumnya</span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 text-dark text-sm rounded-md border border-border hover:border-primary hover:text-primary transition-colors">← Sebelumnya</a>
                            @endif
                            
                            @foreach(range(1, min(5, $orders->lastPage())) as $i)
                                @if($i == $orders->currentPage())
                                    <span class="px-3 py-1.5 bg-primary text-white text-sm rounded-md">{{ $i }}</span>
                                @else
                                    <a href="{{ $orders->url($i) }}" class="px-3 py-1.5 text-dark text-sm rounded-md border border-border hover:border-primary hover:text-primary transition-colors">{{ $i }}</a>
                                @endif
                            @endforeach
                            
                            @if($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 text-dark text-sm rounded-md border border-border hover:border-primary hover:text-primary transition-colors">Berikutnya →</a>
                            @else
                                <span class="px-3 py-1.5 text-medium text-sm rounded-md border border-border bg-gray-50">Berikutnya →</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </main>

    <!-- JavaScript for Toggle Details -->
    <script>
        function toggleOrderDetails(orderId) {
            const detailsElement = document.getElementById('order-details-' + orderId);
            detailsElement.classList.toggle('hidden');
        }
        
        // Add Font Awesome icons
        if (!document.querySelector('#fa-icons')) {
            const faLink = document.createElement('link');
            faLink.id = 'fa-icons';
            faLink.rel = 'stylesheet';
            faLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            document.head.appendChild(faLink);
        }
        
        // Filter tabs functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterTabs = document.querySelectorAll('button[class*="px-4 py-2"]');
            
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active state from all tabs
                    filterTabs.forEach(t => {
                        t.classList.remove('text-dark', 'border-b-2', 'border-primary');
                        t.classList.add('text-medium');
                    });
                    
                    // Add active state to clicked tab
                    this.classList.remove('text-medium');
                    this.classList.add('text-dark', 'border-b-2', 'border-primary');
                    
                    // In a real app, you would filter orders here
                    // This is just for visual demonstration
                });
            });
        });
    </script>

</body>
</html>