@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    
    @php
        // Ambil halaman aktif dari URL, default-nya 'info'
        $page = request()->query('page', 'info');
    @endphp

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            @if (session('status') === 'profile-updated')
                Informasi profil berhasil diperbarui.
            @elseif (session('status') === 'password-updated')
                Password berhasil diperbarui.
            @else
                {{ session('status') }}
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row gy-4">

        {{-- ================================================== --}}
        {{-- BAGIAN 1: INFORMASI PROFIL --}}
        {{-- ================================================== --}}
        @if ($page == 'info')
        <div class="col-lg-8">
            {{-- Pastikan file partial ini sudah dikonversi ke Bootstrap 5 --}}
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- ================================================== --}}
        {{-- BAGIAN 2: UPDATE PASSWORD --}}
        {{-- ================================================== --}}
        @elseif ($page == 'password')
        <div class="col-lg-8">
            {{-- Pastikan file partial ini sudah dikonversi ke Bootstrap 5 --}}
            @include('profile.partials.update-password-form')
        </div>

        {{-- ================================================== --}}
        {{-- BAGIAN 3: HAPUS AKUN (Untuk SEMUA Role) --}}
        {{-- ================================================== --}}
        @elseif ($page == 'hapus')
        <div class="col-lg-8">
            {{-- Pastikan file partial ini sudah dikonversi ke Bootstrap 5 --}}
            @include('profile.partials.delete-user-form')
        </div>
        
        {{-- Fallback jika halaman tidak ditemukan --}}
        @else
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                Halaman tidak ditemukan atau Anda tidak memiliki izin untuk melihat ini.
            </div>
        </div>
        @endif

    </div>
</div>
@endsection