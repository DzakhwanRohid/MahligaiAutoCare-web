@extends('layouts.dashboard')

@section('title', 'Edit Promosi: ' . $promotion->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.promosi.update', $promotion->id) }}">
                        @csrf
                        @method('PUT') <div class="mb-3">
                            <label for="name" class="form-label">Nama Promosi</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name', $promotion->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Promo (Unik)</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                   id="code" name="code"
                                   value="{{ old('code', $promotion->code) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe Diskon</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="percentage" {{ old('type', $promotion->type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="fixed" {{ old('type', $promotion->type) == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">Nilai</label>
                            <input type="number" class="form-control @error('value') is-invalid @enderror"
                                   id="value" name="value"
                                   value="{{ old('value', $promotion->value) }}"
                                   placeholder="Isi 20 untuk 20% atau 10000 untuk Rp 10.000" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai Berlaku</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date"
                                           value="{{ old('start_date', $promotion->start_date) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date"
                                           value="{{ old('end_date', $promotion->end_date) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                <option value="1" {{ old('is_active', $promotion->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $promotion->is_active) == '0' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.promosi.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Update Promosi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
