<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MASUK - Tunas Tirta Fresh</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10b981', // Pastikan warna hijau ini sama dengan warna primary di web Anda
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4 font-inter antialiased text-gray-900">
    
    <div class="w-full max-w-md">
        
        <!-- Header (Persis sama dengan Header Produk Pilihan) -->
        <div class="flex items-center justify-between mb-8 border-b-2 border-gray-100 pb-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase">MASUK</h2>
                <p class="text-gray-600 font-medium mt-1">Gunakan akun pelanggan Anda.</p>
            </div>
        </div>

        <!-- Card Login (Strukturnya meniru persis Card Produk) -->
        <div class="bg-white rounded-lg border border-gray-200 hover:border-primary transition-colors duration-200 flex flex-col group overflow-hidden">
            
            <form class="p-6 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email / Phone (Style input meniru Select Varian) -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">
                        Email / Nomor Telepon
                    </label>
                    <input 
                        type="text"
                        name="email"
                        class="w-full bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md px-3 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-shadow"
                        placeholder="Contoh: 08123456789"
                        required
                    />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                            Kata Sandi
                        </label>
                        <a href="#" class="text-[10px] font-black text-primary hover:text-gray-900 transition-colors uppercase tracking-widest">
                            Lupa Sandi?
                        </a>
                    </div>
                    <input 
                        type="password"
                        name="password"
                        class="w-full bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md px-3 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-shadow"
                        placeholder="••••••••"
                        required
                    />
                </div>

                <!-- Submit Button (Persis sama dengan tombol TAMBAH) -->
                <button 
                    type="submit"
                    class="w-full bg-gray-900 hover:bg-primary text-white font-bold h-12 rounded-md transition-colors duration-200 flex items-center justify-center gap-2 uppercase tracking-widest text-sm"
                >
                    <span>MASUK</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </button>
            </form>

            <!-- Divider (Diletakkan di area footer kartu) -->
            <div class="px-6 pb-2">
                <div class="relative flex items-center justify-center">
                    <div class="w-full border-t border-gray-100"></div>
                    <span class="absolute bg-white px-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest italic">Atau masuk dengan</span>
                </div>
            </div>

            <!-- Google Button (Area bawah kartu) -->
            <div class="px-6 py-6 pt-4">
                <a 
                    href="{{ route('google.login') }}"
                    class="flex items-center justify-center gap-3 w-full border border-gray-300 hover:border-gray-900 bg-white text-gray-900 font-bold h-12 rounded-md transition-colors duration-200 uppercase tracking-widest text-[11px]"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Google
                </a>
            </div>

            <!-- Footer (Persis sama dengan footer kartu produk: bg-gray-50) -->
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center leading-relaxed">
                    Belum punya akun? <br>
                    <span class="text-gray-600">Hubungi admin untuk aktivasi pelanggan baru.</span>
                </p>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="/" class="text-xs font-bold text-gray-400 hover:text-primary transition-colors uppercase tracking-[0.2em]">
                <i class="fas fa-chevron-left text-[9px] mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>