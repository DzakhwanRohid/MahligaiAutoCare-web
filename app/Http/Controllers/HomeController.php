<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Customer;
use App\Models\ContactMessage;
use App\Models\Transaction;
use App\Models\Promotion;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman Beranda (Homepage).
     */
    public function index()
    {
        $promotions = Promotion::where('is_active', true)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->latest()
            ->get();

        return view('home.index', compact('promotions'));
    }

    /**
     * Menampilkan halaman Layanan.
     */
    public function layanan()
    {
        $services = Service::all();
        return view('home.layanan', compact('services'));
    }

    /**
     * Menampilkan halaman Tentang Kami.
     */
    public function tentangKami()
    {
        return view('home.tentang');
    }

    /**
     * Menampilkan halaman Kontak.
     */
    public function kontak()
    {
        return view('home.kontak');
    }

    /**
     * ==========================================================
     * PERUBAHAN: Menampilkan halaman Pantau Antrean (Slot & Jadwal)
     * ==========================================================
     */
   public function pantauAntrian()
    {
        // 1. Data untuk 4 Slot Fisik (Yang sedang dicuci) - Tetap Sama
        $slots = Transaction::where('status', 'Sedang Dicuci')
            ->whereNotNull('slot')
            ->with(['service', 'customer'])
            ->get()
            ->keyBy('slot');

        // 2. Data untuk Jadwal Booking Hari Ini (Timeline) - Tetap Sama
        $today = Carbon::now()->format('Y-m-d');
        $now = Carbon::now()->format('H:i');

        $bookedSlots = Transaction::whereDate('booking_date', $today)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Sedang Dicuci'])
            ->get()
            ->map(function ($item) {
                return Carbon::parse($item->booking_date)->format('H:i');
            })
            ->toArray();

        $startHour = 9;
        $endHour = 17;

        // 3. (BARU) Ambil Pesanan Aktif Milik User yang Sedang Login
        $myActiveBookings = collect(); // Buat koleksi kosong

        if (Auth::check() && Auth::user()->customer) {
            $myActiveBookings = Transaction::with('service')
                ->where('customer_id', Auth::user()->customer->id)
                // Hanya ambil yang statusnya belum selesai
                ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Sedang Dicuci'])
                ->orderBy('booking_date', 'asc')
                ->get();
        }

        return view('home.pantau', compact(
            'slots',
            'bookedSlots',
            'startHour',
            'endHour',
            'now',
            'today',
            'myActiveBookings' // <-- Kirim data pesanan aktif ke view
        ));
    }
    /**
     * API AJAX: Mengecek slot waktu yang sudah terisi.
     */
    public function getSlots(Request $request)
    {
        $date = $request->date;

        if (!$date) {
            return response()->json([]);
        }

        try {
            $bookedSlots = Transaction::whereDate('booking_date', $date)
                ->whereIn('status', ['Menunggu', 'Sedang Dicuci'])
                ->get()
                ->map(function ($item) {
                    return Carbon::parse($item->booking_date)->format('H:i');
                })
                ->toArray();

            $today = Carbon::now()->format('Y-m-d');
            $currentTime = Carbon::now()->format('H:i');

            return response()->json([
                'booked' => $bookedSlots,
                'is_today' => ($date == $today),
                'current_time' => $currentTime
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: '.$e->getMessage()], 500);
        }
    }

    /**
     * API AJAX: Mengecek kode promo.
     */
    public function checkPromo(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'promo_code' => 'required|string',
        ]);

        $service = Service::find($request->service_id);
        $basePrice = $service->price;
        $discountAmount = 0;
        $totalPrice = $basePrice;

        $code = strtoupper($request->promo_code);
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
            $totalPrice = $basePrice - $discountAmount;
            if ($totalPrice < 0) $totalPrice = 0;

            return response()->json([
                'success' => true,
                'base_price' => $basePrice,
                'discount' => $discountAmount,
                'total' => $totalPrice,
                'message' => 'Kode promo "' . $promo->code . '" berhasil dipakai!',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'base_price' => $basePrice,
                'discount' => 0,
                'total' => $basePrice,
                'message' => 'Kode promo tidak valid atau kadaluarsa.',
            ], 404);
        }
    }

    /**
     * Menyimpan data pemesanan dari form.
     */
    public function storePemesanan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'license_plate' => 'required|string|max:20',
            'vehicle_type' => 'required|string|max:100',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'payment_method' => 'required|in:Tunai,Transfer,QRIS',
            'payment_proof' => 'required_if:payment_method,Transfer|image|max:2048',
            'payment_proof_qris' => 'required_if:payment_method,QRIS|image|max:2048',
        ]);

        $isBooked = Transaction::where('booking_date', $request->booking_date)
            ->whereIn('status', ['Menunggu', 'Sedang Dicuci'])
            ->exists();

        if ($isBooked) {
            return back()->withErrors(['booking_date' => 'Mohon maaf, jam tersebut baru saja diambil orang lain.'])->withInput();
        }

        $user = Auth::user();
        $customerId = null;

        if ($user) {
            $customer = $user->customer;
            if (!$customer) {
                $customer = Customer::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'email' => $user->email,
                    'phone' => $request->phone,
                    'license_plate' => $request->license_plate,
                    'vehicle_type' => $request->vehicle_type,
                ]);
            } else {
                $customer->update([
                    'phone' => $request->phone,
                    'license_plate' => $request->license_plate,
                    'vehicle_type' => $request->vehicle_type,
                ]);
            }
            $customerId = $customer->id;
        } else {
            $customer = Customer::updateOrCreate(
                ['license_plate' => $request->license_plate],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'vehicle_type' => $request->vehicle_type,
                ]
            );
            $customerId = $customer->id;
        }

        $total = $request->final_total_price;
        $basePrice = $request->final_base_price;
        $discountAmount = $request->final_discount_amount;
        $promotionId = null;

        if($request->filled('promotion_code') && $discountAmount > 0) {
             $promo = Promotion::where('code', $request->promotion_code)->first();
             if($promo) $promotionId = $promo->id;
        }

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('bukti_bayar', 'public');
        } elseif ($request->hasFile('payment_proof_qris')) {
            $proofPath = $request->file('payment_proof_qris')->store('bukti_bayar', 'public');
        }

        Transaction::create([
            'invoice' => 'BKG-' . time(),
            'customer_id' => $customerId,
            'service_id' => $request->service_id,
            'user_id' => $user ? $user->id : 1,
            'vehicle_brand' => $request->vehicle_type,
            'vehicle_plate' => $request->license_plate,
            'total' => $total,
            'base_price' => $basePrice,
            'discount' => $discountAmount,
            'promotion_id' => $promotionId,
            'status' => 'Menunggu',
            'payment_method' => $request->payment_method,
            'booking_date' => $request->booking_date,
            'payment_proof' => $proofPath,
        ]);

        return redirect()->route('home.pantau')->with('success', 'Booking berhasil! Silakan datang sesuai jadwal.');
    }


    // /Kontak
    public function storeContact(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10',
    ]);

    ContactMessage::create([
        'name' => $request->name,
        'email' => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

   return redirect()->route('home.kontak')->with('success', 'Pesan Anda telah terkirim! Terima kasih atas masukan Anda.');
}
}
