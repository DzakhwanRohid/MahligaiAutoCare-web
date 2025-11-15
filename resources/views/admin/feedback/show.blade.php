@extends('layouts.dashboard')

@section('title', 'Detail Pesan')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Detail Pesan</h5>
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Kembali ke Inbox
            </a>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h6 class="fw-bold">Pengirim:</h6>
                {{-- Link 'mailto' agar bisa langsung balas email --}}
                <p>{{ $message->name }} (<a href="mailto:{{ $message->email }}">{{ $message->email }}</a>)</p>
            </div>
            <div class="mb-3">
                <h6 class="fw-bold">Tanggal:</h6>
                <p>{{ $message->created_at->format('d M Y, H:i') }}</Hh6>
            </div>
            <div class="mb-3">
                <h6 class="fw-bold">Subjek:</h6>
                <p>{{ $message->subject }}</p>
            </div>
            <hr>
            <div class="mb-3">
                <h6 class="fw-bold">Isi Pesan:</h6>
                {{-- 'white-space: pre-wrap' agar format paragraf/enter tetap rapi --}}
                <div class="p-3 bg-light rounded" style="min-height: 150px; white-space: pre-wrap;">
                    {{ $message->message }}
                </div>
            </div>

            <hr>
            <form action="{{ route('admin.feedback.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pesan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-trash me-1"></i> Hapus Pesan Ini
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
