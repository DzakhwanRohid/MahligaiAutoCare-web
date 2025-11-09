<div class="topbar container-fluid bg-dark text-light px-4">
    <div class="container d-flex justify-content-between">
        <div class="social-icons d-none d-md-flex align-items-center">
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i
                    class="fab fa-twitter"></i></a>
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i
                    class="fab fa-facebook-f"></i></a>
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i
                    class="fab fa-linkedin-in"></i></a>
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i
                    class="fab fa-instagram"></i></a>
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href="#"><i
                    class="fab fa-youtube"></i></a>
        </div>

        <div class="auth-links d-flex align-items-center">
            @guest
                <a href="{{ route('login') }}" class="text-light me-3">Login</a>
                <a href="{{ route('register') }}" class="text-light">Register</a>
            @else
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-light me-3">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                @elseif (Auth::user()->role == 'kasir')
                    <a href="{{ route('kasir.dashboard') }}" class="text-light me-3">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-light me-3">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="text-light"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </a>
                </form>
            @endguest
        </div>
    </div>
</div>