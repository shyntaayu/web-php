<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = ['kode_mk', 'nama_mk', 'sks'];

    // Relasi Many-to-Many ke Mahasiswa
    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'krs', 'matkul_id', 'mahasiswa_id')
            ->withPivot('nilai', 'semester') // Agar kolom ekstra bisa dibaca
            ->withTimestamps();
    }
}
