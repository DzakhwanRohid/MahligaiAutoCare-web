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
        // 4. VALIDASI DASAR
        $request->validate([
            'customer_type' => 'required|in:terdaftar,walkin',
            'service_id' => 'required|exists:services,id',
            'payment_method' => 'required|string',
        ]);

        $customerId = null;

        // 5. LOGIKA PELANGGAN
        if ($request->customer_type == 'walkin') {
            // Jika walk-in, validasi input pelanggan baru
            $walkinData = $request->validate([
                'walkin_name' => 'required|string|max:255',
                'walkin_phone' => 'required|string|max:20',
                'walkin_license_plate' => 'required|string|max:20|unique:customers,license_plate',
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
            // Jika terdaftar, validasi customer_id
            $request->validate(['customer_id' => 'required|exists:customers,id']);
            $customerId = $request->customer_id;
        }

        // 6. LOGIKA HARGA & DISKON
        $service = Service::find($request->service_id);
        $basePrice = $service->price;
        $discountAmount = 0;
        $promotionId = null;

        if ($request->filled('promotion_code')) {
            $promo = Promotion::where('code', $request->promotion_code)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', Carbon::today())
                ->whereDate('end_date', '>=', Carbon::today())
                ->first();

            if ($promo) {
                // Jika promo ditemukan dan valid
                if ($promo->type == 'percentage') {
                    $discountAmount = ($basePrice * $promo->value) / 100;
                } else {
                    $discountAmount = $promo->value;
                }
                $promotionId = $promo->id;
            } else {
                // Jika kode diisi tapi tidak valid
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['promotion_code' => 'Kode promosi tidak valid atau sudah kedaluwarsa.']);
            }
        }

        $totalPrice = $basePrice - $discountAmount;

        // 7. BUAT TRANSAKSI
        $transaction = Transaction::create([
            'customer_id' => $customerId,
            'service_id' => $request->service_id,
            'promotion_id' => $promotionId,
            'transaction_code' => 'INV-' . time(), // Nanti bisa dibuat lebih bagus
            'status' => 'Menunggu', // Status awal saat dibuat
            'payment_method' => $request->payment_method,
            'base_price' => $basePrice,
            'discount' => $discountAmount,
            'total_price' => $totalPrice,
        ]);

        // 8. REDIRECT KE STRUK
        return redirect()->route('pos.struk', $transaction->id) ->with('success', 'Transaksi baru berhasil dibuat!');
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

