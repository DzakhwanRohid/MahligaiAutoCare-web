<nav class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 shadow-sm sticky-top">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand p-0">
             <img src="{{ asset('img/logo_project.png') }}" alt="Mahligai AutoCare Logo" class="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>

       <div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav mx-auto py-0">
        <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('home.tentang') }}" class="nav-item nav-link {{ request()->routeIs('home.tentang') ? 'active' : '' }}">Tentang</a>
        <a href="{{ route('home.layanan') }}" class="nav-item nav-link {{ request()->routeIs('home.layanan') ? 'active' : '' }}">Layanan</a>
        <a href="{{ route('pemesanan.create') }}" class="nav-item nav-link {{ request()->routeIs('pemesanan.create') ? 'active' : '' }}">Pemesanan</a>
        <a href="{{ route('home.kontak') }}" class="nav-item nav-link {{ request()->routeIs('home.kontak') ? 'active' : '' }}">Kontak</a>
    </div>
     <a href="{{ route('home.pantau') }}" class="btn btn-cta rounded-pill text-white py-2 px-4 d-none d-lg-block">Pantau Antrian</a>
</div>
    </div>
</nav>
