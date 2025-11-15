@extends('layouts.main')

@section('content')
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4" data-aos="fade-down">Kilau Sempurna, Performa Maksimal</h1>
            <p class="lead mb-5" data-aos="fade-down" data-aos-delay="200">Percayakan perawatan kendaraan Anda pada ahlinya.
                Standar baru dalam dunia auto detailing di Pekanbaru.</p>
            <a href="{{ route('home.layanan') }}" class="btn btn-cta rounded-pill text-white py-3 px-5 fs-5 animated-cta" data-aos="fade-up" data-aos-delay="400">Lihat Layanan Kami</a>
        </div>
    </div>

@if($promotions->count() > 0)
<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fas fa-fire text-danger me-2"></i>Promo Spesial Hari Ini</h3>
            <p class="text-muted mb-0 small">Ambil kupon diskon Anda sebelum kehabisan!</p>
        </div>
        <div class="d-none d-md-flex gap-2">
            <button class="btn btn-light shadow-sm rounded-circle" id="promo-prev-btn"><i class="fa fa-chevron-left"></i></button>
            <button class="btn btn-light shadow-sm rounded-circle" id="promo-next-btn"><i class="fa fa-chevron-right"></i></button>
        </div>
    </div>
    <div class="promo-carousel-wrapper">
        <div class="promo-carousel-track" id="promo-carousel-track">
            @foreach($promotions as $promo)
                <div class="coupon-card shadow-sm">
                    <div class="coupon-left">
                        <div class="coupon-content">
                            @if($promo->type == 'percentage')
                                <span class="coupon-label">DISKON</span>
                                <h2 class="coupon-value">{{ number_format($promo->value, 0) }}%</h2>
                            @else
                                <span class="coupon-label">POTONGAN</span>
                                <h2 class="coupon-value1">Rp{{ number_format($promo->value / 1000, 0) }}K</h2>
                            @endif
                            <small class="text-white-50">OFF</small>
                        </div>
                        <div class="coupon-circle-top"></div>
                        <div class="coupon-circle-bottom"></div>
                    </div>

                    <div class="coupon-right">
                        <div class="coupon-info">
                            <h5 class="coupon-title text-truncate" title="{{ $promo->name }}">{{ $promo->name }}</h5>
                            <p class="coupon-expiry mb-3">
                                <i class="far fa-clock me-1"></i> Berlaku s/d {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}
                            </p>

                            <div class="coupon-code-area">
                                <div class="code-box">
                                    <span class="code-text" id="code-{{ $promo->id }}">{{ $promo->code }}</span>
                                    <button class="btn-copy" onclick="copyToClipboard('{{ $promo->code }}', 'btn-{{ $promo->id }}')">
                                        <i class="far fa-copy" id="icon-{{ $promo->id }}"></i>
                                        <span class="copy-label" id="label-{{ $promo->id }}">Salin</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

    <div class="home-section container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="section-title">Selamat Datang di Mahligai AutoCare</h2>
                <p class="text-muted" style="line-height: 1.8;">
                    Bukan sekadar tempat cuci mobil, kami adalah partner terpercaya Anda dalam seni merawat kendaraan. Di
                    Mahligai AutoCare, kami percaya bahwa setiap kendaraan memiliki potensi untuk tampil memukau. Dengan
                    hasrat pada detail dan didukung oleh tim profesional bersertifikat, kami mendedikasikan keahlian kami
                    untuk mengembalikan kilau asli dan melindungi investasi Anda, memastikan setiap inci kendaraan Anda
                    mendapatkan sentuhan kesempurnaan.
                </p>

                <div class="row mt-4 g-3">
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-badge">
                            <div class="badge-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <span>Profesional <br>Bersertifikat</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-badge">
                            <div class="badge-icon">
                                <i class="fas fa-flask"></i>
                            </div>
                            <span>Produk <br>Premium</span>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-badge">
                            <div class="badge-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span>Garansi <br>Kepuasan</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('home.tentang') }}" class="btn btn-outline-success rounded-pill py-2 px-4 mt-5">Pelajari
                    Lebih Lanjut</a>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="img/tentang_index.png" class="img-fluid rounded-3 shadow" alt="Proses Detailing Mobil">
            </div>
        </div>
    </div>

    <div class="home-section bg-light-dark">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Layanan Unggulan</h2>
                <p class="text-muted">Perawatan paling populer yang dipilih oleh pelanggan kami.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card service-card h-100">
                        <img src="img/nano.jpg"
                            class="card-img-top" alt="Nano Coating">
                        <div class="card-body">
                            <h5 class="card-title">Nano Ceramic Coating</h5>
                            <p class="card-text">Proteksi cat superior dengan efek daun talas untuk kilau maksimal dan
                                perlindungan jangka panjang.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card service-card h-100">
                        <img src="img/interior.webp"
                            class="card-img-top" alt="Detailing Interior">
                        <div class="card-body">
                            <h5 class="card-title">Interior Detailing</h5>
                            <p class="card-text">Membersihkan dan meremajakan setiap sudut interior mobil Anda, membuatnya
                                terasa seperti baru.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card service-card h-100">
                        <img src="img/premium.jpg"
                            class="card-img-top" alt="Cuci Mobil Premium">
                        <div class="card-body">
                            <h5 class="card-title">Premium Car Wash</h5>
                            <p class="card-text">Bukan cuci biasa. Kami menggunakan teknik 2 bucket wash yang aman untuk cat
                                mobil Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="home-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Hubungi Kami</h2>
            <p class="text-muted">Kami siap mendengar pertanyaan, kritik, dan saran dari Anda.</p>
        </div>
        <div class="row g-5">
            <div class="col-lg-5" data-aos="fade-right" data-aos-delay="100">
                <div class="contact-info-box">
                    <h4 class="mb-4" style="color: var(--primary-green);">Informasi Kontak</h4>
                    <div class="info-item"><div class="info-icon"><i class="fas fa-map-marker-alt"></i></div><div><h6 class="mb-0">Alamat:</h6><p class="text-muted mb-0">Jl. Jend. Sudirman No. 123, Pekanbaru, Riau</p></div></div>
                    <div class="info-item"><div class="info-icon"><i class="fas fa-phone-alt"></i></div><div><h6 class="mb-0">Telepon:</h6><p class="text-muted mb-0">+62 812 3456 7890</p></div></div>
                    <div class="info-item"><div class="info-icon"><i class="fas fa-envelope"></i></div><div><h6 class="mb-0">Email:</h6><p class="text-muted mb-0">info@mahligaiautocare.com</p></div></div>
                    <div class="info-item mb-0"><div class="info-icon"><i class="fas fa-clock"></i></div><div><h6 class="mb-0">Jam Operasional:</h6><p class="text-muted mb-0">Senin - Minggu: 08:00 - 20:00 WIB</p></div></div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
                <div class="contact-form">
                    <h4 class="mb-4" style="color: var(--primary-green);">Kirim Pesan</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6"><div class="form-floating"><input type="text" class="form-control" id="name" placeholder="Nama Anda"><label for="name">Nama Anda</label></div></div>
                            <div class="col-md-6"><div class="form-floating"><input type="email" class="form-control" id="email" placeholder="Email Anda"><label for="email">Email Anda</label></div></div>
                            <div class="col-12"><div class="form-floating"><input type="text" class="form-control" id="subject" placeholder="Subjek"><label for="subject">Subjek</label></div></div>
                            <div class="col-12"><div class="form-floating"><textarea class="form-control" placeholder="Tinggalkan pesan di sini" id="message" style="height: 150px"></textarea><label for="message">Pesan</label></div></div>
                            <div class="col-12"><button class="btn btn-cta rounded-pill text-white py-3 px-5" type="submit">Kirim Pesan</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- PETA LOKASI SECTION --}}
