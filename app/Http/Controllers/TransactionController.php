<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Menampilkan halaman riwayat transaksi.
     */
    public function index()
    {
        $transactions = Transaction::with(['customer', 'service'])
                                    ->latest() // Urutkan dari yang terbaru
                                    ->paginate(10); // Buat paginasi

        return view('admin.transaksi', compact('transactions'));
    }

    public function kasirIndex()
    {
        // Ganti dummy data dengan query Eloquent
        $transactions = Transaction::with(['customer', 'service'])
                                    ->latest()
                                    ->paginate(10);
        
        // Daftar status tetap kita perlukan untuk dropdown
        $statuses = ['Ditunggu', 'Dalam Proses', 'Selesai'];

        return view('kasir.transaksi', compact('transactions', 'statuses'));
    }

    public function vehicleStatus()
    {
        // Ganti dummy data dengan query Eloquent
        // Ambil transaksi yang statusnya belum 'Selesai' atau 'Dibayar'
        $transactions = Transaction::with('customer')
                                    ->whereIn('status', ['Ditunggu', 'Dalam Proses'])
                                    ->get();
        
        $statuses = ['Ditunggu', 'Dalam Proses', 'Selesai'];

        return view('kasir.status', compact('transactions', 'statuses'));
    }


    /* Menampilkan form untuk membuat transaksi baru.*/
    public function create()
    {
        // Ganti dummy data dengan data dari database
        $services = Service::orderBy('name', 'asc')->get();

        return view('kasir.create', compact('services'));
    }


    /* Menyimpan transaksi baru ke database.*/
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'vehicle_brand' => 'required|string|max:255',
            'vehicle_plate' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id' // Cek apakah service_id ada di tabel services
        ]);

        // 2. Cari atau buat customer baru
        // Ini akan mencari customer dengan nomor HP yang sama,
        // jika tidak ada, akan membuat data baru.
        $customer = Customer::firstOrCreate(
            ['phone' => $request->customer_phone],
            ['name' => $request->customer_name]
        );

        // 3. Ambil detail layanan (terutama harga)
        $service = Service::find($request->service_id);

        // 4. Buat transaksi baru
        $transaction = Transaction::create([
            'invoice' => 'INV-' . date('Ymd') . '-' . rand(100, 999), // Buat invoice unik (sementara)
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'user_id' => Auth::id(), // ID kasir yang sedang login
            'vehicle_brand' => $request->vehicle_brand,
            'vehicle_plate' => $request->vehicle_plate,
            'total' => $service->price, // Ambil harga dari tabel services
            'status' => 'Ditunggu' // Status default
        ]);

        // 5. Redirect dengan pesan sukses
        return redirect()->route('kasir.transaksi')
                         ->with('success', 'Transaksi baru ' . $transaction->invoice . ' berhasil didaftarkan!');
        
        
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        // 1. Validasi input
        $request->validate([
            'status' => 'required|string|in:Ditunggu,Dalam Proses,Selesai'
        ]);

        // 2. Update status di database
        $transaction->status = $request->status;
        $transaction->save();

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status untuk invoice ' . $transaction->invoice . ' berhasil diupdate menjadi ' . $request->status);
    }
}