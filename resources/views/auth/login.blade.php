@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f4f4f4] p-0 md:p-10">
    
    <!-- Main Container: Tanpa Border, Tanpa Garis Luar -->
    <div class="bg-white w-full max-w-6xl min-h-[600px] flex flex-col lg:flex-row overflow-hidden shadow-2xl">
        
        <!-- KOLOM KIRI: IMAGE (Hidden on Mobile) -->
        <div class="hidden lg:block lg:w-1/2 relative">
            <img src="https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&q=80&w=1000" 
                 class="w-full h-full object-cover contrast-110" 
                 alt="Fresh Fruit">
            
            <!-- Overlay Info - Font Rapat & Tegas -->
            <div class="absolute inset-0 bg-dark/10 flex flex-col justify-between p-12">
                <div class="flex justify-start">
                    <span class="text-white text-[10px] font-black uppercase tracking-tighter bg-primary px-3 py-1">Quality Selected</span>
                </div>

                <div class="space-y-1">
                    <h2 class="text-white text-5xl font-black leading-[0.85] uppercase tracking-tighter">
                        TUNAS TIRTA<br><span class="text-white/90 text-4xl">FRESH.</span>
                    </h2>
                    <p class="text-white text-[9px] font-bold uppercase tracking-tighter opacity-90 mt-2">Premium Fruit Distributor Bali</p>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-20 bg-white">
            
            <!-- Header -->
            <div class="mb-12">
                <div class="mb-8">
                    <!-- <img src="{{ asset('images/logo.png') }}" class="h-7 w-auto" alt="Logo"> -->
                </div>
                <h1 class="text-4xl font-black text-dark uppercase leading-none tracking-tighter mb-2">
                    Selamat <span class="text-primary"> Datang.</span>
                </h1>
                <p class="text-gray-400 text-[9px] font-bold uppercase tracking-tighter">Silakan masuk untuk melanjutkan pesanan</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-dark uppercase tracking-tighter">Email / Telepon</label>
                    <input type="text" name="email" required
                           class="w-full bg-gray-50 border-none rounded-none px-5 py-4 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-primary/30 transition-all placeholder:text-gray-300 tracking-tighter"
                           placeholder="ALAMAT EMAIL ANDA">
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between">
                        <label class="text-[10px] font-black text-dark uppercase tracking-tighter">Kata Sandi</label>
                        <a href="#" class="text-[9px] font-bold text-gray-300 uppercase tracking-tighter hover:text-primary transition-colors">Lupa?</a>
                    </div>
                    <input type="password" name="password" required
                           class="w-full bg-gray-50 border-none rounded-none px-5 py-4 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-primary/30 transition-all placeholder:text-gray-300 tracking-tighter"
                           placeholder="••••••••">
                </div>

                <div class="pt-4 space-y-3">
                    <button type="submit" 
                            class="w-full bg-dark hover:bg-primary text-white py-5 rounded-none text-[11px] font-black uppercase tracking-tight transition-all active:scale-[0.98]">
                        Masuk Sekarang
                    </button>

                    <div class="relative flex items-center justify-center py-4">
                        <div class="w-full border-t border-gray-50"></div>
                        <span class="absolute bg-white px-4 text-[9px] font-black text-gray-200 uppercase tracking-tighter">Atau</span>
                    </div>

                    <a href="{{ route('google.login') }}" 
                       class="w-full bg-gray-50 hover:bg-gray-100 rounded-none py-4 flex items-center justify-center gap-3 transition-all">
                       <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4 grayscale opacity-50" alt="Google">
                       <span class="text-[10px] font-black text-dark uppercase tracking-tighter">Masuk dengan Google</span>
                    </a>
                </div>
            </form>

            <!-- Footer Section -->
            <div class="mt-16 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                    Belum punya akun? <a href="#" class="text-dark font-black"> Login dengan Google</a>
                </p>

                <!-- Social Icons
                <div class="flex gap-6 text-gray-300">
                    <a href="#" class="hover:text-dark transition-colors"><i class="fab fa-facebook-f text-xs"></i></a>
                    <a href="#" class="hover:text-dark transition-colors"><i class="fab fa-instagram text-xs"></i></a>
                    <a href="#" class="hover:text-dark transition-colors"><i class="fab fa-whatsapp text-xs"></i></a>
                </div> -->
            </div>

        </div>
    </div>
</div>
@endsection