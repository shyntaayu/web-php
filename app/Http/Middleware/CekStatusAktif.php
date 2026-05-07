<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekStatusAktif
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek status aktif mahasiswa yang login
        $mahasiswa = Auth::user()->mahasiswa;

        if ($mahasiswa && !$mahasiswa->aktif) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda tidak aktif. Hubungi admin.');
        }

        // Lanjutkan ke request berikutnya
        return $next($request);
    }
}
