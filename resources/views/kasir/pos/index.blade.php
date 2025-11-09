@extends('layouts.dashboard')

@section('title', 'Point of Sale - Pilih Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pilih Transaksi untuk Pembayaran</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Nama Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Total Tagihan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->invoice }}</td>
                                <td>{{ $transaction->customer->name }}</td>
                                <td>{{ $transaction->vehicle_brand }}</td>
                                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td>
                                    {{-- Arahkan ke route yang benar dengan ID transaksi --}}
                                    <a href="{{ route('kasir.pembayaran.form', $transaction->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-money-bill-wave"></i> Proses Pembayaran
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada transaksi yang siap dibayar (status "Selesai").</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection