@extends('layouts.dashboard')

@section('title', 'Edit Layanan: ' . $service->name)

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

                    <form method="POST" action="{{ route('admin.layanan.update', $service->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="mb-3">
                            <label for="name" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name', $service->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" required>{{ old('description', $service->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price"
                                   value="{{ old('price', $service->price) }}"
                                   placeholder="Contoh: 50000" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Layanan</label>

                            @if($service->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                            @endif

                            <input class="form-control @error('image') is-invalid @enderror"
                                   type="file" id="image" name="image">
                            <div class="form-text">Kosongkan jika tidak ingin mengganti gambar. (Maks. 2MB)</div>
                        </div>


                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Update Layanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
