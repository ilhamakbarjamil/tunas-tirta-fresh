<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MASUK - Tunas Tirta Fresh</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome untuk konsistensi icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#EC4899', // Sesuaikan dengan warna hijau emerald/primary Anda
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 font-inter antialiased">

    <div class="w-full max-w-md bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">

        <!-- Header (Konsisten dengan Header Riwayat & Produk) -->
        <div class="px-8 pt-10 pb-6 border-b-2 border-gray-100 text-center">
            <div class="w-10 h-10">
                <img src="{{ asset('images/logo.png') }}" alt="Tunas Tirta Fresh" class="w-full h-full object-contain">
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase">
                MASUK
            </h1>
            <p class="text-gray-500 font-medium mt-1 text-sm">
                Akses akun pelanggan Tunas Tirta Fresh
            </p>
        </div>

        <!-- Form -->
        <form class="p-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Email / Phone -->
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2 tracking-widest">
                    Email / Nomor Telepon
                </label>
                <div class="relative">
                    <input type="text" name="email"
                        class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all text-sm font-medium"
                        placeholder="nama@email.com" required />
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                        <i class="far fa-user"></i>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                        Kata Sandi
                    </label>
                    <a href="#" class="text-xs font-bold text-primary hover:text-gray-900 transition-colors">
                        LUPA?
                    </a>
                </div>
                <div class="relative">
                    <input type="password" name="password"
                        class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all text-sm font-medium"
                        placeholder="••••••••" required />
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Submit Button (Konsisten dengan tombol TAMBAH/CHECKOUT) -->
            <button type="submit"
                class="w-full bg-gray-900 hover:bg-primary text-white font-black text-sm py-4 rounded-md transition-colors duration-300 uppercase tracking-[0.2em] shadow-lg shadow-gray-100">
                MASUK SEKARANG
            </button>
        </form>

        <!-- Divider -->
        <div class="px-8">
            <div class="relative flex items-center justify-center">
                <div class="w-full border-t border-gray-100"></div>
                <span
                    class="absolute bg-white px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">atau</span>
            </div>
        </div>

        <!-- Google Button -->
        <div class="p-8 pt-6">
            <a href="{{ route('google.login') }}"
                class="flex items-center justify-center gap-3 w-full border border-gray-300 hover:border-primary bg-white text-gray-700 font-bold text-xs py-3.5 rounded-md transition-all uppercase tracking-wider">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        fill="#FBBC05" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335" />
                </svg>
                Masuk dengan Google
            </a>
        </div>

        <!-- Footer Info -->
        <div class="bg-gray-50 border-t border-gray-100 px-8 py-5 text-center">
            <p class="text-[11px] font-bold text-gray-500 leading-relaxed italic">
                <i class="fas fa-info-circle text-primary mr-1"></i>
                Belum punya akun? Silakan hubungi admin untuk pendaftaran pelanggan baru.
            </p>
        </div>
    </div>

    <!-- Back to Home Link -->
    <div class="fixed bottom-8 text-center w-full">
        <a href="/"
            class="text-xs font-black text-gray-400 hover:text-primary uppercase tracking-[0.2em] transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Toko
        </a>
    </div>

</body>

</html>