<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

public function edit(Request $request): View
{
    $user = $request->user();
    $transactions = [];

    // HANYA jika yang login adalah 'user' (pelanggan), ambil data transaksinya
    if ($user->role == 'user') {
            if ($user->customer) {
                $transactions = Transaction::where('customer_id', $user->customer->id)
                                    ->with('service') 
                                    ->latest() 
                                    ->get();
            }
            
            // Tampilkan view BREEZE/TAILWIND (yang lama)
            return view('profile.edit', [
                'user' => $user,
                'transactions' => $transactions, 
            ]);

        } 
        // JIKA YANG LOGIN ADALAH ADMIN ATAU KASIR
        else if (in_array($user->role, ['admin', 'kasir'])) {
            
            // Tampilkan view DASHBOARD/BOOTSTRAP (yang baru)
            return view('profile.admin-edit', [ 
                'user' => $user,
                // Kita tidak perlu kirim $transactions karena view ini tidak menampilkannya
            ]);
        }

        // Fallback jika role tidak dikenali
        abort(403);
}

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
