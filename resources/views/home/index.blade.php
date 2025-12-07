@extends('layouts.main')
@section('content')
    <section class="hero-modern-with-image">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content" data-aos="fade-right">
                    <div class="hero-badge">
                        <span class="badge-pill">
                            <i class="fas fa-crown me-2"></i>Premium Auto Care
                        </span>
                    </div>

                    <h1 class="hero-title">
                        <span class="text-outline">Kilau</span>
                        <span class="text-gradient">Sempurna</span>,
                        <br>
                        <span class="text-outline">Performa</span>
                        <span class="text-gradient">Maksimal</span>
                    </h1>

                    <p class="hero-subtitle">
                        Transformasi kendaraan Anda menjadi mahakarya berkilau.
                        Percayakan perawatan pada spesialis detailing terbaik di Pekanbaru.
                    </p>

                    <div class="hero-cta-group">
                        <a href="{{ route('home.layanan') }}" class="btn btn-hero-primary">
                            <i class="fas fa-sparkles me-2"></i>Lihat Layanan
                        </a>
                        <a href="{{ route('pemesanan.create') }}" class="btn btn-hero-secondary">
                            <i class="fas fa-calendar-alt me-2"></i>Booking Sekarang
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <div>
                                <h3>500+</h3>
                                <p>Kendaraan Dirawat</p>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h3>4.9<span>/5.0</span></h3>
                                <p>Rating Pelanggan</p>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <h3>5+</h3>
                                <p>Tahun Pengalaman</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual" data-aos="fade-left" data-aos-delay="300">
                    <div class="visual-container">
                        <!-- Main Hero Image -->
                        <div class="main-image-wrapper">
                            <img src="{{ asset('img/hero-car-detailing.jpg') }}"
                                 alt="Proses Auto Detailing Mobil Premium"
                                 class="hero-main-image">
                            <div class="image-overlay"></div>

                            <!-- Floating Badge on Image -->
                            <div class="floating-badge badge-1">
                                <i class="fas fa-shield-alt"></i>
                                <span>Garansi 100%</span>
                            </div>

                            <div class="floating-badge badge-2">
                                <i class="fas fa-clock"></i>
                                <span>Proses Cepat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Background Elements -->
        <div class="hero-bg-shape shape-left"></div>
        <div class="hero-bg-shape shape-right"></div>
    </section>

    <!-- ====================
        HORIZONTAL PROMO CAROUSEL
    ===================== -->
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

    <!-- ====================
        SIMPLE ABOUT SECTION
    ===================== -->
    <section class="simple-about-section">
        <div class="container">
            <div class="about-content-wrapper">
                <div class="about-text" data-aos="fade-right">
                    <div class="section-label">
                        <i class="fas fa-star"></i>
                        <span>Tentang Kami</span>
                    </div>
                    <h2 class="section-title">Mahligai <span class="text-gradient">AutoCare</span></h2>
                    <p class="about-description">
                        Bukan sekadar tempat cuci mobil, kami adalah partner terpercaya Anda dalam seni merawat kendaraan.
                        Di Mahligai AutoCare, kami percaya bahwa setiap kendaraan memiliki potensi untuk tampil memukau.
                    </p>

                    <div class="about-points">
                        <div class="point-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Tim profesional bersertifikat</span>
                        </div>
                        <div class="point-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Produk premium berkualitas</span>
                        </div>
                        <div class="point-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Garansi kepuasan pelanggan</span>
                        </div>
                        <div class="point-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Teknologi terbaru dan modern</span>
                        </div>
                    </div>

                    <a href="{{ route('home.tentang') }}" class="btn-about">
                        <i class="fas fa-book-open me-2"></i>Pelajari Lebih Lanjut
                    </a>
                </div>

                <div class="about-image" data-aos="fade-left">
                    <img src="{{ asset('img/tentang_index.png') }}" alt="Proses Detailing Mobil" class="about-img">
                </div>
            </div>
        </div>
    </section>

    <!-- ====================
        SERVICES SECTION - Card Design Improved
    ===================== -->
    <section class="services-section-modern">
      {{-- DETAIL LAYANAN --}}
