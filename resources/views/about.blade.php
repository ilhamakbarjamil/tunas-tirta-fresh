@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen text-gray-900">
    
    {{-- Section 1: Hero Typography --}}
    {{-- Tanpa background warna, hanya teks besar yang tegas (Editorial Style) --}}
    <div class="container mx-auto px-6 pt-20 pb-12 border-b border-gray-200">
        <div class="max-w-4xl">
            <span class="block text-green-700 font-bold tracking-widest uppercase text-sm mb-4">
                — Profil Perusahaan
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight">
                Mitra Strategis <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-700 to-green-900">
                    Distribusi Buah Segar
                </span>
                di Bali.
            </h1>
        </div>
    </div>

    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
            
            {{-- Kolom Kiri: Informasi Entitas (Lebih kecil, sticky/fixed feel) --}}
            <div class="md:col-span-4">
                <div class="sticky top-10">
                    <div class="flex items-center gap-4 mb-8">
                        
                        <div class="w-20 h-20 flex-shrink-0 bg-white border border-gray-100 rounded-lg shadow-sm flex items-center justify-center p-2 overflow-hidden">
                            <img src="{{ asset('images/logo.png') }}" 
                                 alt="Logo PT Alam Tunas Tirta" 
                                 class="w-full h-full object-contain">
                        </div>

                        <div>
                            <h3 class="font-bold text-lg uppercase leading-tight text-gray-800">
                                PT. Alam <br>Tunas Tirta
                            </h3>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Unit Bisnis</p>
                            <p class="font-semibold">Tunas Tirta Fresh</p>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Berdiri Sejak</p>
                            <p class="font-semibold">2024</p>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Lokasi</p>
                            <p class="font-semibold">Denpasar Utara, Bali</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Narasi Utama --}}
            <div class="md:col-span-8 space-y-12">
                
                {{-- Paragraf Pembuka --}}
                <div class="text-xl md:text-2xl font-light leading-relaxed text-gray-800">
                    <p>
                        Kami memastikan rantai pasok buah segar tetap terjaga kualitasnya dari perkebunan hingga ke dapur komersial Anda. Kepercayaan adalah mata uang utama kami.
                    </p>
                </div>

                {{-- Detail Konten dengan Grid --}}
                <div class="grid grid-cols-1 gap-8 text-gray-600 leading-relaxed border-l-2 border-gray-200 pl-8">
                    <p>
                        <strong class="text-gray-900">Tunas Tirta Fresh</strong> merupakan unit usaha strategis di bawah naungan 
                        <strong>PT. Alam Tunas Tirta</strong>. Kami mengelola distribusi dan suplai buah segar dengan standar profesional untuk memenuhi permintaan pasar Bali yang dinamis.
                    </p>
                    <p>
                        Fokus utama kami melayani sektor B2B (Business to Business) mencakup Hotel, Supermarket, Restoran, dan Villa. Sistem kami dirancang untuk menjamin ketepatan waktu dan konsistensi kualitas produk (Quality Control).
                    </p>
                </div>

                {{-- Key Points Horizontal (Tampilan Data) --}}
                <div class="bg-gray-50 p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-check-circle text-green-700 mr-2"></i> Quality Control
                        </h4>
                        <p class="text-sm text-gray-600">Sortir ketat untuk memastikan hanya buah grade terbaik yang dikirim.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-clock text-green-700 mr-2"></i> On-Time Delivery
                        </h4>
                        <p class="text-sm text-gray-600">Armada logistik yang siap mengantar sesuai jadwal operasional klien.</p>
                    </div>
                </div>

                {{-- Footer Address Section --}}
                <div class="pt-8 mt-8 border-t border-gray-200">
                    <h3 class="font-bold text-gray-900 text-sm uppercase mb-4">Head Office</h3>
                    <p class="text-gray-600 font-mono text-sm">
                        Jl. Cargo Sari III, Ubung Kaja, Kec. Denpasar Utara<br>
                        Kota Denpasar, Bali 80116
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection