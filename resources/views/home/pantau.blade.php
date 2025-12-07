@extends('layouts.main')

@section('content')

<!-- HEADER SECTION - Konsisten dengan halaman lain -->
<section class="page-header-services">
    <div class="header-gradient"></div>

    <div class="container">
        <div class="header-content-inner">
            <h1 class="main-title">
                <span class="title-line title-line-outline">Pantau</span>
                <span class="title-line title-line-white">Antrian &</span>
                <span class="title-line title-line-green">Jadwal</span>
            </h1>

            <div class="title-separator">
                <span class="separator-line"></span>
                <span class="separator-icon">◈</span>
                <span class="separator-line"></span>
            </div>

            <p class="subtitle">
                Pantau kondisi slot cuci dan jadwal booking untuk hari ini
            </p>
        </div>
    </div>

    <div class="wave-divider">
        <svg viewBox="0 0 1200 30" preserveAspectRatio="none">
            <path d="M0,0V15c150,10,300,10,450,5S750,5,900,10s300,10,450,5V0Z" fill="#fff"/>
        </svg>
    </div>
</section>

{{-- MAIN CONTENT --}}
<div class="container mb-5 pantau-container">

    {{-- NOTIFIKASI PESANAN AKTIF --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(isset($myActiveBookings) && $myActiveBookings->count() > 0)
        <div class="pantau-active-booking-card mb-5">
            <div class="pantau-active-header">
                <i class="fas fa-bell text-warning"></i>
                <h3>Status Pesanan Aktif Anda</h3>
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-history me-2"></i>Lihat Riwayat Transaksi</a></li>
                    <li><a class="dropdown-item" href="{{ route('pemesanan.create') }}"><i class="fas fa-calendar-plus me-2"></i>Buat Pesanan Baru</a></li>
                </ul>
            </div>
            <div class="pantau-active-body">
                @foreach($myActiveBookings as $booking)
                    <div class="pantau-booking-item">
                        <div class="pantau-booking-info">
                            <h5>{{ $booking->service->name }}</h5>
                            <div class="pantau-booking-meta">
                                <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, d F Y') }}</span>
                                <span><i class="fas fa-clock"></i> Pukul {{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB</span>
                                <span><i class="fas fa-map-marker-alt"></i> Slot {{ $booking->slot }}</span>
                            </div>
                        </div>
                        <div class="pantau-booking-status">
                            @if($booking->status == 'Terkonfirmasi')
                                <span class="pantau-status-badge pantau-status-confirmed">Terkonfirmasi</span>
                                <small>Silakan datang sesuai jadwal</small>
                            @elseif($booking->status == 'Sedang Dicuci')
                                <span class="pantau-status-badge pantau-status-processing">Sedang Dicuci</span>
                                <small>Mobil Anda sedang diproses</small>
                            @else
                                <span class="pantau-status-badge pantau-status-pending">Menunggu Verifikasi</span>
                                <small>Admin akan cek bukti bayar</small>
                            @endif
                        </div>
                    </div>
                @endforeach
                <div class="pantau-active-footer">
                    <a href="{{ route('profile.edit') }}" class="pantau-view-all">
                        Lihat semua riwayat di halaman profil <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- STATUS TOKO --}}
    @php
        $currentHour = (int) date('H');
        $isStoreOpen = $currentHour >= 7 && $currentHour < 17;
    @endphp

    <div class="pantau-store-status mb-5">
        @if(!$isStoreOpen)
            <div class="pantau-store-closed">
                <i class="fas fa-store-slash"></i>
                <div>
                    <h3>Layanan Sedang Tutup</h3>
                    <p>Jam operasional kami adalah 07:00 - 17:00 WIB.
                       <br>Waktu sekarang: {{ date('H:i') }} WIB.
                       Silakan booking untuk besok.</p>
                    <a href="{{ route('pemesanan.create') }}" class="btn btn-pantau-primary">
                        <i class="fas fa-calendar-check me-2"></i>Booking Untuk Besok
                    </a>
                </div>
            </div>
        @else
            <div class="pantau-store-open">
                <i class="fas fa-store"></i>
                <div>
                    <h3>Layanan Sedang Buka</h3>
                    <p>Jam operasional: 07:00 - 17:00 WIB | Waktu sekarang: {{ date('H:i') }} WIB</p>
                </div>
            </div>
        @endif
    </div>

    {{-- SLOT CUCI --}}
    <div class="text-center mb-5">
        <h2 class="pantau-section-title">Kondisi Slot Cuci (Saat Ini)</h2>
        @if(!$isStoreOpen)
            <p class="text-muted">Layanan buka mulai jam 07:00 WIB</p>
        @else
            <p class="text-muted">Status 4 slot fisik pencucian kami saat ini</p>
        @endif
    </div>

    {{-- Tampilkan Slot hanya jika Toko Buka --}}
    @if($isStoreOpen)
        <div class="row g-4 justify-content-center mb-5">
            @for ($i = 1; $i <= 4; $i++)
                @php
                    $currentSlot = $slots[$i] ?? null;
                @endphp
                <div class="col-md-6 col-lg-3">
                    <div class="pantau-slot-card {{ $currentSlot ? 'pantau-slot-busy' : 'pantau-slot-available' }}">
                        <div class="pantau-slot-header">
                            <h4>SLOT {{ $i }}</h4>
                            <div class="pantau-slot-indicator {{ $currentSlot ? 'busy' : 'available' }}"></div>
                        </div>

                        <div class="pantau-slot-body">
                            @if($currentSlot)
                                <div class="pantau-washing-icon">
                                    <i class="fas fa-car"></i>
                                    <i class="fas fa-shower"></i>
                                </div>

                                <span class="pantau-slot-badge pantau-slot-busy-badge">SEDANG DICUCI</span>

                                <h5>{{ $currentSlot->vehicle_brand ?? 'Kendaraan' }}</h5>
                                @php
                                    $plat = $currentSlot->vehicle_plate ?? 'XXXXXX';
                                    $sensor = substr($plat, 0, 2) . ' *** ' . substr($plat, -2);
                                @endphp
                                <p class="pantau-vehicle-plate">{{ $sensor }}</p>

                                <div class="pantau-estimation">
                                    <small>Estimasi Selesai:</small>
                                    @php
                                        $duration = $currentSlot->service->duration_minutes ?? 60;
                                        $estimasiSelesai = \Carbon\Carbon::parse($currentSlot->booking_date)->addMinutes($duration);
                                    @endphp
                                    <div class="pantau-estimation-time">{{ $estimasiSelesai->format('H:i') }} WIB</div>
                                </div>
                            @else
                                <div class="pantau-available-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>

                                <span class="pantau-slot-badge pantau-slot-available-badge">TERSEDIA</span>

                                <p>Slot ini kosong dan siap digunakan.</p>

                                <div class="pantau-slot-action">
                                    <a href="{{ route('pemesanan.create') }}" class="btn btn-pantau-primary">Booking Slot</a>
                                </div>
                            @endif
                        </div>

                        <div class="pantau-slot-footer">
                            <button type="button"
                                    class="btn btn-pantau-outline btn-sm pantau-view-schedule-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#slotInfoModal"
                                    data-slot-id="{{ $i }}"
                                    data-slot-number="{{ $i }}">
                                <i class="fas fa-calendar-alt me-1"></i> Lihat Jadwal Slot {{ $i }}
                            </button>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    @endif

    <hr class="pantau-divider">

    {{-- CARD INFORMATIF --}}
    <div class="text-center mb-5">
        <h2 class="pantau-section-title">Informasi Layanan</h2>
        <p class="text-muted">Informasi penting tentang layanan kami</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="pantau-info-card">
                <div class="pantau-info-icon">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h4>Jam Operasional</h4>
                <p>07:00 - 17:00 WIB</p>
                <small class="text-muted">Setiap hari termasuk weekend</small>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="pantau-info-card">
                <div class="pantau-info-icon">
                    <i class="fas fa-car fa-2x"></i>
                </div>
                <h4>Kapasitas</h4>
                <p>4 Slot Tersedia</p>
                <small class="text-muted">Maksimal 1 mobil per slot</small>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="pantau-info-card">
                <div class="pantau-info-icon">
                    <i class="fas fa-calendar-check fa-2x"></i>
                </div>
                <h4>Booking Online</h4>
                <p>Pesan via Website</p>
                <small class="text-muted">Tanpa perlu antri di tempat</small>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="pantau-info-card">
                <div class="pantau-info-icon">
                    <i class="fas fa-user-clock fa-2x"></i>
                </div>
                <h4>Durasi Layanan</h4>
                <p>60-90 Menit</p>
                <small class="text-muted">Tergantung paket layanan</small>
            </div>
        </div>
    </div>

    {{-- TOMBOL BOOKING --}}
    @if($isStoreOpen)
        <div class="text-center mb-5">
            <a href="{{ route('pemesanan.create') }}" class="btn btn-pantau-primary btn-lg">
                <i class="fas fa-calendar-check me-2"></i> Buat Booking Sekarang
            </a>
        </div>
    @endif
</div>

{{-- MODAL UNTUK INFO JADWAL SLOT --}}
<div class="modal fade" id="slotInfoModal" tabindex="-1" aria-labelledby="slotInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-pantau-primary text-white">
                <h5 class="modal-title" id="slotInfoModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i>Jadwal Booking - Slot <span id="modalSlotNumber">1</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="modal-info-card">
                            <div class="modal-info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="modal-info-content">
                                <h6>Informasi Slot</h6>
                                <p id="modalSlotStatus">Status: Tersedia</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-info-card">
                            <div class="modal-info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="modal-info-content">
                                <h6>Jam Operasional</h6>
                                <p>07:00 - 17:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-section-header">
                    <h6><i class="fas fa-list me-2"></i>Daftar Booking Slot <span id="modalSlotNumber2">1</span> Hari Ini</h6>
                </div>

                <div class="pantau-modal-timeline" id="scheduleTimeline">
                    <div class="text-center text-muted py-5">
                        <div class="spinner-border text-pantau-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Memuat jadwal booking...</p>
                    </div>
                </div>

                <div class="mt-4 text-center" id="modalNoBookings" style="display: none;">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Tidak ada booking untuk slot ini hari ini</h6>
                    <p class="small text-muted">Semua slot waktu masih tersedia untuk booking</p>
                    <a href="{{ route('pemesanan.create') }}" class="btn btn-pantau-primary btn-sm mt-2">
                        <i class="fas fa-calendar-plus me-1"></i>Booking Slot Ini
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('pemesanan.create') }}" class="btn btn-pantau-primary">
                    <i class="fas fa-calendar-plus me-1"></i>Booking Slot Ini
                </a>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL BOOKING --}}
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-pantau-primary text-white">
                <h5 class="modal-title" id="bookingDetailModalLabel">
                    <i class="fas fa-car me-2"></i>Detail Booking
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="booking-detail-item">
                    <div class="detail-label">Waktu:</div>
                    <div class="detail-value" id="detailTime">-</div>
                </div>
                <div class="booking-detail-item">
                    <div class="detail-label">Slot:</div>
                    <div class="detail-value" id="detailSlot">-</div>
                </div>
                <div class="booking-detail-item">
                    <div class="detail-label">Plat Nomor:</div>
                    <div class="detail-value" id="detailPlate">-</div>
                </div>
                <div class="booking-detail-item">
                    <div class="detail-label">Merek Kendaraan:</div>
                    <div class="detail-value" id="detailBrand">-</div>
                </div>
                <div class="booking-detail-item">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value">
                        <span class="pantau-status-badge pantau-status-confirmed" id="detailStatus">-</span>
                    </div>
                </div>
                <div class="booking-detail-item">
                    <div class="detail-label">Pelanggan:</div>
                    <div class="detail-value" id="detailCustomer">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* === VARIABEL WARNA PANTAU === */
:root {
    --pantau-primary: #7baa23;
    --pantau-primary-dark: #5d7e2c;
    --pantau-primary-light: #f0f8e8;
    --pantau-warning: #ff9800;
    --pantau-danger: #dc3545;
    --pantau-success: #28a745;
    --pantau-gray: #6c757d;
    --pantau-gray-light: #f8f9fa;
    --pantau-gray-border: #e9ecef;
    --pantau-white: #ffffff;
}

.bg-pantau-primary {
    background: linear-gradient(135deg, var(--pantau-primary) 0%, var(--pantau-primary-dark) 100%) !important;
}

.text-pantau-primary {
    color: var(--pantau-primary) !important;
}

/* === CARD INFORMATIF === */
.pantau-info-card {
    background: var(--pantau-white);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--pantau-gray-border);
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
}

.pantau-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(123, 170, 35, 0.1);
    border-color: var(--pantau-primary);
}

.pantau-info-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--pantau-primary) 0%, var(--pantau-primary-dark) 100%);
    color: var(--pantau-white);
    border-radius: 50%;
    font-size: 1.5rem;
}

.pantau-info-card h4 {
    color: var(--pantau-primary-dark);
    margin-bottom: 10px;
    font-weight: 600;
    font-size: 1.2rem;
}

.pantau-info-card p {
    color: var(--pantau-primary);
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.pantau-info-card small {
    color: var(--pantau-gray);
    font-size: 0.85rem;
}

/* === MODAL STYLING === */
.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

.modal-info-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: var(--pantau-primary-light);
    border-radius: 10px;
    border: 1px solid rgba(123, 170, 35, 0.2);
}

