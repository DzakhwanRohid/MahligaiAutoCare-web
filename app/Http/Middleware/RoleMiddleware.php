<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            // --- INI ADALAH PERBAIKANNYA ---
            // Jika role tidak sesuai (misal: kasir coba buka halaman admin),
            // kembalikan saja dia ke dashboard-nya yang benar.
            // Kita sudah punya satu rute 'dashboard' yang pintar.

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
