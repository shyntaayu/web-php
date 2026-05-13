<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data session saat ini (atau buat baru jika belum ada)
        $jumlah   = session('kunjungan_jumlah', 0);
        $pertama  = session('kunjungan_pertama');
        $terakhir = session('kunjungan_terakhir');

        // Tambah hitungan
        $jumlah++;

        // Catat waktu pertama kali berkunjung (hanya sekali)
        if (!$pertama) {
            $pertama = now()->format('d M Y, H:i:s');
        }

        // Selalu perbarui waktu kunjungan terakhir
        $terakhir = now()->format('d M Y, H:i:s');

        // Simpan kembali ke session
        session([
            'kunjungan_jumlah'   => $jumlah,
            'kunjungan_pertama'  => $pertama,
            'kunjungan_terakhir' => $terakhir,
        ]);

        return view('dashboard-sijam', compact('jumlah', 'pertama', 'terakhir'));
    }

    public function reset(Request $request)
    {
        // Hapus hanya data kunjungan dari session (bukan seluruh session)
        session()->forget(['kunjungan_jumlah', 'kunjungan_pertama', 'kunjungan_terakhir']);

        return redirect()->route('dashboard')
            ->with('pesan', 'Hitungan kunjungan berhasil direset!');
    }
}