.modal-info-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--pantau-primary);
    color: var(--pantau-white);
    border-radius: 50%;
    font-size: 1.2rem;
}

.modal-info-content h6 {
    color: var(--pantau-primary-dark);
    margin-bottom: 5px;
    font-weight: 600;
}

.modal-info-content p {
    color: var(--pantau-gray);
    margin: 0;
    font-size: 0.9rem;
}

.modal-section-header {
    padding: 15px;
    background: var(--pantau-gray-light);
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid var(--pantau-primary);
}

.modal-section-header h6 {
    color: var(--pantau-primary-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

/* === BOOKING DETAIL MODAL === */
.booking-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--pantau-gray-border);
}

.booking-detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: var(--pantau-gray);
    min-width: 150px;
}

.detail-value {
    color: var(--pantau-primary-dark);
    text-align: right;
    flex: 1;
}

/* === BUTTON VIEW SCHEDULE === */
.pantau-view-schedule-btn {
    width: 100%;
    transition: all 0.3s;
}

.pantau-view-schedule-btn:hover {
    background-color: var(--pantau-primary);
    color: var(--pantau-white);
    transform: translateY(-2px);
}

/* === MODAL TIMELINE === */
.pantau-modal-timeline {
    max-height: 400px;
    overflow-y: auto;
}

