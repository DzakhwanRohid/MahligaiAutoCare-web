@extends('layouts.main')

@push('styles')
    <link href="{{ asset('css/pemesanan.css') }}" rel="stylesheet">
@endpush

@section('content')

<!-- HEADER SECTION - Dark Gradient (Sama persis dengan halaman layanan) -->
<section class="page-header-services">
    <div class="header-gradient"></div>

    <div class="container">
        <div class="header-content-inner">
            <h1 class="main-title">
                <span class="title-line title-line-outline">Booking</span>
                <span class="title-line title-line-white">Layanan</span>
                <span class="title-line title-line-green">Mahligai AutoCare</span>
            </h1>

            <div class="title-separator">
                <span class="separator-line"></span>
                <span class="separator-icon">◈</span>
                <span class="separator-line"></span>
            </div>

            <p class="subtitle">
                Jadwalkan perawatan kendaraan Anda tanpa antre
            </p>
        </div>
    </div>

    <div class="wave-divider">
        <svg viewBox="0 0 1200 30" preserveAspectRatio="none">
            <path d="M0,0V15c150,10,300,10,450,5S750,5,900,10s300,10,450,5V0Z" fill="#fff"/>
        </svg>
    </div>
</section>

{{-- FORMULIR PEMESANAN --}}
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="booking-card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form dengan data-attribute untuk JS --}}
                    <form action="{{ route('pemesanan.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="bookingForm"
                          data-schedule-url="{{ route('booking.getSchedule') }}"
                          data-check-promo-url="{{ route('check.promo') }}"
                          data-csrf-token="{{ csrf_token() }}">
                        @csrf

                        {{-- BAGIAN 1: DATA DIRI --}}
                        <div class="section-header mb-4">
                            <h4 class="section-title"><i class="fa fa-user me-2"></i>Data Diri & Kendaraan</h4>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. WhatsApp</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Polisi</label>
                                <input type="text" name="license_plate" id="license_plate" class="form-control text-uppercase" value="{{ old('license_plate', $customer->license_plate ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jenis Kendaraan</label>
                                <input type="text" name="vehicle_type" id="vehicle_type" class="form-control" value="{{ old('vehicle_type', $customer->vehicle_type ?? '') }}" required>
                            </div>
                        </div>

                        {{-- BAGIAN 2: LAYANAN & JADWAL --}}
                        <div class="section-header mb-4">
                            <h4 class="section-title"><i class="fa fa-calendar-alt me-2"></i>Layanan & Jadwal</h4>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">1. Pilih Layanan</label>
                            <select name="service_id" id="service_id" class="form-select" required>
                                <option value="" data-price="0" data-name="Belum Dipilih">-- Pilih Layanan (Lihat Durasi) --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-name="{{ $service->name }}" data-duration="{{ $service->duration_minutes }}">
                                        {{ $service->name }} (Estimasi {{ $service->duration_minutes }} Menit)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">2. Pilih Tanggal Kedatangan</label>
                            <input type="date" id="date_picker" class="form-control" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">3. Pilih Slot Cuci</label>
                            <div id="slots_container" class="slot-selection-box">
                                <div class="text-muted fst-italic small">Silakan pilih layanan dan tanggal...</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">4. Pilih Jam Tersedia (Untuk Slot yang Dipilih)</label>
                            <div id="slots_time_container">
                                {{-- Akan diisi oleh JavaScript --}}
                            </div>
                            {{-- Input tersembunyi untuk menyimpan data final --}}
                            <input type="hidden" name="booking_date" id="final_booking_date" required>
                            <input type="hidden" name="slot" id="final_slot" required>
                        </div>

                        {{-- BAGIAN 3: PEMBAYARAN --}}
                        <div class="section-header mb-4">
                            <h4 class="section-title"><i class="fa fa-wallet me-2"></i>Pembayaran</h4>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Punya Kode Promo?</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                <input type="text" name="promotion_code" id="promo_code_input" class="form-control" placeholder="Masukkan kode (Opsional)" style="text-transform: uppercase;">
                                <button class="btn btn-green" type="button" id="apply_promo_btn">Terapkan</button>
                            </div>
                            <div id="promo_message" class="small mt-1"></div>
                        </div>

                        {{-- RINCIAN BIAYA --}}
                        <div class="mb-3 p-3 rounded border" id="rincian_box">
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
                                    <label class="btn btn-outline-secondary w-100 h-100 py-3 text-center" for="pay_tunai"><i class="fa fa-money-bill-wave d-block mb-2 fa-2x text-success"></i> Tunai</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_transfer" value="Transfer">
                                    <label class="btn btn-outline-secondary w-100 h-100 py-3 text-center" for="pay_transfer"><i class="fa fa-university d-block mb-2 fa-2x text-primary"></i> Transfer</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_qris" value="QRIS">
                                    <label class="btn btn-outline-secondary w-100 h-100 py-3 text-center" for="pay_qris"><i class="fa fa-qrcode d-block mb-2 fa-2x text-danger"></i> QRIS</label>
                                </div>
                            </div>
                        </div>

                        {{-- Boks Transfer Bank --}}
                        <div id="payment_info_transfer" class="d-none p-3 rounded border mb-4">
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

                        {{-- Boks QRIS --}}
                        <div id="payment_info_qris" class="d-none p-3 rounded border mb-4 text-center">
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
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-green btn-lg py-3 rounded-pill" id="submit_button">
                                Konfirmasi Pemesanan <i class="fa fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-green text-white">
                <h5 class="modal-title" id="confirmationModalLabel"><i class="fa fa-check-circle me-2"></i>Konfirmasi Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin melanjutkan pemesanan dengan rincian di bawah ini?</p>
                <div class="p-3 mb-3 border rounded">
                    <h6 class="fw-bold border-bottom pb-1 text-green">Detail Booking</h6>
                    <p class="mb-1"><strong>Nama:</strong> <span id="modal_nama"></span></p>
                    <p class="mb-1"><strong>Layanan:</strong> <span id="modal_layanan"></span></p>
                    <p class="mb-1"><strong>Waktu:</strong> <span id="modal_waktu"></span></p>
                    <p class="mb-1"><strong>Slot:</strong> <span id="modal_slot"></span></p>
                    <p class="mb-1"><strong>Metode:</strong> <span id="modal_bayar"></span></p>
                </div>

                <div class="p-3 bg-light rounded">
                    <h6 class="fw-bold border-bottom pb-1 text-green">Rincian Biaya</h6>
                    <div class="d-flex justify-content-between"><span>Harga Dasar:</span> <span id="modal_harga_dasar"></span></div>
                    <div class="d-flex justify-content-between text-danger" id="modal_diskon_row" style="display: none;"><span>Diskon:</span> <span id="modal_diskon"></span></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>TOTAL AKHIR:</span> <span id="modal_total_akhir" class="text-success"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-green" id="final_submit_btn">Ya, Pesan Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pemesanan.js') }}"></script>
@endpush

<style>
/* --- VARIABEL WARNA HIJAU UTAMA (Sama dengan halaman layanan) --- */
:root {
    --primary-color: #7baa23;
    --primary-dark: #5d7e2c;
    --primary-light: rgba(123, 170, 35, 0.1);
}

/* --- HEADER PEMESANAN (Sama persis dengan halaman layanan) --- */
.page-header-services {
    position: relative;
    background: linear-gradient(135deg, #0f172b 0%, #1e293b 100%);
    color: white;
    padding: 50px 0 70px;
    overflow: hidden;
    margin-bottom: 40px;
}

.page-header-services .header-gradient {
    position: absolute;
    inset: 0;
    background: radial-gradient(
            circle at 20% 30%,
            rgba(123, 170, 35, 0.1) 0%,
            transparent 50%
        ),
        radial-gradient(
            circle at 80% 70%,
            rgba(91, 126, 44, 0.05) 0%,
            transparent 50%
        );
}

.page-header-services .container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.page-header-services .header-content-inner {
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
}

.page-header-services .main-title {
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.page-header-services .title-line {
    display: block;
}

.page-header-services .title-line-outline {
    font-size: 1.8rem;
    font-weight: 700;
    color: transparent;
    -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
    letter-spacing: 1px;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.page-header-services .title-line-white {
    font-size: 2.8rem;
    font-weight: 800;
    color: white;
    margin-right: 5px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    display: inline-block;
}

.page-header-services .title-line-green {
    font-size: 2.8rem;
    font-weight: 800;
    color: var(--primary-color, #7baa23);
    text-shadow: 0 2px 8px rgba(123, 170, 35, 0.3);
    display: inline-block;
}

.page-header-services .subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin-top: 25px;
    line-height: 1.6;
    font-weight: 300;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    letter-spacing: 0.5px;
}

.page-header-services .title-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin: 20px 0 25px;
}

.page-header-services .separator-line {
    width: 80px;
    height: 1px;
    background: rgba(255, 255, 255, 0.2);
}

.page-header-services .separator-icon {
    color: var(--primary-color, #7baa23);
    font-size: 0.9rem;
    opacity: 0.8;
}

.page-header-services .wave-divider {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 30px;
}

.page-header-services .wave-divider svg {
    width: 100%;
    height: 100%;
    display: block;
}

/* --- BUTTON HIJAU (Menggunakan warna hijau yang sama) --- */
.btn-green {
    background-color: var(--primary-color, #7baa23) !important;
    border-color: var(--primary-color, #7baa23) !important;
    color: white !important;
}

.btn-green:hover {
    background-color: var(--primary-dark, #5d7e2c) !important;
    border-color: var(--primary-dark, #5d7e2c) !important;
    color: white !important;
}

.bg-green {
    background-color: var(--primary-color, #7baa23) !important;
}

.text-green {
    color: var(--primary-color, #7baa23) !important;
}

/* --- FORM STYLING --- */
.booking-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.section-header {
    border-bottom: 2px solid #e9ecef;
}

.section-title {
    color: var(--primary-color, #7baa23);
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0;
    padding-bottom: 10px;
}

.form-control, .form-select {
    padding: 10px 15px;
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color, #7baa23);
    box-shadow: 0 0 0 0.15rem rgba(123, 170, 35, 0.25);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

/* --- SLOT STYLING --- */
.slot-selection-box {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.slot-selection-box .btn-check + .btn {
    flex: 1;
    min-width: 120px;
    padding: 12px 15px;
    font-weight: 500;
    border-radius: 8px;
    background: white;
    border: 2px solid #dee2e6;
}

.slot-selection-box .btn-check:checked + .btn {
    background: var(--primary-color, #7baa23);
    color: white;
    border-color: var(--primary-color, #7baa23);
}

/* --- TIME SLOTS --- */
#slots_time_container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    max-height: 250px;
    overflow-y: auto;
}

.time-slot-btn {
    flex-basis: 100px;
    padding: 10px;
    text-align: center;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.2s;
}

.time-slot-btn:hover:not(:disabled) {
    border-color: var(--primary-color, #7baa23);
    background: rgba(123, 170, 35, 0.05);
}

.time-slot-btn.active {
    background: var(--primary-color, #7baa23);
    color: white;
    border-color: var(--primary-color, #7baa23);
}

/* --- RINCIAN BOX --- */
#rincian_box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 12px;
}

#rincian_box .h5 {
    color: var(--primary-color, #7baa23);
}

/* --- PAYMENT METHODS --- */
.btn-check + .btn {
    border: 2px solid #dee2e6;
    transition: all 0.3s;
}

.btn-check:checked + .btn {
    border-color: var(--primary-color, #7baa23);
    background: rgba(123, 170, 35, 0.1);
}

/* --- ALERT STYLING --- */
.alert-info {
    background-color: rgba(123, 170, 35, 0.1);
    border-color: rgba(123, 170, 35, 0.2);
    color: #5d7e2c;
}

/* --- RESPONSIVE --- */
@media (max-width: 768px) {
    .page-header-services {
        padding: 40px 0 60px;
    }

    .page-header-services .main-title {
        font-size: 2.2rem;
    }

    .page-header-services .title-line-outline {
        font-size: 1.4rem;
    }

    .page-header-services .title-line-white,
    .page-header-services .title-line-green {
        font-size: 2.2rem;
    }

    .booking-card {
        border-radius: 12px;
    }

    .card-body {
        padding: 25px 20px !important;
    }

    .slot-selection-box .btn-check + .btn {
        min-width: 100px;
    }

    .col-4 {
        margin-bottom: 10px;
    }
}

@media (max-width: 576px) {
    .slot-selection-box {
        flex-direction: column;
    }

    .slot-selection-box .btn-check + .btn {
        min-width: 100%;
    }

    #slots_time_container {
        max-height: 200px;
    }

    .time-slot-btn {
        flex-basis: 80px;
        padding: 8px;
    }
}
</style>
