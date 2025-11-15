@extends('layouts.dashboard')

@section('title', 'Pesan & Feedback Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">@yield('title')</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">Status</th>
                            <th>Nama</th>
                            <th>Subjek Pesan</th>
                            <th>Tanggal Masuk</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $msg)
                        {{-- Baris akan tebal jika belum dibaca --}}
                        <tr class="{{ !$msg->is_read ? 'fw-bold' : 'text-muted' }}">
                            <td class="text-center">
                                @if(!$msg->is_read)
                                    <i class="fa fa-envelope text-success" title="Belum Dibaca"></i>
                                @else
                                    <i class="fa fa-envelope-open" title="Sudah Dibaca"></i>
                                @endif
                            </td>
                            <td>{{ $msg->name }}</td>
                            <td>
                                {{-- Link ke halaman 'show' --}}
                                <a href="{{ route('admin.feedback.show', $msg->id) }}">{{ $msg->subject }}</a>
                            </td>
                            <td>{{ $msg->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.feedback.show', $msg->id) }}" class="btn btn-sm btn-info" title="Baca Pesan">
                                    <i class="fa fa-eye"></i> Baca
                                </a>
                                <form action="{{ route('admin.feedback.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pesan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada pesan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $messages->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
