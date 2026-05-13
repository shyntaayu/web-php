<?php

use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PreferensiController;

Route::get('/', function () {
    return view('dashboard-sijam');
});

Route::get('/tentang', function () {
    return view('tentang');
});

Route::get('/kontak', function () {
    return view('kontak');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [KunjunganController::class, 'index'])->name('dashboard');
    Route::post('/kunjungan/reset', [KunjunganController::class, 'reset'])->name('kunjungan.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('mahasiswa', MahasiswaController::class);
    Route::post('/mahasiswa/restore-all', [MahasiswaController::class, 'restoreAll'])->name('mahasiswa.restore-all');
    Route::get('/api/mahasiswa',          [MahasiswaController::class, 'apiIndex']);
    Route::get('/api/mahasiswa/{id}',     [MahasiswaController::class, 'apiShow']);
    Route::post('/api/mahasiswa',         [MahasiswaController::class, 'apiStore']);
    Route::delete('/api/mahasiswa/{id}',  [MahasiswaController::class, 'apiDestroy']);
});

Route::middleware('auth')->prefix('preferensi')->group(function () {
    Route::get('/',       [PreferensiController::class, 'ambil'])->name('preferensi.ambil');
    Route::post('/simpan', [PreferensiController::class, 'simpan'])->name('preferensi.simpan');
    Route::delete('/',    [PreferensiController::class, 'hapus'])->name('preferensi.hapus');
});


require __DIR__ . '/auth.php';
