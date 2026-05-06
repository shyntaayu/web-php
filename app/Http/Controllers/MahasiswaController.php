<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Jika pakai data manual (mocking), pastikan semua kunci ada
        $mahasiswas = collect([
            (object) [
                'id' => 1,
                'nim' => '2024001',
                'nama' => 'Budi Sudarsono',
                'jurusan' => 'Teknik Informatika', // Tambahkan ini
                'ipk' => 3.85
            ],
            (object) [
                'id' => 2,
                'nim' => '2024002',
                'nama' => 'Siti Aminah',
                'jurusan' => 'Sistem Informasi', // Tambahkan ini
                'ipk' => 3.70
            ],
        ]);

        // Jika pakai database asli, cukup:
        // $mahasiswas = Mahasiswa::paginate(10);

        return view('mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
