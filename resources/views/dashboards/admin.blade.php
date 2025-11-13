@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Administrator</h1>
    </div>

    {{-- BARIS 1: KARTU KPI (Tetap Sama) --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-info">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <h5>Total Pendapatan (Bulan Ini)</h5>
                    <p>Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-success">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h5>Pengguna Baru (Bulan Ini)</h5>
                    <p>{{ $penggunaBaruBulanIni }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-primary">
                    <i class="fas fa-receipt"></i>
                </div>
                <div>
                    <h5>Total Transaksi</h5>
                    <p>{{ $totalTransaksi }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-warning">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div>
                    <h5>Jumlah Layanan</h5>
                    <p>{{ $jumlahLayanan }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK UTAMA & TABEL TERBARU --}}
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="content-box h-100">
                <h4 class="mb-4">Grafik Pendapatan (6 Bulan Terakhir)</h4>
                <div style="height: 300px;">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="content-box h-100">
                <h4 class="mb-4">Transaksi Terbaru</h4>
                <ul class="list-group list-group-flush">
                    @forelse($transaksi_terbaru as $tx)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <strong>{{ $tx->customer->name ?? 'Guest' }}</strong><br>
                                <small class="text-muted">{{ $tx->service->name }}</small>
                            </div>
                            <span class="badge bg-{{ $tx->status == 'Selesai' ? 'success' : 'warning' }}">
                                {{ $tx->status }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Belum ada transaksi.</li>
                    @endforelse
                </ul>
                <div class="mt-3 text-center">
                    <a href="{{ route('transaksi.riwayat') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 3: GRAFIK TAMBAHAN (BARU) --}}
    <div class="row">
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="content-box h-100">
                <h4 class="mb-4">Layanan Terpopuler</h4>
                <div style="height: 250px; position: relative;">
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="content-box h-100">
                <h4 class="mb-4">Tren Transaksi Harian (7 Hari Terakhir)</h4>
                <div style="height: 250px;">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. GRAFIK PENDAPATAN (Line Chart - Desain Anda)
        const ctxIncome = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctxIncome, {
            type: 'line',
            data: {
                labels: {!! json_encode($incomeLabels) !!},
                datasets: [{
                    label: 'Pendapatan (Juta Rp)',
                    data: {!! json_encode($incomeData) !!},
                    backgroundColor: 'rgba(123, 170, 35, 0.2)', // Warna Hijau Anda
                    borderColor: 'rgba(123, 170, 35, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(123, 170, 35, 1)'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 2] },
                        ticks: {
                            callback: function(value) { return 'Rp ' + value + ' Jt'; }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. GRAFIK LAYANAN (Pie Chart - Baru)
        const ctxService = document.getElementById('serviceChart').getContext('2d');
        new Chart(ctxService, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($serviceLabels) !!},
                datasets: [{
                    data: {!! json_encode($serviceData) !!},
                    backgroundColor: [
                        'rgba(123, 170, 35, 0.8)',  // Hijau Utama
                        'rgba(54, 162, 235, 0.8)',  // Biru
                        'rgba(255, 206, 86, 0.8)',  // Kuning
                        'rgba(75, 192, 192, 0.8)',  // Tosca
                        'rgba(153, 102, 255, 0.8)', // Ungu
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                },
                cutout: '70%', // Membuat lubang donat lebih besar (modern)
            }
        });

        // 3. GRAFIK HARIAN (Bar Chart - Baru)
        const ctxDaily = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctxDaily, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dailyLabels) !!},
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($dailyData) !!},
                    backgroundColor: 'rgba(123, 170, 35, 0.6)', // Hijau agak transparan
                    borderColor: 'rgba(123, 170, 35, 1)',
                    borderWidth: 1,
                    borderRadius: 5 // Bar membulat (modern)
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }, // Agar angka bulat (tidak 1.5 transaksi)
                        grid: { borderDash: [2, 2] }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
@endsection
