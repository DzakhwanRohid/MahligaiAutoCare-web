<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Logic Search: Mencari berdasarkan Nama, No HP, atau Plat Nomor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }
        $customers = $query->latest()->paginate(10)->withQueryString();
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

        return redirect()->route('admin.customer.index')->with('success', 'Pelanggan (walk-in) baru berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Tidak digunakan
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
            'license_plate' => 'nullable|string|max:20|unique:customers,license_plate,' . $customer->id,
            'vehicle_type' => 'nullable|string|max:100',
        ]);
        // 1. Update Data di Tabel Customers
        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'license_plate' => $request->license_plate,
            'vehicle_type' => $request->vehicle_type,
        ]);
        // 2.Update Data di Tabel Users (Jika punya akun)
        if ($customer->user_id) {
            $user = \App\Models\User::find($customer->user_id);
            if ($user) {
                $user->update([
                    'name' => $request->name, // Sinkronkan Nama
                    // Email tidak kita update di sini demi keamanan login,
                    // kecuali Anda ingin admin bisa ubah email login user juga.
                ]);
            }
        }
        return redirect()->route('admin.customer.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // 1. Cek jika pelanggan punya akun user
        if ($customer->user) {
            return redirect()->route('admin.customer.index')
                ->with('error', 'Tidak bisa menghapus pelanggan ini. Dia terdaftar sebagai User. Hapus Akun User-nya terlebih dahulu di Manajemen User.');
        }
        // 2. Cek jika pelanggan punya riwayat transaksi
        if ($customer->transactions()->count() > 0) {
            return redirect()->route('admin.customer.index')
                ->with('error', 'Tidak bisa menghapus pelanggan ini. Dia memiliki riwayat transaksi.');
        }
        // 3. Jika aman (walk-in dan tidak punya transaksi), hapus.
        $customer->delete();
        return redirect()->route('admin.customer.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
