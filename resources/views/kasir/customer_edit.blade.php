@extends('layouts.dashboard')

@section('title', 'Edit Data Pelanggan')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Pelanggan: {{ $customer->name }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- Form ini akan mengirim data ke route 'kasir.customer.update' --}}
            <form action="{{ route('kasir.customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pelanggan</labe>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ $customer->name }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ $customer->phone }}" required>
                </div>
                
                <a href="{{ route('kasir.laporan') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection