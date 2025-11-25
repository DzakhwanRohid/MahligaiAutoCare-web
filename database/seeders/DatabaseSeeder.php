<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Promotion;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin dan Kasir
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mahligai.com',
            'role' => 'admin',
            'password' => Hash::make('password'), // Password: password
        ]);

        $kasir = User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@mahligai.com',
            'role' => 'kasir',
            'password' => Hash::make('password'), // Password: password
        ]);

        // Isi Settings
        $settings = [
            'business_name' => 'Mahligai Auto Care',
            'business_address' => 'Jl. Soekarno Hatta No. 88, Pekanbaru, Riau',
            'business_phone' => '6281234567890',
            'whatsapp_api_url' => '',
            'whatsapp_api_token' => '',
        ];
        foreach ($settings as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }

        // Isi Layanan & Harga (SERVICES)
        $services = [
            [
                'name' => 'Cuci Body Hidrolik',
                'description' => 'Pencucian seluruh bagian luar mobil menggunakan hidrolik untuk membersihkan kolong.',
                'price' => 60000,
            ],
            [
                'name' => 'Cuci Komplit + Vacuum',
                'description' => 'Cuci body luar, semir ban, dan pembersihan interior dengan vacuum cleaner.',
                'price' => 85000,
            ],
            [
                'name' => 'Premium Wax & Polish',
                'description' => 'Cuci bersih ditambah aplikasi wax premium untuk kilau tahan lama.',
                'price' => 200000,
            ],
            [
                'name' => 'Full Auto Detailing',
                'description' => 'Paket lengkap: Jamur kaca, poles body, cleaning interior, dan ruang mesin.',
                'price' => 850000,
            ],
        ];

        foreach ($services as $svc) {
            Service::create($svc);
        }

        // Isi Diskon & Promosi
        Promotion::create([
            'name' => 'Diskon Grand Opening',
            'code' => 'MAHLIGAI20',
            'type' => 'percentage',
            'value' => 20, // 20%
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addMonths(1),
            'is_active' => true,
        ]);

        Promotion::create([
            'name' => 'Potongan Akhir Pekan',
            'code' => 'HEMAT15RB',
            'type' => 'fixed',
            'value' => 15000, // Rp 15.000
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addMonths(2),
            'is_active' => true,
        ]);

        // Isi Data Pelanggan (CUSTOMERS)
        $customersData = [
            ['name' => 'Budi Santoso', 'phone' => '081211112222', 'license_plate' => 'BM 1234 AA', 'vehicle_type' => 'Toyota Avanza'],
            ['name' => 'Siti Aminah', 'phone' => '081333334444', 'license_plate' => 'BM 5678 BB', 'vehicle_type' => 'Honda Jazz'],
            ['name' => 'Rudi Hartono', 'phone' => '081999998888', 'license_plate' => 'BM 9012 CC', 'vehicle_type' => 'Mitsubishi Pajero'],
            ['name' => 'Dewi Sartika', 'phone' => '085212345678', 'license_plate' => 'BM 3456 DD', 'vehicle_type' => 'Daihatsu Sigra'],
        ];

        $customers = [];
        foreach ($customersData as $data) {
            $customers[] = Customer::create($data);
        }

        // 6. Isi Data

        // layanan untuk contoh
        $svc1 = Service::where('name', 'Cuci Body Hidrolik')->first();
        $svc2 = Service::where('name', 'Cuci Komplit + Vacuum')->first();
        $svc3 = Service::where('name', 'Premium Wax & Polish')->first();

        // TRANSAKSI 1: SELESAI (Pendapatan Masuk)
        Transaction::create([
            'customer_id' => $customers[0]->id,
            'service_id' => $svc1->id,
            'user_id' => $kasir->id,
            'invoice' => 'INV-' . time(),
            'vehicle_brand' => $customers[0]->vehicle_type,
            'vehicle_plate' => $customers[0]->license_plate,
            'status' => 'Selesai',
            'payment_method' => 'Tunai',
            'base_price' => $svc1->price,
            'discount' => 0,
            'total' => $svc1->price,
            'created_at' => Carbon::now()->subHours(2),
        ]);

        // TRANSAKSI 2: SEDANG DICUCI (Masuk Kanban "Sedang Dicuci")
        Transaction::create([
            'customer_id' => $customers[1]->id,
            'service_id' => $svc2->id,
            'user_id' => $kasir->id,
            'invoice' => 'INV-' . (time() + 1),
            'vehicle_brand' => $customers[1]->vehicle_type,
            'vehicle_plate' => $customers[1]->license_plate,
            'status' => 'Sedang Dicuci',
            'payment_method' => 'QRIS',
            'base_price' => $svc2->price,
            'discount' => 0,
            'total' => $svc2->price,
            'created_at' => Carbon::now()->subMinutes(30),
        ]);

        // TRANSAKSI 3: MENUNGGU (Masuk Kanban "Antrean")
        Transaction::create([
            'customer_id' => $customers[2]->id,
            'service_id' => $svc3->id,
            'user_id' => $kasir->id,
            'invoice' => 'INV-' . (time() + 2),
            'vehicle_brand' => $customers[2]->vehicle_type,
            'vehicle_plate' => $customers[2]->license_plate,
            'status' => 'Menunggu',
            'payment_method' => 'Tunai',
            'base_price' => $svc3->price,
            'discount' => 0,
            'total' => $svc3->price,
            'created_at' => Carbon::now(),
        ]);
    }
}
