<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman utama laporan.
     */
    public function index()
    {
        // Data Dummy untuk Ringkasan
        $summary = [
            'total_pendapatan' => 15730000,
            'total_transaksi' => 352,
            'rata_rata_transaksi' => 15730000 / 352,
        ];

        // Data Dummy untuk Tabel Laporan Pendapatan Harian
        $dailyReport = [
            ['tanggal' => '2025-10-03', 'jumlah_transaksi' => 8, 'total_pendapatan' => 1850000],
            ['tanggal' => '2025-10-02', 'jumlah_transaksi' => 12, 'total_pendapatan' => 2300000],
            ['tanggal' => '2025-10-01', 'jumlah_transaksi' => 9, 'total_pendapatan' => 1950000],
        ];

        // Data Dummy untuk Tabel Laporan Kinerja Layanan
        $serviceReport = [
            ['nama_layanan' => 'Premium Car Wash', 'jumlah_dipesan' => 80, 'total_pendapatan' => 12000000],
            ['nama_layanan' => 'Nano Ceramic Coating', 'jumlah_dipesan' => 15, 'total_pendapatan' => 11250000],
            ['nama_layanan' => 'Interior Detailing', 'jumlah_dipesan' => 25, 'total_pendapatan' => 8750000],
        ];

        // Mengirim semua data ke view 'admin.laporan'
        return view('admin.laporan', compact('summary', 'dailyReport', 'serviceReport'));
    }

    public function indexKasir()
    {
        // Data dummy, nanti bisa diganti dengan query dari database
        $transaksis = [
            (object) [
                'nama_pelanggan' => 'Budi Santoso',
                'mobil' => 'Toyota Avanza',
                'plat_mobil' => 'BM 1234 ABC'
            ],
            (object) [
                'nama_pelanggan' => 'Citra Lestari',
                'mobil' => 'Honda Brio',
                'plat_mobil' => 'BM 5678 DEF'
            ],
            (object) [
                'nama_pelanggan' => 'Ahmad Subarjo',
                'mobil' => 'Mitsubishi Pajero',
                'plat_mobil' => 'BM 9101 GHI'
            ],
            (object) [
                'nama_pelanggan' => 'Rina Marlina',
                'mobil' => 'Daihatsu Terios',
                'plat_mobil' => 'BM 1122 JKL'
            ],
        ];

        // Arahkan ke view laporan dan kirim data dummy
        return view('kasir.laporan', ['transaksis' => $transaksis]);
    }
}