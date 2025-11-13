<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon; // <-- 1. TAMBAHKAN IMPORT CARBON

class TransactionController extends Controller
{
    /**
     * Menampilkan halaman Riwayat Transaksi (Sudah ada, tapi kita rapikan)
     * Ini dipanggil oleh route 'transaksi.riwayat'
     */
    public function index()
    {
        // Ambil transaksi yang 'Selesai' atau 'Sudah Dibayar' sebagai riwayat
        $transactions = Transaction::whereIn('status', ['Selesai', 'Sudah Dibayar'])
            ->with(['customer', 'service']) // Optimasi query
            ->latest()
            ->paginate(20);

        return view('kasir.transaksi', compact('transactions'));
    }

    /**
     * ==========================================================
     * METHOD BARU UNTUK ANTREAN KANBAN
     * ==========================================================
     * Ini dipanggil oleh route 'transaksi.antrean'
     */
    public function antrean()
    {
        // 2. Ambil data transaksi HARI INI saja
        $today = Carbon::today();

        // Ambil data antrean (Menunggu)
        $antrean = Transaction::whereDate('created_at', $today)
            ->where('status', 'Menunggu')
            ->with(['customer', 'service'])
            ->orderBy('created_at', 'asc') // Urutkan dari yang paling lama
            ->get();

        // Ambil data yang Sedang Dicuci
        $dicuci = Transaction::whereDate('created_at', $today)
            ->where('status', 'Sedang Dicuci')
            ->with(['customer', 'service'])
            ->orderBy('updated_at', 'asc')
            ->get();

        // Ambil data yang Selesai (tapi belum dibayar/diambil)
        $selesai = Transaction::whereDate('created_at', $today)
            ->where('status', 'Selesai')
            ->with(['customer', 'service'])
            ->orderBy('updated_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        // 3. Tampilkan view Kanban
        return view('operasional.antrean', compact('antrean', 'dicuci', 'selesai'));
    }

    /**
     * ==========================================================
     * METHOD BARU UNTUK UPDATE STATUS
     * ==========================================================
     * Ini dipanggil oleh route 'transaksi.update_status' (via POST)
     */
   public function update_status(Request $request, Transaction $transaction)
    {
        // Jika tombol "Selesai Cuci" ditekan
        if ($transaction->status == 'Sedang Dicuci') {
            $transaction->status = 'Selesai';
            $transaction->slot = null; // Kosongkan slot agar bisa dipakai orang lain
            $transaction->save();
            return back()->with('success', 'Mobil selesai dicuci. Slot sekarang kosong.');
        }

        // Jika tombol "Masukan ke Slot" ditekan (dari Menunggu -> Sedang Dicuci)
        if ($transaction->status == 'Menunggu' && $request->has('target_slot')) {
            $targetSlot = $request->target_slot;

            // Validasi slot kosong lagi (untuk keamanan)
            $isFilled = Transaction::where('status', 'Sedang Dicuci')->where('slot', $targetSlot)->exists();
            if ($isFilled) {
                return back()->with('error', 'Slot ' . $targetSlot . ' baru saja terisi!');
            }

            $transaction->status = 'Sedang Dicuci';
            $transaction->slot = $targetSlot;
            $transaction->save();
            return back()->with('success', 'Mobil masuk ke Slot ' . $targetSlot);
        }

        // Default fallback (jika ada logika lain)
        return back();
    }

    /**
     * (Method 'status' lama dari file Anda)
     * Kita ganti dengan 'update_status' yang baru
     */
    public function status(string $id)
    {
        // Method ini tidak kita pakai lagi,
        // kita ganti dengan update_status(Transaction $transaction)
        // yang lebih modern (Route Model Binding) dan aman (POST).
        return redirect()->route('transaksi.antrean');
    }
}
