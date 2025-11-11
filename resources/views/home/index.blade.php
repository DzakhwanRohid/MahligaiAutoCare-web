@extends('layouts.main')

@section('content')
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4" data-aos="fade-down">Kilau Sempurna, Performa Maksimal</h1>
            <p class="lead mb-5" data-aos="fade-down" data-aos-delay="200">Percayakan perawatan kendaraan Anda pada ahlinya.
                Standar baru dalam dunia auto detailing di Pekanbaru.</p>
            <a href="{{ route('layanan') }}" class="btn btn-cta rounded-pill text-white py-3 px-5 fs-5 animated-cta"
                data-aos="fade-up" data-aos-delay="400">Lihat Layanan Kami</a>
        </div>
    </div>

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

                <a href="{{ route('tentang.kami') }}" class="btn btn-outline-success rounded-pill py-2 px-4 mt-5">Pelajari
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
@endsection
