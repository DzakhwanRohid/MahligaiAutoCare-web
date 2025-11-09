@extends('layouts.dashboard')

@section('title', 'Struk Pembayaran - ' . $transaction->invoice)

@section('content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header text-center">
                    <img src="{{ asset('img/logo_project.png') }}" alt="Logo" style="height: 50px;">
                    <h5 class="mt-2 mb-0">Mahligai AutoCare</h5>
                    <p class="mb-0 small">Jl. Raya Pekanbaru No. 123</p>
                    <p class="mb-0 small">Telp: (0761) 123456</p>
                </div>
                <div class="card-body">
                    <h4 class="text-center mb-3">STRUK PEMBAYARAN</h4>
                    
                    <p><strong>No. Invoice:</strong> {{ $transaction->invoice }}</p>
                    <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    <p><strong>Kasir:</strong> {{ $transaction->user->name }}</p>
                    <p><strong>Pelanggan:</strong> {{ $transaction->customer->name }}</p>
                    
                    <hr>
                    
                    <table class="table table-sm table-borderless">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th class="text-end">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $transaction->service->name }}</td>
                                <td class="text-end">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <span class="font-weight-bold">Total Tagihan</span>
                        <span class="font-weight-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Metode Bayar</span>
                        <span>{{ $transaction->payment_method }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Jumlah Bayar</span>
                        <span>Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="font-weight-bold">Kembalian</span>
                        <span class="font-weight-bold">Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
                    </div>

                    <hr>
                    <p class="text-center small">Terima kasih atas kunjungan Anda!</p>

                    <div class="d-flex justify-content-around mt-4">
                        <a href="{{ route('kasir.pembayaran.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Transaksi Baru
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Struk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection