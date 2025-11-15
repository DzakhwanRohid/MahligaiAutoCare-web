@extends('layouts.dashboard')

@section('title', 'Daftar Transaksi')

@section('content')
<div class.container-fluid>

    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-inbox me-2"></i>Pesanan Online (Menunggu Verifikasi)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Tgl Booking</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Jadwal Datang</th>
                            <th>Metode Bayar</th>
                            <th class="text-center" style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($onlineBookings as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                {{ $tx->customer->name ?? 'N/A' }}
                                <small class="d-block text-muted">{{ $tx->vehicle_plate ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $tx->service->name ?? 'N/A' }}</td>
                            <td class="fw-bold">{{ $tx->booking_date ? \Carbon\Carbon::parse($tx->booking_date)->format('d M Y, H:i') : 'N/A' }}</td>
                            <td>
                                {{ $tx->payment_method }}
                                {{-- Tombol "Lihat Bukti" jika BUKAN Tunai --}}
                                @if($tx->payment_method != 'Tunai' && $tx->payment_proof)
                                    <a href="{{ asset('storage/' . $tx->payment_proof) }}" class="btn btn-sm btn-outline-dark ms-2" target="_blank" title="Lihat Bukti Bayar">
                                        <i class="fa fa-receipt"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Tombol Aksi (Terima / Tolak) --}}
                                <form action="{{ route('transaksi.approve', $tx->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Terima Booking">
                                        <i class="fa fa-check"></i> Terima
                                    </button>
                                </form>
                                <form action="{{ route('transaksi.reject', $tx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak booking ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Tolak Booking">
                                        <i class="fa fa-times"></i> Tolak
                                    </button>
                                </form>
                                <a href="{{ route('pos.struk', $tx->id) }}" class="btn btn-sm btn-info" target="_blank" title="Lihat Struk/Detail">
                                    <i class="fa fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada pesanan online yang perlu diverifikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-history me-2"></i>Riwayat Transaksi (Selesai & Walk-in)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($processedTransactions as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $tx->invoice }}</td>
                            <td>
                                {{ $tx->customer->name ?? 'N/A' }}
                                <small class="d-block text-muted">{{ $tx->vehicle_plate ?? 'N/A' }}</Ssmall>
                            </td>
                            <td>{{ $tx->service->name ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                            <td>
                                @if($tx->status == 'Selesai' || $tx->status == 'Sudah Dibayar')
                                    <span class="badge bg-success">{{ $tx->status }}</span>
                                @elseif($tx->status == 'Terkonfirmasi')
                                    <span class="badge bg-info text-dark">Terkonfirmasi</span>
                                @elseif($tx->status == 'Sedang Dicuci')
                                    <span class="badge bg-warning text-dark">Sedang Dicuci</span>
                                @elseif($tx->status == 'Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ $tx->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Aksi hanya cetak struk --}}
                                <a href="{{ route('pos.struk', $tx->id) }}" class="btn btn-sm btn-info" target="_blank" title="Cetak Struk">
                                    <i class="fa fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada riwayat transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasi untuk Tabel Riwayat --}}
            <div class="mt-3 d-flex justify-content-end">
                {{ $processedTransactions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>
@endsection
