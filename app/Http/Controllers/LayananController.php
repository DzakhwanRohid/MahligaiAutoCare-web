<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = Service::query();

        // 2. Logika Search (Jika ada input 'search' dari user)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 3. Ambil data dengan Pagination (10 baris per halaman)
        // withQueryString() penting agar saat pindah halaman, kata kunci pencarian tidak hilang
        $services = $query->latest()->paginate(5)->withQueryString();

        return view('admin.layanan.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15', // <-- VALIDASI BARU
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('layanan', 'public');

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes, // <-- SIMPAN BARU
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        // Tidak kita gunakan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $layanan) // Gunakan $layanan (sesuai perbaikan terakhir)
    {
        return view('admin.layanan.edit', ['service' => $layanan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $layanan) // Gunakan $layanan
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $layanan->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:15', // <-- VALIDASI BARU
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('_token', '_method', 'image');

        if ($request->hasFile('image')) {
            if ($layanan->image) {
                Storage::disk('public')->delete($layanan->image);
            }
            $data['image'] = $request->file('image')->store('layanan', 'public');
        }

        $data['duration_minutes'] = $request->duration_minutes; // <-- UPDATE BARU

        $layanan->update($data);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $layanan) // Gunakan $layanan
    {
        if ($layanan->image) {
            Storage::disk('public')->delete($layanan->image);
        }

        $layanan->delete();

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
