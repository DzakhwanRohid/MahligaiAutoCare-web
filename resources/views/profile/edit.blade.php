@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    
    @php
        // Ambil halaman aktif dari URL, default-nya 'info'
        $page = request()->query('page', 'info');
    @endphp

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            @if (session('status') === 'profile-updated')
                Informasi profil berhasil diperbarui.
            @elseif (session('status') === 'password-updated')
                Password berhasil diperbarui.
            @else
                {{ session('status') }}
            @endif
            <button type-"button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row gy-4">

        {{-- ================================================== --}}
        {{-- BAGIAN 1: INFORMASI PROFIL --}}
        {{-- ================================================== --}}
        @if ($page == 'info')
        <div class="col-lg-8">
            {{-- Pastikan file partial ini sudah dikonversi ke Bootstrap 5 --}}
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- ================================================== --}}
        {{-- BAGIAN 2: UPDATE PASSWORD --}}
        {{-- ================================================== --}}
        @elseif ($page == 'password')
        <div class="col-lg-8">
            {{-- Pastikan file partial ini sudah dikonversi ke Bootstrap 5 --}}
            @include('profile.partials.update-password-form')
        </div>

        {{-- ================================================== --}}
        {{-- BAGIAN 3: RIWAYAT TRANSAKSI (Hanya Role 'user') --}}
        {{-- ================================================== --}}
        @elseif ($page == 'riwayat' && Auth::user()->role == 'user')
        <div class="col-12">
            <div class="card h-100 border-0 shadow-sm">
                {{-- Header Card --}}
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fa fa-history me-2"></i>{{ __('Riwayat Transaksi & Booking Saya') }}
                    </h5>
                </div>

                {{-- Body Card --}}
                <div class="card-body p-4">
                    <p class="card-text mb-4 text-muted small">
                        <i class="fa fa-info-circle me-1"></i>
                        {{ __('Pantau status booking Anda atau lihat riwayat cucian sebelumnya secara real-time.') }}
                    </p>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4 text-secondary small text-uppercase fw-bold"><i class="fa fa-calendar-alt me-2"></i>Jadwal/Tanggal</th>
                                    <th class="py-3 px-4 text-secondary small text-uppercase fw-bold"><i class="fa fa-tag me-2"></i>Layanan</th>
                                    <th class="py-3 px-4 text-secondary small text-uppercase fw-bold"><i class="fa fa-wallet me-2"></i>Total</th>
                                    <th class="py-3 px-4 text-secondary small text-uppercase fw-bold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $tx)
                                <tr>
                                    <td class="px-4 py-3 text-muted fw-medium">
                                        {{ $tx->booking_date ? \Carbon\Carbon::parse($tx->booking_date)->format('d M Y, H:i') : $tx->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="fw-bold text-dark">{{ $tx->service->name ?? 'Layanan Dihapus' }}</span>
                                    </td>
                                    <td class="px-4 py-3 fw-bold text-success">
                                        {{-- Pastikan menggunakan total_amount sesuai database --}}
                                        Rp {{ number_format($tx->total_amount ?? $tx->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{-- Logika Status dengan Badge Modern --}}
                                        @if($tx->status == 'Terkonfirmasi')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                                <i class="fa fa-check-circle me-1"></i> Terkonfirmasi
                                            </span>
                                        @elseif($tx->status == 'Selesai' || $tx->status == 'Sudah Dibayar')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                <i class="fa fa-check-double me-1"></i> Selesai
                                            </span>
                                        @elseif($tx->status == 'Sedang Dicuci' || $tx->status == 'proses')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
                                                <i class="fa fa-soap me-1"></i> Sedang Dicuci
                                            </span>
                                        @elseif($tx->status == 'Ditolak')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                                <i class="fa fa-times-circle me-1"></i> Ditolak
                                            </span>
                                        @elseif($tx->status == 'Menunggu' || $tx->status == 'pending')
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
                                                <i class="fa fa-clock me-1"></i> Menunggu
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                                {{ $tx->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fa fa-clipboard-list fa-3x mb-3 text-gray-300"></i>
                                            <p class="mb-0 fw-medium">Belum ada riwayat transaksi.</p>
                                            <small>Silakan lakukan pemesanan layanan kami.</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================== --}}
        {{-- KODE YANG DIUBAH: BAGIAN 4: HAPUS AKUN (Untuk SEMUA Role) --}}
        {{-- ================================================== --}}
        @elseif ($page == 'hapus')
        <div class="col-lg-8">
            {{-- Pastikan file partial ini sudah dikonversi ke Bootstrap 5 --}}
            @include('profile.partials.delete-user-form')
        </div>
        
        {{-- Fallback jika halaman tidak ditemukan --}}
        @else
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                Halaman tidak ditemukan atau Anda tidak memiliki izin untuk melihat ini.
            </div>
        </div>
        @endif

    </div>
</div>
@endsection