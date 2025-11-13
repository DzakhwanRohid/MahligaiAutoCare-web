@extends('layouts.dashboard')

@section('title', 'Monitoring Slot & Antrean')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3 text-gray-800"><i class="fa fa-shower"></i> Area Pencucian (4 Slot)</h4>
    <div class="row mb-5">
        @for ($i = 1; $i <= 4; $i++)
            @php
                // Cari transaksi yang sedang di slot ini
                $tx = $dicuci->where('slot', $i)->first();
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

                            <form action="{{ route('transaksi.update_status', $tx->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 btn-sm">
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

    <h4 class="mb-3 text-gray-800"><i class="fa fa-clock"></i> Daftar Tunggu (Belum Masuk Slot)</h4>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu Masuk</th>
                            <th>No. Polisi</th>
                            <th>Kendaraan</th>
                            <th>Layanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($antrean as $tx)
                            <tr>
                                <td>{{ $tx->created_at->format('H:i') }}</td>
                                <td class="fw-bold">{{ $tx->vehicle_plate }}</td>
                                <td>{{ $tx->vehicle_brand }}</td>
                                <td>{{ $tx->service->name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                            Masukan ke Slot
                                        </button>
                                        <ul class="dropdown-menu">
                                            @for ($i = 1; $i <= 4; $i++)
                                                @php
                                                    // Cek apakah slot ini kosong
                                                    $isFilled = $dicuci->where('slot', $i)->count() > 0;
                                                @endphp
                                                @if(!$isFilled)
                                                    <li>
                                                        <form action="{{ route('transaksi.update_status', $tx->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="target_slot" value="{{ $i }}">
                                                            <button type="submit" class="dropdown-item">
                                                                Ke Slot {{ $i }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            @endfor
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada antrean menunggu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
