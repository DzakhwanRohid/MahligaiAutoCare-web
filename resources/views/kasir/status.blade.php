@extends('layouts.dashboard')

@section('title', 'Status Kendaraan')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Status Kendaraan</h1>
    </div>

    {{-- TAMBAHKAN INI: Untuk menampilkan pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @forelse ($transactions as $transaction)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        {{-- UBAH BAGIAN FORM INI --}}
                        <form action="{{ route('kasir.status.update', $transaction->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="text-center mb-3">
                                <img src="{{ asset('img/default-car.png') }}" class="rounded-circle" width="80" height="80" alt="Car">
                            </div>

                            <h5 class="card-title text-center font-weight-bold">{{ $transaction->customer->name }}</h5>
                            <p class="card-text text-center text-muted">
                                {{ $transaction->vehicle_plate }} ({{ $transaction->vehicle_brand }})
                            </p>
                            <p class="card-text text-center small">{{ $transaction->invoice }}</p>

                            <div class="mt-3">
                                <select name="status" class="form-select">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ $transaction->status == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">Tidak ada kendaraan yang sedang diproses.</p>
            </div>
        @endforelse
    </div>
@endsection