@extends('layouts.main')

@section('content')

{{-- HEADER PEMESANAN (Menggunakan gaya clean header yang baru) --}}
<div class="page-header text-center">
    <div class="container">
        <h1 class="display-4">Pesan Layanan Sekarang</h1>
        {{-- class lead sudah memiliki warna kontras di CSS yang baru --}}
        <p class="lead">Pesan antrian Anda dengan mudah dan nikmati layanan tanpa menunggu lama.</p>
    </div>
</div>

{{-- FORMULIR PEMESANAN --}}
<div class="container home-section">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="p-4 p-md-5 rounded-3 shadow-lg" data-aos="fade-up" style="border: 1px solid var(--primary-green, #198754);">
                <h2 class="section-title text-center mb-4" style="color: var(--primary-green);">Formulir Pemesanan Layanan</h2>

                {{-- Form action disesuaikan dengan route yang sudah didefinisikan di HomeController --}}
                <form action="{{ route('pemesanan.store') }}" method="POST">
                    @csrf

                    {{-- Data Diri Pelanggan --}}
                    <h5 class="mt-4 mb-3 fw-bold" style="color: #343a40;"><i class="fas fa-user-circle me-2"></i>Data Pelanggan</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" placeholder="Nama Lengkap" required>
                                <label for="nama_pelanggan">Nama Lengkap</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="telepon" name="telepon" placeholder="Nomor Telepon/WhatsApp" required>
                                <label for="telepon">Nomor Telepon/WhatsApp</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email (Opsional)">
                                <label for="email">Email (Opsional)</label>
                            </div>
                        </div>
                    </div>

                    {{-- Data Kendaraan --}}
                    <h5 class="mt-4 mb-3 fw-bold" style="color: #343a40;"><i class="fas fa-car me-2"></i>Detail Kendaraan</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="merek" name="merek" placeholder="Merek Kendaraan (Ex: Toyota)">
                                <label for="merek">Merek Kendaraan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" placeholder="Plat Nomor (Ex: BM 1234 XY)">
                                <label for="plat_nomor">Plat Nomor</label>
                            </div>
                        </div>
                    </div>

                    {{-- Pilihan Layanan dan Waktu --}}
                    <h5 class="mt-4 mb-3 fw-bold" style="color: #343a40;"><i class="fas fa-calendar-alt me-2"></i>Jadwal dan Layanan</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="layanan_id" name="layanan_id" required>
                                    <option value="" disabled selected>Pilih Layanan</option>
                                    {{-- Data Layanan Statis (Ganti dengan Blade Loop jika mengambil dari DB) --}}
                                    <option value="1">Premium Car Wash</option>
                                    <option value="2">Interior Detailing</option>
                                    <option value="3">Nano Ceramic Coating</option>
                                    {{-- ... Layanan lainnya --}}
                                </select>
                                <label for="layanan_id">Pilih Layanan Utama</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="tanggal_pesan" name="tanggal_pesan" required>
                                <label for="tanggal_pesan">Tanggal Pemesanan</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="catatan" name="catatan" placeholder="Catatan Tambahan" style="height: 100px"></textarea>
                                <label for="catatan">Catatan Tambahan (Opsional)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center pt-3">
                        <button class="btn btn-cta rounded-pill text-white py-3 px-5 fs-5" type="submit">Konfirmasi Pemesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
