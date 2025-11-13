<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

// Controller ini sudah kita buat
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute untuk halaman publik (landing page)
|
*/

// RUTE PUBLIK (TETAP SAMA dan SEKARANG AKAN BERFUNGSI)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pantau-antrean', [HomeController::class, 'pantau'])->name('home.pantau');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('home.layanan'); // <-- INI YANG ERROR
Route::get('/tentang', [HomeController::class, 'tentang'])->name('home.tentang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('home.kontak');
Route::get('/pemesanan', [HomeController::class, 'pemesanan_create'])->name('pemesanan.create');
Route::post('/pemesanan', [HomeController::class, 'pemesanan'])->name('home.pemesanan');

/*
|--------------------------------------------------------------------------
| Rute Dashboard & Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rute untuk KASIR & ADMIN (URL INI SUDAH BENAR)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,kasir'])->group(function () {

        Route::get('/kasir/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/kasir/pos', [POSController::class, 'store'])->name('pos.store');
        Route::get('/kasir/pos/struk/{transaction}', [POSController::class, 'struk'])->name('pos.struk');

        Route::get('/transaksi/antrean', [TransactionController::class, 'antrean'])->name('transaksi.antrean');
        Route::post('/transaksi/status/{transaction}', [TransactionController::class, 'update_status'])->name('transaksi.update_status');

        Route::get('/kasir/transaksi', [TransactionController::class, 'index'])->name('transaksi.riwayat');
    });

    /*
    |--------------------------------------------------------------------------
    | Rute HANYA UNTUK ADMIN (INI YANG DIPERBAIKI)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->group(function () { 

        // --- Manajemen Data ---
        // URL menjadi /admin/layanan, Nama menjadi admin.layanan.index
        Route::resource('layanan', LayananController::class)->names('admin.layanan');
        // URL menjadi /admin/promosi, Nama menjadi admin.promosi.index
        Route::resource('promosi', PromotionController::class)->except(['show'])->names('admin.promosi');


        // --- Manajemen Pengguna ---
        // URL menjadi /admin/users, Nama menjadi admin.users.index
        Route::resource('users', UserController::class)->names('admin.users');
        // URL menjadi /admin/customer, Nama menjadi admin.customer.index
        Route::resource('customer', CustomerController::class)->names('admin.customer');


        // --- Laporan & Analitik ---
        // URL menjadi /admin/laporan/pemesanan
        Route::get('/laporan/pemesanan', [ReportController::class, 'pemesanan'])->name('laporan.pemesanan');
        // URL menjadi /admin/laporan (sudah benar)
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.pendapatan');
        // URL menjadi /admin/laporan/filter (sudah benar)
        Route::post('/laporan/filter', [ReportController::class, 'filter'])->name('laporan.filter');


        // --- Pengaturan ---
        // URL menjadi /admin/pengaturan
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');

    });

});


// Rute Autentikasi (Login, Register, dll.)
require __DIR__.'/auth.php';
