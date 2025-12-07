@extends('layouts.dashboard')
@section('title', 'Antrean Real-time & Manajemen Slot')
@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h4 class="mb-3 text-gray-800"><i class="fas fa-shower"></i> Area Pencucian (Slot Aktif)</h4>
    <div class="row mb-5">
        @for ($i = 1; $i <= 4; $i++)
            @php
                $tx = $dicuci->get($i);
            @endphp
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card h-100 shadow-sm border-{{ $tx ? 'danger' : 'success' }}">
                    <div class="card-header py-2 text-center bg-{{ $tx ? 'danger' : 'success' }} text-white">
                        <h5 class="m-0 font-weight-bold">SLOT {{ $i }}</h5>
                    </div>
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        @if($tx)
                            <h4 class="font-weight-bold mb-1">{{ $tx->vehicle_plate }}</h4>
                            <p class="text-muted mb-2">{{ $tx->vehicle_brand }}</p>
                            <span class="badge bg-light text-dark border mb-3">
                                <i class="fa fa-user"></i> {{ $tx->customer->name }}
                            </span>
                            {{-- Info Durasi --}}
                            @php
                                $estimasiSelesai = \Carbon\Carbon::parse($tx->booking_date)->addMinutes($tx->service->duration_minutes);
                            @endphp
                            <small class="d-block text-danger fw-bold">Estimasi Selesai: {{ $estimasiSelesai->format('H:i') }}</small>

                            <form action="{{ route('transaksi.update_status', $tx->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type*="submit" class="btn btn-success w-100 btn-sm">
                                    <i class="fa fa-check"></i> Selesai Cuci
                                </button>
                            </form>
                        @else
                            <div class="py-4">
                                <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                                <h5 class="text-success">KOSONG</h5>
                                <small class="text-muted">Siap digunakan</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-check"></i> Daftar Tunggu - Booking Online ({{ $antrean_booking->count() }})</h5>
            <small>Pelanggan yang sudah booking slot & jam. Klik "Mulai Cuci" saat mereka tiba.</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Jadwal Booking</th>
                            <th>Slot Dipesan</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Status Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($antrean_booking as $tx)
                        <tr class="{{ $tx->status == 'Terkonfirmasi' ? 'table-success' : '' }}">
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($tx->booking_date)->format('H:i') }} WIB</td>
                            <td class="fw-bold text-center">SLOT {{ $tx->slot }}</td>
                            <td>{{ $tx->customer->name }} ({{ $tx->vehicle_plate }})</td>
                            <td>{{ $tx->service->name }}</td>
                            <td>
                                {{ $tx->payment_method }}
                                @if($tx->payment_proof)
                                    <a href="{{ asset('storage/' . $tx->payment_proof) }}" target="_blank">(Lihat Bukti)</a>
                                @endif
                            </td>
                            <td>
                                @if($tx->status == 'Terkonfirmasi')
                                    <form action="{{ route('transaksi.update_status', $tx->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-play"></i> Mulai Cuci
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                    <a href="{{ route('transaksi.riwayat') }}">(Lihat di Riwayat)</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada booking online untuk hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-walking"></i> Daftar Tunggu - Walk-in ({{ $antrean_walkin->count() }})</h5>
            <small>Pelanggan dari POS yang sedang menunggu slot kosong.</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu Datang</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Aksi (Pilih Slot Kosong)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($antrean_walkin as $tx)
                        <tr>
                            <td>{{ $tx->created_at->format('H:i') }} WIB</td>
                            <td>{{ $tx->customer->name }} ({{ $tx->vehicle_plate }})</td>
                            <td>{{ $tx->service->name }}</td>
                            <td>
                                <form action="{{ route('transaksi.update_status', $tx->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <select name="target_slot" class="form-select form-select-sm" required>
                                            <option value="">Pilih Slot...</option>
                                            @for ($i = 1; $i <= 4; $i++)
                                                @php $isFilled = $dicuci->has($i); @endphp
                                                <option value="{{ $i }}" {{ $isFilled ? 'disabled' : '' }}>
                                                    Slot {{ $i }} {{ $isFilled ? '(Terisi)' : '(Kosong)' }}
                                                </option>
                                            @endfor
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-sign-in-alt"></i> Masukkan
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada antrean walk-in.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