<div class="container py-5" data-aos="fade-up">
    <div class="map-responsive">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127870.21133316147!2d101.37893902148117!3d0.5104218357732202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ac1a27cd9011%3A0x2035b1856d11f62!2sPekanbaru%2C%20Pekanbaru%20City%2C%20Riau!5e0!3m2!1sen!2sid!4v1668668615397!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no--downgrade"></iframe>
    </div>
</div>

<style>
    .promo-carousel-wrapper {
        overflow: hidden;
        padding: 10px 5px;
    }
    .promo-carousel-track {
        display: flex;
        gap: 20px;
        transition: transform 0.4s ease-in-out;
    }

    /* Desain Kartu Kupon */
    .coupon-card {
        flex: 0 0 320px; /* Lebar kartu */
        display: flex;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        border: 1px solid #e0e0e0;
        transition: transform 0.2s;
    }
    .coupon-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Bagian Kiri (Hijau) */
    .coupon-left {
        width: 100px;
        background: linear-gradient(180deg, #198754 0%, #146c43 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        border-right: 2px dashed rgba(255,255,255,0.6);
    }
    .coupon-content {
        text-align: center;
    }
    .coupon-label {
        font-size: 0.7rem;
        letter-spacing: 1px;
        opacity: 0.9;
    }
    .coupon-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        margin: 5px 0;
    }
    .coupon-value1 {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1;
        margin: 5px 0;
    }

    /* Bagian Kanan (Putih) */
    .coupon-right {
        flex: 1;
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .coupon-title {
        font-size: 1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }
    .coupon-expiry {
        font-size: 0.75rem;
        color: #888;
    }

    /* Area Kode & Tombol Copy */
    .coupon-code-area {
        margin-top: auto;
    }
    .code-box {
        background: #f8f9fa;
        border: 1px dashed #198754;
        border-radius: 6px;
        padding: 5px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .code-text {
        font-family: monospace;
        font-weight: bold;
        font-size: 1rem;
        color: #198754;
        letter-spacing: 1px;
    }
    .btn-copy {
        background: none;
        border: none;
        color: #6c757d;
        font-size: 0.8rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: color 0.2s;
    }
    .btn-copy:hover {
        color: #198754;
    }

    /* Efek Sobekan Lingkaran */
    .coupon-circle-top, .coupon-circle-bottom {
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: #fff; /* Warna background halaman */
        border-radius: 50%;
        right: -10px; /* Setengah lingkaran masuk ke kiri */
        z-index: 2;
    }
    .coupon-circle-top { top: -10px; }
    .coupon-circle-bottom { bottom: -10px; }

    /* Override background untuk menyesuaikan halaman jika abu-abu */
    body.bg-light .coupon-circle-top,
    body.bg-light .coupon-circle-bottom {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Fungsi Copy Code
    function copyToClipboard(text, btnId) {
        navigator.clipboard.writeText(text).then(() => {
            // Ubah tampilan tombol sementara
            const btn = document.querySelector(`button[onclick*="${btnId}"]`);
            const icon = btn.querySelector('i');
            const label = btn.querySelector('.copy-label');

            const originalIcon = icon.className;
            const originalText = label.innerText;

            icon.className = 'fas fa-check text-success';
            label.innerText = 'Disalin!';
            label.classList.add('text-success');

            setTimeout(() => {
                icon.className = 'far fa-copy';
                label.innerText = 'Salin';
                label.classList.remove('text-success');
            }, 2000);
        });
    }

    // Logic Carousel (Sama seperti sebelumnya)
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('promo-carousel-track');
        const prevBtn = document.getElementById('promo-prev-btn');
        const nextBtn = document.getElementById('promo-next-btn');
        const items = document.querySelectorAll('.coupon-card');

        if (items.length === 0) return;

        let index = 0;
        const itemWidth = 340; // 320px card + 20px gap

        function updateCarousel() {
            const maxIndex = Math.max(0, items.length - Math.floor(track.parentElement.offsetWidth / itemWidth));
            if (index > maxIndex) index = maxIndex;
            if (index < 0) index = 0;

            track.style.transform = `translateX(-${index * itemWidth}px)`;

            prevBtn.disabled = index === 0;
            nextBtn.disabled = index >= maxIndex;
            prevBtn.style.opacity = index === 0 ? 0.5 : 1;
            nextBtn.style.opacity = index >= maxIndex ? 0.5 : 1;
        }

        prevBtn.addEventListener('click', () => { index--; updateCarousel(); });
        nextBtn.addEventListener('click', () => { index++; updateCarousel(); });

        window.addEventListener('resize', updateCarousel);
        updateCarousel(); // Init
    });
</script>
@endsection
