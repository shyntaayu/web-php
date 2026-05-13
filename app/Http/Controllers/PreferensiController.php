<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;


class PreferensiController extends Controller
{
    // Membaca cookie yang dikirim browser
    public function ambil(Request $request): JsonResponse
    {
        // Cookie dari browser tersedia via $request->cookie()
        $tema   = $request->cookie('preferensi_tema', 'light');
        $bahasa = $request->cookie('preferensi_bahasa', 'id');

        return response()->json([
            'tema'   => $tema,
            'bahasa' => $bahasa,
        ]);
    }

    // Menyimpan cookie via server (dikirim dalam response)
    public function simpan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tema'   => 'required|in:light,dark,system',
            'bahasa' => 'required|in:id,en',
        ]);

        // Cookie::make(name, value, minutes)
        $cookieTema   = Cookie::make('preferensi_tema',   $validated['tema'],   60 * 24 * 365);
        $cookieBahasa = Cookie::make('preferensi_bahasa', $validated['bahasa'], 60 * 24 * 365);

        return response()->json([
            'success' => true,
            'message' => 'Preferensi disimpan',
        ])->withCookie($cookieTema)->withCookie($cookieBahasa);
    }

    // Menghapus cookie
    public function hapus(): JsonResponse
    {
        return response()->json(['success' => true])
            ->withCookie(Cookie::forget('preferensi_tema'))
            ->withCookie(Cookie::forget('preferensi_bahasa'));
    }
}
