<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Menampilkan halaman Beranda (Homepage).
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * Menampilkan halaman Layanan.
     */
    public function layanan()
    {

        return view('home.layanan');
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
    public function pantauAntrian() {
    return view('home.pantau');
}
public function pemesanan_create()
{
    $services = Service::orderBy('name')->get();
    return view('home.pemesanan', compact('services'));
}
public function showPemesanan()
{
    return view('home.pemesanan');
}
public function storePemesanan(Request $request)
{
    // ... Logika Validasi dan Penyimpanan ke Database (Customer dan Transaction)
    // ...
    return redirect()->route('pantau-antrian')->with('success', 'Pemesanan berhasil!');

}
}
