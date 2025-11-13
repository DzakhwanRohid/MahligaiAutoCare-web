<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'kasir'])->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'in:admin,kasir'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User karyawan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Tidak kita gunakan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // <-- Diubah dari string $id
    {
        // 2. TAMPILKAN VIEW EDIT
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // <-- Diubah dari string $id
    {
        // 3. VALIDASI UPDATE
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Validasi email unik, tapi abaikan email user ini sendiri
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'role' => ['required', 'string', 'in:admin,kasir'],
            // Password boleh kosong, tapi jika diisi, harus terkonfirmasi
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // 4. SIAPKAN DATA
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Cek jika user yang sedang login BUKAN user yang di-edit
        // Ini mencegah admin mengganti role-nya sendiri
        if (Auth::id() != $user->id) {
            $data['role'] = $request->role;
        }

        // Cek jika password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 5. UPDATE DATA
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) // <-- Diubah dari string $id
    {
        // 6. JANGAN HAPUS DIRI SENDIRI
        if (Auth::id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // 7. HAPUS USER
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User karyawan berhasil dihapus.');
    }
}
