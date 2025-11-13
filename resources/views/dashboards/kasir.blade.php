@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h1 class="h3 mb-0">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="mb-0 text-muted">Anda login sebagai Kasir. Mari kita mulai bekerja.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">MOBIL DALAM ANTREAN</h5>
                            <h2 class="fw-bold">{{ $antrean_count }}</h2>
                        </div>
                        <i class="fa fa-clock fa-3x text-dark opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">MOBIL SEDANG DICUCI</h5>
                            <h2 class="fw-bold">{{ $dicuci_count }}</h2>
                        </div>
                        <i class="fa fa-shower fa-3x text-white opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Akses Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('pos.index') }}" class="text-decoration-none">
                                <div class="card shadow-hover text-center p-4 h-100 bg-primary text-white">
                                    <i class="fa fa-cash-register fa-4x mb-3"></i>
                                    <h4 class="fw-bold">BUAT TRANSAKSI BARU</h4>
                                    <p class="mb-0">Buka halaman Kasir (POS)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="{{ route('transaksi.antrean') }}" class="text-decoration-none">
                                <div class="card shadow-hover text-center p-4 h-100 bg-light text-dark">
                                    <i class="fa fa-tasks fa-4x mb-3"></i>
                                    <h4 class="fw-bold">LIHAT ANTREN REAL-TIME</h4>
                                    <p class="mb-0">Buka Kanban Board Antrean</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-hover {
        transition: box-shadow 0.2s ease-in-out;
    }
    .shadow-hover:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
</style>
@endsection
