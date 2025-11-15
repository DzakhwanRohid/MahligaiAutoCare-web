@extends('layouts.dashboard')

@section('title', 'Tambah Layanan Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.layanan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        </div>

                        {{-- HARGA & DURASI (SIDE BY SIDE) --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}"
                                           placeholder="Contoh: 50000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- INPUT BARU --}}
                                <div class="mb-3">
                                    <label for="duration_minutes" class="form-label">Estimasi Durasi (Menit)</label>
                                    <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                           id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Layanan</label>
                            <input class="form-control @error('image') is-invalid @enderror"
                                   type="file" id="image" name="image" required>
                            <div class="form-text">Maks. 2MB (JPG, PNG, WEBP)</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Layanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
