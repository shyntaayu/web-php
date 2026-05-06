<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;

Route::get('/', function () {
    return view('dashboard-sijam');
    // return view('welcome');
});

Route::get('/tentang', function () {
    return view('tentang');
});

Route::get('/kontak', function () {
    return view('kontak');
});

Route::get('/dashboard', function () {
    return view('dashboard'); // Pastikan file resources/views/dashboard.blade.php ada
})->name('dashboard');


Route::view('/contoh', 'contoh', [
    'judul' => "Contoh Blade Template",
    'mahasiswa' => (object) [
        'nama' => 'Budi Sudarsono',
        'aktif' => false,
        'nim' => '2024001',
        'ipk' => 3.85
    ],
    'nama' => null, // Untuk contoh null coalescing
    'ipk' => 3.85, // Untuk contoh kondisi badge
    'foto' => "avatar.png", // Untuk contoh @isset
    'daftar' => ['Data 1', 'Data 2', 'ada'], // Untuk contoh @empty
    'mahasiswas' => [], // Untuk contoh looping kosong
    'items' => ['Buku', 'Pena', 'Penggaris'], // Untuk contoh looping
    'kontenHTML' => '<strong>Ini konten HTML yang dirender!</strong>'
]);

Route::resource('mahasiswa', MahasiswaController::class);
