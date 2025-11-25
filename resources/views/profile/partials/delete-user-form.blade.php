<div class="card border-danger h-100">
    <div class="card-header bg-danger bg-opacity-10 border-danger border-opacity-25">
         <h5 class="card-title text-danger mb-0 fw-bold"><i class="fa fa-exclamation-triangle me-2"></i>{{ __('Hapus Akun') }}</h5>
    </div>
    <div class="card-body">
        <p class="card-text mb-3 text-muted">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
        
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
            <i class="fa fa-trash-alt me-1"></i> {{ __('Hapus Akun Saya') }}
        </button>
    </div>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header bg-danger text-white">
                        <h1 class="modal-title fs-5 fw-bold" id="confirmUserDeletionModalLabel">
                            <i class="fa fa-exclamation-circle me-2"></i>{{ __('Konfirmasi Penghapusan') }}
                        </h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        <p class="text-danger fw-semibold">
                            {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
                        </p>
                        <p class="text-muted small">
                            {{ __('Setelah akun dihapus, semua data akan hilang permanen. Silakan masukkan password Anda untuk konfirmasi.') }}
                        </p>

                        <div class="mt-4">
                            <label for="password_delete" class="form-label fw-semibold">{{ __('Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-key"></i></span>
                                <input id="password_delete" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Masukkan password Anda" autocomplete="current-password">
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash me-1"></i> {{ __('Ya, Hapus Akun') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk membuka modal otomatis jika ada error validasi --}}
@if($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
            modal.show();
        });
    </script>
@endif