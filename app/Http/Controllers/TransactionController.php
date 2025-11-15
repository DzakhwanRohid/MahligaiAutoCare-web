<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Menampilkan halaman Riwayat Transaksi (Sekarang dipisah 2 tabel)
     */
    public function index()
    {
        // 1. Ambil Pesanan Online (HANYA yang statusnya 'Menunggu' verifikasi)
        $onlineBookings = Transaction::with(['customer', 'service'])
            ->where('status', 'Menunggu') // Hanya yang baru masuk
            ->latest()
            ->get();

        // 2. Ambil Riwayat (Semua selain 'Menunggu')
        $processedTransactions = Transaction::with(['customer', 'service', 'user'])
            ->where('status', '!=', 'Menunggu') // Ambil semua status LAINNYA
            ->latest()
            ->paginate(15);

        return view('kasir.transaksi', compact('onlineBookings', 'processedTransactions'));
    }

    /**
     * Menampilkan halaman Antrean Kanban/Slot
     */
    public function antrean()
    {
        $today = Carbon::today();

        // Daftar Tunggu (HANYA yang sudah Terkonfirmasi)
        $antrean = Transaction::whereDate('booking_date', $today) // Filter booking hari ini
            ->where('status', 'Terkonfirmasi') // <-- PERUBAHAN DI SINI
            ->with(['customer', 'service'])
            ->orderBy('booking_date', 'asc') // Urutkan berdasarkan jam booking
            ->get();

        // Sedang Dicuci (Sama)
        $dicuci = Transaction::where('status', 'Sedang Dicuci')
            ->whereNotNull('slot')
            ->with(['customer', 'service'])
            ->orderBy('updated_at', 'asc')
            ->get();

        // Selesai (Sama)
        $selesai = Transaction::whereDate('created_at', $today)
            ->where('status', 'Selesai')
            ->with(['customer', 'service'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('operasional.antrean', compact('antrean', 'dicuci', 'selesai'));
    }

    /**
     * Mengupdate status dari Antrean Kanban/Slot
     */
    public function update_status(Request $request, Transaction $transaction)
    {
        // ... (Logika Selesai Cuci tetap sama) ...
        if ($transaction->status == 'Sedang Dicuci') {
            $transaction->status = 'Selesai';
            $transaction->slot = null;
            $transaction->save();
            return back()->with('success', 'Mobil selesai dicuci. Slot sekarang kosong.');
        }

        // ... (Logika Masuk Slot dari 'Terkonfirmasi' atau 'Menunggu' [untuk POS]) ...
        if (in_array($transaction->status, ['Menunggu', 'Terkonfirmasi']) && $request->has('target_slot')) {
            $targetSlot = $request->target_slot;

            $isFilled = Transaction::where('status', 'Sedang Dicuci')->where('slot', $targetSlot)->exists();
            if ($isFilled) {
                return back()->with('error', 'Slot ' . $targetSlot . ' baru saja terisi!');
            }

            $transaction->status = 'Sedang Dicuci';
            $transaction->slot = $targetSlot;
            $transaction->save();
            return back()->with('success', 'Mobil masuk ke Slot ' . $targetSlot);
        }

        return back();
    }

    // --- PERBAIKAN LOGIKA APPROVAL ---

    /**
     * Menerima booking (verifikasi pembayaran)
     */
    public function approveBooking(Transaction $transaction)
    {
        if ($transaction->status == 'Menunggu') {
            $transaction->status = 'Terkonfirmasi'; // <-- GANTI STATUS
            $transaction->save();
            return redirect()->route('transaksi.riwayat')->with('success', 'Booking '.$transaction->invoice.' DITERIMA. Siap masuk antrean.');
        }
        return redirect()->route('transaksi.riwayat')->with('error', 'Transaksi ini sudah diproses.');
    }

    /**
     * Menolak booking (pembayaran tidak valid)
     */
    public function rejectBooking(Transaction $transaction)
    {
        if ($transaction->status == 'Menunggu') {
            $transaction->status = 'Ditolak'; // <-- GANTI STATUS
            $transaction->save();
            return redirect()->route('transaksi.riwayat')->with('success', 'Booking '.$transaction->invoice.' berhasil DITOLAK.');
        }
        return redirect()->route('transaksi.riwayat')->with('error', 'Transaksi ini sudah diproses.');
    }
}
