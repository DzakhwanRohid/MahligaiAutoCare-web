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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Transaksi Baru</h3>
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="customerTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="terdaftar-tab" data-bs-toggle="tab"
                                        data-bs-target="#terdaftar" type="button" role="tab"
                                        aria-controls="terdaftar" aria-selected="true">Pelanggan Terdaftar</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="walkin-tab" data-bs-toggle="tab"
                                        data-bs-target="#walkin" type="button" role="tab"
                                        aria-controls="walkin" aria-selected="false">Pelanggan Baru (Walk-in)</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3" id="customerTabContent">

                            <div class="tab-pane fade show active" id="terdaftar" role="tabpanel" aria-labelledby="terdaftar-tab">
                                <input type="hidden" name="customer_type" value="terdaftar">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Cari Pelanggan (No. Polisi / Nama)</label>
                                    <select id="customer_id" name="customer_id" class="form-select">
                                        <option value="">-- Pilih Pelanggan --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->license_plate }} - {{ $customer->name }} ({{ $customer->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="walkin" role="tabpanel" aria-labelledby="walkin-tab">
                                <input type="hidden" name="customer_type" value="walkin" disabled>
                                <div class="mb-3">
                                    <label for="walkin_name" class="form-label">Nama Pelanggan</label>
                                    <input type="text" class="form-control" id="walkin_name" name="walkin_name" value="{{ old('walkin_name') }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="walkin_license_plate" class="form-label">No. Polisi</label>
                                            <input type="text" class="form-control" id="walkin_license_plate" name="walkin_license_plate" value="{{ old('walkin_license_plate') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="walkin_phone" class="form-label">No. HP</label>
                                            <input type="text" class="form-control" id="walkin_phone" name="walkin_phone" value="{{ old('walkin_phone') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="walkin_vehicle_type" class="form-label">Tipe Kendaraan (Opsional)</label>
                                    <input type="text" class="form-control" id="walkin_vehicle_type" name="walkin_vehicle_type" placeholder="Contoh: Avanza Putih" value="{{ old('walkin_vehicle_type') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Layanan & Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Pilih Layanan</label>
                            <select id="service_id" name="service_id" class="form-select" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="promotion_code" class="form-label">Kode Promosi (Opsional)</label>
                            <input type="text" class="form-control" id="promotion_code" name="promotion_code"
                                   value="{{ old('promotion_code') }}" placeholder="Contoh: CUCIHEMAT10">
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select id="payment_method" name="payment_method" class="form-select" required>
                                <option value="Tunai" {{ old('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai (Cash)</option>
                                <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                <option value="Debit" {{ old('payment_method') == 'Debit' ? 'selected' : '' }}>Kartu Debit</option>
                            </select>
                        </div>

                        <hr>

                        {{-- Di sini nanti kita bisa tambahkan JS untuk menampilkan Total Harga --}}

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save"></i> Buat Transaksi & Cetak Struk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const terdaftarTab = document.getElementById('terdaftar-tab');
        const walkinTab = document.getElementById('walkin-tab');

        const terdaftarInputs = document.querySelectorAll('#terdaftar input, #terdaftar select');
        const walkinInputs = document.querySelectorAll('#walkin input, #walkin select');

        function toggleInputs(inputs, status) {
            inputs.forEach(input => input.disabled = !status);
        }

        terdaftarTab.addEventListener('shown.bs.tab', function () {
            toggleInputs(terdaftarInputs, true);
            toggleInputs(walkinInputs, false);
        });

        walkinTab.addEventListener('shown.bs.tab', function () {
            toggleInputs(terdaftarInputs, false);
            toggleInputs(walkinInputs, true);
        });

        // Inisialisasi saat halaman dimuat
        if (walkinTab.classList.contains('active')) {
            toggleInputs(terdaftarInputs, false);
            toggleInputs(walkinInputs, true);
        } else {
            toggleInputs(terdaftarInputs, true);
            toggleInputs(walkinInputs, false);
        }
    });
</script>
