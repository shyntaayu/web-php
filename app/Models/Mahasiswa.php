<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    // Tabel yang digunakan (otomatis: 'mahasiswas')
    protected $table = 'mahasiswas';

    // Kolom yang boleh diisi via mass assignment
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'jurusan',
        'ipk',
        'semester',
        'aktif',
        'foto',
        'alamat',
        'tanggal_lahir',
        'user_id'
    ];

    // Kolom yang tidak boleh diisi via mass assignment
    protected $guarded = ['id'];

    // Cast otomatis tipe data
    protected $casts = [
        'ipk'           => 'decimal:2',
        'aktif'         => 'boolean',
        'tanggal_lahir' => 'date',
    ];

    // Hidden kolom saat serialisasi ke JSON/array
    protected $hidden = ['deleted_at'];

    // ── Relasi ──────────────────────────────────────────────
    // Mahasiswa punya banyak Nilai
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    // Mahasiswa berelasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mahasiswa punya banyak MataKuliah melalui tabel pivot
    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'krs', 'mahasiswa_id', 'matkul_id')
            ->withPivot('nilai', 'semester')
            ->withTimestamps();
    }

    // ── Accessor — ubah cara baca properti ─────────────────
    public function getNamaLengkapAttribute()
    {
        return strtoupper($this->nama);
    }
    // Akses: $mahasiswa->nama_lengkap

    // ── Local Scope — filter yang bisa di-chain ─────────────
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
    public function scopeCumlaude($query)
    {
        return $query->where('ipk', '>=', 3.5);
    }
    // Penggunaan: Mahasiswa::aktif()->cumlaude()->get();

    protected static function booted()
    {
        // Event "creating" dipicu SEBELUM data mahasiswa masuk ke database
        static::creating(function ($mahasiswa) {
            // 1. Otomatis buatkan User baru
            $user = User::create([
                'name'     => $mahasiswa->nama,
                'email'    => $mahasiswa->email,
                'password' => Hash::make('password123'), // Password default
            ]);

            // 2. Isi user_id mahasiswa dengan ID user yang baru saja dibuat
            $mahasiswa->user_id = $user->id;
        });
    }
}
