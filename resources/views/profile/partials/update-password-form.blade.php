<div class="card h-100 border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-3">
        <h5 class="card-title mb-0 fw-bold text-primary"><i class="fa fa-key me-2"></i>{{ __('Perbarui Password') }}</h5>
    </div>
    <div class="card-body p-4">
        {{-- LOGIKA PESAN: Beda pesan untuk Google User & User Biasa --}}
        <p class="card-text mb-4 text-muted small">
            @if(Auth::user()->google_id)
                <i class="fab fa-google text-danger me-1"></i> 
                {{ __('Karena Anda login via Google, silakan langsung buat password baru di bawah ini agar Anda bisa login manual (tanpa Google) kedepannya.') }}
            @else
                {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
            @endif
        </p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            {{-- LOGIKA: Sembunyikan 'Password Saat Ini' jika user Google --}}
            @if(!Auth::user()->google_id)
            <div class="mb-3">
                <label for="update_password_current_password" class="form-label fw-semibold text-secondary small text-uppercase">{{ __('Password Saat Ini') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-lock text-muted"></i></span>
                    <input id="update_password_current_password" name="current_password" type="password" class="form-control border-start-0 @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                    
                    {{-- Tombol Mata --}}
                    <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('update_password_current_password', this)" style="border-color: #ced4da;">
                        <i class="fa fa-eye-slash"></i>
                    </button>

                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif

            <div class="mb-3">
                <label for="update_password_password" class="form-label fw-semibold text-secondary small text-uppercase">{{ __('Password Baru') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-key text-muted"></i></span>
                    <input id="update_password_password" name="password" type="password" class="form-control border-start-0 @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    
                    {{-- Tombol Mata --}}
                    <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('update_password_password', this)" style="border-color: #ced4da;">
                        <i class="fa fa-eye-slash"></i>
                    </button>

                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="update_password_password_confirmation" class="form-label fw-semibold text-secondary small text-uppercase">{{ __('Konfirmasi Password') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-check-double text-muted"></i></span>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control border-start-0 @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    
                    {{-- Tombol Mata --}}
                    <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('update_password_password_confirmation', this)" style="border-color: #ced4da;">
                        <i class="fa fa-eye-slash"></i>
                    </button>

                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
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

{{-- Script Javascript untuk Fitur Mata (Show/Hide) --}}
<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>