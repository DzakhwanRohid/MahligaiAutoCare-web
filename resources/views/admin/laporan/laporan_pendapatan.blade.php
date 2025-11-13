@extends('layouts.dashboard')

@section('title', 'Laporan Pendapatan')

@section('content')

<style>
    @media print {
        /* Sembunyikan semua elemen yang tidak perlu dicetak */
        .sidebar, .top-nav, .card-filter-form, .btn, .pagination, .alert-link {
            display: none !important;
        }
        /* Pastikan area laporan mengambil seluruh layar */
        .main-content {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .container-fluid {
            padding: 0 !important;
        }
        /* Hapus border dan bayangan pada card laporan */
        .card-laporan-hasil {
            border: none !important;
            box-shadow: none !importan;
        }
        .card-laporan-hasil .card-header {
            border: none !important;
            background-color: white !important;
            padding-left: 0;
            padding-right: 0;
        }
        /* Hapus tombol cetak dari tampilan cetak */
        .btn-print {
            display: none !important;
        }
        /* Tampilkan KPI Cards saat print */
        .kpi-card {
            display: block !important;
            width: 33.33%;
            float: left;
        }
        /* Atur tabel agar rapi */
        .table-responsive {
            overflow-x: hidden;
        }
        .table {
            width: 100%;
        }
        /* Hentikan row agar tidak break page */
        .row {
            page-break-inside: avoid;
        }
    }
</style>


<div class="container-fluid">
    <div class="card card-filter-form">
        <div class="card-header">
            <h3 class="card-title">Filter Laporan Pendapatan</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('laporan.filter') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                               value="{{ $startDate ?? '' }}" required>
                    </div>
                    <div class="col-md-5">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                               value="{{ $endDate ?? '' }}" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-filter"></i> Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- AREA HASIL LAPORAN (Hanya tampil jika $laporan ada) --}}
    {{-- ====================================================== --}}
    @isset($laporan)

        <div class="row mt-4">
            <div class="col-lg-4 col-md-6 kpi-card">
                <div class="card bg-success text-white mb-3">
                    <div class="card-body">
                        <h6 class="card-title">TOTAL PENDAPATAN</h6>
                        <h3 class="card-text">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 kpi-card">
                <div class="card bg-primary text-white mb-3">
                    <div class="card-body">
                        <h6 class="card-title">TOTAL TRANSAKSI SELESAI</h6>
                        <h3 class="card-text">{{ $totalTransaksi }} Transaksi</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 kpi-card">
                <div class="card bg-info text-white mb-3">
                    <div class="card-body">
                        <h6 class="card-title">RATA-RATA / TRANSAKSI</h6>
                        <h3 class="card-text">Rp {{ number_format($rataRata, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-2 card-laporan-hasil">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    Hasil Laporan: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}
                </h3>
                <button class="btn btn-sm btn-outline-secondary btn-print" onclick="window.print()">
                    <i class="fa fa-print"></i> Cetak Laporan
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Total Transaksi</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporan as $data)
                            <tr>
                               <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}</td>
                                <td>{{ $data->total_transaksi }}</td>
                                <td>Rp {{ number_format($data->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    Tidak ada data pendapatan pada rentang tanggal ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($laporan->count() > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="2" class="text-end">Total Keseluruhan</td>
                                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

    @else
        <div class="alert alert-info mt-4">
            Silakan pilih rentang tanggal dan klik "Terapkan" untuk melihat laporan pendapatan.
        </div>
    @endisset
</div>
@endsection
