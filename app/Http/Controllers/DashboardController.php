<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Customer;
use App\Models\User; // Pastikan User diimport

class DashboardController extends Controller
{
    /**
     * Method utama yang dipanggil oleh route '/dashboard'
     */
    public function index()
    {
        // Cek role dan panggil method yang sesuai
        if (Auth::user()->role === 'admin') {
            return $this->admin();
        } elseif (Auth::user()->role === 'kasir') {
            return $this->kasir();
        }

        // Fallback jika role user biasa
        return redirect()->route('home');
    }

    /**
     * Logika untuk Dashboard Admin (KPI & Grafik)
     */
    public function admin()
    {
        $today = Carbon::today();

        // --- 1. Data KPI (Kartu Atas) ---
        // Ini adalah variabel yang dicari oleh View Anda:
        $totalPendapatanBulanIni = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year) // Filter tahun ini juga
            ->whereIn('status', ['Selesai', 'Sudah Dibayar'])
            ->sum('total');

        // (Variabel ini untuk KPI lainnya di view)
        $penggunaBaruBulanIni = Customer::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $totalTransaksi = Transaction::count();
        $jumlahLayanan = Service::count();

        // --- 2. Data Grafik Pendapatan (Line Chart) ---
        $incomeLabels = [];
        $incomeData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $incomeLabels[] = $date->translatedFormat('F');

            $total = Transaction::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('status', ['Selesai', 'Sudah Dibayar'])
                ->sum('total');
            $incomeData[] = $total / 1000000; // Ubah ke Juta
        }

        // --- 3. Data Layanan Terpopuler (Pie Chart) ---
        $topServices = Transaction::select('service_id', DB::raw('count(*) as total'))
            ->whereIn('status', ['Selesai', 'Sudah Dibayar'])
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('service')
            ->get();
        $serviceLabels = $topServices->map(function($row) { return $row->service->name ?? 'Layanan Dihapus'; });
        $serviceData = $topServices->pluck('total');

        // --- 4. Data Transaksi Harian (Bar Chart) ---
        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyLabels[] = $date->format('d M');
            $count = Transaction::whereDate('created_at', $date->format('Y-m-d'))->count();
            $dailyData[] = $count;
        }

        // 5. Transaksi Terbaru (Tabel)
        $transaksi_terbaru = Transaction::with(['customer', 'service'])->latest()->take(5)->get();

        // KIRIM SEMUA DATA KE VIEW
        return view('dashboards.admin', compact(
            'totalPendapatanBulanIni', // <-- Variabel yang error
            'penggunaBaruBulanIni',
            'totalTransaksi',
            'jumlahLayanan',
            'incomeLabels',
            'incomeData',
            'serviceLabels',
            'serviceData',
            'dailyLabels',
            'dailyData',
            'transaksi_terbaru'
        ));
    }

    /**
     * Logika untuk Dashboard Kasir
     */
    public function kasir()
    {
        $today = Carbon::today();

        $antrean_count = Transaction::whereDate('created_at', $today)
            ->where('status', 'Menunggu')
            ->count();

        $dicuci_count = Transaction::whereDate('created_at', $today)
            ->where('status', 'Sedang Dicuci')
            ->count();

        return view('dashboards.kasir', compact(
            'antrean_count',
            'dicuci_count'
        ));
    }
}
