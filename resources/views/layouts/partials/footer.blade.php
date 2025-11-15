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
                <h6 class="text-light">Senin - Jumat:</h6>
                <p class="mb-4">09.00 - 21.00 WIB</p>
                <h6 class="text-light">Sabtu - Minggu:</h6>
                <p class="mb-0">09.00 - 20.00 WIB</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Layanan</h4>
                {{-- Kita tambahkan satu link dinamis ke halaman semua layanan --}}
                <a class="btn btn-link" href="{{ route('home.layanan') }}">Lihat Semua Layanan</a>
                <a class="btn btn-link" href="#">Cuci Mobil Premium</a>
                <a class="btn btn-link" href="#">Detailing Interior</a>
                <a class="btn btn-link" href="#">Nano Ceramic Coating</a>
                <a class="btn btn-link" href="#">Restorasi Lampu</a>
                <a class="btn btn-link" href="#">Pembersihan Mesin</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Newsletter</h4>
                <p>Dapatkan update dan promo terbaru dari kami.</p>
                <div class="position-relative mx-auto" style="max-width: 400px;">
                    <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Email Anda">
                    <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Daftar</button>
                </div>
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
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
