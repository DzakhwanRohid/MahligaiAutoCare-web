<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // <-- WAJIB

class TransactionController extends Controller
{
    private $tz = 'Asia/Jakarta'; // Tentukan Timezone

    /**
     * Menampilkan halaman Riwayat Transaksi (Sekarang dipisah 2 tabel)
     */
   public function index()
    {
        // 1. Ambil Pesanan Online (Perlu Verifikasi)
        $onlineBookings = Transaction::with(['customer', 'service'])
            ->where('status', 'Menunggu')
            ->where('invoice', 'like', 'BKG-%') // Hanya booking online
            ->latest()
            ->get();

        // 2. Ambil Riwayat Booking Online (Sudah diproses: Terkonfirmasi, Selesai, Ditolak)
        $processedBookings = Transaction::with(['customer', 'service', 'user'])
            ->where('status', '!=', 'Menunggu')
            ->where('invoice', 'like', 'BKG-%') // Hanya booking
            ->latest()
            ->paginate(10, ['*'], 'bookings_page'); // Paginator kustom

        // 3. Ambil Riwayat Walk-in (Dari POS)
        $walkinHistory = Transaction::with(['customer', 'service', 'user'])
            ->where('invoice', 'like', 'INV-%') // Hanya walk-in
            ->latest()
            ->paginate(10, ['*'], 'walkin_page'); // Paginator kustom

        return view('kasir.transaksi', compact(
            'onlineBookings',
            'processedBookings', // Riwayat BKG
            'walkinHistory'      // Riwayat INV
        ));
    }

    /**
     * ==========================================================
     * PEROMBAKAN TOTAL: Logika Antrean & Slot (Part 5)
     * ==========================================================
     */
    public function antrean()
    {
        $today = Carbon::now($this->tz)->format('Y-m-d');

        // 1. Data untuk 4 Slot Fisik (Yang sedang dicuci SAAT INI)
        $dicuci = Transaction::where('status', 'Sedang Dicuci')
            ->whereNotNull('slot')
            ->with(['service', 'customer'])
            ->get()
            ->keyBy('slot');

        // 2. Data Booking Online (Sudah punya slot, tunggu jamnya atau konfirmasi)
        $antrean_booking = Transaction::where(function($q) {
                $q->where('status', 'Terkonfirmasi')
                  ->orWhere('status', 'Menunggu'); // <-- Ini juga termasuk yg booking online (BKG-...)
            })
            ->where('invoice', 'like', 'BKG-%') // Hanya booking online
            ->whereDate('booking_date', $today)
            ->with(['service', 'customer'])
            ->orderBy('booking_date', 'asc')
            ->get();

        // 3. Data Walk-in (Dibuat di POS, belum punya slot)
        $antrean_walkin = Transaction::where('status', 'Menunggu')
            ->where('invoice', 'like', 'INV-%') // Hanya dari POS
            ->with(['service', 'customer'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 4. Data Selesai (Sama seperti kode lama Anda)
        $selesai = Transaction::whereDate('created_at', $today)
            ->where('status', 'Selesai')
            ->with(['customer', 'service'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('operasional.antrean', compact('dicuci', 'antrean_booking', 'antrean_walkin', 'selesai'));
    }

    /**
     * ==========================================================
     * PEROMBAKAN TOTAL: Logika Update Status (Part 5)
     * ==========================================================
     */
    public function update_status(Request $request, Transaction $transaction)
    {
        // Aksi 1: Menyelesaikan Cucian (dari "Sedang Dicuci")
        if ($transaction->status == 'Sedang Dicuci') {
            $transaction->status = 'Selesai';
            $transaction->slot = null; // <-- Slot dikosongkan
            $transaction->save();
            return back()->with('success', 'Mobil selesai dicuci. Slot sekarang kosong.');
        }

        // Aksi 2: Memulai Cucian (dari "Terkonfirmasi" / Booking Online)
        // Kasir tidak perlu pilih slot, slot sudah ada
        if ($transaction->status == 'Terkonfirmasi') {
            // Cek apakah slot yang dipesan pelanggan sedang dipakai
            $isFilled = Transaction::where('status', 'Sedang Dicuci')
                ->where('slot', $transaction->slot)
                ->exists();

            if ($isFilled) {
                return back()->with('error', 'Slot ' . $transaction->slot . ' masih terisi! Selesaikan cucian di slot itu terlebih dahulu.');
            }

            $transaction->status = 'Sedang Dicuci';
            // Pastikan booking_date di-update ke waktu "sekarang" saat mulai cuci
            $transaction->booking_date = Carbon::now($this->tz);
            $transaction->save();
            return back()->with('success', 'Mobil booking (' . $transaction->vehicle_plate . ') masuk ke Slot ' . $transaction->slot);
        }

        // Aksi 3: Memasukkan Walk-in ke Slot Kosong (dari "Menunggu")
        if ($transaction->status == 'Menunggu' && $request->has('target_slot')) {
            $targetSlot = $request->target_slot;

            // Cek apakah slot yang dituju sedang dipakai
            $isFilled = Transaction::where('status', 'Sedang Dicuci')->where('slot', $targetSlot)->exists();
            if ($isFilled) {
                return back()->with('error', 'Slot ' . $targetSlot . ' baru saja terisi!');
            }

            $transaction->status = 'Sedang Dicuci';
            $transaction->slot = $targetSlot;
            $transaction->booking_date = Carbon::now($this->tz); // Catat waktu mulainya
            $transaction->save();
            return back()->with('success', 'Mobil walk-in masuk ke Slot ' . $targetSlot);
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    // --- METHOD BARU UNTUK APPROVAL (dari langkah sebelumnya) ---
    public function approveBooking(Transaction $transaction)
    {
        if ($transaction->status == 'Menunggu') {
            $transaction->status = 'Terkonfirmasi'; // <-- GANTI STATUS
            $transaction->save();
            return redirect()->route('transaksi.riwayat')->with('success', 'Booking '.$transaction->invoice.' DITERIMA. Siap masuk antrean.');
        }
        return redirect()->route('transaksi.riwayat')->with('error', 'Transaksi ini sudah diproses.');
    }

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
