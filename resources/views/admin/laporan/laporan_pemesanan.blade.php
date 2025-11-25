@extends('layouts.dashboard')

@section('title', 'Laporan Pemesanan')



@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header dengan Title dan Refresh Button -->
            <div class="header-action-container">
                <div class="title-section">
                    <h1>Laporan Pemesanan</h1>
                </div>
                <div class="action-section">
                    <a href="{{ route('laporan.pemesanan') }}" class="btn refresh-btn">
                        <i class="fa fa-refresh me-1"></i> Refresh Data
                    </a>
                </div>
            </div>

            <!-- Search Box -->
            <div class="custom-search-container">
                <form action="{{ route('laporan.pemesanan') }}" method="GET">
                    <input type="text"
                           class="custom-search-input"
                           name="search"
                           placeholder="Cari No. Transaksi, Nama Pelanggan, atau No. Polisi..."
                           value="{{ request('search') }}">
                    <button type="submit" class="custom-search-button">
                        <i class="fa fa-search me-1"></i> Cari
                    </button>
                </form>
            </div>

            <!-- Filter Section -->
            <div class="card filter-section">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan Pemesanan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.pemesanan') }}" method="GET">
                        <!-- Include search parameter in filter form -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="service_id" class="form-label">Layanan</label>
                                <select name="service_id" id="service_id" class="form-select">
                                    <option value="">Semua Layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Sedang Dicuci" {{ request('status') == 'Sedang Dicuci' ? 'selected' : '' }}>Sedang Dicuci</option>
                                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Sudah Dibayar" {{ request('status') == 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                                    <option value="Terkonfirmasi" {{ request('status') == 'Terkonfirmasi' ? 'selected' : '' }}>Terkonfirmasi</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('laporan.pemesanan') }}" class="btn btn-secondary me-2">Reset Semua</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Hasil Laporan</h3>
                    @if(request()->anyFilled(['start_date', 'end_date', 'service_id', 'status', 'search']))
                        <div class="mt-2">
                            <small class="text-muted">
                                Menampilkan hasil untuk:
                                @if(request('search')) <span class="badge bg-info">Pencarian: "{{ request('search') }}"</span> @endif
                                @if(request('start_date')) <span class="badge bg-info">Dari: {{ request('start_date') }}</span> @endif
                                @if(request('end_date')) <span class="badge bg-info">Sampai: {{ request('end_date') }}</span> @endif
                                @if(request('service_id')) <span class="badge bg-info">Layanan: {{ $services->where('id', request('service_id'))->first()->name ?? '' }}</span> @endif
                                @if(request('status')) <span class="badge bg-info">Status: {{ request('status') }}</span> @endif
                            </small>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>No. Polisi</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                    <th>Total Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $tx)
                                <tr>
                                    <td>{{ $tx->created_at->format('d M Y, H:i') }}</td>
                                    <td>{{ $tx->invoice }}</td>
                                    <td>{{ $tx->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $tx->customer->license_plate ?? 'N/A' }}</td>
                                    <td>{{ $tx->service->name ?? 'N/A' }}</td>

                                    {{-- LOGIKA STATUS --}}
                                    <td>
                                        @if($tx->status == 'Selesai' || $tx->status == 'Sudah Dibayar')
                                            <span class="badge bg-success">{{ $tx->status }}</span>
                                        @elseif($tx->status == 'Terkonfirmasi')
                                            <span class="badge bg-info text-dark">Terkonfirmasi</span>
                                        @elseif($tx->status == 'Sedang Dicuci')
                                            <span class="badge bg-warning text-dark">Sedang Dicuci</span>
                                        @elseif($tx->status == 'Ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @elseif($tx->status == 'Menunggu')
                                            <span class="badge bg-secondary">Menunggu Verifikasi</span>
                                        @else
                                            <span class="badge bg-dark">{{ $tx->status }}</span>
                                        @endif
                                    </td>
                                    {{-- ======================================= --}}

                                    <td>Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        @if(request()->anyFilled(['start_date', 'end_date', 'service_id', 'status', 'search']))
                                            Tidak ada data transaksi yang sesuai dengan filter yang dipilih.
                                        @else
                                            Belum ada data transaksi.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
