<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('user')->latest()->paginate(10);
        return view('admin.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            // No. Polisi harus unik di tabel customers
            'license_plate' => 'required|string|max:20|unique:customers,license_plate',
            'vehicle_type' => 'nullable|string|max:100',
        ]);

        // Simpan sebagai 'walk-in' (user_id = null)
        Customer::create($request->all());

        return redirect()->route('customer.index')->with('success', 'Pelanggan (walk-in) baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Tidak kita gunakan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            // Validasi unik, tapi abaikan ID customer ini sendiri
            'license_plate' => 'required|string|max:20|unique:customers,license_plate,' . $customer->id,
            'vehicle_type' => 'nullable|string|max:100',
        ]);

        // Update data
        $customer->update($request->all());

        return redirect()->route('customer.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // LOGIKA KEAMANAN PENTING:

        // 1. Cek jika pelanggan punya akun user
        if ($customer->user) {
            return redirect()->route('customer.index')
                ->with('error', 'Tidak bisa menghapus pelanggan ini. Dia terdaftar sebagai User. Hapus Akun User-nya terlebih dahulu di Manajemen User.');
        }

        // 2. Cek jika pelanggan punya riwayat transaksi
        if ($customer->transactions()->count() > 0) {
            return redirect()->route('customer.index')
                ->with('error', 'Tidak bisa menghapus pelanggan ini. Dia memiliki riwayat transaksi.');
        }

        // 3. Jika aman (walk-in dan tidak punya transaksi), hapus.
        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
