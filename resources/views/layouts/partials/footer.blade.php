<div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Alamat</h4>
                {{-- BAGIAN DINAMIS: Mengambil Alamat dari Pengaturan --}}
                <p class="mb-2">
                    <i class="fa fa-map-marker-alt me-3"></i>
                    {{ $appSettings['business_address'] ?? 'Jl. Jend. Sudirman No. 123, Pekanbaru' }}
                </p>
                {{-- BAGIAN DINAMIS: Mengambil No HP dari Pengaturan --}}
                <p class="mb-2">
                    <i class="fa fa-phone-alt me-3"></i>
                    {{ $appSettings['business_phone'] ?? '+62 812 3456 7890' }}
                </p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@mahligaiautocare.com</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Jam Buka</h4>
                <h6 class="text-light">Setiap Hari:</h6>
                <p class="mb-4">07.00 - 17.00 WIB</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Layanan</h4>
                {{-- Kita tambahkan satu link dinamis ke halaman semua layanan --}}
                <a class="btn btn-link" href="{{ route('home.layanan') }}">Lihat Semua Layanan</a>
            </div>
              <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Kritik & Saran</h4>
                <p class="mb-3">Masukan Anda sangat berharga untuk kami meningkatkan pelayanan.</p>
                <a href="{{ route('home.kontak') }}" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-comment-alt me-2"></i>Kirim Kritik & Saran
                </a>
                <p class="small text-muted mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    Privasi Anda terlindungi
                </p>
                <p class="small text-muted">
                    <i class="fas fa-clock me-2"></i>
                    Respon maksimal 24 jam
                </p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    {{-- BAGIAN DINAMIS: Mengambil Nama Usaha dari Pengaturan --}}
                    &copy; <a class="border-bottom" href="#">{{ $appSettings['business_name'] ?? 'Mahligai AutoCare' }}</a>, All Right Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    Designed By <a class="border-bottom" href="https://www.instagram.com/dzakhwan_rohid?igsh=MTRhMGZtMWN1dTlqdw%3D%3D&utm_source=qr">_DzakhwanRohid</a>
                </div>
            </div>
        </div>
    </div>
</div>
