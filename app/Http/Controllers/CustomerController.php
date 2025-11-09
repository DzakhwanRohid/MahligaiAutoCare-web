<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name', 'asc')->paginate(10);
        return view('kasir.laporan', compact('customers'));
    }

    /**
     * Menampilkan form untuk mengedit data pelanggan.
     */
    public function edit(Customer $customer)
    {
        // Laravel akan otomatis mencari customer berdasarkan ID di URL
        return view('kasir.customer_edit', compact('customer'));
    }

    /**
     * Menyimpan perubahan data pelanggan.
     */
    public function update(Request $request, Customer $customer)
    {
        // 1. Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // 2. Update data di database
        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        // 3. Kembali ke halaman daftar pelanggan dengan pesan sukses
        return redirect()->route('kasir.laporan')
                         ->with('success', 'Data pelanggan "' . $customer->name . '" berhasil diperbarui.');
    }
}
