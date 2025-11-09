@extends('layouts.dashboard')

@section('title', 'Form Pembayaran')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Form Pembayaran: {{ $transaction->invoice }}</h1>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Detail Tagihan</h6></div>
                <div class="card-body">
                    {{-- Gunakan $transaction->customer->name, bukan array --}}
                    <p><strong>Nama Pelanggan:</strong> {{ $transaction->customer->name }}</p>
                    <p><strong>Kendaraan:</strong> {{ $transaction->vehicle_brand }} ({{ $transaction->vehicle_plate }})</p>
                    <hr>
                    <h5>Layanan yang Diambil:</h5>
                    <p>{{ $transaction->service->name }}</p>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-bold">Total Tagihan:</h4>
                        <h4 class="font-weight-bold text-danger">Rp {{ number_format($transaction->total, 0, ',', '.') }}</h4>
                        <input type="hidden" id="totalTagihan" value="{{ $transaction->total }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Proses Pembayaran</h6></div>
                <div class="card-body">
                    {{-- Perbarui FORM ACTION dan tambahkan @method('PUT') --}}
                    <form action="{{ route('kasir.pembayaran.process', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method }}">{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Jumlah Bayar</label>
                            <input type="number" class="form-control" id="amount_paid" name="amount_paid" placeholder="Masukkan jumlah uang" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold">Kembalian:</h5>
                            <h5 class="font-weight-bold text-success" id="changeDisplay">Rp 0</h5>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Proses Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Sederhana untuk Menghitung Kembalian (tidak berubah) --}}
    <script>
        document.getElementById('amount_paid').addEventListener('input', function() {
            const totalTagihan = parseInt(document.getElementById('totalTagihan').value);
            const jumlahBayar = parseInt(this.value);
            const kembalian = jumlahBayar - totalTagihan;

            const changeDisplay = document.getElementById('changeDisplay');
            if (isNaN(kembalian) || kembalian < 0) {
                changeDisplay.textContent = 'Rp 0';
            } else {
                changeDisplay.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
            }
        });
    </script>
@endsection