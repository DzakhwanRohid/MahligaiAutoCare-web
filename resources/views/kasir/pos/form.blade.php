<div class="container-fluid">
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
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pelanggan</h6>
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
                                    <label class="form-label">Cari Pelanggan</label>
                                    <select id="customer_id" name="customer_id" class="form-select">
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->license_plate }} - {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="walkin" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label">Nama Pelanggan</label>
                                    <input type="text" class="form-control" name="walkin_name" disabled>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No. Polisi</label>
                                        <input type="text" class="form-control" name="walkin_license_plate" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No. HP</label>
                                        <input type="text" class="form-control" name="walkin_phone" disabled>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipe Kendaraan</label>
                                    <input type="text" class="form-control" name="walkin_vehicle_type" placeholder="Contoh: Pajero Sport" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">Rincian Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Layanan</label>
                            <select id="service_id" name="service_id" class="form-select form-select-lg" required onchange="updateTotal()">
                                <option value="" data-price="0">-- Pilih Layanan --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Penempatan Mobil</label>
                            <div class="row g-2">
                                <div class="col-12 mb-2">
                                    <input type="radio" class="btn-check" name="selected_slot" id="slot_queue" value="" checked>
                                    <label class="btn btn-outline-secondary w-100" for="slot_queue">
                                        <i class="fa fa-clock"></i> Masuk Antrean (Menunggu)
                                    </label>
                                </div>

                                {{-- Loop 4 Slot --}}
                                @for ($i = 1; $i <= 4; $i++)
                                    @php
                                        $isFilled = in_array($i, $filledSlots ?? []);
                                    @endphp
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="selected_slot" id="slot_{{ $i }}" value="{{ $i }}" {{ $isFilled ? 'disabled' : '' }}>
                                        <label class="btn btn-outline-{{ $isFilled ? 'danger' : 'success' }} w-100" for="slot_{{ $i }}">
                                            @if($isFilled)
                                                <i class="fa fa-times-circle"></i> Slot {{ $i }} (Isi)
                                            @else
                                                <i class="fa fa-check-circle"></i> Slot {{ $i }} (Kosong)
                                            @endif
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kode Promosi (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input type="text" class="form-control" name="promotion_code" placeholder="Contoh: HEMAT10" style="text-transform: uppercase">
                            </div>
                            <div class="form-text">Total harga akan dipotong otomatis setelah diproses.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePaymentMethod()">
                                <option value="Tunai">Tunai (Cash)</option>
                                <option value="QRIS">QRIS (Scan QR)</option>
                                <option value="Debit">Kartu Debit/Kredit (EDC)</option>
                            </select>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 mb-0">Total Tagihan:</span>
                            <span class="h3 mb-0 text-primary fw-bold" id="display_total">Rp 0</span>
                        </div>

                        <div id="cash_section">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Uang Diterima</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control form-control-lg" id="amount_paid" name="amount_paid" placeholder="0">
                                </div>
                                <div class="form-text text-end" id="display_change">Kembali: Rp 0</div>
                            </div>
                        </div>

                        <div id="qris_section" class="text-center d-none mb-3">
                            <div class="alert alert-info">Silakan scan QR Code di bawah ini</div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg"
                                 alt="QRIS Code" class="img-fluid border p-2" style="max-width: 200px;">
                            <p class="mt-2 text-muted small">Otomatis lunas setelah scan berhasil.</p>
                        </div>

                        <div id="debit_section" class="text-center d-none mb-3">
                            <div class="alert alert-warning">
                                <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                                Silakan lakukan pembayaran menggunakan mesin EDC.<br>
                                <strong>Pastikan transaksi BERHASIL sebelum menyimpan.</strong>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fa fa-save me-2"></i> Proses & Cetak Struk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Variabel Global
    const serviceSelect = document.getElementById('service_id');
    const displayTotal = document.getElementById('display_total');
    const amountInput = document.getElementById('amount_paid');
    const displayChange = document.getElementById('display_change');

    // Elemen Section Pembayaran
    const paymentSelect = document.getElementById('payment_method');
    const cashSection = document.getElementById('cash_section');
    const qrisSection = document.getElementById('qris_section');
    const debitSection = document.getElementById('debit_section');

    let currentTotal = 0;

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    function updateTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        currentTotal = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        displayTotal.innerText = formatRupiah(currentTotal);
        calculateChange(); // Hitung ulang kembalian jika harga berubah
    }

    function togglePaymentMethod() {
        const method = paymentSelect.value;

        // Reset tampilan
        cashSection.classList.add('d-none');
        qrisSection.classList.add('d-none');
        debitSection.classList.add('d-none');

        // Reset input tunai
        amountInput.value = '';
        amountInput.required = false; // Default tidak wajib
        displayChange.innerText = 'Kembali: Rp 0';

        if (method === 'Tunai') {
            cashSection.classList.remove('d-none');
            amountInput.required = true; // Tunai wajib isi jumlah
        } else if (method === 'QRIS') {
            qrisSection.classList.remove('d-none');
        } else if (method === 'Debit') {
            debitSection.classList.remove('d-none');
        }
    }

    function calculateChange() {
        // Hanya hitung jika metode Tunai
        if (paymentSelect.value === 'Tunai') {
            const paid = parseFloat(amountInput.value) || 0;
            const change = paid - currentTotal;

            if(paid > 0) {
                displayChange.innerText = 'Kembali: ' + formatRupiah(change);
                if(change < 0) {
                    displayChange.classList.add('text-danger');
                } else {
                    displayChange.classList.remove('text-danger');
                }
            } else {
                displayChange.innerText = 'Kembali: Rp 0';
            }
        }
    }

    // Event Listeners
    amountInput.addEventListener('input', calculateChange);

    // Tab Logic
    document.addEventListener('DOMContentLoaded', function () {
        // ... (Logic Tab Pelanggan sama seperti sebelumnya) ...
        const terdaftarTab = document.getElementById('terdaftar-tab');
        const walkinTab = document.getElementById('walkin-tab');
        const terdaftarInputs = document.querySelectorAll('#terdaftar select');
        const walkinInputs = document.querySelectorAll('#walkin input');
        const typeInput = document.getElementById('customer_type_input');

        terdaftarTab.addEventListener('shown.bs.tab', () => {
            typeInput.value = 'terdaftar';
            terdaftarInputs.forEach(i => i.disabled = false);
            walkinInputs.forEach(i => i.disabled = true);
        });

        walkinTab.addEventListener('shown.bs.tab', () => {
            typeInput.value = 'walkin';
            terdaftarInputs.forEach(i => i.disabled = true);
            walkinInputs.forEach(i => i.disabled = false);
        });
    });
</script>
