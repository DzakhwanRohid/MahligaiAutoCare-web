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
                <a href="{{ route('home') }}"><img src="{{ asset('img/logo_project.png') }}" alt="Logo" class="logo"></a>
                <h5 class="mt-2">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Kasir' }}</h5>
            </div>

            <ul class="sidebar-menu">
                {{-- 1. Dashboard Utama --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                </li>

                {{-- 2. KASIR (POS) - MENU TERPISAH (BARU) --}}
                @if(in_array(Auth::user()->role, ['admin', 'kasir']))
                    <li>
                        <a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.*') ? 'active' : '' }}">
                            <i class="fa fa-cash-register"></i> <span>Kasir (POS)</span>
                        </a>
                    </li>
                @endif

                {{-- 3. Manajemen Operasional (Sisa Menu) --}}
                @if(in_array(Auth::user()->role, ['admin', 'kasir']))
                    @php
                        // Hapus 'pos.*' dari sini karena sudah dipisah
                        $isOperasionalActive = request()->routeIs('transaksi.antrean') ||
                                               request()->routeIs('transaksi.riwayat');
                    @endphp
                    <li>
                        <a href="#menuOperasional" data-bs-toggle="collapse" role="button"
                           aria-expanded="{{ $isOperasionalActive ? 'true' : 'false' }}"
                           class="{{ $isOperasionalActive ? 'active' : '' }}">
                            <i class="fa fa-tasks"></i> <span>Manajemen Operasional</span>
                            <i class="fa fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="submenu collapse {{ $isOperasionalActive ? 'show' : '' }}" id="menuOperasional">
                            {{-- Link POS dihapus dari sini --}}
                            <li><a href="{{ route('transaksi.antrean') }}" class="{{ request()->routeIs('transaksi.antrean') ? 'active-sub' : '' }}">Antrean Real-time</a></li>
                            <li><a href="{{ route('transaksi.riwayat') }}" class="{{ request()->routeIs('transaksi.riwayat') ? 'active-sub' : '' }}">Riwayat Transaksi</a></li>
                        </ul>
                    </li>
                @endif

                {{-- 4. Manajemen Data (Hanya Admin) --}}
                @if(Auth::user()->role == 'admin')
                    @php
                        $isDataActive = request()->routeIs('admin.layanan.*') ||
                                        request()->routeIs('admin.promosi.*');
                    @endphp
                    <li>
                        <a href="#menuData" data-bs-toggle="collapse" role="button"
                           aria-expanded="{{ $isDataActive ? 'true' : 'false' }}"
                           class="{{ $isDataActive ? 'active' : '' }}">
                            <i class="fa fa-database"></i> <span>Manajemen Data</span>
                            <i class="fa fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="submenu collapse {{ $isDataActive ? 'show' : '' }}" id="menuData">
                            <li><a href="{{ route('admin.layanan.index') }}" class="{{ request()->routeIs('admin.layanan.*') ? 'active-sub' : '' }}">Layanan & Harga</a></li>
                            <li><a href="{{ route('admin.promosi.index') }}" class="{{ request()->routeIs('admin.promosi.*') ? 'active-sub' : '' }}">Diskon & Promosi</a></li>
                        </ul>
                    </li>
                @endif

                {{-- 5. Manajemen Pengguna (Hanya Admin) --}}
                @if(Auth::user()->role == 'admin')
                    @php
                        $isPenggunaActive = request()->routeIs('admin.users.*') ||
                                            request()->routeIs('admin.customer.*');
                    @endphp
                    <li>
                        <a href="#menuPengguna" data-bs-toggle="collapse" role="button"
                           aria-expanded="{{ $isPenggunaActive ? 'true' : 'false' }}"
                           class="{{ $isPenggunaActive ? 'active' : '' }}">
                            <i class="fa fa-users"></i> <span>Manajemen Pengguna</span>
                            <i class="fa fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="submenu collapse {{ $isPenggunaActive ? 'show' : '' }}" id="menuPengguna">
                            <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active-sub' : '' }}">Manajemen Akun</a></li>
                            <li><a href="{{ route('admin.customer.index') }}" class="{{ request()->routeIs('admin.customer.*') ? 'active-sub' : '' }}">Data Pelanggan</a></li>
                        </ul>
                    </li>
                @endif

                {{-- 6. Laporan & Analitik (Hanya Admin) --}}
                @if(Auth::user()->role == 'admin')
                    @php
                        $isLaporanActive = request()->routeIs('laporan.*');
                    @endphp
                    <li>
                        <a href="#menuLaporan" data-bs-toggle="collapse" role="button"
                           aria-expanded="{{ $isLaporanActive ? 'true' : 'false' }}"
                           class="{{ $isLaporanActive ? 'active' : '' }}">
                            <i class="fa fa-chart-bar"></i> <span>Laporan & Analitik</span>
                            <i class="fa fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="submenu collapse {{ $isLaporanActive ? 'show' : '' }}" id="menuLaporan">
                            <li><a href="{{ route('laporan.pemesanan') }}" class="{{ request()->routeIs('laporan.pemesanan') ? 'active-sub' : '' }}">Laporan Pemesanan</a></li>
                            <li><a href="{{ route('laporan.pendapatan') }}" class="{{ request()->routeIs('laporan.pendapatan') ? 'active-sub' : '' }}">Laporan Pendapatan</a></li>
                            <li>
                                <a href="{{ route('admin.feedback.index') }}" class="{{ request()->routeIs('admin.feedback.*') ? 'active-sub' : '' }}">
                                    Pesan Pengguna
                              
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- 7. Pengaturan (Hanya Admin) --}}
                @if(Auth::user()->role == 'admin')
                    <li>
                        <a href="{{ route('pengaturan.index') }}" class="{{ request()->routeIs('pengaturan.index') ? 'active' : '' }}">
                            <i class="fa fa-cogs"></i> <span>Pengaturan</span>
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
                        <li><hr class="dropdown-divider"></li>
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
