@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12 border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl font-light text-gray-800 mb-2">About Us</h1>
        <p class="text-gray-500 text-sm tracking-widest uppercase">Tunas Tirta Fresh</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-12 items-center">
        
        <div class="w-full md:w-1/3">
            <div class="bg-green-50 rounded-2xl p-10 text-center border border-green-100 shadow-sm">
                <i class="fas fa-leaf text-6xl text-primary mb-4"></i>
                <h2 class="font-bold text-xl text-gray-700">PT. Alam Tunas Tirta</h2>
                <p class="text-xs text-gray-500 mt-2">EST. 2024</p>
            </div>
        </div>

        <div class="w-full md:w-2/3 text-gray-600 leading-relaxed space-y-6 text-[15px]">
            <p>
                <strong class="text-primary">Tunas Tirta Fresh</strong> merupakan unit usaha yang berada di bawah naungan 
                <strong>PT. Alam Tunas Tirta</strong>, perusahaan yang telah berpengalaman dalam distribusi dan suplai buah segar di Bali. 
                Dengan dukungan sistem operasional dan jaringan distribusi yang telah terbangun, kami mampu menyediakan produk 
                dengan kualitas yang konsisten dan pengiriman yang tepat waktu.
            </p>

            <p>
                Saat ini, kami telah bermitra dengan berbagai <span class="text-gray-800 font-medium">hotel, supermarket, restoran, dan villa</span> 
                di wilayah Bali, baik untuk kebutuhan suplai rutin maupun pemesanan khusus.
            </p>

            <div class="bg-primary/5 border-l-4 border-primary p-4 italic text-gray-700">
                "Kepercayaan dari para mitra bisnis menjadi komitmen bagi kami untuk terus menjaga mutu produk dan pelayanan secara profesional."
            </div>
            
            <div class="pt-6 border-t mt-6">
                <h3 class="font-bold text-gray-800 mb-2 text-sm uppercase tracking-wide">Head Office</h3>
                <p class="flex items-start">
                    <i class="fas fa-map-marker-alt text-primary mt-1 mr-3"></i>
                    Jl. Cargo Sari III, Ubung Kaja,<br>
                    Kec. Denpasar Utara, Kota Denpasar,<br>
                    Bali 80116
                </p>
            </div>
        </div>

    </div>
</div>
@endsection