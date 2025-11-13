@extends('layouts.dashboard')

@section('title', 'Manajemen Layanan & Harga')

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
                        <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Layanan Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 10%;">Gambar</th>
                                    <th>Nama Layanan</th>
                                    <th>Deskripsi</th>
                                    <th>Harga</th>
                                    <th style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($services as $service)
                                <tr>
                                    <td>
                                        @if($service->image)
                                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="img-fluid rounded" style="max-width: 100px;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->description }}</td>
                                    <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.layanan.edit', $service->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.layanan.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
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
                                    <td colspan="5" class="text-center">
                                        Belum ada data layanan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $services->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
