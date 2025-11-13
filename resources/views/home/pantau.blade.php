@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 mb-3">Pantau Antrean & Ketersediaan</h1>
        <p class="lead text-muted">Lihat ketersediaan slot cuci secara real-time sebelum Anda datang.</p>
    </div>

    <div class="row g-4 justify-content-center">
        @for ($i = 1; $i <= 4; $i++)
            @php
                $tx = $slots[$i] ?? null;
            @endphp
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center {{ $tx ? 'bg-light' : 'bg-success text-white' }}">
                    <div class="card-body py-5">
                        <div class="mb-3">
                            <i class="fa {{ $tx ? 'fa-car-side' : 'fa-check-circle' }} fa-4x"></i>
                        </div>
                        <h3 class="fw-bold mb-1">SLOT {{ $i }}</h3>

                        @if($tx)
                            <span class="badge bg-danger fs-6 mb-3">SEDANG DIGUNAKAN</span>
                            <h5 class="text-dark">{{ $tx->vehicle_brand ?? 'Kendaraan' }}</h5>
                            <p class="text-muted mb-0">
                                {{ substr($tx->vehicle_plate, 0, 2) }} *** {{ substr($tx->vehicle_plate, -2) }}
                            </p>
                            <small class="text-danger fw-bold">Estimasi: Sedang Proses</small>
                        @else
                            <span class="badge bg-light text-success fs-6 mb-3">TERSEDIA</span>
                            <h5 class="mb-3">Siap Digunakan</h5>
                            <a href="{{ route('pemesanan.create') }}" class="btn btn-light rounded-pill px-4">
                                Booking Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="alert alert-info d-inline-block">
                <i class="fa fa-info-circle me-2"></i>
                Data diperbarui secara otomatis setiap kali ada perubahan status oleh kasir.
            </div>
        </div>
    </div>
</div>
@endsection