.timeline-item {
    display: flex;
    padding: 15px;
    border-left: 3px solid var(--pantau-primary);
    margin-bottom: 15px;
    background: var(--pantau-gray-light);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.timeline-item:hover {
    background: var(--pantau-primary-light);
    transform: translateX(5px);
}

.timeline-time {
    min-width: 80px;
    font-weight: 700;
    color: var(--pantau-primary);
    margin-right: 15px;
}

.timeline-content {
    flex: 1;
}

.timeline-plate {
    font-weight: 600;
    color: var(--pantau-primary-dark);
    margin-bottom: 5px;
}

.timeline-details {
    font-size: 0.85rem;
    color: var(--pantau-gray);
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.timeline-details span {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .pantau-info-card {
        padding: 20px;
        margin-bottom: 15px;
    }

    .pantau-info-icon {
        width: 60px;
        height: 60px;
        font-size: 1.3rem;
    }

    .modal-info-card {
        margin-bottom: 10px;
    }

    .timeline-item {
        flex-direction: column;
    }

    .timeline-time {
        margin-right: 0;
        margin-bottom: 10px;
    }

    .detail-label {
        min-width: 120px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Fix modal close issue - SIMPLIFIED VERSION
document.addEventListener('DOMContentLoaded', function() {
    console.log('Pantau Antrian JavaScript Loaded');

    // Data dari controller
    const jadwalSlots = @json($jadwalSlots ?? []);
    console.log('Jadwal slots data:', jadwalSlots);

    // Elemen modal
    const slotInfoModal = document.getElementById('slotInfoModal');
    const modalSlotNumber = document.getElementById('modalSlotNumber');
    const modalSlotNumber2 = document.getElementById('modalSlotNumber2');
    const scheduleTimeline = document.getElementById('scheduleTimeline');
    const modalNoBookings = document.getElementById('modalNoBookings');
    const modalSlotStatus = document.getElementById('modalSlotStatus');

    // Modal detail booking
    const bookingDetailModal = document.getElementById('bookingDetailModal');
    const detailTime = document.getElementById('detailTime');
    const detailSlot = document.getElementById('detailSlot');
    const detailPlate = document.getElementById('detailPlate');
    const detailBrand = document.getElementById('detailBrand');
    const detailStatus = document.getElementById('detailStatus');
    const detailCustomer = document.getElementById('detailCustomer');

    // 1. Tombol "Lihat Jadwal Slot" pada card
    const viewScheduleButtons = document.querySelectorAll('.pantau-view-schedule-btn');
    console.log('Found view schedule buttons:', viewScheduleButtons.length);

    viewScheduleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const slotId = this.getAttribute('data-slot-id');
            const slotNumber = this.getAttribute('data-slot-number');

            console.log('Tombol ditekan - Slot ID:', slotId, 'Slot Number:', slotNumber);

            // Update modal title
            modalSlotNumber.textContent = slotNumber;
            modalSlotNumber2.textContent = slotNumber;

            // Tampilkan loading
            scheduleTimeline.innerHTML = `
                <div class="text-center text-muted py-5">
                    <div class="spinner-border text-pantau-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Memuat jadwal booking Slot ${slotNumber}...</p>
                </div>
            `;
            modalNoBookings.style.display = 'none';

            // Update status slot
            const slotCard = this.closest('.pantau-slot-card');
            if (slotCard.classList.contains('pantau-slot-busy')) {
                modalSlotStatus.innerHTML = 'Status: <span class="text-danger">Sedang Dipakai</span>';
            } else {
                modalSlotStatus.innerHTML = 'Status: <span class="text-success">Tersedia</span>';
            }

            // Tampilkan modal
            const modal = new bootstrap.Modal(slotInfoModal);
            modal.show();

            // Load data setelah modal ditampilkan
            setTimeout(() => {
                loadSlotSchedule(slotId, slotNumber);
            }, 300);
        });
    });

    // 3. Fungsi untuk memuat jadwal slot
    function loadSlotSchedule(slotId, slotNumber) {
        console.log('Loading schedule for slot:', slotId, 'Number:', slotNumber);

        // Cek apakah ada data untuk slot ini
        const slotData = jadwalSlots[slotId] || [];
        console.log('Slot data found:', slotData);

        if (slotData.length === 0) {
            // Tidak ada booking
            scheduleTimeline.innerHTML = '';
            modalNoBookings.style.display = 'block';
            return;
        }

        // Urutkan berdasarkan waktu
        const sortedBookings = slotData.sort((a, b) => {
            const timeA = new Date(a.booking_date || a.created_at).getTime();
            const timeB = new Date(b.booking_date || b.created_at).getTime();
            return timeA - timeB;
        });

        // Buat timeline
        let html = '';
        sortedBookings.forEach(booking => {
            const bookingTime = new Date(booking.booking_date || booking.created_at);
            const formattedTime = bookingTime.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            const plate = booking.vehicle_plate || 'XXXXXX';
            const brand = booking.vehicle_brand || 'Tidak diketahui';
            const status = booking.status || 'Terkonfirmasi';
            const customer = booking.customer?.name || booking.user?.name || 'Tidak diketahui';
            const service = booking.service?.name || 'Layanan';

            // Tentukan warna status
            let statusClass = 'pantau-status-confirmed';
            let statusText = status;

            if (status.includes('Sedang Dicuci')) {
                statusClass = 'pantau-status-processing';
            } else if (status.includes('Menunggu')) {
                statusClass = 'pantau-status-pending';
            }

            html += `
                <div class="timeline-item">
                    <div class="timeline-time">${formattedTime}</div>
                    <div class="timeline-content">
                        <div class="timeline-plate">${plate} - ${brand}</div>
                        <div class="timeline-details">
                            <span><i class="fas fa-user"></i> ${customer}</span>
                            <span><i class="fas fa-concierge-bell"></i> ${service}</span>
                            <span class="${statusClass}">${statusText}</span>
                        </div>
                    </div>
                </div>
            `;
        });

        scheduleTimeline.innerHTML = html;
        modalNoBookings.style.display = 'none';
    }

    // Fix modal close dengan cara yang lebih sederhana
    document.addEventListener('hidden.bs.modal', function (event) {
        // Reset body state
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Remove backdrop jika masih ada
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            backdrop.remove();
        });
    });
});
</script>
@endpush
