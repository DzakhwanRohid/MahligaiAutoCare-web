@extends('layouts.dashboard')

@section('title', 'Kasir (POS) - Transaksi Baru')

{{-- 1. Perintahkan halaman ini untuk memuat pos.css --}}
@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
@endpush

@section('content')
    {{-- 2. Bungkus formulir dengan class .pos-wrapper --}}
    <div class="pos-wrapper">
        @include('kasir.pos.form')
    </div>
@endsection
