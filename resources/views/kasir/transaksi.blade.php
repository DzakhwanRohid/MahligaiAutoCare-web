@extends('layouts.dashboard')

@section('title', 'Manajemen Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Transaksi</h1>
    </div>

    {{-- Menampilkan notifikasi sukses jika ada --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Nama Pelanggan</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->invoice }}</td>
                                
                                {{-- Mengambil data dari relasi customer --}}
                                <td>{{ $transaction->customer->name }}</td>
                                
                                {{-- Mengambil data dari relasi service --}}
                                <td>{{ $transaction->service->name }}</td>
                                
                                <td>{{ $transaction->status }}</td>

                                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data transaksi masih kosong.</td>
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