<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction; // <-- 1. Import Transaction
use App\Models\Customer;    // <-- 2. Import Customer
use Carbon\Carbon;          // <-- 3. Import Carbon

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        if (Auth::user()->role === 'admin') {
            // --- Data untuk Admin ---

            // 1. KPI Pendapatan
            $pendapatan_hari_ini = Transaction::whereDate('created_at', $today)
                ->where('status', 'Selesai') // Hanya hitung yang Selesai
                ->sum('total');

            // 2. KPI Transaksi
            $transaksi_hari_ini = Transaction::whereDate('created_at', $today)->count();

            // 3. KPI Pelanggan Baru
            $pelanggan_baru_hari_ini = Customer::whereDate('created_at', $today)->count();

            // 4. KPI Antrean
            $antrean_count = Transaction::whereDate('created_at', $today)
                ->where('status', 'Menunggu')
                ->count();

            // 5. Tabel Transaksi Terbaru
            $transaksi_terbaru = Transaction::with(['customer', 'service'])
                ->latest() // Ambil yang paling baru
                ->take(5)  // Ambil 5 saja
                ->get();

            return view('dashboards.admin', compact(
                'pendapatan_hari_ini',
                'transaksi_hari_ini',
                'pelanggan_baru_hari_ini',
                'antrean_count',
                'transaksi_terbaru'
            ));

        } elseif (Auth::user()->role === 'kasir') {
            // --- Data untuk Kasir ---

            // 1. KPI Antrean
            $antrean_count = Transaction::whereDate('created_at', $today)
                ->where('status', 'Menunggu')
                ->count();

            // 2. KPI Sedang Dicuci
            $dicuci_count = Transaction::whereDate('created_at', $today)
                ->where('status', 'Sedang Dicuci')
                ->count();

            return view('dashboards.kasir', compact(
                'antrean_count',
                'dicuci_count'
            ));
        }

        // Fallback jika role tidak dikenal
        return redirect()->route('home');
    }
}
