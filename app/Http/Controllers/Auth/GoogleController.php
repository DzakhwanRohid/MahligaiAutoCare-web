<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);
                return redirect('/dashboard');
            } else {
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    $existingUser->update([
                        'google_id' => $googleUser->id
                    ]);
                    Auth::login($existingUser);
                    return redirect('/dashboard');
                } else {
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => 'password',
                        'role' => 'user',
                    ]);

                    Auth::login($newUser);
                    return redirect('/');
                }
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}