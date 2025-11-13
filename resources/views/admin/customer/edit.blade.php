@extends('layouts.dashboard')

@section('title', 'Edit Data Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">
                    @if($customer->user)
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> Pelanggan ini **Terdaftar** dengan email: <strong>{{ $customer->user->email }}</strong>.
                            <br>
                            Mengubah nama di sini tidak akan mengubah nama akun login mereka.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Pelanggan ini berstatus **Walk-in** (tidak memiliki akun).
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.customer.update', $customer->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name', $customer->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="license_plate" class="form-label">No. Polisi</label>
                            <input type="text" class="form-control @error('license_plate') is-invalid @enderror"
                                   id="license_plate" name="license_plate"
                                   value="{{ old('license_plate', $customer->license_plate) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">No. HP</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone"
                                   value="{{ old('phone', $customer->phone) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_type" class="form-label">Tipe Kendaraan</label>
                            <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror"
                                   id="vehicle_type" name="vehicle_type"
                                   value="{{ old('vehicle_type', $customer->vehicle_type) }}"
                                   placeholder="Contoh: Toyota Avanza (Putih)">
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Update Pelanggan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
