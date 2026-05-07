<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard-sijam');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tentang', function () {
        return view('tentang');
    });

    Route::get('/kontak', function () {
        return view('kontak');
    });
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::post('/mahasiswa/restore-all', [MahasiswaController::class, 'restoreAll'])->name('mahasiswa.restore-all');
});

require __DIR__ . '/auth.php';
