@extends('layouts.app')

@section('content')
<div class="bg-white">
    
    {{-- 01. HERO SECTION: THE MISSION --}}
    {{-- Menambahkan pt-10 agar tidak terlalu mepet dengan header dari layout --}}
    <section class="container mx-auto px-4 pt-12 pb-16 border-b-2 border-dark">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end">
            <div class="md:col-span-8">
                <span class="text-primary font-mono font-bold tracking-tighter uppercase mb-4 block">/ Company Manifesto</span>
                <h1 class="text-5xl md:text-8xl font-black leading-[0.9] tracking-tighter text-dark uppercase">
                    Freshness <br> <span class="text-gray-300">is our</span> <br> Infrastructure.
                </h1>
            </div>
            <div class="md:col-span-4 pb-2">
                <p class="text-sm font-mono text-medium leading-relaxed uppercase">
                    PT. Alam Tunas Tirta beroperasi sebagai pusat saraf distribusi buah segar di Bali, menghubungkan standar agrikultur dengan kebutuhan industri komersial.
                </p>
            </div>
        </div>
    </section>

    {{-- 02. IDENTITY SECTION: BOLD CONTRAST --}}
    <section class="container mx-auto px-4 mt-12">
        <div class="grid grid-cols-1 md:grid-cols-12 border-2 border-dark">
            <!-- Kolom Identitas (Hitam) -->
            <div class="md:col-span-5 bg-dark text-white p-12 flex flex-col justify-between min-h-[400px]">
                <div>
                    {{-- Logo menggunakan filter brightness agar putih di bg hitam --}}
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto mb-10 brightness-0 invert">
                    <h2 class="text-3xl font-bold uppercase leading-tight mb-6">
                        Menjaga Kepercayaan <br> Di Setiap Pengiriman.
                    </h2>
                </div>
                <div class="space-y-2 font-mono text-xs tracking-widest text-gray-400 uppercase">
                    <p>Registration: PT. ALAM TUNAS TIRTA</p>
                    <p>Operational Unit: TUNAS TIRTA FRESH</p>
                    <p>Region: DENPASAR, BALI - INDONESIA</p>
                </div>
            </div>

            <!-- Kolom Narasi (Putih) -->
            <div class="md:col-span-7 p-12 self-center bg-white">
                <div class="max-w-xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-primary mb-8">About the entity —</h3>
                    <div class="space-y-6 text-lg text-dark leading-relaxed font-medium">
                        <p>
                            <span class="text-primary font-bold">Tunas Tirta Fresh</span> bukan sekadar supplier. Kami adalah unit strategis yang dirancang untuk menjawab dinamika pasar B2B di Bali. 
                        </p>
                        <p>
                            Kami memahami tantangan sektor hospitality. Ketepatan waktu dan konsistensi grade produk adalah dua variabel yang tidak bisa dinegosiasikan dalam operasional kami.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 03. VALUES SECTION: THE GRID SYSTEM --}}
    <section class="container mx-auto px-4 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-2 border-dark">
            <!-- Point 01 -->
            <div class="p-10 border-b-2 md:border-b-0 md:border-r-2 border-dark group hover:bg-primary transition-colors duration-300 cursor-default">
                <span class="block font-mono text-4xl font-black mb-10 group-hover:text-white transition-colors">01/</span>
                <h4 class="text-xl font-black uppercase mb-4 group-hover:text-white text-dark transition-colors">Industrial Quality Control</h4>
                <p class="text-sm font-medium leading-relaxed group-hover:text-white text-medium transition-colors">
                    Setiap buah melewati protokol sortir manual untuk memastikan standar kematangan dan fisik yang presisi.
                </p>
            </div>
            <!-- Point 02 -->
            <div class="p-10 border-b-2 md:border-b-0 md:border-r-2 border-dark bg-gray-50 group hover:bg-dark transition-colors duration-300 cursor-default">
                <span class="block font-mono text-4xl font-black mb-10 group-hover:text-primary text-dark transition-colors">02/</span>
                <h4 class="text-xl font-black uppercase mb-4 group-hover:text-white text-dark transition-colors">B2B Strategic Partnership</h4>
                <p class="text-sm font-medium leading-relaxed group-hover:text-gray-300 text-medium transition-colors">
                    Fokus utama kami adalah mendukung rantai pasok Hotel, Restoran, dan Supermarket dengan sistem kontrak yang stabil.
                </p>
            </div>
            <!-- Point 03 -->
            <div class="p-10 group hover:bg-primary transition-colors duration-300 cursor-default">
                <span class="block font-mono text-4xl font-black mb-10 group-hover:text-white text-dark transition-colors">03/</span>
                <h4 class="text-xl font-black uppercase mb-4 group-hover:text-white text-dark transition-colors">Denpasar Logistics Hub</h4>
                <p class="text-sm font-medium leading-relaxed group-hover:text-white text-medium transition-colors">
                    Lokasi strategis di Denpasar Utara memungkinkan kami melakukan pengiriman harian dengan efisiensi waktu maksimal.
                </p>
            </div>
        </div>
    </section>

    {{-- 04. CONTACT SECTION: MINIMALIST BLOCK --}}
    <section class="container mx-auto px-4 pb-20">
        <div class="bg-gray-100 p-1 border-2 border-dark">
            <div class="bg-white border border-dark p-12 flex flex-col md:flex-row justify-between items-center gap-8 text-center md:text-left">
                <div>
                    <h2 class="text-4xl font-black uppercase italic leading-none mb-2">Ready to supply?</h2>
                    <p class="font-mono text-xs uppercase tracking-widest text-medium">Kami siap menjadi mitra logistik buah Anda.</p>
                </div>
                {{-- Tombol dengan shadow Brutalist --}}
                <a href="https://wa.me/6285701797522" 
                   target="_blank"
                   class="bg-dark text-white px-12 py-5 font-black uppercase text-sm tracking-widest hover:bg-primary transition-all duration-300 transform hover:-translate-y-1 shadow-[6px_6px_0px_0px_rgba(255,105,180,1)] active:translate-y-0 active:shadow-none">
                    Contact Us Now
                </a>
            </div>
        </div>
    </section>

</div>

<style>
    /* Styling khusus untuk mempertegas karakter halaman About tanpa merusak layout utama */
    .font-black {
        letter-spacing: -0.05em;
    }
    
    /* Menghilangkan border rounded pada elemen di halaman ini saja agar terlihat lebih tegas */
    .container * {
        border-radius: 0 !important;
    }
</style>
@endsection