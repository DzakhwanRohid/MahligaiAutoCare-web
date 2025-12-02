<x-guest-layout>
    <h2>Login Akun</h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Container Scroll --}}
    <div style="max-height: 500px; overflow-y: auto; padding-right: 5px; -webkit-overflow-scrolling: touch;">

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            {{-- Input Email --}}
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Input Password --}}
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" class="form-control" type="password" name="password" required
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me & Lupa Password --}}
            <div class="form-group d-flex justify-content-between align-items-center">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                    <label class="custom-control-label" for="remember_me">
                        {{ __('Remember me') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>

            {{-- BAGIAN TOMBOL YANG DIPERBAIKI (FORCE FULL WIDTH) --}}
            <div class="form-group text-center">
                
                {{-- 1. Tombol Login --}}
                {{-- Ditambah style width: 100% agar dijamin lebar penuh --}}
                <button type="submit" class="btn btn-primary" style="width: 100%; display: block; margin-bottom: 15px;">
                    {{ __('Log in') }}
                </button>

                {{-- 2. Tombol Google --}}
                {{-- Style width: 100% dan display: flex untuk meratakan tengah --}}
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
                    
                    <span style="font-weight: 600;">Masuk dengan Google</span>
                </a>

            </div>

            @if (Route::has('register'))
                <p class="auth-link-register mt-4 text-center">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                </p>
            @endif
        </form>

    </div>
</x-guest-layout>