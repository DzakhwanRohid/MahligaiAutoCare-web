<x-guest-layout>
    <h2>Lupa Password</h2>

    <div class="mb-4 text-sm" style="color: #666; text-align: left;">
        {{ __('Lupa password Anda? Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan email berisi tautan pengaturan ulang password yang memungkinkan Anda memilih yang baru.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Kirim Link Reset Password') }}
            </button>
        </div>

        <p class="auth-link-register">
            <a class="auth-link" href="{{ route('login') }}">
                {{ __('Kembali ke Login') }}
            </a>
        </p>
    </form>
</x-guest-layout>
