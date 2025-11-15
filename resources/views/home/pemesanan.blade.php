@extends('layouts.main')

{{-- Menambahkan CSS khusus di <head> --}}
@push('styles')
    <link href="{{ asset('css/pemesanan.css') }}" rel="stylesheet">
@endpush

@section('content')

{{-- HEADER PEMESANAN --}}
<div class="page-header text-center mb-5">
    <div class="container">
        <h1 class="display-4">Booking Layanan</h1>
        <p class="lead">Jadwalkan perawatan kendaraan Anda tanpa antre.</p>
    </div>
</div>

{{-- FORMULIR PEMESANAN --}}
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form utama --}}
                    {{--
                      PENTING: Kita tambahkan data- attribute di sini
                      agar bisa dibaca oleh file pemesanan.js
                    --}}
                    <form action="{{ route('pemesanan.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="bookingForm"
                          data-check-promo-url="{{ route('check.promo') }}"
                          data-check-slots-url="{{ route('check.slots') }}"
                          data-csrf-token="{{ csrf_token() }}">
                        @csrf

                        {{-- BAGIAN 1: DATA DIRI --}}
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="fa fa-user me-2"></i>Data Diri & Kendaraan</h4>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. WhatsApp</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}" required placeholder="0812...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Polisi</label>
                                <input type="text" name="license_plate" id="license_plate" class="form-control text-uppercase" value="{{ old('license_plate', $customer->license_plate ?? '') }}" placeholder="BM 1234 XX" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jenis Kendaraan</label>
                                <input type="text" name="vehicle_type" id="vehicle_type" class="form-control" value="{{ old('vehicle_type', $customer->vehicle_type ?? '') }}" placeholder="Cth: Honda Jazz Merah" required>
                            </div>
                        </div>

                        {{-- BAGIAN 2: LAYANAN & JADWAL --}}
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="fa fa-calendar-alt me-2"></i>Layanan & Jadwal</h4>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Layanan</label>
                            <select name="service_id" id="service_id" class="form-select form-select-lg" required>
                                <option value="" data-price="0" data-name="Belum Dipilih">-- Pilih Layanan --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-name="{{ $service->name }}">
                                        {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Punya Kode Promo?</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-success"><i class="fas fa-ticket-alt"></i></span>
                                <input type="text" name="promotion_code" id="promo_code_input" class="form-control" placeholder="Masukkan kode (Opsional)" style="text-transform: uppercase;">
                                <button class="btn btn-outline-success" type="button" id="apply_promo_btn">Terapkan</button>
                            </div>
                            <div id="promo_message" class="small mt-1"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Tanggal Kedatangan</label>
                            <input type="date" id="date_picker" class="form-control" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Jam (Slot Tersedia)</label>
                            <div id="slots_container" class="d-flex flex-wrap gap-2">
                                <div class="text-muted fst-italic">Silakan pilih tanggal terlebih dahulu...</div>
                            </div>
                            <input type="hidden" name="booking_date" id="final_booking_date" required>
                        </div>

                        {{-- BAGIAN 3: PEMBAYARAN --}}
                        <h4 class="mb-4 text-primary border-bottom pb-2"><i class="fa fa-wallet me-2"></i>Pembayaran</h4>

                        {{-- RINCIAN BIAYA (Dinamis) --}}
                        <div class="mb-3 p-3 bg-light rounded border border-success" id="rincian_box">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Harga Layanan</span>
                                <span id="rincian_harga">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between" id="rincian_diskon_row" style="display: none !important;">
                                <span class="text-danger">Diskon</span>
                                <span id="rincian_diskon" class="text-danger">- Rp 0</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold h5">
                                <span>Total Bayar</span>
                                <span id="rincian_total" class="text-success">Rp 0</span>
                            </div>
                            {{-- HIDDEN INPUTS UNTUK DIKIRIM KE CONTROLLER --}}
                            <input type="hidden" name="final_total_price" id="final_total_price" value="0">
                            <input type="hidden" name="final_discount_amount" id="final_discount_amount" value="0">
                            <input type="hidden" name="final_base_price" id="final_base_price" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_tunai" value="Tunai" checked>
                                    <label class="btn btn-outline-secondary w-100 h-100 py-3 text-center shadow-sm" for="pay_tunai"><i class="fa fa-money-bill-wave d-block mb-2 fa-2x text-success"></i> Tunai</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_transfer" value="Transfer">
                                    <label class="btn btn-outline-secondary w-100 h-100 py-3 text-center shadow-sm" for="pay_transfer"><i class="fa fa-university d-block mb-2 fa-2x text-primary"></i> Transfer</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_qris" value="QRIS">
                                    <label class="btn btn-outline-secondary w-100 h-100 py-3 text-center shadow-sm" for="pay_qris"><i class="fa fa-qrcode d-block mb-2 fa-2x text-danger"></i> QRIS</label>
                                </div>
                            </div>
                        </div>

                      {{-- Boks Transfer Bank (Bukti Bayar) --}}
<div id="payment_info_transfer" class="d-none p-3 bg-light rounded border border-success mb-4">
    <div class="alert alert-info mb-3">
        <strong>Info Pembayaran Transfer:</strong><br>
        Silakan transfer sebesar <strong id="display_price_transfer" class="text-dark fs-5">Rp 0</strong> ke:<br>
        <ul class="mb-0 mt-2 ps-3">
            <li><strong>BCA:</strong> 123-456-7890 (Mahligai Auto)</li>
        </ul>
    </div>
    <label class="form-label fw-bold">Upload Bukti Transfer <span class="text-danger">*</span></label>
    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*">
</div>

{{-- Boks QRIS (Hanya Tampil QR) --}}
<div id="payment_info_qris" class="d-none p-3 bg-light rounded border border-success mb-4 text-center">
    <div class="alert alert-info mb-3">
        <strong>Info Pembayaran QRIS:</strong><br>
        Silakan scan QR Code di bawah ini sejumlah <strong id="display_price_qris" class="text-dark fs-5">Rp 0</strong>
    </div>
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=ContohQRISMahligai"
        alt="QRIS Code" class="img-fluid border p-2" style="max-width: 200px;">
    <label class="form-label fw-bold mt-3">Upload Bukti Scan <span class="text-danger">*</span></label>
    <input type="file" name="payment_proof_qris" id="payment_proof_qris" class="form-control" accept="image/*">
</div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid mt-5">
                            <button type="button" class="btn btn-success btn-lg py-3 rounded-pill shadow hover-scale" id="submit_button">
                                Konfirmasi Pemesanan <i class="fa fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI (Pop-up) --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="confirmationModalLabel"><i class="fa fa-check-circle me-2"></i>Konfirmasi Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin melanjutkan pemesanan dengan rincian di bawah ini?</p>
                <div class="p-3 mb-3 border rounded">
                    <h6 class="fw-bold border-bottom pb-1 text-primary">Detail Booking</h6>
                    <p class="mb-1"><strong>Nama:</strong> <span id="modal_nama"></span></p>
                    <p class="mb-1"><strong>Layanan:</strong> <span id="modal_layanan"></span></p>
                    <p class="mb-1"><strong>Waktu:</strong> <span id="modal_waktu"></span></p>
                    <p class="mb-1"><strong>Metode:</strong> <span id="modal_bayar"></span></p>
                </div>

                <div class="p-3 bg-light rounded">
                    <h6 class="fw-bold border-bottom pb-1 text-primary">Rincian Biaya</h6>
                    <div class="d-flex justify-content-between"><span>Harga Dasar:</span> <span id="modal_harga_dasar"></span></div>
                    <div class="d-flex justify-content-between text-danger" id="modal_diskon_row" style="display: none;"><span>Diskon:</span> <span id="modal_diskon"></span></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>TOTAL AKHIR:</span> <span id="modal_total_akhir" class="text-success"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-success" id="final_submit_btn">Ya, Pesan Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Memuat JS khusus di bagian bawah body --}}
@push('scripts')
    <script src="{{ asset('js/pemesanan.js') }}"></script>
@endpush
