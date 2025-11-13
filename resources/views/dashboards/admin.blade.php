@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h1 class="h3 mb-0">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="mb-0 text-muted">Anda login sebagai Administrator. Berikut ringkasan bisnis Anda hari ini.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">PENDAPATAN HARI INI</h6>
                    <h2 class="fw-bold">Rp {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}</h2>
                    <i class="fa fa-wallet fa-2x opacity-50 position-absolute bottom-0 end-0 p-3"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">TRANSAKSI HARI INI</h6>
                    <h2 class="fw-bold">{{ $transaksi_hari_ini }}</h2>
                    <i class="fa fa-receipt fa-2x opacity-50 position-absolute bottom-0 end-0 p-3"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">PELANGGAN BARU (HARI INI)</h6>
                    <h2 class="fw-bold">{{ $pelanggan_baru_hari_ini }}</h2>
                    <i class="fa fa-user-plus fa-2x opacity-50 position-absolute bottom-0 end-0 p-3"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body">
                    <h6 class="card-title">MOBIL DALAM ANTREAN</h6>
                    <h2 class="fw-bold">{{ $antrean_count }}</h2>
                    <i class="fa fa-clock fa-2x opacity-50 position-absolute bottom-0 end-0 p-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-list-alt"></i> 5 Transaksi Terakhir</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksi_terbaru as $tx)
                                <tr>
                                    <td>{{ $tx->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $tx->service->name ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($tx->status == 'Selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($tx->status == 'Sedang Dicuci')
                                            <span class="badge bg-warning">Sedang Dicuci</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $tx->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada transaksi hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-cogs"></i> Panel Manajemen</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.layanan.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Manajemen Layanan & Harga
                            <i class="fa fa-concierge-bell"></i>
                        </a>
                        <a href="{{ route('admin.promosi.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Manajemen Diskon & Promosi
                            <i class="fa fa-tags"></i>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Manajemen Akun Karyawan
                            <i class="fa fa-users-cog"></i>
                        </a>
                        <a href="{{ route('admin.customer.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Manajemen Data Pelanggan
                            <i class="fa fa-users"></i>
                        </a>
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('pos.index') }}" class="btn btn-primary">
                            <i class="fa fa-cash-register"></i> Buka Halaman POS
                        </a>
                        <a href="{{ route('transaksi.antrean') }}" class="btn btn-outline-dark">
                            <i class="fa fa-tasks"></i> Buka Antrean Kanban
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
