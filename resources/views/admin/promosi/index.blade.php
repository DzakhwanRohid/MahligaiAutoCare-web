@extends('layouts.dashboard')

@section('title', 'Manajemen Diskon & Promosi')

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
                        <a href="{{ route('admin.promosi.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Promosi Baru
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
                                    <th>Nama Promosi</th>
                                    <th>Kode</th>
                                    <th>Tipe</th>
                                    <th>Nilai</th>
                                    <th>Mulai Berlaku</th>
                                    <th>Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($promotions as $promo)
                                <tr>
                                    <td>{{ $promo->name }}</td>
                                    <td>{{ $promo->code }}</td>
                                    <td>{{ $promo->type == 'percentage' ? 'Persen (%)' : 'Nominal (Rp)' }}</td>
                                    <td>
                                        @if($promo->type == 'percentage')
                                            {{ $promo->value }}%
                                        @else
                                            Rp {{ number_format($promo->value, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($promo->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.promosi.edit', $promo->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.promosi.destroy', $promo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus promosi ini?');">
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
                                    <td colspan="8" class="text-center">
                                        Belum ada data promosi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $promotions->links('pagination::bootstrap-5') }} </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
