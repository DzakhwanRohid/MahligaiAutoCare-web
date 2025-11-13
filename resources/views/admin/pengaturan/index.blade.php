@extends('layouts.dashboard')

@section('title', 'Pengaturan Usaha')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pengaturan.update') }}">
                        @csrf

                        <h5 class="mb-3">Profil Usaha</h5>

                        <div class="mb-3">
                            <label for="business_name" class="form-label">Nama Usaha</label>
                            <input type="text" class="form-control" id="business_name" name="business_name"
                                   value="{{ old('business_name', $settings['business_name'] ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="business_address" class="form-label">Alamat Usaha</label>
                            <textarea class="form-control" id="business_address" name="business_address" rows="3">{{ old('business_address', $settings['business_address'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="business_phone" class="form-label">No. HP (WhatsApp)</label>
                            <input type="text" class="form-control" id="business_phone" name="business_phone"
                                   value="{{ old('business_phone', $settings['business_phone'] ?? '') }}"
                                   placeholder="Contoh: 628123456789">
                        </div>

                        <hr>
                        <h5 class="mb-3">Integrasi (Opsional)</h5>

                        <div class="mb-3">
                            <label for="whatsapp_api_url" class="form-label">WhatsApp Gateway API URL</label>
                            <input type="text" class="form-control" id="whatsapp_api_url" name="whatsapp_api_url"
                                   value="{{ old('whatsapp_api_url', $settings['whatsapp_api_url'] ?? '') }}"
                                   placeholder="Contoh: https://api.whatapp.com/send">
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_api_token" class="form-label">WhatsApp Gateway API Token</label>
                            <input type="text" class="form-control" id="whatsapp_api_token" name="whatsapp_api_token"
                                   value="{{ old('whatsapp_api_token', $settings['whatsapp_api_token'] ?? '') }}"
                                   placeholder="Token rahasia Anda">
                        </div>

                        {{-- Kita akan tambahkan Logo & Struk di langkah selanjutnya --}}

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
