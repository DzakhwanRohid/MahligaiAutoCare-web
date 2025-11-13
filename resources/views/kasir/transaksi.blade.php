@extends('layouts.dashboard')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                    <div class="card-subtitle text-muted">Menampilkan semua transaksi yang telah selesai.</div>
                </div>
                <div class="card-body">

                    {{-- Kita bisa tambahkan filter tanggal di sini nanti jika perlu --}}

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>No. Polisi</th>
                                    <th>Layanan</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th style="width: 10%;">Aksi</th>
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
                                    <td>Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($tx->status == 'Sudah Dibayar')
                                            <span class="badge bg-success">Sudah Dibayar</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $tx->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pos.struk', $tx->id) }}" class="btn btn-sm btn-info" target="_blank" title="Cetak Struk">
                                            <i class="fa fa-print"></i>
                                        </a>
                                        {{-- Bisa tambahkan tombol 'Detail' jika perlu --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Belum ada riwayat transaksi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $transactions->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
