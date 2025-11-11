@extends('layouts.main')

@section('content')

{{-- HEADER KONTAK - Tanpa BG --}}
<div class="page-header text-center">
    <div class="container">
        <h1 class="display-4">Hubungi Kami</h1>
        {{-- Hapus class text-white-50 --}}
        <p class="lead">Kami siap mendengar pertanyaan, kritik, dan saran dari Anda.</p>
    </div>
</div>

{{-- FORMULIR DAN INFO KONTAK --}}
<div class="container home-section">
    <div class="row g-5">
        <div class="col-lg-5" data-aos="fade-right">
            <div class="contact-info-box">
                <h4 class="mb-4" style="color: var(--primary-green);">Informasi Kontak</h4>
                <div class="info-item"><div class="info-icon"><i class="fas fa-map-marker-alt"></i></div><div><h6 class="mb-0">Alamat:</h6><p class="text-muted mb-0">Jl. Jend. Sudirman No. 123, Pekanbaru, Riau</p></div></div>
                <div class="info-item"><div class="info-icon"><i class="fas fa-phone-alt"></i></div><div><h6 class="mb-0">Telepon:</h6><p class="text-muted mb-0">+62 812 3456 7890</p></div></div>
                <div class="info-item"><div class="info-icon"><i class="fas fa-envelope"></i></div><div><h6 class="mb-0">Email:</h6><p class="text-muted mb-0">info@mahligaiautocare.com</p></div></div>
                <div class="info-item"><div class="info-icon"><i class="fas fa-clock"></i></div><div><h6 class="mb-0">Jam Operasional:</h6><p class="text-muted mb-0">Senin - Minggu: 08:00 - 20:00 WIB</p></div></div>
            </div>
        </div>

        <div class="col-lg-7" data-aos="fade-left">
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

{{-- PETA LOKASI SECTION --}}
<div class="container py-5" data-aos="fade-up">
    <div class="map-responsive">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127870.21133316147!2d101.37893902148117!3d0.5104218357732202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ac1a27cd9011%3A0x2035b1856d11f62!2sPekanbaru%2C%20Pekanbaru%20City%2C%20Riau!5e0!3m2!1sen!2sid!4v1668668615397!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no--downgrade"></iframe>
    </div>
</div>
@endsection 
