@extends('layouts.main')

@section('content')

{{-- HEADER LAYANAN --}}
<div class="page-header text-center">
    <div class="container">
        <h1 class="display-4">Layanan Kami</h1>
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
        {{-- Looping Data Services dari Database --}}
        @forelse($services as $service)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card service-card h-100 shadow-sm border-0">

                    {{-- Logika Gambar: Cek apakah ada gambar di database --}}
                    <div style="height: 200px; overflow: hidden;">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top w-100 h-100"
                                alt="{{ $service->name }}" style="object-fit: cover;">
                        @else
                            {{-- Gambar Placeholder jika tidak ada gambar --}}
                            <img src="{{ asset('img/default-car.png') }}" class="card-img-top w-100 h-100"
                                alt="{{ $service->name }}" style="object-fit: cover;">
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $service->name }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ $service->description }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-primary fw-bold fs-5">
                                Rp {{ number_format($service->price, 0, ',', '.') }}
                            </span>
                            {{-- Link ke halaman booking (bisa diarahkan ke form pemesanan) --}}
                            <a href="{{ route('pemesanan.create') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-info d-inline-block">
                    <i class="fa fa-info-circle me-2"></i> Belum ada layanan yang tersedia saat ini.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
