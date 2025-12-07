<x-guest-layout>
    <div class="auth-glass-card">
        {{-- LEFT SIDE - Brand Section --}}
        <div class="auth-brand-side">
            <div class="brand-pattern"></div>

            <div class="logo-container">
                <div class="logo">
    <img src="{{ asset('img/logo_project.png') }}" alt="Mahligai AutoCare Logo" class="logo">
</div>
                <h1 class="brand-title">Mahligai AutoCare</h1>
                <p class="brand-subtitle">Professional Car Wash & Detailing</p>
            </div>

            <div class="brand-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Garansi Kepuasan</strong>
                        <span>100% garansi kualitas layanan</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Buka Setiap Hari</strong>
                        <span>07:00 - 17:00 WIB</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Rating 4.9/5.0</strong>
                        <span>Dari 1000+ pelanggan puas</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE - Form Section --}}
        <div class="auth-form-side">
            <div class="form-container">
                <div class="card-header">
                    <h2 class="card-title">Login</h2>
                    <p class="card-subtitle">Masuk ke akun Anda</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email"
                                   required
                                   autofocus>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="error-message" />
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Enter your password"
                                   required>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="error-message" />
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" id="remember_me" name="remember">
                            <span class="checkbox-label">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="auth-submit-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Log in
                    </button>

                    {{-- Divider --}}
                    <div class="divider">
                        <span class="divider-text">atau</span>
                    </div>

                    {{-- Google Login --}}
                    <a href="{{ route('google.login') }}" class="google-btn">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
                        Masuk dengan Google
                    </a>

                    {{-- Register Link --}}
                    @if (Route::has('register'))
                        <p class="auth-link">
                            Don't have an account?
                            <a href="{{ route('register') }}">Register Now</a>
                        </p>
                    @endif
                </form>

                {{-- Footer --}}
                <div class="auth-footer">
                    <p class="terms-link">
                        Dengan login, Anda menyetujui <a href="#">Syarat & Ketentuan</a> kami
                    </p>

                    <p class="help-link">
                        Kembali Ke <a href="/">Beranda</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
