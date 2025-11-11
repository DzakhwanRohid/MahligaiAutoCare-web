<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\layananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;

// Web Routes
// Rute untuk halaman-halaman utama (Publik)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('layanan');
Route::get('/tentang-kami', [HomeController::class, 'tentangKami'])->name('tentang.kami');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
Route::get('/pantau', [HomeController::class, 'pantauAntrian'])->name('pantau');

//Pemesanan route
Route::get('/pemesanan', [HomeController::class, 'showPemesanan'])->name('pemesanan.create');
Route::post('/pemesanan', [HomeController::class, 'storePemesanan'])->name('pemesanan.store');


// Rute Dashboard bawaan Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Rute Profil bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/admin/transaksi', [TransactionController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.transaksi');

    Route::get('/admin/laporan', [ReportController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.laporan');

    Route::get('/admin/users',[UserController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.users.index');

    Route::get('admin/layanan',[layananController::class,'index'])
        ->middleware('role:admin')
        ->name('admin.layanan.index');

    Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])
        ->middleware('role:kasir')
        ->name('kasir.dashboard');

    Route::get('/kasir/transaksi',[TransactionController::class,'kasirIndex'])
        ->middleware('role:kasir')
        ->name('kasir.transaksi');

    Route::get('/kasir/laporan', [CustomerController::class, 'index'])
        ->middleware('role:kasir')
        ->name('kasir.laporan');

    Route::get('/kasir/pelanggan/{customer}/edit', [CustomerController::class, 'edit'])
        ->middleware('role:kasir')
        ->name('kasir.customer.edit');

    Route::put('/kasir/pelanggan/{customer}', [CustomerController::class, 'update'])
        ->middleware('role:kasir')
        ->name('kasir.customer.update');

    Route::get('/kasir/status-kendaraan', [TransactionController::class, 'vehicleStatus'])
        ->middleware('role:kasir')
        ->name('kasir.status');

    Route::patch('/kasir/status-kendaraan/{transaction}', [TransactionController::class, 'updateStatus'])
        ->middleware('role:kasir')
        ->name('kasir.status.update');

        // ROUTE UNTUK MENAMPILKAN FORM PENDAFTARAN
    Route::get('/kasir/pendaftaran', [TransactionController::class, 'create'])
        ->middleware('role:kasir')
        ->name('kasir.pendaftaran');

        // ROUTE UNTUK MENYIMPAN DATA DARI FORM
    Route::post('/kasir/pendaftaran', [TransactionController::class, 'store'])
        ->middleware('role:kasir')
        ->name('kasir.pendaftaran.store');

        // ROUTE UNTUK HALAMAN AWAL POS (DAFTAR TRANSAKSI)
    Route::get('/kasir/pembayaran', [POSController::class, 'index'])
        ->middleware('role:kasir')
        ->name('kasir.pembayaran.index');

        // ROUTE UNTUK MENAMPILKAN FORM PEMBAYARAN SPESIFIK
    Route::get('/kasir/pembayaran/{id}', [POSController::class, 'showPaymentForm'])
        ->middleware('role:kasir')
        ->name('kasir.pembayaran.form');

    // 1. Untuk memproses pembayaran (saat tombol di form diklik)
    Route::put('/kasir/pembayaran/{transaction}', [POSController::class, 'processPayment'])
        ->middleware('role:kasir')
        ->name('kasir.pembayaran.process');

    // 2. Untuk menampilkan halaman struk setelah berhasil bayar
    Route::get('/kasir/struk/{transaction}', [POSController::class, 'showStruk'])
        ->middleware('role:kasir')
        ->name('kasir.struk.show');


});

// Memuat semua rute autentikasi (login, register, logout, dll.) dari Breeze
require __DIR__.'/auth.php';
