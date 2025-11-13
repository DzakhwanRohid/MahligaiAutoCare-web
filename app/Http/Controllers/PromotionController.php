<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promosi.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promosi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:promotions,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        Promotion::create($request->all());

        // PERBAIKAN: Gunakan route 'admin.promosi.index'
        return redirect()->route('admin.promosi.index')->with('success', 'Promosi baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promosi)
    {
        // Kosong
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promosi) // Gunakan $promosi sesuai parameter route
    {
        // PERBAIKAN: Kirim data sebagai 'promotion' agar view tidak error
        return view('admin.promosi.edit', ['promotion' => $promosi]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promosi) // Gunakan $promosi
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // PERBAIKAN: Gunakan $promosi->id untuk pengecualian unique
            'code' => 'required|string|max:100|unique:promotions,code,' . $promosi->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        // PERBAIKAN: Gunakan variabel $promosi
        $promosi->update($request->all());

        return redirect()->route('admin.promosi.index')->with('success', 'Promosi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promosi) // Gunakan $promosi
    {
        // PERBAIKAN: Gunakan variabel $promosi
        $promosi->delete();

        return redirect()->route('admin.promosi.index')->with('success', 'Promosi berhasil dihapus.');
    }
}
