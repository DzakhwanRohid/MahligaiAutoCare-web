@extends('layouts.dashboard')

@section('title', 'Antrean Real-time')

@section('content')

<style>
    .kanban-board {
        display: flex;
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    .kanban-column {
        flex: 1 0 300px; /* Lebar minimum 300px, bisa membesar */
        min-width: 300px;
        margin-right: 15px;
        border-radius: 0.375rem;
    }
    .kanban-column:last-child {
        margin-right: 0;
    }
    .kanban-column .card-header {
        font-weight: bold;
    }
    .kanban-column .kanban-cards {
        min-height: 200px; /* Tinggi minimum agar terlihat */
        background-color: #f8f9fa; /* Latar belakang kolom */
        padding: 10px;
        border-radius: 0 0 0.375rem 0.375rem;
    }
    .kanban-card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .kanban-card .card-body {
        padding: 1rem;
    }
</style>

<div class="container-fluid">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">@yield('title') (Hari Ini)</h3>
        <a href="{{ route('transaksi.antrean') }}" class="btn btn-outline-primary">
            <i class="fa fa-sync-alt"></i> Refresh Data
        </a>
    </div>

    <div class="kanban-board">

        <div class="kanban-column">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="fa fa-clock"></i> Antrean (Menunggu)
                    <span class="badge bg-light text-dark float-end">{{ $antrean->count() }}</span>
                </div>
                <div class="kanban-cards">
                    @forelse($antrean as $tx)
                        @include('operasional.partials.kanban_card', ['tx' => $tx, 'action_text' => 'Mulai Cuci', 'action_class' => 'btn-primary'])
                    @empty
                        <p class="text-muted text-center mt-3">Tidak ada mobil dalam antrean.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="kanban-column">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fa fa-shower"></i> Sedang Dicuci
                    <span class="badge bg-dark text-white float-end">{{ $dicuci->count() }}</span>
                </div>
                <div class="kanban-cards">
                    @forelse($dicuci as $tx)
                        @include('operasional.partials.kanban_card', ['tx' => $tx, 'action_text' => 'Selesaikan Cucian', 'action_class' => 'btn-success'])
                    @empty
                        <p class="text-muted text-center mt-3">Tidak ada mobil yang sedang dicuci.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="kanban-column">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fa fa-check-circle"></i> Selesai (Siap Diambil)
                    <span class="badge bg-light text-dark float-end">{{ $selesai->count() }}</span>
                </div>
                <div class="kanban-cards">
                    @forelse($selesai as $tx)
                        @include('operasional.partials.kanban_card', ['tx' => $tx, 'action_text' => 'Sudah Diambil', 'action_class' => 'btn-outline-dark'])
                    @empty
                        <p class="text-muted text-center mt-3">Belum ada cucian yang selesai.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
