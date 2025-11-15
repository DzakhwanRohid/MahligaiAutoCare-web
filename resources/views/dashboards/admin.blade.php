@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h2 mb-0 page-title-modern">Dashboard Administrator</h1>
    </div>

    {{-- BARIS 1: KARTU KPI (DESAIN GRADIENT) --}}
    <div class="row">
        {{-- KPI Card 1: Pendapatan (TEMA HIJAU) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card-gradient theme-green">
                <div class="stat-icon-gradient">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-value">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</p>
                    <h5 class="stat-title">Total Pendapatan (Bulan Ini)</h5>
                </div>
            </div>
        </div>

        {{-- KPI Card 2: Pengguna Baru (TEMA BIRU) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card-gradient theme-blue">
                <div class="stat-icon-gradient">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-value">{{ $penggunaBaruBulanIni }}</p>
                    <h5 class="stat-title">Pengguna Baru (Bulan Ini)</h5>
                </div>
            </div>
        </div>

        {{-- KPI Card 3: Total Transaksi (TEMA ORANYE) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card-gradient theme-orange">
                <div class="stat-icon-gradient">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-value">{{ $totalTransaksi }}</p>
                    <h5 class="stat-title">Total Transaksi</h5>
                </div>
            </div>
        </div>

        {{-- KPI Card 4: Jumlah Layanan (TEMA TOSCA) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card-gradient theme-cyan">
                <div class="stat-icon-gradient">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-value">{{ $jumlahLayanan }}</p>
                    <h5 class="stat-title">Jumlah Layanan</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK UTAMA & TABEL TERBARU --}}
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="content-box h-100">
                <div class="card-header-modern">
                    <h4 class="card-title-modern">Grafik Pendapatan (6 Bulan Terakhir)</h4>
                </div>
                <div style="height: 300px; padding-top: 10px;">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="content-box h-100">
                <div class="card-header-modern">
                    <h4 class="card-title-modern">Transaksi Terbaru</h4>
                </div>
                <div class="recent-list-wrapper">
                    @forelse($transaksi_terbaru as $tx)
                        <div class="recent-item">
                            <div class="recent-info">
                                <strong>{{ $tx->customer->name ?? 'Guest' }}</strong>
                                <small class="text-muted">{{ $tx->service->name }}</small>
                            </div>
                            <span class="badge bg-{{ $tx->status == 'Selesai' ? 'success' : 'warning' }}">
                                {{ $tx->status }}
                            </span>
                        </div>
                    @empty
                        <div class="recent-item text-center text-muted">Belum ada transaksi.</div>
                    @endforelse
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('transaksi.riwayat') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 3: GRAFIK TAMBAHAN --}}
    <div class="row">
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="content-box h-100">
                <div class="card-header-modern">
                    <h4 class="card-title-modern">Layanan Terpopuler</h4>
                </div>
                <div style="height: 250px; position: relative; padding-top: 10px;">
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="content-box h-100">
                <div class="card-header-modern">
                    <h4 class="card-title-modern">Tren Transaksi Harian</h4>
                </div>
                <div style="height: 250px; padding-top: 10px;">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. GRAFIK PENDAPATAN (Line Chart - DENGAN GRADIENT BARU)
        const ctxIncome = document.getElementById('incomeChart').getContext('2d');

        // ▼▼▼ BUAT GRADIENT UNTUK INCOME CHART ▼▼▼
        const gradientIncome = ctxIncome.createLinearGradient(0, 0, 0, 300);
        gradientIncome.addColorStop(0, 'rgba(123, 170, 35, 0.5)'); // Mulai dengan hijau tema
        gradientIncome.addColorStop(1, 'rgba(123, 170, 35, 0.0)'); // Akhiri dengan transparan

        new Chart(ctxIncome, {
            type: 'line',
            data: {
                labels: {!! json_encode($incomeLabels) !!},
                datasets: [{
                    label: 'Pendapatan (Juta Rp)',
                    data: {!! json_encode($incomeData) !!},
                    backgroundColor: gradientIncome, // <-- Terapkan gradient di sini
                    borderColor: 'rgba(123, 170, 35, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true, // <-- Pastikan 'fill' true
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(123, 170, 35, 1)'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
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

        // 2. GRAFIK LAYANAN (Doughnut Chart - Tetap segmented)
        // Catatan: Gradien pada donat tidak praktis & tidak sesuai referensi Anda.
        // Kita tetap pakai style segmented (border putih) yang sudah modern.
        const ctxService = document.getElementById('serviceChart').getContext('2d');
        new Chart(ctxService, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($serviceLabels) !!},
                datasets: [{
                    data: {!! json_encode($serviceData) !!},
                    backgroundColor: [
                        'rgba(123, 170, 35, 0.9)',  // Hijau Utama
                        'rgba(93, 126, 44, 0.8)',   // Hijau Tua
                        'rgba(140, 192, 59, 0.8)',  // Hijau Cerah
                        'rgba(44, 62, 80, 0.7)',    // Biru Gelap
                        'rgba(149, 165, 166, 0.8)', // Abu-abu
                    ],
                    borderWidth: 4, // <-- Style segmented
                    borderColor: '#fff',
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                },
                cutout: '80%', // <-- Lubang besar
            }
        });

        // 3. GRAFIK HARIAN (Bar Chart - DENGAN GRADIENT BARU)
        const ctxDaily = document.getElementById('dailyChart').getContext('2d');

        // ▼▼▼ BUAT GRADIENT UNTUK BAR CHART ▼▼▼
        const gradientDaily = ctxDaily.createLinearGradient(0, 0, 0, 250);
        gradientDaily.addColorStop(0, 'rgba(123, 170, 35, 0.8)'); // Mulai dengan hijau kuat
        gradientDaily.addColorStop(1, 'rgba(123, 170, 35, 0.2)'); // Akhiri dengan hijau pudar

        new Chart(ctxDaily, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dailyLabels) !!},
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($dailyData) !!},
                    backgroundColor: gradientDaily, // <-- Terapkan gradient di sini
                    borderColor: 'rgba(123, 170, 35, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    // Sumbu Y (Minimalis)
                    y: {
                        beginAtZero: true,
                        grid: { display: false },
                        border: { display: false },
                        ticks: { display: false }
                    },
                    // Sumbu X (Minimalis)
                    x: {
                        grid: {
                            display: true,
                            color: '#f1f1f1',
                            borderDash: [3, 3]
                        },
                        border: { display: false }
                    }
                }
            }
        });
    </script>
@endsection
