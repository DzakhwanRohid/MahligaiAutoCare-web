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
    public function index()
    {
        $services = Service::latest()->paginate(10);
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
            'name' => 'required|string|max:255|unique:services,name', // Tambah unique
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('layanan', 'public');

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return redirect()->route('layanan.index')->with('success', 'Layanan baru berhasil ditambahkan.');
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
    public function edit(Service $service) // <-- Diubah dari string $id
    {
        // Tampilkan view edit dan kirim data layanan yang mau diedit
        return view('admin.layanan.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service) // <-- Diubah dari string $id
    {
        // Validasi (gambar 'nullable' artinya boleh kosong)
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id, // unique tapi abaikan id ini
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Boleh kosong
        ]);

        $data = $request->except('_token', '_method', 'image');

        // Logika Update Gambar
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama (jika ada)
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            // 2. Upload gambar baru
            $data['image'] = $request->file('image')->store('layanan', 'public');
        }

        // Update data di database
        $service->update($data);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service) // <-- Diubah dari string $id
    {
        // 1. Hapus gambar dari storage (jika ada)
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        // 2. Hapus data dari database
        $service->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
