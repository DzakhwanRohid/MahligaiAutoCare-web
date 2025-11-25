<div class="card h-100">
    <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title mb-0 fw-bold text-primary"><i class="fa fa-user-edit me-2"></i>{{ __('Informasi Profil') }}</h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            
            <div class="col-md-4 text-center border-end mb-4 mb-md-0">
                <div class="mb-3 position-relative d-inline-block">
                    {{-- Menampilkan gambar dari public/img/profile.jpg --}}
                    <img src="{{ asset('img/profile.jpg') }}" 
                         alt="Foto Profil" 
                         class="rounded-circle img-thumbnail shadow-sm" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                {{-- Menampilkan Nama & Role di bawah foto --}}
                <h5 class="fw-bold mb-0 text-dark">{{ $user->name }}</h5>
                <span class="badge bg-secondary mt-2 rounded-pill px-3">{{ ucfirst($user->role) }}</span>
            </div>

            <div class="col-md-8 ps-md-4">
                <p class="card-text mb-4 text-muted small">
                    {{ __("Perbarui informasi profil akun dan alamat email Anda di sini.") }}
                </p>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-secondary">{{ __('Nama') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa fa-user"></i></span>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-secondary">{{ __('Alamat Email') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa fa-envelope"></i></span>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2 alert alert-warning d-flex align-items-center" role="alert">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <div>
                                    {{ __('Email Anda belum terverifikasi.') }}
                                    <button form="send-verification" class="btn btn-link p-0 text-decoration-none fw-bold">
                                        {{ __('Kirim ulang verifikasi.') }}
                                    </button>
                                </div>
                            </div>
                            @if (session('status') === 'verification-link-sent')
                                <div class="mt-2 text-success small">
                                    <i class="fa fa-check-circle me-1"></i> {{ __('Tautan verifikasi baru telah dikirim.') }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-save me-1"></i> {{ __('Simpan Perubahan') }}
                        </button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success small fw-bold animate__animated animate__fadeIn">
                                <i class="fa fa-check-circle me-1"></i> {{ __('Berhasil disimpan.') }}
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>