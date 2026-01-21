@extends('layouts.app')

@section('content')
<!-- Header Section - Konsisten dengan Header Keranjang -->
<div class="border-b border-gray-200 bg-white">
    <div class="container mx-auto px-4 py-8 md:py-12">
        <h1 class="text-xl md:text-3xl font-black text-dark uppercase tracking-tight">
            Tentang <span class="text-primary">Kami</span>
        </h1>
        <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-2">
            Mengenal Tunas Tirta Fresh lebih dekat
        </p>
    </div>
</div>

<div class="container mx-auto px-4 py-10 md:py-16">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-start">
        
        <!-- Gambar / Dekoratif Visual -->
        <div class="w-full lg:w-5/12">
            <div class="relative">
                <!-- Box Aksen ala desain keranjang -->
                <div class="absolute -bottom-4 -right-4 w-full h-full border border-dark hidden md:block"></div>
                <div class="relative bg-gray-50 border border-gray-100 overflow-hidden">
                    <!-- Placeholder untuk foto kantor/produk segar -->
                    <img src="https://images.unsplash.com/photo-1610348725531-843dff563e2c?auto=format&fit=crop&q=80&w=800" 
                         alt="Fresh Produce" 
                         class="w-full h-[300px] md:h-[450px] object-cover mix-blend-multiply opacity-80">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/20 to-transparent"></div>
                </div>
            </div>
            
            <!-- Statistik Singkat - Gaya Minimalis -->
            <div class="grid grid-cols-2 gap-4 mt-8 md:mt-12">
                <div class="border-l-2 border-primary pl-4 py-1">
                    <p class="text-lg font-black text-dark leading-none">Bali</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Cakupan Distribusi</p>
                </div>
                <div class="border-l-2 border-primary pl-4 py-1">
                    <p class="text-lg font-black text-dark leading-none">100%</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Jaminan Mutu</p>
                </div>
            </div>
        </div>

        <!-- Konten Teks -->
        <div class="w-full lg:w-7/12">
            <div class="space-y-8">
                <div>
                    <h3 class="text-[10px] md:text-[11px] font-black text-dark uppercase tracking-[0.2em] mb-4 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-dark"></span> Profil Perusahaan
                    </h3>
                    <p class="text-gray-600 text-[13px] leading-relaxed text-justify">
                        Tunas Tirta Fresh merupakan unit usaha yang berada di bawah naungan <strong class="text-dark">PT. Alam Tunas Tirta</strong>, perusahaan yang telah berpengalaman dalam distribusi dan suplai buah segar di Bali. Dengan dukungan sistem operasional dan jaringan distribusi yang telah terbangun, kami mampu menyediakan produk dengan kualitas yang konsisten dan pengiriman yang tepat waktu.
                    </p>
                </div>

                <div>
                    <h3 class="text-[10px] md:text-[11px] font-black text-dark uppercase tracking-[0.2em] mb-4 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-dark"></span> Kemitraan & Kepercayaan
                    </h3>
                    <p class="text-gray-600 text-[13px] leading-relaxed text-justify">
                        Saat ini, kami telah bermitra dengan berbagai hotel, supermarket, restoran, dan villa di wilayah Bali, baik untuk kebutuhan suplai rutin maupun pemesanan khusus. Kepercayaan dari para mitra bisnis menjadi komitmen bagi kami untuk terus menjaga mutu produk dan pelayanan secara profesional.
                    </p>
                </div>

                <!-- Ikon Fitur - Mengambil gaya dari Product Detail -->
                <div class="pt-8 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary text-xs mt-1"></i>
                            <div>
                                <h4 class="text-[10px] font-black text-dark uppercase tracking-wide">Kualitas Terjaga</h4>
                                <p class="text-[11px] text-gray-400 mt-1">Seleksi ketat untuk setiap buah.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-truck text-primary text-xs mt-1"></i>
                            <div>
                                <h4 class="text-[10px] font-black text-dark uppercase tracking-wide">Pengiriman Rutin</h4>
                                <p class="text-[11px] text-gray-400 mt-1">Tepat waktu ke seluruh wilayah Bali.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-handshake text-primary text-xs mt-1"></i>
                            <div>
                                <h4 class="text-[10px] font-black text-dark uppercase tracking-wide">Profesional</h4>
                                <p class="text-[11px] text-gray-400 mt-1">Layanan khusus untuk bisnis (B2B).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="pt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 bg-dark text-white px-8 py-4 text-[10px] font-bold uppercase tracking-widest hover:bg-primary transition-all group">
                        Jelajahi Produk Kami
                        <i class="fas fa-arrow-right text-[8px] transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection