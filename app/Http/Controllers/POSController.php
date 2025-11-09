<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    /**
     * Menampilkan halaman awal POS dengan daftar transaksi yang siap dibayar.
     */
    public function index()
    {
        $transactions = Transaction::with('customer')
                                    ->where('status', 'Selesai')
                                    ->latest()
                                    ->get();

        return view('kasir.pos.index', compact('transactions'));
    }

    /**
     * Menampilkan form pembayaran untuk transaksi yang dipilih.
     */
    public function showPaymentForm($id)
    {
        $transaction = Transaction::with(['customer', 'service'])->find($id);

        if (!$transaction) {
            abort(404, 'Transaksi dengan ID ' . $id . ' tidak ditemukan.');
        }

        if (is_null($transaction->customer)) {
            abort(500, 'Gagal memuat data Pelanggan (customer) untuk transaksi ini. Cek foreign key `customer_id` di tabel transactions, dan pastikan relasi `customer()` di file model `Transaction.php` sudah benar (termasuk "use App\Models\Customer;").');
        }
        
        if (is_null($transaction->service)) {
            abort(500, 'Gagal memuat data Layanan (service) untuk transaksi ini. Cek foreign key `service_id` dan relasi `service()` di file model `Transaction.php` (termasuk "use App\Models\Service;").');
        }

        $paymentMethods = ['Cash', 'Kartu Debit', 'Kartu Kredit', 'QRIS'];

        return view('kasir.pos.form', compact('transaction', 'paymentMethods'));
    }

    /**
     * Memproses pembayaran dan menyimpan ke database.
     */
    public function processPayment(Request $request, Transaction $transaction)
    {
        // 1. Validasi input
        $request->validate([
            'payment_method' => 'required|string',
            'amount_paid' => 'required|numeric|min:' . $transaction->total, // Uang bayar tidak boleh kurang dari total
        ]);

        // 2. Hitung kembalian
        $kembalian = $request->amount_paid - $transaction->total;

        // 3. Update transaksi di database
        $transaction->update([
            'status' => 'Dibayar',
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'change' => $kembalian,
        ]);

        // 4. Redirect ke halaman struk
        return redirect()->route('kasir.struk.show', $transaction->id);
    }

    /**
     * Menampilkan halaman struk pembayaran.
     */
    public function showStruk(Transaction $transaction)
    {
        // Pastikan semua data relasi ter-load untuk struk
        $transaction->load(['customer', 'service', 'user']);

        return view('kasir.pos.struk', compact('transaction'));
    }
}