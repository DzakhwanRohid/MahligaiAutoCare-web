@extends('layouts.dashboard')

@section('title', 'Kasir (POS) - Transaksi Baru')

@section('content')
    {{--
       PENTING:
       Halaman ini bertugas menampilkan FORMULIR TRANSAKSI BARU.
       Kita memanggil file 'form.blade.php' yang sudah kita siapkan.

       Data $services dan $customers sudah dikirim oleh POSController.
    --}}
    @include('kasir.pos.form')
@endsection
