<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Promotion;
use App\Models\Transaction;
use App\Models\Setting;
use Carbon\Carbon;

class POSController extends Controller
{
    /**
     * Menampilkan halaman utama POS
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('license_plate', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(5);
        $customers->appends(['search' => $search]);
        $services = Service::all();
        $setting = Setting::first();
        return view('kasir.pos.index', compact('customers', 'services', 'setting'));
    }

    /**
     * API untuk pencarian pelanggan (Select2 compatible)
     */
    public function searchCustomers(Request $request)
    {
        $search = $request->input('q');
        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('license_plate', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'phone', 'license_plate', 'vehicle_type')
            ->limit(5)
            ->get();
        $formattedCustomers = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => $customer->license_plate . ' - ' . $customer->name . ' (' . $customer->phone . ')',
                'license_plate' => $customer->license_plate,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'vehicle_type' => $customer->vehicle_type
            ];
        });
        return response()->json([
            'results' => $formattedCustomers,
            'pagination' => ['more' => false]
        ]);
    }

    /**
     * Menyimpan transaksi baru dari POS
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_type' => 'required|in:terdaftar,walkin',
            'service_id' => 'required|exists:services,id',
            'payment_method' => 'required|string',
            'promotion_code' => 'nullable|string',
        ]);
        $customerId = null;
        if ($request->customer_type == 'walkin') {
            $walkinData = $request->validate([
                'walkin_name' => 'required|string|max:255',
                'walkin_phone' => 'required|string|max:20',
                'walkin_license_plate' => 'required|string|max:20',
                'walkin_vehicle_type' => 'nullable|string|max:100',
            ]);
            $newCustomer = Customer::create([
                'name' => $walkinData['walkin_name'],
                'phone' => $walkinData['walkin_phone'],
                'license_plate' => $walkinData['walkin_license_plate'],
                'vehicle_type' => $walkinData['walkin_vehicle_type'],
                'user_id' => null,
            ]);
            $customerId = $newCustomer->id;
        } else {
            $request->validate(['customer_id' => 'required|exists:customers,id']);
            $customerId = $request->customer_id;
        }
        $service = Service::find($request->service_id);
        $basePrice = $service->price;
        $discountAmount = 0;
        $promotionId = null;
        if ($request->filled('promotion_code')) {
            $code = strtoupper($request->promotion_code);
            $promo = Promotion::where('code', $code)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', Carbon::today())
                ->whereDate('end_date', '>=', Carbon::today())
                ->first();
            if ($promo) {
                if ($promo->type == 'percentage') {
                    $discountAmount = ($basePrice * $promo->value) / 100;
                } else {
                    $discountAmount = $promo->value;
                }
                $promotionId = $promo->id;
            } else {
                return back()->withErrors(['promotion_code' => 'Kode promosi tidak valid atau sudah berakhir.'])->withInput();
            }
        }
        // Hitung Total Akhir (Harga Asli - Diskon)
        $totalPrice = $basePrice - $discountAmount;
        if ($totalPrice < 0) $totalPrice = 0;
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
        // Jika kasir memilih slot, pastikan slot itu belum dipakai
        if ($request->filled('selected_slot')) {
            $isFilled = Transaction::where('status', 'Sedang Dicuci')
                ->where('slot', $request->selected_slot)
                ->exists();
            if ($isFilled) {
                return back()->withErrors(['selected_slot' => 'Slot ' . $request->selected_slot . ' sudah terisi!'])->withInput();
            }
        }
        $status = 'Menunggu';
        $slot = null;
        if ($request->filled('selected_slot')) {
            $status = 'Sedang Dicuci';
            $slot = $request->selected_slot;
        }
        // 7. SIMPAN TRANSAKSI KE DATABASE
        $transaction = Transaction::create([
            'customer_id' => $customerId,
            'service_id' => $request->service_id,
            'promotion_id' => $promotionId,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'invoice' => 'INV-' . time(),
            'vehicle_brand' => $vehicle_brand,
            'vehicle_plate' => $vehicle_plate,
            'status' => 'Menunggu',
            'payment_method' => $request->payment_method,
            'base_price' => $basePrice,
            'discount' => $discountAmount,
            'total' => $totalPrice,
            'amount_paid' => $amountPaid,
            'change' => $change,
            'status' => $status,
            'slot' => $slot,
        ]);
        // 8. Redirect ke Halaman Struk
        return redirect()->route('pos.struk', $transaction->id)
            ->with('success', 'Transaksi berhasil! Mobil masuk ke ' . ($slot ? 'Slot ' . $slot : 'Antrean Menunggu'));
    }
    /**
     * Menampilkan halaman struk
     */
    public function struk(Transaction $transaction)
    {
        // 9. AMBIL DATA TRANSAKSI
        $transaction->load(['customer', 'service', 'promotion']);
        return view('kasir.pos.struk', compact('transaction'));
    }
}
