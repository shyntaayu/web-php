<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
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

    // GET /mahasiswa — tampilkan semua data
    // public function index()
    // {
    //     $mahasiswas = Mahasiswa::latest()->paginate(10);
    //     return view('mahasiswa.index', compact('mahasiswas'));
    // }

    // GET /mahasiswa/create — tampilkan form tambah
    public function create()
    {
        return view('mahasiswa.create');
    }

    // POST /mahasiswa — simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'     => 'required|unique:mahasiswas|max:15',
            'nama'    => 'required|min:3|max:100',
            'email'   => 'required|email|unique:mahasiswas',
            'jurusan' => 'required',
            'ipk'     => 'nullable|numeric|min:0|max:4',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    // GET /mahasiswa/{id} — tampilkan detail
    public function show(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    // GET /mahasiswa/{id}/edit — tampilkan form edit
    public function edit(Mahasiswa $mahasiswa)
    {

        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // PUT /mahasiswa/{id} — update data
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim'     => 'required|max:15|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama'    => 'required|min:3|max:100',
            'email'   => 'required|email|unique:mahasiswas,email,' . $mahasiswa->id,
            'jurusan' => 'required',
            'ipk'     => 'nullable|numeric|min:0|max:4',
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    // DELETE /mahasiswa/{id} — hapus data
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data berhasil dihapus!');
    }
}
