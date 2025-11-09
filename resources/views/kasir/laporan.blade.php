@extends('layouts.dashboard')

@section('title', 'Laporan Pelanggan')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pelanggan</h1>
    </div>

    {{-- Untuk menampilkan pesan sukses setelah update --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Data Pelanggan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pelanggan</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $index => $customer)
                            <tr>
                                <td>{{ $customers->firstItem() + $index }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>
                                    <a href="{{ route('kasir.customer.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    {{-- Tombol Hapus tidak kita masukkan sesuai keputusan --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data pelanggan masih kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Menampilkan link Paginasi --}}
            <div class="d-flex justify-content-end">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection