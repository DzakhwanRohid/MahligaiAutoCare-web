@extends('layouts.main')

@section('content')

<!-- HEADER SECTION - Dark Gradient -->
<section class="page-header-services">
    <div class="header-gradient"></div>

    <div class="container">
        <div class="header-content-inner">
            <h1 class="main-title">
                <span class="title-line title-line-outline">Layanan</span>
                <span class="title-line title-line-white">Mahligai</span>
                <span class="title-line title-line-green">AutoCare</span>
            </h1>

            <div class="title-separator">
                <span class="separator-line"></span>
                <span class="separator-icon">◈</span>
                <span class="separator-line"></span>
            </div>

            <p class="subtitle">
                Kualitas terbaik untuk kilau sempurna kendaraan Anda
            </p>
        </div>
    </div>

    <div class="wave-divider">
        <svg viewBox="0 0 1200 30" preserveAspectRatio="none">
            <path d="M0,0V15c150,10,300,10,450,5S750,5,900,10s300,10,450,5V0Z" fill="#fff"/>
        </svg>
    </div>
</section>

{{-- DETAIL LAYANAN --}}
<div class="services-container">
    <div class="services-header text-center mb-6">
        <div class="section-mini-label">Paket Layanan</div>
        <h2 class="services-main-title">Detail Layanan Perawatan</h2>
        <p class="services-subtitle">Berbagai pilihan layanan premium untuk memenuhi setiap kebutuhan mobil Anda</p>
    </div>

    <div class="services-grid">
        {{-- Looping Data Services dari Database --}}
        @forelse($services as $service)
            <div class="service-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                {{-- Badge Premium --}}
                <div class="service-badge">Premium</div>

                {{-- Gambar Service --}}
                <div class="service-image-container">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="service-image"
                            alt="{{ $service->name }}">
                    @else
                        <img src="{{ asset('img/default-car.png') }}" class="service-image"
                            alt="{{ $service->name }}">
                    @endif
                </div>

                <div class="service-info">
                    <h3 class="service-title">{{ $service->name }}</h3>
                    <p class="service-description">{{ Str::limit($service->description, 80) }}</p>

                    <div class="service-meta">
                        <div class="duration">
                            <i class="fas fa-clock"></i>
                            <span>{{ $service->duration_minutes ?? '60' }} menit</span>
                        </div>
                    </div>

                    <div class="service-footer">
                        <div class="service-price">
                            <span class="price-value">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('pemesanan.create') }}" class="service-button">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-services">
                <div class="empty-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h4>Belum ada layanan tersedia</h4>
                <p>Silahkan kembali lagi nanti</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
