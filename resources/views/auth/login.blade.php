@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 md:py-12 font-inter antialiased bg-gray-50/50">

    <!-- Card Container -->
    <div class="w-full max-w-[400px] md:max-w-md bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden transition-all duration-300">
        
        <!-- Header Card with Top Accent -->
        <div class="relative px-6 py-8 md:px-10 md:pt-12 md:pb-8 text-center">
            
            <!-- Accent Line -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-20 h-1.5 bg-primary rounded-b-full shadow-sm shadow-primary/20"></div>

            <!-- Logo Container -->
            <div class="mb-6 relative inline-block group">
                <!-- Lingkaran dekoratif (soft glow) -->
                <div class="absolute inset-0 bg-primary/10 rounded-full scale-150 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative bg-white p-2.5 rounded-2xl shadow-sm border border-gray-100 transform transition-transform group-hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Tunas Tirta Fresh" 
                         class="h-12 w-12 md:h-16 md:w-16 object-contain">
                </div>
            </div>

            <!-- Title Section -->
            <div class="space-y-2">
                <h1 class="text-2xl md:text-3xl font-[900] text-gray-900 tracking-tight uppercase leading-tight">
                    Selamat <span class="text-primary">Datang</span>
                </h1>
                <!-- <div class="flex items-center justify-center gap-3">
                    <span class="h-[1px] w-6 bg-gray-200"></span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-[0.3em]">Portal Pelanggan</span>
                    <span class="h-[1px] w-6 bg-gray-200"></span>
                </div> -->
            </div>

            <!-- Subtitle -->
            <p class="mt-5 text-gray-500 text-xs md:text-sm font-medium px-2 leading-relaxed">
                Silakan masuk untuk melanjutkan pesanan Anda di <span class="text-gray-900 font-bold">Tunas Tirta Fresh</span>
            </p>

            <!-- Bottom Decorative Border -->
            <div class="mt-8 border-b border-dashed border-gray-200/80"></div>
        </div>

        <!-- Form Login -->
        <form class="px-6 pb-6 md:px-10 md:pb-8 space-y-5" action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Email / Phone -->
            <div class="space-y-2">
                <label class="block text-[10px] md:text-xs font-black text-gray-500 uppercase tracking-widest ml-1">
                    Email / Nomor Telepon
                </label>
                <div class="relative group">
                    <input type="text" name="email"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white transition-all text-sm font-medium placeholder:text-gray-400"
                        placeholder="nama@email.com" required />
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                        <i class="far fa-user text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex justify-between items-center px-1">
                    <label class="block text-[10px] md:text-xs font-black text-gray-500 uppercase tracking-widest">
                        Kata Sandi
                    </label>
                    <a href="#" class="text-[10px] font-bold text-primary hover:text-gray-900 transition-colors uppercase tracking-tighter">
                        Lupa Sandi?
                    </a>
                </div>
                <div class="relative group">
                    <input type="password" name="password"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white transition-all text-sm font-medium placeholder:text-gray-400"
                        placeholder="••••••••" required />
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                        <i class="fas fa-lock text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-gray-900 hover:bg-primary text-white font-black text-xs md:text-sm py-4 rounded-xl transition-all duration-300 uppercase tracking-[0.2em] shadow-lg shadow-gray-200 active:scale-[0.98] mt-2">
                Masuk Sekarang
            </button>
        </form>

        <!-- Divider -->
        <div class="px-6 md:px-10">
            <div class="relative flex items-center justify-center">
                <div class="w-full border-t border-gray-100"></div>
                <span class="absolute bg-white px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Atau</span>
            </div>
        </div>

        <!-- Google Login -->
        <div class="px-6 py-6 md:px-10 md:py-8">
            <a href="{{ route('google.login') }}"
                class="flex items-center justify-center gap-3 w-full border border-gray-200 hover:border-primary hover:bg-primary/5 bg-white text-gray-700 font-bold text-[11px] md:text-xs py-3.5 rounded-xl transition-all uppercase tracking-wider group">
                <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                </svg>
                Masuk dengan Google
            </a>
        </div>

        <!-- Footer Info -->
        <div class="bg-gray-50/80 border-t border-gray-100 px-6 py-5 md:px-10 text-center">
            <p class="text-[10px] md:text-[11px] font-bold text-gray-500 leading-relaxed italic">
                <i class="fas fa-info-circle text-primary mr-1"></i>
                Belum punya akun? Hubungi Admin untuk registrasi.
            </p>
        </div>
    </div>

    <!-- Back to Home Button -->
    <!-- <div class="mt-8 text-center">
        <a href="/" class="group inline-flex items-center gap-2 text-xs font-black text-gray-400 hover:text-primary uppercase tracking-[0.2em] transition-all">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> 
            Kembali ke Toko
        </a>
    </div> -->

</div>
@endsection