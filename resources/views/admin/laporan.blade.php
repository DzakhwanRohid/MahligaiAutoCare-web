@extends('layouts.dashboard')

@section('title', 'Laporan & Analitik')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan & Analitik</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date">
                </div>
                <div class="col-md-5">
                    <label for="end_date">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Terapkan</button>
                </div>
            </div>
            <hr>
            <div class="row mt-4">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="stat-card border-left-primary">
                        <h5>Total Pendapatan</h5>
                        <p>Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="stat-card border-left-success">
                        <h5>Total Transaksi</h5>
                        <p>{{ $summary['total_transaksi'] }}</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="stat-card border-left-info">
                        <h5>Rata-rata per Transaksi</h5>
                        <p>Rp {{ number_format($summary['rata_rata_transaksi'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Laporan Pendapatan Harian</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Total Pendapatan Harian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dailyReport as $report)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($report['tanggal'])->format('d M Y') }}</td>
                                        <td>{{ $report['jumlah_transaksi'] }}</td>
                                        <td>Rp {{ number_format($report['total_pendapatan'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Laporan Kinerja Layanan</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Layanan</th>
                                    <th>Jumlah Dipesan</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($serviceReport as $report)
                                    <tr>
                                        <td>{{ $report['nama_layanan'] }}</td>
                                        <td>{{ $report['jumlah_dipesan'] }}</td>
                                        <td>Rp {{ number_format($report['total_pendapatan'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection