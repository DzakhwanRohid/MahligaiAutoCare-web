<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Promotion; // <-- 1. IMPORT PROMOTION
use App\Models\Transaction; // <-- 2. IMPORT TRANSACTION
use Carbon\Carbon;

class POSController extends Controller
{
    /**
     * Menampilkan halaman utama POS
     */
    public function index()
    {
        // 3. KIRIM DATA KE VIEW
        $services = Service::orderBy('name')->get();
        // Ambil pelanggan yang terdaftar (punya user_id) ATAU yang walk-in tapi punya No. Polisi
        $customers = Customer::with('user')
                        ->whereNotNull('license_plate')
                        ->orderBy('name')
                        ->get();

        return view('kasir.pos.index', compact('services', 'customers'));
    }

    /**
     * Menyimpan transaksi baru dari POS
     */
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT DASAR
        $request->validate([
            'customer_type' => 'required|in:terdaftar,walkin',
            'service_id' => 'required|exists:services,id',
            'payment_method' => 'required|string',
            // Validasi 'amount_paid' akan dilakukan di bawah setelah total dihitung
            'promotion_code' => 'nullable|string', // Validasi kode promo (opsional)
        ]);

        // 2. LOGIKA PELANGGAN (Cari atau Buat Baru)
        $customerId = null;
        if ($request->customer_type == 'walkin') {
            // Jika pelanggan baru (walk-in)
            $walkinData = $request->validate([
                'walkin_name' => 'required|string|max:255',
                'walkin_phone' => 'required|string|max:20',
                'walkin_license_plate' => 'required|string|max:20',
                'walkin_vehicle_type' => 'nullable|string|max:100',
            ]);

            // Buat customer baru (status walk-in, user_id = null)
            $newCustomer = Customer::create([
                'name' => $walkinData['walkin_name'],
                'phone' => $walkinData['walkin_phone'],
                'license_plate' => $walkinData['walkin_license_plate'],
                'vehicle_type' => $walkinData['walkin_vehicle_type'],
                'user_id' => null,
            ]);
            $customerId = $newCustomer->id;
        } else {
            // Jika pelanggan terdaftar
            $request->validate(['customer_id' => 'required|exists:customers,id']);
            $customerId = $request->customer_id;
        }

        // 3. LOGIKA HARGA DASAR
        $service = Service::find($request->service_id);
        $basePrice = $service->price;

        // 4. LOGIKA DISKON & PROMOSI (BARU DITAMBAHKAN)
        $discountAmount = 0;
        $promotionId = null;

        if ($request->filled('promotion_code')) {
            $code = strtoupper($request->promotion_code);
            // Cari promo yang kodenya cocok, aktif, dan tanggalnya valid hari ini
            $promo = Promotion::where('code', $code)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', Carbon::today())
                ->whereDate('end_date', '>=', Carbon::today())
                ->first();

            if ($promo) {
                // Jika promo valid, hitung diskon
                if ($promo->type == 'percentage') {
                    $discountAmount = ($basePrice * $promo->value) / 100;
                } else {
                    $discountAmount = $promo->value;
                }
                $promotionId = $promo->id;
            } else {
                // Jika kode diisi tapi salah/kadaluarsa, kembalikan error
                return back()->withErrors(['promotion_code' => 'Kode promosi tidak valid atau sudah berakhir.'])->withInput();
            }
        }

        // Hitung Total Akhir (Harga Asli - Diskon)
        $totalPrice = $basePrice - $discountAmount;
        if ($totalPrice < 0) $totalPrice = 0; // Pastikan total tidak minus

        // 5. LOGIKA PEMBAYARAN & KEMBALIAN
        $amountPaid = 0;
        $change = 0;

        if ($request->payment_method == 'Tunai') {
            // Jika Tunai, validasi 'amount_paid' HARUS >= total harga akhir
            $request->validate([
                'amount_paid' => 'required|numeric|min:' . $totalPrice,
            ], [
                'amount_paid.min' => 'Uang kurang! Total setelah diskon adalah Rp ' . number_format($totalPrice, 0, ',', '.'),
                'amount_paid.required' => 'Masukkan jumlah uang yang diterima.',
            ]);

            $amountPaid = $request->amount_paid;
            $change = $amountPaid - $totalPrice;
        } else {
            // Jika QRIS/Debit, anggap pembayaran LUNAS PAS (Uang diterima = Total Tagihan)
            $amountPaid = $totalPrice;
            $change = 0;
        }

        // 6. Ambil data kendaraan untuk disimpan di transaksi (Snapshot data saat ini)
        $vehicle_plate = $request->walkin_license_plate;
        $vehicle_brand = $request->walkin_vehicle_type;
        if ($request->customer_type == 'terdaftar') {
            $customer = Customer::find($customerId);
            $vehicle_plate = $customer->license_plate;
            $vehicle_brand = $customer->vehicle_type;
        }

        // 7. SIMPAN TRANSAKSI KE DATABASE
        $transaction = Transaction::create([
            'customer_id' => $customerId,
            'service_id' => $request->service_id,
            'promotion_id' => $promotionId, // <-- Menyimpan ID Promo
            'user_id' => \Illuminate\Support\Facades\Auth::id(), // Kasir yang menginput
            'invoice' => 'INV-' . time(),
            'vehicle_brand' => $vehicle_brand,
            'vehicle_plate' => $vehicle_plate,
            'status' => 'Menunggu', // Status awal masuk antrean
            'payment_method' => $request->payment_method,
            'base_price' => $basePrice,
            'discount' => $discountAmount, // <-- Menyimpan Jumlah Diskon
            'total' => $totalPrice,        // <-- Menyimpan Total Akhir
            'amount_paid' => $amountPaid,  // <-- Menyimpan Uang Bayar
            'change' => $change,           // <-- Menyimpan Kembalian
        ]);

        // 8. Redirect ke Halaman Struk
        return redirect()->route('pos.struk', $transaction->id)
                         ->with('success', 'Transaksi berhasil dibuat!');
    }

    /**
     * Menampilkan halaman struk
     */
    public function struk(Transaction $transaction) // Ganti $id menjadi $transaction
    {
        // 9. AMBIL DATA TRANSAKSI (sudah otomatis via Route Model Binding)
        // Kita panggil relasi agar datanya ikut ter-load
        $transaction->load(['customer', 'service', 'promotion']);

        return view('kasir.pos.struk', compact('transaction'));
    }
}

