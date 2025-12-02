<x-guest-layout>
    {{-- Container Scrollable --}}
    <div style="max-height: 80vh; overflow-y: auto; padding-right: 10px;">

        <h2 class="text-center mb-4 font-weight-bold">Buat Akun Baru</h2>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group mb-3">
                <label for="name" class="form-label fw-bold">{{ __('Nama Lengkap') }}</label>
                <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus
                    autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-danger small" />
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label fw-bold">{{ __('Email') }}</label>
                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                    autocomplete="username" placeholder="contoh@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
            </div>

            <div class="form-group mb-3">
                <label for="phone" class="form-label fw-bold">{{ __('No. Handphone (WhatsApp)') }}</label>
                <input id="phone" class="form-control" type="text" name="phone" :value="old('phone')" required
                    placeholder="081234567890" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1 text-danger small" />
            </div>

            {{-- GRID: Data Kendaraan (Bersebelahan) --}}
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="license_plate" class="form-label fw-bold">{{ __('No. Polisi (Opsional)') }}</label>
                    <input id="license_plate" class="form-control text-uppercase" type="text" name="license_plate"
                        :value="old('license_plate')" placeholder="BM 1234 AB" />
                    <x-input-error :messages="$errors->get('license_plate')" class="mt-1 text-danger small" />
                </div>
                <div class="col-md-6">
                    <label for="vehicle_type" class="form-label fw-bold">{{ __('Jenis Mobil (Opsional)') }}</label>
                    <input id="vehicle_type" class="form-control" type="text" name="vehicle_type"
                        :value="old('vehicle_type')" placeholder="Cth: Avanza Hitam" />
                    <x-input-error :messages="$errors->get('vehicle_type')" class="mt-1 text-danger small" />
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label fw-bold">{{ __('Password') }}</label>
                <input id="password" class="form-control" type="password" name="password" required
                    autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
            </div>

            <div class="form-group mb-4">
                <label for="password_confirmation" class="form-label fw-bold">{{ __('Konfirmasi Password') }}</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation"
                    required autocomplete="new-password" placeholder="Ulangi password Anda" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-danger small" />
            </div>

            <div class="form-group text-center d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    {{ __('Daftar Sekarang') }}
                </button>
            </div>

            <div class="form-group text-center mt-3">
                <a href="{{ route('google.login') }}"
                   class="btn"
                   style="width: 100%; 
                          display: flex; 
                          justify-content: center; 
                          align-items: center; 
                          background-color: white; 
                          border: 1px solid #ccc; 
                          border-radius: 4px; 
                          padding: 8px 0; 
                          text-decoration: none; 
                          color: #333;">
                    
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" 
                         width="20" height="20" 
                         alt="Google Logo" 
                         style="margin-right: 10px;">
                    
                    <span style="font-weight: 600;">Daftar dengan Google</span>
                </a>
            </div>

            <p class="text-center mt-3 mb-0">
                Sudah punya akun?
                <a class="text-primary fw-bold text-decoration-none" href="{{ route('login') }}">
                    {{ __('Login di sini') }}
                </a>
            </p>
        </form>
    </div>


    {{-- Style Tambahan untuk Scrollbar Cantik --}}
    <style>
        /* Kustomisasi Scrollbar agar tidak kaku */
        div::-webkit-scrollbar {
            width: 8px;
        }

        div::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        div::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        div::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</x-guest-layout>