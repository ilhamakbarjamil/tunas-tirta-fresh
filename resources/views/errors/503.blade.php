<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Pemeliharaan - Kami Akan Segera Kembali</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .bg-gradient { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-gradient text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full text-center">
        <!-- Icon -->
        <div class="mb-8 inline-block p-6 rounded-full bg-blue-500/10 text-blue-400 animate-pulse">
            <i class="fa-solid fa-screwdriver-wrench text-6xl"></i>
        </div>

        <!-- Heading -->
        <h1 class="text-4xl md:text-6xl font-bold mb-4 tracking-tight">
            Sedang <span class="text-blue-400">Maintenance</span>
        </h1>
        
        <!-- Description -->
        <p class="text-gray-400 text-lg md:text-xl mb-10 leading-relaxed">
            Kami sedang melakukan peningkatan sistem untuk memberikan pengalaman belanja yang lebih baik bagi Anda. Kami akan segera kembali dalam beberapa saat.
        </p>

        <!-- Progress Bar (Opsional) -->
        <div class="w-full bg-gray-800 rounded-full h-2.5 mb-10">
            <div class="bg-blue-500 h-2.5 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.5)]" style="width: 75%"></div>
            <p class="text-xs text-gray-500 mt-2 text-right italic">Optimasi Database - 75%</p>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
            <div class="glass p-4 rounded-2xl">
                <i class="fa-solid fa-clock text-blue-400 mb-2"></i>
                <p class="text-sm font-semibold">Estimasi Selesai</p>
                <p class="text-xs text-gray-400">30 - 60 Menit</p>
            </div>
            <div class="glass p-4 rounded-2xl">
                <i class="fa-solid fa-envelope text-blue-400 mb-2"></i>
                <p class="text-sm font-semibold">Hubungi Kami</p>
                <p class="text-xs text-gray-400">support@tokoanda.com</p>
            </div>
            <div class="glass p-4 rounded-2xl">
                <i class="fa-solid fa-share-nodes text-blue-400 mb-2"></i>
                <p class="text-sm font-semibold">Ikuti Update</p>
                <p class="text-xs text-gray-400">@tokoanda_id</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Terima kasih atas kesabaran Anda.
        </div>
    </div>
</body>
</html>