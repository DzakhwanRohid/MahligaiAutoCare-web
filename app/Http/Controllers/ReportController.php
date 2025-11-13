<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Service;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan Laporan Pendapatan (Halaman Form)
     */
    public function index()
    {
        // Menampilkan view dengan form filter
        return view('admin.laporan.laporan_pendapatan');
    }

    /**
     * Memfilter dan Menampilkan Hasil Laporan Pendapatan
     */
    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // 1. Buat Query Dasar untuk Laporan Pendapatan (Hanya yang Selesai)
        $queryBase = Transaction::whereBetween('created_at', [$startDate, $endDate])
                                ->where('status', 'Selesai');

        // 2. Hitung KPI (Ringkasan Total)
        // Kita clone query agar tidak mempengaruhi query tabel harian
        $summaryQuery = clone $queryBase;
        $totalPendapatan = $summaryQuery->sum('total');
        $totalTransaksi = $summaryQuery->count();
        $rataRata = ($totalTransaksi > 0) ? $totalPendapatan / $totalTransaksi : 0;

        // 3. Ambil Laporan Harian (Grup per Tanggal)
        $laporan = $queryBase
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total_transaksi, SUM(total) as total_pendapatan')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // 4. Kirim semua data ke view
        return view('admin.laporan.laporan_pendapatan', compact(
            'laporan',
            'startDate',
            'endDate',
            'totalPendapatan',
            'totalTransaksi',
            'rataRata'
        ));
    }

    /**
     * Laporan Pemesanan (Ini sudah benar dari langkah sebelumnya)
     */
    public function pemesanan(Request $request)
    {
        $query = Transaction::with(['customer', 'service'])->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = Service::orderBy('name')->get();
        $transactions = $query->paginate(15);

        return view('admin.laporan.laporan_pemesanan', compact('transactions', 'services'));
    }
}
