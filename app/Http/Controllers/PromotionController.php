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

        return redirect()->route('promosi.index')->with('success', 'Promosi baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promotion)
    {
        // Kita tidak pakai 'show' di resource route kita, jadi biarkan kosong
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        // 1. TAMPILKAN VIEW FORM EDIT
        // $promotion otomatis didapat dari ID di URL (Route Model Binding)
        return view('admin.promosi.edit', compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        // 2. VALIDASI INPUT (Mirip store, tapi rule 'unique' diubah)
        $request->validate([
            'name' => 'required|string|max:255',
            // Rule unique: abaikan ID dari promosi yang sedang di-edit
            'code' => 'required|string|max:100|unique:promotions,code,' . $promotion->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        // 3. UPDATE DATA DI DATABASE
        $promotion->update($request->all());

        // 4. KEMBALIKAN KE HALAMAN INDEX DENGAN PESAN SUKSES
        return redirect()->route('promosi.index')->with('success', 'Promosi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        // 1. HAPUS DATA
        $promotion->delete();

        // 2. KEMBALIKAN KE HALAMAN INDEX DENGAN PESAN SUKSES
        return redirect()->route('promosi.index')->with('success', 'Promosi berhasil dihapus.');
    }
}
