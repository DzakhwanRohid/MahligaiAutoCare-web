@extends('layouts.main')

{{-- Menambahkan CSS khusus di <head> --}}
@push('styles')
    <link href="{{ asset('css/kontak.css') }}" rel="stylesheet">
@endpush

@section('content')

{{-- HEADER KONTAK --}}
<div class="page-header text-center">
    <div class="container">
        <h1 class="display-4">Hubungi Kami</h1>
        <p class="lead">Kami siap mendengar pertanyaan, kritik, dan saran dari Anda.</p>
    </div>
</div>

{{-- FORMULIR DAN INFO KONTAK --}}
<div class="container home-section">
    <div class="row g-5">

        {{-- KIRI: INFO KONTAK (DINAMIS DARI $appSettings) --}}
        <div class="col-lg-5" data-aos="fade-right">
            <div class="contact-info-box shadow-sm">
                <h4 class="mb-4">Informasi Kontak</h4>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h6 class="mb-0">Alamat:</h6>
                        <p class="text-muted mb-0">{{ $appSettings['business_address'] }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <h6 class="mb-0">Telepon:</h6>
                        <p class="text-muted mb-0">{{ $appSettings['business_phone'] }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h6 class="mb-0">Email:</h6>
                        <p class="text-muted mb-0">{{ $appSettings['business_email'] }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <h6 class="mb-0">Jam Operasional:</h6>
                        <p class="text-muted mb-0">{{ $appSettings['business_hours'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: FORMULIR KIRIM PESAN (DINAMIS) --}}
        <div class="col-lg-7" data-aos="fade-left">
            <div class="contact-form shadow-sm">
                <h4 class="mb-4">Kirim Pesan</h4>

                {{-- Tampilkan Pesan Sukses --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                {{-- Tampilkan Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORMULIR YANG SUDAH TERHUBUNG --}}
                <form action="{{ route('kontak.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">

                        {{-- Logika Cek Login untuk Auto-fill --}}
                        @auth
                        {{-- Jika SUDAH LOGIN --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" readonly>
                                <label for="name">Nama Anda (Terdaftar)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                                <label for="email">Email Anda (Terdaftar)</label>
                            </div>
                        </div>
                        @else
                        {{-- Jika TAMU (Guest) --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nama Anda" required>
                                <label for="name">Nama Anda</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Anda" required>
                                <label for="email">Email Anda</label>
                            </div>
                        </div>
                        @endauth

                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subjek" required>
                                <label for="subject">Subjek</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Tinggalkan pesan di sini" id="message" name="message" style="height: 150px" required></textarea>
                                <label for="message">Pesan</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success rounded-pill w-100 py-3" type="submit">Kirim Pesan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- PETA LOKASI SECTION (Tetap Ada) --}}
<div class="container py-5" data-aos="fade-up">
    <div class="map-responsive shadow-lg">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127870.21133316147!2d101.37893902148117!3d0.5104218357732202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ac1a27cd9011%3A0x2035b1856d11f62!2sPekanbaru%2C%20Pekanbaru%20City%2C%20Riau!5e0!3m2!1sen!2sid!4v1668668615397!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>
@endsection

{{-- Memuat JS khusus di bagian bawah body --}}
@push('scripts')
    <script src="{{ asset('js/kontak.js') }}"></script>
@endpush
