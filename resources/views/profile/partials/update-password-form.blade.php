<div class="card h-100">
    <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title mb-0 fw-bold text-primary"><i class="fa fa-key me-2"></i>{{ __('Perbarui Password') }}</h5>
    </div>
    <div class="card-body">
        <p class="card-text mb-4 text-muted small">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label fw-semibold text-secondary">{{ __('Password Saat Ini') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa fa-lock"></i></span>
                    <input id="update_password_current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label fw-semibold text-secondary">{{ __('Password Baru') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa fa-key"></i></span>
                    <input id="update_password_password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="update_password_password_confirmation" class="form-label fw-semibold text-secondary">{{ __('Konfirmasi Password') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa fa-check-double"></i></span>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-save me-1"></i> {{ __('Simpan Password') }}
                </button>

                @if (session('status') === 'password-updated')
                    <span class="text-success small fw-bold animate__animated animate__fadeIn">
                        <i class="fa fa-check-circle me-1"></i> {{ __('Berhasil disimpan.') }}
                    </span>
                @endif
            </div>
        </form>
    </div>
</div>