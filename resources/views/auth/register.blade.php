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
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Booking Online</strong>
                        <span>Pesan slot cuci dengan mudah</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Promo Eksklusif</strong>
                        <span>Diskon khusus member</span>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Riwayat Transaksi</strong>
                        <span>Pantau semua layanan Anda</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE - Form Section --}}
        <div class="auth-form-side">
            <div class="form-container">
                <div class="card-header">
                    <h2 class="card-title">Register</h2>
                    <p class="card-subtitle">Buat akun baru Anda</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="Enter your full name"
                                   required
                                   autofocus>
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="error-message" />
                    </div>

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
                                   required>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="error-message" />
                    </div>

                    {{-- Phone --}}
                    <div class="form-group">
                        <label for="phone" class="form-label">No. Handphone</label>
                        <div class="input-group">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   class="form-control"
                                   value="{{ old('phone') }}"
                                   placeholder="081234567890"
                                   required>
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="error-message" />
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
                                   placeholder="Minimal 8 karakter"
                                   required>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="error-message" />
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Confirm your password"
                                   required>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
                    </div>

                    {{-- Vehicle Info (Optional) --}}
                    <div class="form-group">
                        <label class="form-label">Info Kendaraan <small style="font-weight: normal; opacity: 0.7;">(Opsional)</small></label>
                        <div class="input-group mb-2">
                            <i class="fas fa-car input-icon"></i>
                            <input type="text"
                                   name="license_plate"
                                   class="form-control"
                                   value="{{ old('license_plate') }}"
                                   placeholder="No. Polisi (BM 1234 AB)">
                        </div>
                        <br>
                        <div class="input-group">
                            <i class="fas fa-cog input-icon"></i>
                            <input type="text"
                                   name="vehicle_type"
                                   class="form-control"
                                   value="{{ old('vehicle_type') }}"
                                   placeholder="Jenis Mobil (Avanza Hitam)">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="auth-submit-btn">
                        <i class="fas fa-user-plus"></i>
                        Register Now
                    </button>

                    {{-- Login Link --}}
                    <p class="auth-link mt-4">
                        Already have an account?
                        <a href="{{ route('login') }}">Login here</a>
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
