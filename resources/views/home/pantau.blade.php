@extends('layouts.main')

{{-- Tambahkan CSS khusus di <head> --}}
@push('styles')
    <link href="{{ asset('css/pantau.css') }}" rel="stylesheet">
@endpush

@section('content')

{{-- HEADER --}}
<div class="page-header text-center mb-5">
    <div class="container">
        <h1 class="display-4">Pantau Antrean & Jadwal</h1>
        <p class="lead">Lihat kondisi slot cuci dan jadwal booking untuk hari ini.</p>
    </div>
</div>

<div class="container mb-5">

    {{-- Notifikasi Sukses / Status Aktif --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(isset($myActiveBookings) && $myActiveBookings->count() > 0)
        <div class="card shadow-lg border-0 mb-5" style="background-color: #f0fff4; border: 2px solid #198754 !important;" data-aos="fade-up">
            <div class="card-body p-4 p-md-5">
                <h3 class="fw-bold text-success mb-3"><i class="fa fa-bell me-2"></i>Status Pesanan Aktif Anda</h3>
                @foreach($myActiveBookings as $booking)
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $booking->service->name }}</h5>
                            <p class="mb-0 text-muted">
                                <i class="fa fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, d F Y') }}
                                <i class="fa fa-clock me-1 ms-3"></i> Pukul {{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB
                                <i class="fa fa-map-marker-alt me-1 ms-3"></i> Slot {{ $booking->slot }}
                            </p>
                        </div>
                        <div class="text-end" style="min-width: 150px;">
                            @if($booking->status == 'Terkonfirmasi')
                                <span class="badge bg-success fs-6 rounded-pill px-3 py-2">Terkonfirmasi</span>
                                <small class="d-block text-muted mt-1">Silakan datang sesuai jadwal</small>
                            @elseif($booking->status == 'Sedang Dicuci')
                                <span class="badge bg-primary fs-6 rounded-pill px-3 py-2">Sedang Dicuci</span>
                                <small class="d-block text-muted mt-1">Mobil Anda sedang diproses</small>
                            @else
                                <span class="badge bg-warning text-dark fs-6 rounded-pill px-3 py-2">Menunggu Verifikasi</span>
                                <small class="d-block text-muted mt-1">Admin akan cek bukti bayar</small>
                            @endif
                        </div>
                    </div>
                @endforeach
                <a href="{{ route('profile.edit') }}" class="btn btn-link text-success fw-bold p-0">
                    Lihat semua riwayat di halaman profil <i class="fa fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    @endif

    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="section-title">Kondisi Slot Cuci (Saat Ini)</h2>

        {{-- === LOGIKA BARU: Cek Toko Tutup === --}}
        @if($isClosed)
            {{-- JIKA SUDAH TUTUP (Lewat jam 17:00) --}}
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-lg-8">
                    <div class="alert alert-warning shadow-lg text-center p-4">
                        <h3 class="fw-bold"><i class="fa fa-clock me-2"></i> Layanan Hari Ini Sudah Tutup</h3>
                        <p class="lead mb-0">Jam operasional kami adalah 09:00 - 17:00 WIB.</p>
                        <hr>
                        <p class="mb-0">Silakan cek jadwal atau lakukan booking untuk besok.</p>
                        <a href="{{ route('pemesanan.create') }}" class="btn btn-success mt-3 rounded-pill px-4">
                            <i class="fa fa-calendar-check me-2"></i> Booking Untuk Besok
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- JIKA MASIH BUKA (Tampilkan 4 Slot seperti biasa) --}}
            <p class="text-muted">Status 4 slot fisik pencucian kami saat ini.</p>
        @endif
        {{-- ============================== --}}
    </div>

    {{-- Tampilkan Slot hanya jika Toko Belum Tutup --}}
    @if(!$isClosed)
        <div class="row g-4 justify-content-center mb-5">
            @for ($i = 1; $i <= 4; $i++)
                @php
                    $tx = $slots[$i] ?? null;
                @endphp
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="card h-100 border-0 shadow-lg slot-card {{ $tx ? 'bg-light' : 'bg-success-soft' }}">
                        <div class="card-body text-center py-5 d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="fw-bold text-secondary mb-4">SLOT {{ $i }}</h4>
                                <div class="mb-4 position-relative">
                                    @if($tx)
                                        <div class="status-icon washing"><i class="fa fa-car fa-3x text-primary"></i><i class="fa fa-shower fa-2x text-info position-absolute top-0 start-50 translate-middle"></i></div>
                                    @else
                                        <div class="status-icon available"><i class="fa fa-check-circle fa-4x text-success"></i></div>
                                    @endif
                                </div>
                            </div>
                            @if($tx)
                                <span class="badge bg-primary fs-6 mb-3 px-3 py-2 rounded-pill">SEDANG DICUCI</span>
                                <h5 class="fw-bold text-dark mb-1">{{ $tx->vehicle_brand ?? 'Kendaraan' }}</h5>
                                @php
                                    $plat = $tx->vehicle_plate ?? 'XXXXXX';
                                    $sensor = substr($plat, 0, 2) . ' *** ' . substr($plat, -2);
                                @endphp
                                <p class="text-muted fw-bold font-monospace fs-5">{{ $sensor }}</p>

                                <div class="mt-auto pt-3 border-top">
                                    <small class="text-muted d-block">Estimasi Selesai:</small>
                                    @php
                                        // Pastikan $tx->service ada sebelum mengakses duration_minutes
                                        $duration = $tx->service->duration_minutes ?? 60; // default 60 menit jika service tidak ditemukan
                                        $estimasiSelesai = \Carbon\Carbon::parse($tx->booking_date)->addMinutes($duration);
                                    @endphp
                                    <small class="fw-bold text-danger">Pukul {{ $estimasiSelesai->format('H:i') }} WIB</small>
                                </div>
                            @else
                                <span class="badge bg-success fs-6 mb-3 px-3 py-2 rounded-pill">TERSEDIA</span>
                                <p class="text-muted mb-4">Slot ini kosong dan siap digunakan.</p>
                                <div class="mt-auto">
                                    <a href="{{ route('pemesanan.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm hover-scale">Booking Slot</a>
                                </div>
                            @endif

                            {{-- Tombol Info Jadwal --}}
                            <div class="d-grid mt-3">
                                <button class="btn btn-outline-dark btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#slotInfoModal"
                                        data-slot-id="{{ $i }}">
                                    <i class="fa fa-calendar-alt me-1"></i> Lihat Jadwal Slot {{ $i }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    @endif

    <hr class="my-5">

    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="section-title">Jadwal Booking Hari Ini ({{ \Carbon\Carbon::parse($today)->format('d F Y') }})</h2>
        {{-- Logika Tampilan Teks (Tutup/Buka) --}}
        @if($isClosed)
            <p class="text-muted">Jadwal untuk hari ini sudah selesai.</p>
        @else
            <p class="text-muted">Lihat jam yang sudah terisi atau masih kosong untuk booking online.</p>
        @endif
    </div>

    <div class="row justify-content-center" data-aos="fade-up">
        <div class="col-lg-10">
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                {{-- Loop Jam Operasional --}}
                @for ($i = $startHour; $i <= $endHour; $i++)
                    @php
                        $timeString = ($i < 10 ? '0' . $i : $i) . ':00';
                        $isBooked = in_array($timeString, $bookedSlots);
                        $isPassed = $timeString <= $now;
                    @endphp

                    <div class="slot-waktu-wrapper">
                        <div class="btn slot-waktu-btn
                            {{ $isBooked ? 'btn-danger' : 'btn-success' }}
                            {{ ($isPassed && !$isBooked) ? 'btn-light disabled' : '' }}
                            {{ ($isPassed && $isBooked) ? 'btn-danger disabled' : '' }}"
                            title="{{ $isBooked ? 'Jam sudah dibooking' : ($isPassed ? 'Waktu sudah lewat' : 'Slot tersedia') }}">

                            <div class="slot-waktu-jam">{{ $timeString }}</div>
                            @if($isBooked)
                                <div class="slot-waktu-status"><i class="fa fa-lock me-1"></i> Dipesan</div>
                            @elseif($isPassed)
                                <div class="slot-waktu-status"><i class="fa fa-history me-1"></i> Lewat</div>
                            @else
                                <div class="slot-waktu-status"><i class="fa fa-check me-1"></i> Tersedia</div>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>

            {{-- Tombol Booking (Sembunyikan jika tutup) --}}
            @if(!$isClosed)
            <div class="text-center mt-4">
                <a href="{{ route('pemesanan.create') }}" class="btn btn-primary rounded-pill px-5 py-3 fs-5 hover-scale">
                    <i class="fa fa-calendar-check me-2"></i> Buat Janji Temu Sekarang
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL UNTUK INFO JADWAL SLOT --}}
<div class="modal fade" id="slotInfoModal" tabindex="-1" aria-labelledby="slotInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="slotInfoModalLabel">Jadwal Booking Hari Ini: Slot 1</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Menampilkan jadwal yang sudah dibooking (Terkonfirmasi/Menunggu) untuk hari ini.</p>

                {{-- Timeline akan diisi oleh JavaScript --}}
                <ul class="list-group list-group-flush" id="scheduleTimeline">
                    {{-- Contoh (akan dihapus oleh JS): --}}
                    <li class="list-group-item text-muted">Memuat jadwal...</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Kirim data Backend (Jadwal) ke Frontend (JS) --}}
<script>
    // Konversi data PHP ke JS
    // Pastikan variabel $jadwalSlots, $startHour, $endHour, dan $now dikirim dari Controller
    const jadwalSlots = @json($jadwalSlots ?? []);
    const jamBuka = {{ $startHour ?? 9 }};
    const jamTutup = {{ $endHour ?? 17 }};
    const jamSekarang = "{{ $now ?? '00:00' }}";
</script>

{{-- Panggil file JS khusus halaman ini --}}
<script src="{{ asset('js/pantau.js') }}"></script>
@endpush