<div class="services-container">
    <div class="services-header text-center mb-6">
        <div class="section-mini-label">Paket Layanan</div>
        <h2 class="services-main-title">Detail Layanan Perawatan</h2>
        <p class="services-subtitle">Berbagai pilihan layanan premium untuk memenuhi setiap kebutuhan mobil Anda</p>
    </div>

    <div class="services-grid">
        {{-- Looping Data Services dari Database --}}
        @forelse($services as $service)
            <div class="service-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                {{-- Badge Premium --}}
                <div class="service-badge">Premium</div>

                {{-- Gambar Service --}}
                <div class="service-image-container">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="service-image"
                            alt="{{ $service->name }}">
                    @else
                        <img src="{{ asset('img/default-car.png') }}" class="service-image"
                            alt="{{ $service->name }}">
                    @endif
                </div>

                <div class="service-info">
                    <h3 class="service-title">{{ $service->name }}</h3>
                    <p class="service-description">{{ Str::limit($service->description, 80) }}</p>

                    <div class="service-meta">
                        <div class="duration">
                            <i class="fas fa-clock"></i>
                            <span>{{ $service->duration_minutes ?? '60' }} menit</span>
                        </div>
                    </div>

                    <div class="service-footer">
                        <div class="service-price">
                            <span class="price-value">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('pemesanan.create') }}" class="service-button">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-services">
                <div class="empty-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h4>Belum ada layanan tersedia</h4>
                <p>Silahkan kembali lagi nanti</p>
            </div>
        @endforelse
    </div>
</div>
    </section>

    <!-- ====================
        CONTACT SECTION - Enhanced Design
    ===================== -->
    <section class="contact-section-enhanced">
        <div class="container">
            <div class="section-header center">
                <h2 class="section-title">Hubungi <span class="text-gradient">Kami</span></h2>
                <p class="section-subtitle">Butuh bantuan? Tim support kami siap membantu 24/7</p>
            </div>

            <div class="contact-grid-enhanced">
                <div class="contact-info-enhanced" data-aos="fade-right">
                    <div class="contact-card">
                        <div class="contact-card-header">
                            <div class="contact-icon-large">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3>Kami Siap Membantu</h3>
                            <p class="contact-intro">Dapatkan respon cepat dari tim kami</p>
                        </div>

                        <div class="contact-details-list">
                            <div class="contact-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Alamat Workshop</h4>
                                    <p>Jl. Jend. Sudirman No. 123, Pekanbaru, Riau</p>
                                </div>
                            </div>

                            <div class="contact-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Telepon & WhatsApp</h4>
                                    <p>+62 812 3456 7890</p>
                                </div>
                            </div>

                            <div class="contact-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Email</h4>
                                    <p>info@mahligaiautocare.com</p>
                                </div>
                            </div>

                            <div class="contact-detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="detail-content">
                                    <h4>Jam Operasional</h4>
                                    <p>Senin - Minggu: 08:00 - 20:00 WIB</p>
                                </div>
                            </div>
                        </div>

                        <div class="contact-social-links">
                            <p class="social-title">Ikuti Kami</p>
                            <div class="social-icons">
                                <a href="#" class="social-link">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="social-link">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-form-enhanced" data-aos="fade-left">
                    <div class="form-card">
                        <div class="form-header">
                            <h3><i class="fas fa-paper-plane me-2"></i>Kiri Kritik & Saran</h3>
                            <p>Kritik & Saran Anda Sangat Bermanfaat Bagi Kami</p>
                        </div>

                        <form id="contactFormEnhanced">
                            <div class="form-row">
                                @auth
                                <div class="form-group-enhanced">
                                    <label>Nama Anda</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user"></i>
                                        <input type="text" value="{{ Auth::user()->name }}" readonly class="form-input-filled">
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label>Email Anda</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" value="{{ Auth::user()->email }}" readonly class="form-input-filled">
                                    </div>
                                </div>
                                @else
                                <div class="form-group-enhanced">
                                    <label>Nama Lengkap</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user"></i>
                                        <input type="text" placeholder="Masukkan nama Anda" required>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label>Email</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" placeholder="email@contoh.com" required>
                                    </div>
                                </div>
                                @endauth
                            </div>

                            <div class="form-group-enhanced">
                                <label>Subjek Pesan</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-tag"></i>
                                    <input type="text" placeholder="Apa yang bisa kami bantu?" required>
                                </div>
                            </div>

                            <div class="form-group-enhanced">
                                <label>Pesan Anda</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-comment-alt"></i>
                                    <textarea placeholder="Tulis pesan Anda di sini..." rows="1" required></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit-enhanced">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================
        MAP SECTION - Enhanced
    ===================== -->
    <section class="map-section-enhanced">
        <div class="container">
            <div class="map-wrapper">
                <div class="map-header">
                    <h3><i class="fas fa-map-marker-alt me-2"></i>Lokasi Workshop Kami</h3>
                    <p>Kunjungi langsung untuk konsultasi gratis</p>
                </div>

                <div class="map-container-enhanced">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127870.21133316147!2d101.37893902148117!3d0.5104218357732202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ac1a27cd9011%3A0x2035b1856d11f62!2sPekanbaru%2C%20Pekanbaru%20City%2C%20Riau!5e0!3m2!1sen!2sid!4v1668668615397!5m2!1sen!2sid"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                    <div class="map-overlay">
                        <div class="overlay-badge">
                            <i class="fas fa-car"></i>
                            <span>Parkir Luas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // PROMO CAROUSEL FUNCTIONALITY
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize promo carousel
            const promoCarousel = document.getElementById('promoCarousel');
            const prevBtn = document.getElementById('prevPromo');
            const nextBtn = document.getElementById('nextPromo');

            if (promoCarousel && prevBtn && nextBtn) {
                let currentPosition = 0;
                const cardWidth = 320; // Width + gap
                const totalCards = promoCarousel.children.length;

                function updateCarousel() {
                    promoCarousel.style.transform = `translateX(-${currentPosition * cardWidth}px)`;

                    // Disable/enable buttons
                    prevBtn.disabled = currentPosition === 0;
                    nextBtn.disabled = currentPosition >= totalCards - 3; // Show 3 cards at a time

                    prevBtn.style.opacity = currentPosition === 0 ? '0.5' : '1';
                    nextBtn.style.opacity = currentPosition >= totalCards - 3 ? '0.5' : '1';
                }

                prevBtn.addEventListener('click', () => {
                    if (currentPosition > 0) {
                        currentPosition--;
                        updateCarousel();
                    }
                });

                nextBtn.addEventListener('click', () => {
                    if (currentPosition < totalCards - 3) {
                        currentPosition++;
                        updateCarousel();
                    }
                });

                // Initialize
                updateCarousel();

                // Auto slide every 5 seconds
                setInterval(() => {
                    if (currentPosition < totalCards - 3) {
                        currentPosition++;
                    } else {
                        currentPosition = 0;
                    }
                    updateCarousel();
                }, 5000);
            }

            // Function to copy promo code
            window.copyPromoCode = function(code) {
                navigator.clipboard.writeText(code).then(() => {
                    // Show feedback
                    const button = event.currentTarget;
                    const icon = button.querySelector('i');
                    const originalClass = icon.className;

                    // Change icon to check
                    icon.className = 'fas fa-check';
                    button.style.background = '#198754';

                    // Revert after 2 seconds
                    setTimeout(() => {
                        icon.className = originalClass;
                        button.style.background = '#7baa23';
                    }, 2000);

                    // Show toast notification
                    showToast('Kode promo berhasil disalin!');
                });
            };

            // Form submission
            document.getElementById('contactFormEnhanced')?.addEventListener('submit', function(e) {
                e.preventDefault();

                // Simple validation
                let isValid = true;
                this.querySelectorAll('[required]').forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.style.borderColor = '#dc3545';
                    } else {
                        input.style.borderColor = '#7baa23';
                    }
                });

                if (isValid) {
                    // Simulate form submission
                    const submitBtn = this.querySelector('.btn-submit-enhanced');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
                    submitBtn.disabled = true;

                    setTimeout(() => {
                        showToast('Pesan berhasil dikirim! Kami akan membalas segera.');
                        this.reset();
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 1500);
                }
            });

            // Show service info (placeholder function)
            window.showServiceInfo = function(serviceId) {
                alert('Informasi detail layanan akan ditampilkan di sini.\nService ID: ' + serviceId);
            };

            // Toast notification function
            function showToast(message) {
                const toast = document.createElement('div');
                toast.className = 'toast-notification';
                toast.textContent = message;
                toast.style.cssText = `
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: #7baa23;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 8px;
                    font-weight: 600;
                    z-index: 1000;
                    animation: slideIn 0.3s ease;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Add animation styles
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);

            // Initialize AOS if available
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 100
                });
            }
        });
    </script>
@endsection
