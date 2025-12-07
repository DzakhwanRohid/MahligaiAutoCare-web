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
                <p class="brand-subtitle">Reset Password</p>
            </div>

            <div class="brand-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Reset Cepat</strong>
                        <span>Link reset dikirim dalam 1 menit</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Keamanan Terjamin</strong>
                        <span>Proses reset yang aman</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Support 24/7</strong>
                        <span>Tim support siap membantu</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE - Form Section --}}
        <div class="auth-form-side">
            <div class="form-container">
                <div class="card-header">
                    <h2 class="card-title">Forgot Password</h2>
                    <p class="card-subtitle">Masukkan email untuk reset password</p>
                </div>

                <div class="mb-4" style="color: rgba(255, 255, 255, 0.8); font-size: 14px; line-height: 1.5;">
                    Lupa password Anda? Tidak masalah. Berikan alamat email Anda dan kami akan mengirimkan link reset password kepada Anda.
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert"
                         style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 12px; border-radius: 8px; border: 1px solid rgba(46, 204, 113, 0.3); margin-bottom: 20px; font-size: 14px;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                    @csrf

                    {{-- Email Input --}}
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email address"
                                   required
                                   autofocus>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="error-message" />
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="auth-submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        Send Reset Link
                    </button>

                    {{-- Back to Login --}}
                    <p class="auth-link mt-4">
                        <a href="{{ route('login') }}">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Login
                        </a>
                    </p>
                </form>

                {{-- Footer --}}
                <div class="auth-footer">
                    <p class="help-link">
                        Kembali Ke <a href="/">Beranda</a>
                    </p>


                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
