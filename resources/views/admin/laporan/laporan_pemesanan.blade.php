@extends('layouts.dashboard')

@section('title', 'Laporan Pemesanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan Pemesanan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.pemesanan') }}" method="GET">
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
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('laporan.pemesanan') }}" class="btn btn-secondary me-2">Reset</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Hasil Laporan</h3>
                    {{-- Tambahkan tombol Export jika data ditemukan --}}
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
                                    <td>{{ $tx->transaction_code }}</td>
                                    <td>{{ $tx->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $tx->customer->license_plate ?? 'N/A' }}</td>
                                    <td>{{ $tx->service->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($tx->status == 'Selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($tx->status == 'Sedang Dicuci')
                                            <span class="badge bg-warning">Sedang Dicuci</span>
                                        @else
                                            <span class="badge bg-secondary">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Tidak ada data transaksi yang sesuai dengan filter.
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
