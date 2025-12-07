@extends('layouts.main')
@section('content')

<!-- HEADER SECTION - Konsisten dengan warna hijau -->
<section class="contact-header-section">
    <div class="header-overlay"></div>
    <div class="container">
        <div class="header-content">
            <h1 class="contact-header-title">
                <span class="contact-title-line">Hubungi Kami</span>
                <span class="contact-title-highlight">Mahligai AutoCare</span>
            </h1>
            <div class="contact-divider">
                <div class="contact-divider-line"></div>
                <div class="contact-divider-icon">◈</div>
                <div class="contact-divider-line"></div>
            </div>
            <p class="contact-header-subtitle">Dapatkan Konsultasi Gratis Hari Ini!</p>
        </div>
    </div>
    <div class="contact-wave">
        <svg viewBox="0 0 1200 30" preserveAspectRatio="none">
            <path d="M0,0V15c150,10,300,10,450,5S750,5,900,10s300,10,450,5V0Z" fill="#fff"/>
        </svg>
    </div>
</section>

{{-- FORMULIR DAN INFO KONTAK --}}
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
                    <h3><i class="fas fa-map-marker-alt me-2"></i>Lokasi Kami</h3>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/kontak.js') }}"></script>
@endpush

