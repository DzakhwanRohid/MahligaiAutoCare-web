@extends('layouts.main')

@section('content')

<!-- HEADER SECTION - Dark Gradient -->
<section class="page-header-about">
    <div class="header-gradient"></div>

    <div class="container">
        <div class="header-content-inner">
            <h1 class="main-title">
                <span class="title-line title-line-outline">Tentang Kami</span>
                <span class="title-line title-line-white">Mahligai</span>
                <span class="title-line title-line-green">AutoCare</span>
            </h1>

            <div class="title-separator">
                <span class="separator-line"></span>
                <span class="separator-icon">◈</span>
                <span class="separator-line"></span>
            </div>

            <p class="subtitle">
                Dedikasi kami dalam perawatan mobil profesional di Pekanbaru
            </p>
        </div>
    </div>

    <div class="wave-divider">
        <svg viewBox="0 0 1200 30" preserveAspectRatio="none">
            <path d="M0,0V15c150,10,300,10,450,5S750,5,900,10s300,10,450,5V0Z" fill="#fff"/>
        </svg>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<div class="about-container">
    <!-- Tentang Kami Section - Split Layout -->
    <section class="about-section">
        <div class="container">
            <div class="about-content-wrapper">
                <div class="about-text" data-aos="fade-right">
                    <div class="section-label">
                        <i class="fas fa-star"></i>
                        <span>Tentang Kami</span>
                    </div>
                    <h2 class="section-title">Mahligai <span class="text-gradient">AutoCare</span></h2>
<p class="about-description">
    Lebih dari sekadar tempat cuci mobil, kami adalah mitra terpercaya dalam seni merawat kendaraan Anda.
    Di Mahligai AutoCare, setiap kendaraan memiliki potensi untuk tampil memukau dengan kilau sempurna.
</p>
<p class="about-description">
    Dengan pengalaman bertahun-tahun dan tim profesional, kami menghadirkan perawatan premium yang
    menjaga performa dan keindahan kendaraan Anda. Setiap detail diperhatikan dengan cermat.
</p>
<p class="about-description">
    Kepercayaan Anda menjadi prioritas utama. Mari bersama-sama menjaga kendaraan Anda tetap
    prima dan elegan setiap saat dengan layanan komprehensif dari Mahligai AutoCare.
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
                </div>

                <div class="about-image" data-aos="fade-left">
                    <img src="{{ asset('img/tentang_index.png') }}" alt="Proses Detailing Mobil" class="about-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section - Green Theme -->
    <section class="why-choose-section">
        <div class="section-header-center">
            <div class="section-label-green">Keunggulan</div>
            <h2 class="section-title-green">Mengapa Memilih Kami</h2>
            <p class="section-subtitle-green">Layanan yang membedakan kami dari yang lain</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon-wrapper">
                    <div class="service-icon-circle">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <h3 class="service-title">Tim Ahli</h3>
                <p class="service-description">Detailer bersertifikat dengan pengalaman bertahun-tahun.</p>
            </div>

            <div class="service-card">
                <div class="service-icon-wrapper">
                    <div class="service-icon-circle">
                        <i class="fas fa-spray-can"></i>
                    </div>
                </div>
                <h3 class="service-title">Teknologi Modern</h3>
                <p class="service-description">Peralatan canggih dan produk premium untuk hasil optimal.</p>
            </div>

            <div class="service-card">
                <div class="service-icon-wrapper">
                    <div class="service-icon-circle">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <h3 class="service-title">Garansi Kualitas</h3>
                <p class="service-description">Jaminan kepuasan 100% untuk setiap layanan.</p>
            </div>
        </div>
    </section>
</div>


@endsection
