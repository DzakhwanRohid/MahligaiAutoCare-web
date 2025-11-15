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
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SettingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute untuk halaman publik
|
*/
// RUTE PUBLIK (TETAP SAMA dan SEKARANG AKAN BERFUNGSI)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pantau-antrean', [HomeController::class, 'pantauAntrian'])->name('home.pantau');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('home.layanan');
Route::get('/tentang', [HomeController::class, 'tentangKami'])->name('home.tentang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('home.kontak');
Route::post('/kontak', [HomeController::class, 'storeContact'])->name('kontak.store');
//RUTE PEMESANAN
Route::get('/pemesanan', [HomeController::class, 'showPemesanan'])->name('pemesanan.create');
Route::get('/check-slots', [HomeController::class, 'getSlots'])->name('check.slots');
Route::post('/check-promo', [HomeController::class, 'checkPromo'])->name('check.promo');
Route::post('/pemesanan', [HomeController::class, 'storePemesanan'])->name('pemesanan.store');
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
    | Rute untuk KASIR & ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::get('/kasir/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/kasir/pos', [POSController::class, 'store'])->name('pos.store');
        Route::get('/kasir/pos/struk/{transaction}', [POSController::class, 'struk'])->name('pos.struk');
        Route::get('/transaksi/antrean', [TransactionController::class, 'antrean'])->name('transaksi.antrean');
        Route::post('/transaksi/status/{transaction}', [TransactionController::class, 'update_status'])->name('transaksi.update_status');
        Route::get('/kasir/transaksi', [TransactionController::class, 'index'])->name('transaksi.riwayat');
        Route::post('/transaksi/approve/{transaction}', [TransactionController::class, 'approveBooking'])->name('transaksi.approve');
        Route::post('/transaksi/reject/{transaction}', [TransactionController::class, 'rejectBooking'])->name('transaksi.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Rute  ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // --- Manajemen Data ---
        Route::resource('layanan', LayananController::class)->names('admin.layanan');
        Route::resource('promosi', PromotionController::class)->except(['show'])->names('admin.promosi');
        // --- Manajemen Pengguna ---
        Route::resource('users', UserController::class)->names('admin.users');
        Route::resource('customer', CustomerController::class)->names('admin.customer');
        // --- Laporan & Analitik ---
        Route::get('/laporan/pemesanan', [ReportController::class, 'pemesanan'])->name('laporan.pemesanan');
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.pendapatan');
        Route::post('/laporan/filter', [ReportController::class, 'filter'])->name('laporan.filter');
       // Rute untuk Pesan/Feedback
    Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::get('/feedback/{contactMessage}', [App\Http\Controllers\FeedbackController::class, 'show'])->name('admin.feedback.show');
    Route::delete('/feedback/{contactMessage}', [App\Http\Controllers\FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
        // --- Pengaturan ---
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');

    });
});
// Rute Autentikasi (Login, Register, dll.)
require __DIR__.'/auth.php';
