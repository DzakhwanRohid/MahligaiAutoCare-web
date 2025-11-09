@extends('layouts.dashboard')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Transaksi</h1>
    </div>

    {{-- Menampilkan notifikasi sukses jika ada --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Seluruh Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Invoice</th>
                            <th>Nama Pelanggan</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $index => $transaction)
                            <tr>
                                {{-- Menggunakan nomor urut dari paginasi --}}
                                <td>{{ $transactions->firstItem() + $index }}</td>
                                <td>{{ $transaction->invoice }}</td>
                                
                                {{-- Mengambil data dari relasi customer --}}
                                <td>{{ $transaction->customer->name }}</td>
                                
                                {{-- Mengambil data dari relasi service --}}
                                <td>{{ $transaction->service->name }}</td>
                                <td>
                                    @if ($transaction->status == 'Selesai' || $transaction->status == 'Dibayar')
                                        <span class="badge bg-success">{{ $transaction->status }}</span>
                                    @elseif ($transaction->status == 'Dalam Proses')
                                        <span class="badge bg-warning text-dark">{{ $transaction->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $transaction->status }}</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data transaksi masih kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Menampilkan link Paginasi --}}
            <div class="d-flex justify-content-end">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection