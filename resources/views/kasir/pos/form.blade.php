{{-- Aset untuk jQuery & Select2 (Pastikan sudah ada di layout utama Anda) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="container-fluid">
    {{-- 1. PEMBERITAHUAN JAM KERJA --}}
    <div id="jam-kerja-alert" class="alert alert-danger d-none fw-bold" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i>
        <strong>DI LUAR JAM KERJA (07:00 - 17:00)!</strong>
        <p class="mb-0 small">Cucian sudah tutup. Harap proses transaksi hanya pada jam operasional.</p>
    </div>

    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

    <form action="{{ route('pos.store') }}" method="POST">
        @csrf
        <div class="row">
            {{-- KOLOM KIRI (INPUT DATA) --}}
            <div class="col-lg-7">
                {{-- CARD PELANGGAN --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">1. Data Pelanggan</h6>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="customerTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="terdaftar-tab" data-bs-toggle="tab" data-bs-target="#terdaftar" type="button" role="tab">Pelanggan Terdaftar</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="walkin-tab" data-bs-toggle="tab" data-bs-target="#walkin" type="button" role="tab">Pelanggan Baru (Walk-in)</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="customerTabContent">
                            <div class="tab-pane fade show active" id="terdaftar" role="tabpanel">
                                <input type="hidden" name="customer_type" id="customer_type_input" value="terdaftar">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cari Pelanggan (No. Polisi / Nama)</label>
                                    <select id="customer_search_box" name="customer_id" class="form-select">
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->license_plate }} - {{ $customer->name }} ({{ $customer->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="walkin" role="tabpanel">
                                {{-- Form Walk-in --}}
                                <div class="mb-3">
                                    <label class="form-label">Nama Pelanggan</label>
                                    <input type="text" class="form-control" name="walkin_name" value="{{ old('walkin_name') }}" disabled>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No. Polisi</label>
                                        <input type="text" class="form-control" name="walkin_license_plate" value="{{ old('walkin_license_plate') }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No. HP</label>
                                        <input type="text" class="form-control" name="walkin_phone" value="{{ old('walkin_phone') }}" disabled>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipe Kendaraan</label>
                                    <input type="text" class="form-control" name="walkin_vehicle_type" value="{{ old('walkin_vehicle_type') }}" placeholder="Contoh: Pajero Sport" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD LAYANAN (DESAIN BARU v4) --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">2. Pilih Layanan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse($services as $service)
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="service_id" id="service_{{ $service->id }}"
                                           value="{{ $service->id }}" data-price="{{ $service->price }}"
                                           {{ old('service_id') == $service->id ? 'checked' : '' }}
                                           required onchange="updateTotal()">
                                    {{-- CARD LAYANAN (DESAIN BARU) --}}
                                    <label class="btn service-card-btn" for="service_{{ $service->id }}">
                                        {{-- ▼▼▼ ICON CHECKMARK BARU ▼▼▼ --}}
                                        <div class="selected-check"><i class="fa fa-check-circle"></i></div>
                                        <i class="fa fa-car-wash fa-2x service-icon"></i>
                                        <div class="service-details">
                                            <span class="service-name">{{ $service->name }}</span>
                                            <span class="service-price">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12"><div class="alert alert-warning">Belum ada layanan.</div></div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- CARD PENEMPATAN (DESAIN BARU v4) --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">3. Pilih Penempatan Mobil</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            {{-- TOMBOL ANTREAN --}}
                            <div class="col-12">
                                <input type="radio" class="btn-check" name="selected_slot" id="slot_queue" value="" checked>
                                <label class="btn slot-card-btn btn-outline-secondary w-100 p-3" for="slot_queue">
                                    {{-- ▼▼▼ ICON CHECKMARK BARU ▼▼▼ --}}
                                    <div class="selected-check"><i class="fa fa-check-circle"></i></div>
                                    <i class="fa fa-clock fa-lg d-block mb-2"></i>
                                    <strong>Masuk Antrean</strong>
                                    <span class="small d-block">(Menunggu)</span>
                                </label>
                            </div>

                            {{-- TOMBOL SLOT --}}
                            @for ($i = 1; $i <= 4; $i++)
                                @php
                                    $isFilled = in_array($i, $filledSlots ?? []);
                                @endphp
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="selected_slot" id="slot_{{ $i }}" value="{{ $i }}" {{ $isFilled ? 'disabled' : '' }}>
                                    <label class="btn slot-card-btn btn-outline-{{ $isFilled ? 'danger' : 'success' }} w-100 p-3" for="slot_{{ $i }}">
                                        {{-- ▼▼▼ ICON CHECKMARK BARU ▼▼▼ --}}
                                        <div class="selected-check"><i class="fa fa-check-circle"></i></div>
                                        @if($isFilled)
                                            <i class="fa fa-times-circle fa-lg d-block mb-2"></i>
                                            <strong>Slot {{ $i }}</strong>
                                            <span class="small d-block">(Isi)</span>
                                        @else
                                            <i class="fa fa-check-circle fa-lg d-block mb-2"></i>
                                            <strong>Slot {{ $i }}</strong>
                                            <span class="small d-block">(Kosong)</span>
                                        @endif
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (PEMBAYARAN) --}}
            <div class="col-lg-5">
                <div class="card shadow-sm" style="position: sticky; top: 20px;">
                    <div class="card-header py-3 pos-payment-header">
                        <h6 class="m-0 font-weight-bold">Rincian Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Promosi (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input type="text" class="form-control" name="promotion_code" value="{{ old('promotion_code') }}" placeholder="Contoh: HEMAT10" style="text-transform: uppercase">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePaymentMethod()">
                                <option value="Tunai" {{ old('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai (Cash)</option>
                                <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS (Scan QR)</option>
                                <option value="Debit" {{ old('payment_method') == 'Debit' ? 'selected' : '' }}>Kartu Debit/Kredit (EDC)</option>
                            </select>
                        </div>

                        <hr class="my-3">

                        <div class="total-box d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 mb-0 pos-total-label">Total Tagihan:</span>
                            <span class="h3 mb-0 pos-total fw-bold" id="display_total">Rp 0</span>
                        </div>

                        <div id="cash_section">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Uang Diterima</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control form-control-lg" id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" placeholder="0">
                                </div>
                                <div class="form-text text-end" id="display_change">Kembali: Rp 0</div>
                            </div>
                        </div>

                        <div id="qris_section" class="text-center d-none mb-3">
                            <div class="alert alert-info">Silakan scan QR Code di bawah ini</div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg"
                                 alt="QRIS Code" class="img-fluid border p-2" style="max-width: 200px;">
                        </div>

                        <div id="debit_section" class="text-center d-none mb-3">
                            <div class="alert alert-warning">
                                <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                                Silakan lakukan pembayaran menggunakan mesin EDC.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg" id="submit_button">
                                <i class="fa fa-save me-2"></i> Proses & Cetak Struk
                            </button>
                        </div>

                        {{-- ▼▼▼ ANIMASI BARU (PULSING ICON) ▼▼▼ --}}
                        <div class="pos-footer-animation mt-4">
                            <div class="secure-icon">
                                <i class="fa fa-shield-alt"></i>
                            </div>
                            <span class="secure-text">Sistem Pembayaran Aman</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Aset untuk jQuery & Select2 (Pastikan sudah ada di layout utama Anda) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // ... (SELURUH KODE JAVASCRIPT ANDA TETAP SAMA SEPERTI SEBELUMNYA) ...
    // ... (Tidak perlu diubah, salin saja semua dari file Anda sebelumnya) ...

    // Variabel Global
    const displayTotal = document.getElementById('display_total');
    const amountInput = document.getElementById('amount_paid');
    const displayChange = document.getElementById('display_change');
    const paymentSelect = document.getElementById('payment_method');
    const cashSection = document.getElementById('cash_section');
    const qrisSection = document.getElementById('qris_section');
    const debitSection = document.getElementById('debit_section');
    let currentTotal = 0;

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    function updateTotal() {
        const selectedService = document.querySelector('input[name="service_id"]:checked');
        if (selectedService) {
            currentTotal = parseFloat(selectedService.getAttribute('data-price')) || 0;
        } else {
            currentTotal = 0;
        }
        displayTotal.innerText = formatRupiah(currentTotal);
        calculateChange();
    }

    function togglePaymentMethod() {
        const method = paymentSelect.value;
        cashSection.classList.add('d-none');
        qrisSection.classList.add('d-none');
        debitSection.classList.add('d-none');
        amountInput.required = false;
        displayChange.innerText = 'Kembali: Rp 0';

        if (method === 'Tunai') {
            cashSection.classList.remove('d-none');
            amountInput.required = true;
        } else if (method === 'QRIS') {
            qrisSection.classList.remove('d-none');
        } else if (method === 'Debit') {
            debitSection.classList.remove('d-none');
        }
        calculateChange();
    }

    function calculateChange() {
        if (paymentSelect.value === 'Tunai') {
            const paid = parseFloat(amountInput.value) || 0;
            const change = paid - currentTotal;
            if(paid > 0) {
                displayChange.innerText = 'Kembali: ' + formatRupiah(change);
                if(change < 0) {
                    displayChange.classList.add('text-danger');
                    displayChange.classList.remove('text-success');
                } else {
                    displayChange.classList.remove('text-danger');
                    displayChange.classList.add('text-success');
                }
            } else {
                displayChange.innerText = 'Kembali: Rp 0';
                displayChange.classList.remove('text-danger', 'text-success');
            }
        } else {
             displayChange.innerText = '';
        }
    }

    function checkWorkingHours() {
        const now = new Date();
        const currentHour = now.getHours();
        const alertBox = document.getElementById('jam-kerja-alert');
        const submitButton = document.getElementById('submit_button');

        if (currentHour < 7 || currentHour >= 17) {
            alertBox.classList.remove('d-none');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa fa-times me-2"></i> CUCIAN SUDAH TUTUP';
            submitButton.classList.add('btn-danger');
            submitButton.classList.remove('btn-success');
        } else {
             alertBox.classList.add('d-none');
             submitButton.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof jQuery !== 'undefined') {
            $('#customer_search_box').select2({
                theme: "bootstrap-5",
                placeholder: '-- Cari No. Polisi / Nama Pelanggan --',
                width: '100%'
            });
        }
        checkWorkingHours();
        updateTotal();
        togglePaymentMethod();
        amountInput.addEventListener('input', calculateChange);

        const terdaftarTab = document.getElementById('terdaftar-tab');
        const walkinTab = document.getElementById('walkin-tab');
        const terdaftarInputs = document.querySelectorAll('#terdaftar select');
        const walkinInputs = document.querySelectorAll('#walkin input');
        const typeInput = document.getElementById('customer_type_input');

        terdaftarTab.addEventListener('shown.bs.tab', () => {
            typeInput.value = 'terdaftar';
            terdaftarInputs.forEach(i => i.disabled = false);
            walkinInputs.forEach(i => { i.disabled = true; i.value = ''; });
            $('#customer_search_box').prop('disabled', false).trigger('change');
        });

        walkinTab.addEventListener('shown.bs.tab', () => {
            typeInput.value = 'walkin';
            terdaftarInputs.forEach(i => i.disabled = true);
            walkinInputs.forEach(i => i.disabled = false);
            $('#customer_search_box').prop('disabled', true).val(null).trigger('change');
        });

        if (document.querySelector('#terdaftar-tab').classList.contains('active')) {
             walkinInputs.forEach(i => i.disabled = true);
             $('#customer_search_box').prop('disabled', false).trigger('change');
        } else {
             terdaftarInputs.forEach(i => i.disabled = true);
             $('#customer_search_box').prop('disabled', true).trigger('change');
        }
    });
</script>
