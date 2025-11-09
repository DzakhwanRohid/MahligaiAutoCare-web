@extends('layouts.dashboard')

@section('title', 'Form Pendaftaran Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Form Pendaftaran Transaksi Baru</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('kasir.pendaftaran.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="customer_phone" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="vehicle_brand" class="form-label">Merk & Model Mobil</label>
                        <input type="text" class="form-control" id="vehicle_brand" name="vehicle_brand"
                            placeholder="Cth: Honda Brio" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="vehicle_plate" class="form-label">Plat Mobil</label>
                        <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate"
                            placeholder="Cth: BM 1234 ABC" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="service_id" class="form-label">Pilih Layanan</label>
                    <select class="form-select" id="service_id" name="service_id" required>
                        <option value="" disabled selected>-- Pilih Layanan --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service['id'] }}">{{ $service['name'] }} - (Rp
                                {{ number_format($service['price'], 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Daftarkan Transaksi</button>
            </form>
        </div>
    </div>
@endsection