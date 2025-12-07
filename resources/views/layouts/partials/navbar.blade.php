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
            <a href="{{ route('home.pantau') }}" class="btn btn-pantau rounded-pill text-white py-3 px-4 d-none d-lg-block">
                <i class="fas fa-eye me-2"></i>Pantau Antrian
            </a>
        </div>
    </div>
</nav>

<style>
/* === NAVBAR STYLING === */
.navbar {
    background: var(--white, #ffffff) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    padding: 12px 0;
}

.navbar-brand .logo {
    height: 65px;
    width: auto;
    transition: transform 0.3s ease;
}

.navbar-brand:hover .logo {
    transform: scale(1.05);
}

/* === NAVBAR MENU ITEMS === */
.navbar-nav {
    gap: 8px;
}

.nav-item .nav-link {
    font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
    font-size: 1.1rem; /* Ukuran diperbesar */
    font-weight: 600; /* Lebih tebal */
    color: var(--gray-800, #2d3436) !important;
    padding: 10px 18px !important;
    margin: 0 4px;
    letter-spacing: 0.3px;
    border-radius: 30px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-item .nav-link:hover {
    color: var(--primary-color, #7baa23) !important;
    background-color: rgba(123, 170, 35, 0.08);
    transform: translateY(-2px);
}

.nav-item .nav-link.active {
    color: var(--white, #ffffff) !important;
    background: linear-gradient(135deg, var(--primary-color, #7baa23) 0%, var(--primary-dark, #5d7e2c) 100%);
    box-shadow: 0 4px 12px rgba(123, 170, 35, 0.3);
}

.nav-item .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 20px;
    height: 3px;
    background: var(--primary-color, #7baa23);
    border-radius: 2px;
}

/* === PANTAU ANTRIAN BUTTON === */
.btn-pantau {
    background: linear-gradient(135deg, var(--primary-color, #7baa23) 0%, var(--primary-dark, #5d7e2c) 100%);
    border: none;
    font-weight: 700;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    box-shadow: 0 6px 20px rgba(123, 170, 35, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-width: 180px;
    text-align: center;
}

.btn-pantau:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(123, 170, 35, 0.4);
    color: var(--white, #ffffff);
}

.btn-pantau:active {
    transform: translateY(-1px);
}

.btn-pantau::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s ease;
}

.btn-pantau:hover::before {
    left: 100%;
}

.btn-pantau i {
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.btn-pantau:hover i {
    transform: translateX(3px);
}

/* === NAVBAR TOGGLER === */
.navbar-toggler {
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    background-color: rgba(123, 170, 35, 0.1);
    transition: all 0.3s ease;
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 3px rgba(123, 170, 35, 0.3);
}

.navbar-toggler .fa-bars {
    color: var(--primary-color, #7baa23);
    font-size: 1.5rem;
}

/* === RESPONSIVE DESIGN === */
@media (max-width: 992px) {
    .navbar-nav {
        text-align: center;
        padding: 15px 0;
        gap: 5px;
    }

    .nav-item .nav-link {
        padding: 12px 20px !important;
        margin: 3px 0;
        border-radius: 10px;
    }

    .nav-item .nav-link.active::after {
        display: none;
    }

    .btn-pantau {
        margin-top: 15px;
        width: 100%;
        max-width: 250px;
        margin-left: auto;
        margin-right: auto;
        display: block !important;
    }
}

@media (max-width: 768px) {
    .navbar {
        padding: 10px 0;
    }

    .navbar-brand .logo {
        height: 55px;
    }

    .nav-item .nav-link {
        font-size: 1.05rem;
        padding: 10px 16px !important;
    }
}

@media (max-width: 480px) {
    .navbar-brand .logo {
        height: 50px;
    }

    .btn-pantau {
        padding: 12px 20px;
        font-size: 1rem;
    }
}

/* === STICKY NAVBAR EFFECT === */
.navbar.sticky-top.scrolled {
    padding: 8px 0;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95) !important;
}

/* Animation for active state */
@keyframes pulse {
    0% {
        box-shadow: 0 4px 12px rgba(123, 170, 35, 0.3);
    }
    50% {
        box-shadow: 0 4px 20px rgba(123, 170, 35, 0.5);
    }
    100% {
        box-shadow: 0 4px 12px rgba(123, 170, 35, 0.3);
    }
}

.nav-item .nav-link.active {
    animation: pulse 2s infinite;
}
</style>

<script>
// Add scroll effect to navbar
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});
</script>
