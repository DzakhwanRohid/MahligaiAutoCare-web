@extends('layouts.main')

@section('content')

{{-- HEADER LAYANAN - Tanpa BG --}}
<div class="page-header text-center">
    <div class="container">
        <h1 class="display-4">Layanan Kami</h1>
        {{-- Hapus class text-white-50 --}}
        <p class="lead">Kualitas terbaik untuk kilau sempurna kendaraan Anda.</p>
    </div>
</div>

{{-- DETAIL LAYANAN --}}
<div class="container home-section">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="section-title">Detail Layanan Perawatan</h2>
        <p class="text-muted">Kami menawarkan berbagai layanan untuk memenuhi setiap kebutuhan mobil Anda.</p>
    </div>

    <div class="row g-4">
        {{-- Card 1: Premium Car Wash --}}
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card service-card h-100">
                <img src="img/premium.jpg" class="card-img-top" alt="Cuci Mobil Premium">
                <div class="card-body">
                    <h5 class="card-title">Premium Car Wash</h5>
                    <p class="card-text">Pencucian menyeluruh menggunakan shampoo pH netral, aman untuk semua jenis cat, membersihkan hingga ke sela-sela terkecil.</p>
                </div>
            </div>
        </div>

        {{-- Card 2: Detailing Interior --}}
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="card service-card h-100">
                <img src="img/interior.webp" class="card-img-top" alt="Detailing Interior">
                <div class="card-body">
                    <h5 class="card-title">Interior Detailing</h5>
                    <p class="card-text">Membersihkan kabin secara detail, mulai dari jok, dashboard, karpet, hingga plafon, mengembalikan suasana baru di dalam mobil.</p>
                </div>
            </div>
        </div>

        {{-- Card 3: Nano Coating --}}
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card service-card h-100">
                <img src="img/nano.jpg" class="card-img-top" alt="Nano Coating">
                <div class="card-body">
                    <h5 class="card-title">Nano Ceramic Coating</h5>
                    <p class="card-text">Memberikan lapisan pelindung super hidrofobik yang melindungi cat dari goresan halus, sinar UV, dan kotoran.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
