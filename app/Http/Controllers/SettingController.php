<?php

namespace App\Http\Controllers;

use App\Models\Setting; // <-- 1. IMPORT MODEL
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman form pengaturan.
     */
    public function index()
    {
        // 2. Ambil semua data dari tabel settings
        // Gunakan pluck() untuk mengubah koleksi Eloquents
        // menjadi array PHP sederhana ['key' => 'value']
        $settings = Setting::pluck('value', 'key')->all();

        // 3. Kirim array settings ke view
        return view('admin.pengaturan.index', compact('settings'));
    }

    /**
     * Menyimpan data pengaturan.
     */
    public function update(Request $request)
    {
        // 4. Ambil semua data input kecuali _token
        $data = $request->except('_token');

        // 5. Lakukan perulangan untuk setiap key (misal: 'business_name')
        foreach ($data as $key => $value) {

            // 6. Gunakan updateOrCreate()
            // - Jika 'key' = 'business_name' sudah ada, update 'value'-nya
            // - Jika 'key' = 'business_name' belum ada, buat baris baru
            Setting::updateOrCreate(
                ['key' => $key],       // Kriteria pencarian
                ['value' => $value ?? ''] // Data yang di-update atau di-create
            );
        }

        // 7. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
