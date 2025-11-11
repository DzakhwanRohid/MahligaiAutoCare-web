@extends('layouts.main')

@section('content')

{{-- Header Halaman Baru (Tanpa Background Gambar) --}}
<div class="page-header text-center">
    <div class="container">
        <h1 class="display-4" data-aos="fade-down">Real-Time Queue Monitoring</h1>
        <p class="lead" data-aos="fade-down" data-aos-delay="100">Status terkini dari setiap area cuci di Mahligai AutoCare.</p>
    </div>
</div>

{{-- Konten Antrian dengan Desain Futuristik --}}
<div class="queue-section-v2">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="queue-pod in-use">
                    <div class="pod-header">
                        <div class="pod-title">
                            <span class="status-indicator working"></span>
                            <h4>CUCI HIDROLIK 1</h4>
                        </div>
                        <span class="pod-status-tag working">IN PROGRESS</span>
                    </div>
                    <div class="pod-body">
                        <div class="vehicle-info">
                            <i class="fas fa-car-alt"></i>
                            <div class="vehicle-details">
                                <strong>Toyota Avanza</strong>
                                <span>Premium Wash</span>
                            </div>
                        </div>
                        <div class="worker-info">
                            <i class="fas fa-user-cog"></i>
                            <span>Budi Santoso</span>
                        </div>
                    </div>
                    <div class="pod-footer">
                        <div class="time-details">
                            <i class="fas fa-stopwatch"></i>
                            <div>
                                <span>Mulai: <strong>10:30 WIB</strong></span>
                                <small>Estimasi Selesai: <strong>11:15 WIB</strong></small>
                            </div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: 65%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="queue-pod available">
                    <div class="available-content">
                        <i class="fas fa-check-circle"></i>
                        <h4>CUCI HIDROLIK 2</h4>
                        <p>Area Ini Tersedia</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="queue-pod finished">
                     <div class="pod-header">
                        <div class="pod-title">
                            <span class="status-indicator finished"></span>
                            <h4>CUCI 1</h4>
                        </div>
                        <span class="pod-status-tag finished">SELESAI</span>
                    </div>
                    <div class="pod-body">
                        <div class="vehicle-info">
                            <i class="fas fa-motorcycle"></i>
                             <div class="vehicle-details">
                                <strong>Honda Vario</strong>
                                <span>Cuci Standar</span>
                            </div>
                        </div>
                         <div class="worker-info">
                            <i class="fas fa-user-cog"></i>
                            <span>Ahmad</span>
                        </div>
                    </div>
                     <div class="pod-footer">
                         <div class="time-details">
                            <i class="fas fa-flag-checkered"></i>
                             <div>
                                <span>Selesai: <strong>10:40 WIB</strong></span>
                                <small class="text-success fw-bold">Siap Diambil!</small>
                            </div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill bg-success" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="queue-pod available">
                    <div class="available-content">
                        <i class="fas fa-check-circle"></i>
                        <h4>CUCI 2</h4>
                        <p>Area Ini Tersedia</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
