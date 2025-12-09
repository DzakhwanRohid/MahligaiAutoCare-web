<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman form pengaturan.
     */
    public function index()
    {
        // 2. Ambil semua data dari tabel settings
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
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }
        // 7. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
