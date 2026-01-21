@extends('layouts.app')

@section('content')
<!-- Header Section - Minimalis & Tipografi Rapat -->
<div class="border-b border-gray-50 bg-white">
    <div class="container mx-auto px-4 py-12 md:py-20">
        <h1 class="text-3xl md:text-5xl font-black text-dark uppercase tracking-tighter leading-none">
            Tentang <span class="text-primary">Kami.</span>
        </h1>
        <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 mt-4">
            Mengenal Tunas Tirta Fresh lebih dekat
        </p>
    </div>
</div>

<div class="container mx-auto px-4 py-12 md:py-24">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-24 items-stretch">
        
        <!-- Kolom Visual - Foto Buah Segar Berwarna -->
        <div class="w-full lg:w-5/12">
            <div class="relative h-full min-h-[400px] bg-gray-50 overflow-hidden shadow-xl">
                <!-- Gambar Buah Segar (Full Color) -->
                <img src="https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&q=80&w=1000" 
                     alt="Premium Fruits Bali" 
                     class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-1000">
                
                <!-- Label Overlay -->
                <div class="absolute top-6 left-6 bg-primary text-white text-[9px] font-black uppercase tracking-[0.2em] px-4 py-2">
                    Fresh Supply
                </div>

                <!-- Statistik Overlay -->
                <div class="absolute bottom-0 left-0 w-full p-8 bg-gradient-to-t from-dark/60 to-transparent">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-2xl font-black text-white tracking-tighter leading-none">BALI</p>
                            <p class="text-[8px] font-black text-primary uppercase tracking-widest mt-1">Cakupan Distribusi</p>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white tracking-tighter leading-none">100%</p>
                            <p class="text-[8px] font-black text-primary uppercase tracking-widest mt-1">Jaminan Mutu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Konten Teks -->
        <div class="w-full lg:w-7/12 flex flex-col justify-center">
            <div class="space-y-12">
                <!-- Profil Perusahaan -->
                <div class="relative">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.3em] mb-4 flex items-center gap-4">
                        <span class="w-10 h-[2px] bg-primary"></span> Profil Perusahaan
                    </h3>
                    <p class="text-gray-600 text-[13px] md:text-[14px] leading-relaxed tracking-tight text-justify uppercase font-bold">
                        Tunas Tirta Fresh merupakan unit usaha yang berada di bawah naungan <strong class="text-dark">PT. Alam Tunas Tirta</strong>, perusahaan yang telah berpengalaman dalam distribusi dan suplai buah segar di Bali. Dengan dukungan sistem operasional dan jaringan distribusi yang telah terbangun, kami mampu menyediakan produk dengan kualitas yang konsisten dan pengiriman yang tepat waktu.
                    </p>
                </div>

                <!-- Kemitraan -->
                <div class="relative">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.3em] mb-4 flex items-center gap-4">
                        <span class="w-10 h-[2px] bg-primary"></span> Kemitraan & Kepercayaan
                    </h3>
                    <p class="text-gray-600 text-[13px] md:text-[14px] leading-relaxed tracking-tight text-justify uppercase font-bold">
                        Saat ini, kami telah bermitra dengan berbagai hotel, supermarket, restoran, dan villa di wilayah Bali, baik untuk kebutuhan suplai rutin maupun pemesanan khusus. Kepercayaan dari para mitra bisnis menjadi komitmen bagi kami untuk terus menjaga mutu produk dan pelayanan secara profesional.
                    </p>
                </div>

                <!-- Fitur Grid - Gaya Modern Boxy -->
                <div class="pt-10 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="space-y-2 group">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-check-double text-primary text-xs"></i>
                                <h4 class="text-[10px] font-black text-dark uppercase tracking-widest">Kualitas Terjaga</h4>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter leading-snug">Seleksi ketat untuk setiap produk buah yang kami kirimkan.</p>
                        </div>
                        <div class="space-y-2 group">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-shipping-fast text-primary text-xs"></i>
                                <h4 class="text-[10px] font-black text-dark uppercase tracking-widest">Pengiriman Rutin</h4>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter leading-snug">Distribusi tepat waktu ke seluruh titik wilayah di Bali.</p>
                        </div>
                        <div class="space-y-2 group">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-star text-primary text-xs"></i>
                                <h4 class="text-[10px] font-black text-dark uppercase tracking-widest">Profesional</h4>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter leading-snug">Layanan khusus untuk kebutuhan hotel, villa, dan restoran.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Button - Boxy Style -->
                <div class="pt-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-4 bg-dark text-white px-10 py-5 text-[10px] font-black uppercase tracking-[0.2em] hover:bg-primary transition-all duration-300 group">
                        Jelajahi Produk Kami
                        <i class="fas fa-arrow-right text-[8px] transform group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Decorative Footer Divider -->
<div class="bg-gray-50 py-16 border-t border-gray-100">
    <div class="container mx-auto px-4 text-center">
        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.4em]">Bali Fresh Fruit Supply Specialist</p>
    </div>
</div>
@endsection