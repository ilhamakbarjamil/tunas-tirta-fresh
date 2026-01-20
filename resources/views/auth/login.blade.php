<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MASUK - Tunas Tirta Fresh</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                        <img src="{{ asset('images/logo.png') }}" alt="Tunas Tirta Fresh"
                            class="w-full h-full object-contain">
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
                    <input 
                        type="text"
                        name="email"
                        class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all text-sm font-medium"
                        placeholder="nama@email.com"
                        required
                    />
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
                    <input 
                        type="password"
                        name="password"
                        class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all text-sm font-medium"
                        placeholder="••••••••"
                        required
                    />
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Submit Button (Konsisten dengan tombol TAMBAH/CHECKOUT) -->
            <button 
                type="submit"
                class="w-full bg-gray-900 hover:bg-primary text-white font-black text-sm py-4 rounded-md transition-colors duration-300 uppercase tracking-[0.2em] shadow-lg shadow-gray-100"
            >
                MASUK SEKARANG
            </button>
        </form>

        <!-- Divider -->
        <div class="px-8">
            <div class="relative flex items-center justify-center">
                <div class="w-full border-t border-gray-100"></div>
                <span class="absolute bg-white px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">atau</span>
            </div>
        </div>

        <!-- Google Button -->
        <div class="p-8 pt-6">
            <a 
                href="{{ route('google.login') }}"
                class="flex items-center justify-center gap-3 w-full border border-gray-300 hover:border-primary bg-white text-gray-700 font-bold text-xs py-3.5 rounded-md transition-all uppercase tracking-wider"
            >
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-4 h-4" alt="Google">
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
        <a href="/" class="text-xs font-black text-gray-400 hover:text-primary uppercase tracking-[0.2em] transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Toko
        </a>
    </div>

</body>
</html>