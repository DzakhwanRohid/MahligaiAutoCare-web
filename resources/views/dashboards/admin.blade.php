@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Administrator</h1>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-info">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <h5>Total Pendapatan (Bulan Ini)</h5>
                    <p>Rp 15.7M</p>
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
                    <p>48</p>
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
                    <p>352</p>
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
                    <p>12</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="content-box">
                <h4 class="mb-4">Grafik Pendapatan (6 Bulan Terakhir)</h4>
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="content-box">
                <h4 class="mb-4">Pengguna Terbaru</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Rina Marlina
                        <span class="badge bg-secondary">User</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Doni Setiawan
                        <span class="badge bg-secondary">User</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Siti Aminah
                        <span class="badge bg-secondary">User</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Agus Wijaya
                        <span class="badge bg-secondary">User</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Putri Ayu
                        <span class="badge bg-secondary">User</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September'],
                datasets: [{
                    label: 'Pendapatan',
                    data: [12, 19, 15, 17, 20, 25],
                    backgroundColor: 'rgba(123, 170, 35, 0.2)',
                    borderColor: 'rgba(123, 170, 35, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + value + ' Jt';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
