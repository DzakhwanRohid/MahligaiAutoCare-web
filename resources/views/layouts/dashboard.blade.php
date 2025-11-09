<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Mahligai AutoCare</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>

<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('home') }}"><img src="{{ asset('img/logo_project.png') }}" alt="Logo"
                        class="logo"></a>
                <h5 class="mt-2">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</h5>
            </div>
            <ul class="sidebar-menu">
                @if (Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transaksi') }}"
                            class="{{ request()->routeIs('admin.transaksi') ? 'active' : '' }}">
                            <i class="fa fa-receipt"></i> Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan') }}"
                            class="{{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                            <i class="fa fa-chart-bar"></i> Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <i class="fa fa-users"></i> Manajemen User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.layanan.index') }}"
                            class="{{ request()->routeIs('admin.layanan.index') ? 'active' : '' }}">
                            <i class="fa fa-concierge-bell"></i> Manajemen Layanan
                        </a>
                    </li>

                    {{-- MENU UNTUK KASIR --}}
                @elseif(Auth::user()->role === 'kasir')
                    <li>
                        <a href="{{ route('kasir.dashboard') }}"
                            class="{{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                            <i class="fa fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.transaksi') }}"
                            class="{{ request()->routeIs('kasir.transaksi') ? 'active' : '' }}">
                            <i class="fa fa-receipt"></i> Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.laporan') }}"
                            class="{{ request()->routeIs('kasir.laporan') ? 'active' : '' }}">
                            <i class="fa fa-chart-bar"></i> Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.status') }}"
                            class="{{ request()->routeIs('kasir.status') ? 'active' : '' }}">
                            <i class="fa fa-coffee"></i> Status
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.pendaftaran') }}"
                            class="{{ request()->routeIs('kasir.pendaftaran') ? 'active' : '' }}">
                            <i class="fa fa-file"></i> Form Pendaftaran
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.pembayaran.index') }}"
                            class="{{ request()->routeIs('kasir.pembayaran.*') ? 'active' : '' }}">
                            <i class="fa fa-cash-register"></i> Form Pembayaran
                        </a>
                    </li>
                @endif
            </ul>
        </aside>

        <main class="main-content">
            <nav class="top-nav">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>