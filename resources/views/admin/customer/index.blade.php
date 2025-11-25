@extends('layouts.dashboard')

@section('title', 'Data Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Pelanggan (Manual)
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif


                    <div class="custom-search-container">
                            <form method="GET" action="{{ route('admin.customer.index') }}">
                                <input type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Cari nama, HP, atau plat..."
                                    class="custom-search-input">
                                <button type="submit" class="custom-search-button">
                                    Cari
                                </button>
                            </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <th>No. Polisi</th>
                                    <th>Tipe Kendaraan</th>
                                    <th>No. HP</th>
                                    <th>Status Akun</th>
                                    <th style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->user->name ?? $customer->name }}</td>
                                    <td>{{ $customer->license_plate }}</td>
                                    <td>{{ $customer->vehicle_type }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>
                                        @if($customer->user)
                                            <span class="badge bg-success">Terdaftar ({{ $customer->user->email }})</span>
                                        @else
                                            <span class="badge bg-secondary">Walk-in</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.customer.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini? Ini akan mempengaruhi riwayat transaksi.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        Belum ada data pelanggan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $customers->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
