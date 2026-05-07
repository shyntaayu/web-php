<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nim' => '21001', 'nama' => 'Andi Pratama',  'email' => 'andi@email.com',  'jurusan' => 'SI', 'ipk' => 3.75],
            ['nim' => '21002', 'nama' => 'Budi Santoso',  'email' => 'budi@email.com',  'jurusan' => 'TI', 'ipk' => 3.20],
            ['nim' => '21003', 'nama' => 'Cici Rahmawati', 'email' => 'cici@email.com',  'jurusan' => 'SI', 'ipk' => 3.85],
            ['nim' => '21004', 'nama' => 'Dina Oktavia',  'email' => 'dina@email.com',  'jurusan' => 'MI', 'ipk' => 2.90],
            ['nim' => '21005', 'nama' => 'Eko Prasetyo',  'email' => 'eko@email.com',   'jurusan' => 'AK', 'ipk' => 3.55],
        ];

        foreach ($data as $item) {
            // 1. Buat data akun (User) terlebih dahulu untuk mahasiswa ini
            // Menggunakan firstOrCreate agar jika seeder dijalankan 2x, email tidak bentrok/error
            $user = User::firstOrCreate(
                ['email' => $item['email']], // Parameter pencarian
                [
                    'name'     => $item['nama'],
                    'password' => Hash::make('password123') // Password default untuk login
                ]
            );

            // 2. Tambahkan kunci 'user_id' ke dalam array $item
            // Kita ambil ID dari user yang baru saja dibuat di atas
            $item['user_id'] = $user->id;

            // 3. Baru kita simpan data Mahasiswa ke database
            Mahasiswa::updateOrCreate(
                ['nim' => $item['nim']], // Cari berdasarkan NIM agar tidak duplikat
                $item                    // Masukkan seluruh data beserta user_id
            );
        }
    }
}
