<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Promotion; // <-- WAJIB
use App\Models\ContactMessage; // <-- WAJIB
use Carbon\Carbon;          // <-- WAJIB
use Carbon\CarbonPeriod;    // <-- WAJIB
use Illuminate\Support\Facades\Auth; // <-- WAJIB
use Illuminate\Support\Facades\DB; // <-- WAJIB

class HomeController extends Controller
{
    private $tz = 'Asia/Jakarta';
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
        $services = Service::all();
        return view('home.index')
            ->with('promotions', $promotions)
            ->with('services', $services);

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
     * Menyimpan pesan dari form kontak.
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);
        ContactMessage::create($request->all());
        return redirect()->route('home.kontak')->with('success', 'Pesan Anda telah terkirim!');
    }

    /**
     * Menampilkan halaman Pantau Antrean.
     */
   public function pantauAntrian()
    {
        $today = Carbon::now($this->tz);
        $now = $today->clone()->format('H:i'); // Waktu saat ini (string)

        // Definisikan Jam Operasional
        $startHour = 9;
        $endHour = 17; // Jam 5 Sore
        $jamTutup = Carbon::parse($today->format('Y-m-d'), $this->tz)->hour($endHour)->minute(0);

        // Cek apakah jam sekarang sudah melewati jam tutup
        $isClosed = $today->gt($jamTutup);

        // 1. Data untuk 4 Slot Fisik (Yang sedang dicuci SAAT INI)
        $slots = Transaction::where('status', 'Sedang Dicuci')
            ->whereNotNull('slot')
            ->with(['service', 'customer'])
            ->get()
            ->keyBy('slot');

        // 2. Data untuk Jadwal Booking Hari Ini (SEMUA DATA)
        $jadwalHariIni = Transaction::whereDate('booking_date', $today->format('Y-m-d'))
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Sedang Dicuci'])
            ->with('service')
            ->orderBy('booking_date', 'asc')
            ->get();

        // 3. Kebutuhan View 1: $jadwalSlots (Untuk Modal Pop-up)
        $jadwalSlots = $jadwalHariIni->groupBy('slot');

        // 4. Kebutuhan View 2: $bookedSlots (Untuk Timeline Grid Bawah)
        //    INI YANG HILANG SEBELUMNYA
        $bookedSlots = $jadwalHariIni
            ->map(function ($item) {
                return Carbon::parse($item->booking_date)->format('H:i');
            })
            ->toArray();

        // 5. Pesanan Aktif Milik User
        $myActiveBookings = collect();
        if (Auth::check() && Auth::user()->customer) {
            $myActiveBookings = Transaction::with('service')
                ->where('customer_id', Auth::user()->customer->id)
                ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Sedang Dicuci'])
                ->orderBy('booking_date', 'asc')
                ->get();
        }

        return view('home.pantau', compact(
            'slots',
            'jadwalSlots',
            'bookedSlots', // <-- SEKARANG SUDAH DIKIRIM
            'startHour',
            'endHour',
            'now', // Variabel $now diubah jadi $nowString
            'today',
            'myActiveBookings',
            'isClosed'
        ));
    }
    /**
     * Menampilkan halaman form pemesanan.
     */
    public function showPemesanan()
    {
        $services = Service::orderBy('name')->get();
        $user = Auth::user();
        $customer = $user ? $user->customer : null;
        return view('home.pemesanan', compact('services', 'user', 'customer'));
    }

    /**
     * ==========================================================
     * OTAK SISTEM (VERSI BARU YANG DIPERBAIKI)
     * ==========================================================
     */
    public function getAvailableSchedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'service_id' => 'required|exists:services,id',
        ]);

        $tanggal = $request->date;
        $service = Service::find($request->service_id);
        $durasiLayanan = $service->duration_minutes; // Misal: 60

        $jamBuka = Carbon::parse($tanggal, $this->tz)->hour(9)->minute(0);
        $jamTutup = Carbon::parse($tanggal, $this->tz)->hour(17)->minute(0);
        $now = Carbon::now($this->tz);

        // 1. Ambil semua booking yang ada di tanggal itu
        $bookings = Transaction::whereDate('booking_date', $tanggal)
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Sedang Dicuci'])
            ->with('service') // Penting untuk ambil durasi booking lama
            ->get();

        $jadwalTersedia = [ 1 => [], 2 => [], 3 => [], 4 => [] ];
        $interval = 15; // Cek ketersediaan setiap 15 menit

        // 2. Loop untuk setiap 4 Slot
        for ($slotId = 1; $slotId <= 4; $slotId++) {

            // Ambil semua booking HANYA untuk slot ini
            $bookingsInThisSlot = $bookings->where('slot', $slotId);

            // Buat daftar slot waktu (09:00, 09:15, 09:30...)
            $period = CarbonPeriod::create($jamBuka, $interval.' minutes', $jamTutup->clone()->subMinutes($durasiLayanan));

            foreach ($period as $waktuMulaiCek) {
                // $waktuMulaiCek adalah calon jam booking (misal: 09:15)

                // Cek 1: Apakah jam ini sudah lewat?
                if ($waktuMulaiCek->isToday() && $waktuMulaiCek->lt($now)) {
                    continue; // Skip, jam sudah lewat
                }

                $waktuSelesaiCek = $waktuMulaiCek->clone()->addMinutes($durasiLayanan);

                // Cek 2: Apakah jam ini bertabrakan dengan booking lain di slot ini?
                $isClear = true;
                foreach ($bookingsInThisSlot as $tx) {
                    $txStart = Carbon::parse($tx->booking_date, $this->tz);
                    $txEnd = $txStart->clone()->addMinutes($tx->service->duration_minutes);

                    // Cek tumpang tindih (Overlap check)
                    // Jika (MulaiBaru < SelesaiLama) DAN (SelesaiBaru > MulaiLama)
                    if ($waktuMulaiCek->lt($txEnd) && $waktuSelesaiCek->gt($txStart)) {
                        $isClear = false; // Ada tabrakan!
                        break;
                    }
                }

                // Cek 3: Jika lolos 2 cek di atas, slot ini AMAN
                if ($isClear) {
                    $jadwalTersedia[$slotId][] = $waktuMulaiCek->format('H:i');
                }
            }
        }

        return response()->json($jadwalTersedia);
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
            'booking_date' => 'required|date_format:Y-m-d H:i:s',
            'slot' => 'required|integer|in:1,2,3,4',
            'payment_method' => 'required|in:Tunai,Transfer,QRIS',
            'payment_proof' => 'required_if:payment_method,Transfer|image|max:2048',
            'payment_proof_qris' => 'required_if:payment_method,QRIS|image|max:2048',
            'final_base_price' => 'required|numeric',
            'final_discount_amount' => 'required|numeric',
            'final_total_price' => 'required|numeric',
        ]);

        // 2. CEK KETERSEDIAAN SLOT (Validasi Ganda)
        $service = Service::find($request->service_id);
        $waktuMulai = Carbon::parse($request->booking_date);
        $waktuSelesai = $waktuMulai->clone()->addMinutes($service->duration_minutes);
        $tz = 'Asia/Jakarta';

        $isBooked = Transaction::where('slot', $request->slot)
            ->whereDate('booking_date', $waktuMulai->format('Y-m-d'))
            ->whereIn('status', ['Menunggu', 'Terkonfirmasi', 'Sedang Dicuci'])
            ->where(function ($query) use ($waktuMulai, $waktuSelesai, $tz) {
                // Cek tumpang tindih
                $query->where(function($q) use ($waktuMulai, $waktuSelesai) {
                    $q->where('booking_date', '<', $waktuSelesai)
                    ->where(DB::raw("DATE_ADD(booking_date, INTERVAL (SELECT duration_minutes FROM services WHERE id = transactions.service_id) MINUTE)"), '>', $waktuMulai);
                });
            })
            ->exists();

        if ($isBooked) {
            return back()->withErrors(['booking_date' => 'Mohon maaf, jam tersebut baru saja diambil orang lain.'])->withInput();
        }

        // 3. LOGIKA CUSTOMER
        $user = Auth::user();
        $customerId = null;
        if ($user) {
            $customer = $user->customer;
            if(!$customer) {
                $customer = Customer::create([ 'user_id' => $user->id, 'name' => $request->name, 'email' => $user->email, 'phone' => $request->phone, 'license_plate' => $request->license_plate, 'vehicle_type' => $request->vehicle_type, ]);
            } else {
                $customer->update([ 'phone' => $request->phone, 'license_plate' => $request->license_plate, 'vehicle_type' => $request->vehicle_type, ]);
            }
            $customerId = $customer->id;
        } else {
            $customer = Customer::firstOrCreate(
                ['license_plate' => $request->license_plate],
                ['name' => $request->name, 'phone' => $request->phone, 'vehicle_type' => $request->vehicle_type]
            );
            $customerId = $customer->id;
        }

        // 4. AMBIL HARGA FINAL DARI JS
        $total = $request->final_total_price;
        $basePrice = $request->final_base_price;
        $discountAmount = $request->final_discount_amount;
        $promotionId = null;

        if($request->filled('promotion_code') && $discountAmount > 0) {
            $promo = Promotion::where('code', $request->promotion_code)->first();
            if($promo) $promotionId = $promo->id;
        }

        // 5. UPLOAD BUKTI BAYAR
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('bukti_bayar', 'public');
        } elseif ($request->hasFile('payment_proof_qris')) {
            $proofPath = $request->file('payment_proof_qris')->store('bukti_bayar', 'public');
        }

        // 6. SIMPAN TRANSAKSI
        Transaction::create([
            'invoice' => 'BKG-' . time(),
            'customer_id' => $customerId,
            'service_id' => $request->service_id,
            'promotion_id' => $promotionId,
            'user_id' => $user ? $user->id : 1,
            'vehicle_brand' => $request->vehicle_type,
            'vehicle_plate' => $request->license_plate,
            'total' => $total,
            'base_price' => $basePrice,
            'discount' => $discountAmount,
            'status' => 'Menunggu', // Status awal (perlu verifikasi Admin)
            'payment_method' => $request->payment_method,
            'booking_date' => $request->booking_date,
            'slot' => $request->slot,
            'payment_proof' => $proofPath,
        ]);

        return redirect()->route('home.pantau')->with('success', 'Booking berhasil! Menunggu konfirmasi dari Admin.');
    }
}
