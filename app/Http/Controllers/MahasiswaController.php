<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Jika pakai database asli, cukup:
        $mahasiswas = Mahasiswa::paginate(10);

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
            'nim'     => 'required|string|max:15|unique:mahasiswas',
            'nama'    => 'required|string|min:3|max:100',
            'email'   => 'required|email|max:100|unique:mahasiswas',
            'jurusan' => 'required|in:SI,TI,MI,AK',
            'ipk'     => 'nullable|numeric|min:0|max:4',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
        ], [
            // Pesan error kustom (opsional)
            'nim.required'  => 'NIM wajib diisi.',
            'nim.unique'    => 'NIM sudah terdaftar.',
            'email.email'   => 'Format email tidak valid.',
            'foto.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }


        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    // GET /mahasiswa/{id} — tampilkan detail
    public function show(Mahasiswa $mahasiswa)
    {
        // return view('mahasiswa.index', compact('mahasiswa'));
    }

    // GET /mahasiswa/{id}/edit — tampilkan form edit
    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa = Mahasiswa::findOrFail($mahasiswa->id);
        // session()->flashInput($mahasiswa->toArray());
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

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }


        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    // DELETE /mahasiswa/{id} — hapus data
    public function destroy(Mahasiswa $mahasiswa)
    {
        if ($mahasiswa->foto) {
            Storage::disk('public')->delete($mahasiswa->foto);
        }

        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data berhasil dihapus!');
    }

    public function restoreAll()
    {
        // Mengambil semua data yang statusnya terhapus (soft delete) lalu memulihkannya
        $jumlahData = Mahasiswa::onlyTrashed()->count();

        if ($jumlahData > 0) {
            Mahasiswa::onlyTrashed()->restore();
            return redirect()->back()->with('success', $jumlahData . ' semua data berhasil dipulihkan!');
        }

        return redirect()->back()->with('error', 'Tidak ada data di tong sampah.');
    }
}
