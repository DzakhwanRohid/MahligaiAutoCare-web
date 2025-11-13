@extends('layouts.dashboard')

@section('title', 'Struk Pembayaran')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="text-center mb-3">STRUK PEMBAYARAN</h4>
                        {{-- Nama Bisnis dari Settings --}}
                        <h5 class="text-center mb-4">{{ $appSettings['business_name'] ?? 'Mahligai AutoCare' }}</h5>

                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="text-muted">No. Invoice:</small><br>
                                <strong>{{ $transaction->invoice }}</strong>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">Tanggal:</small><br>
                                <strong>{{ $transaction->created_at->format('d M Y, H:i') }}</strong>
                            </div>
                        </div>

                        <div class="border-top border-bottom py-2 mb-3">
                            <p class="mb-1"><strong>Kasir:</strong> {{ $transaction->user->name ?? 'System' }}</p>
                            <p class="mb-1"><strong>Pelanggan:</strong> {{ $transaction->customer->name }}</p>
                            <p class="mb-1"><strong>Kendaraan:</strong> {{ $transaction->vehicle_brand }} ({{ $transaction->vehicle_plate }})</p>

                          {{-- === BAGIAN POSISI MOBIL (UPDATED) === --}}
                            <p class="mb-0"><strong>Status:</strong>
                                @if($transaction->status == 'Selesai' || $transaction->status == 'Sudah Dibayar')
                                    <span class="badge bg-success" style="font-size: 1rem;">SELESAI</span>
                                @elseif($transaction->slot)
                                    <span class="badge bg-primary" style="font-size: 1rem;">SEDANG DICUCI (SLOT {{ $transaction->slot }})</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size: 1rem;">ANTREAN (MENUNGGU)</span>
                                @endif
                            </p>
                            {{-- ===================================== --}}
                        </div>

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
                                    <td class="text-end">Rp {{ number_format($transaction->base_price, 0, ',', '.') }}</td>
                                </tr>

                                @if($transaction->discount > 0)
                                <tr>
                                    <td class="text-danger">Diskon</td>
                                    <td class="text-end text-danger">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                                </tr>
                                @endif

                                <tr class="border-top">
                                    <th class="h5">Total Tagihan</th>
                                    <th class="text-end h5">Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                                </tr>

                                <tr>
                                    <td>Metode Bayar</td>
                                    <td class="text-end">{{ $transaction->payment_method }}</td>
                                </tr>
                                <tr>
                                    <td>Tunai / Bayar</td>
                                    <td class="text-end">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Kembali</td>
                                    <td class="text-end">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <hr>
                        <p class="text-center small mb-0">Terima kasih atas kunjungan Anda!</p>
                        {{-- Alamat Bisnis dari Settings --}}
                        <p class="text-center small text-muted">{{ $appSettings['business_address'] ?? '' }}</p>

                        {{-- Tombol Aksi (Disembunyikan saat Print) --}}
                        <div class="d-flex justify-content-around mt-4 d-print-none">
                            <a href="{{ route('pos.index') }}" class="btn btn-secondary">
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
    </div>

    {{-- Style Khusus Print agar Tampilan Bersih --}}
    <style>
        @media print {
            .sidebar, .top-nav, .d-print-none {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            body {
                background-color: white !important;
            }
            .container-fluid {
                padding: 0 !important;
            }
        }
    </style>
@endsection
