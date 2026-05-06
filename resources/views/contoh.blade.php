{{-- File: resources/views/contoh.blade.php --}}

@php
// ── Deklarasi Semua Variabel agar View Muncul ──
$judul = "Contoh Blade Template";
$mahasiswa = (object) [
'nama' => 'Budi Sudarsono',
'aktif' => false,
'nim' => '2024001',
'ipk' => 3.85
];
$kontenHTML = '<strong>Ini konten HTML yang dirender!</strong>';
$nama; // Untuk contoh null coalescing
$ipk = 3.85; // Untuk contoh kondisi badge
$foto = "avatar.png"; // Untuk contoh @isset
$daftar = ['Data 1', 'Data 2', 'ada']; // Untuk contoh @empty

// Data untuk looping
$mahasiswas = [];

$items = ['Buku', 'Pena', 'Penggaris'];
@endphp

{{-- ── Menampilkan variabel ─────── --}}
<h1>{{ $judul }}</h1>
<p>NIM: {{ $mahasiswa->nim }}</p>

{{-- Tampilkan HTML tanpa escape --}}
<div>{!! $kontenHTML !!}</div>

{{-- Nilai default jika variabel null --}}
<p>Halo, {{ $nama ?? 'Anonim' }}</p>

{{-- ── Kondisi ─────────────────── --}}
@if ($ipk >= 3.5)
<span style="color: green;">Status: Cumlaude</span>
@elseif ($ipk >= 3.0)
<span>Status: Sangat Memuaskan</span>
@else
<span>Status: Memuaskan</span>
@endif

<br>

@unless ($mahasiswa->aktif)
<p>Mahasiswa tidak aktif</p> //false
@else
<p>Status: Mahasiswa Aktif</p> //true
@endunless

@isset($foto)
<p>Foto profil tersedia: {{ $foto }}</p>
@endisset

@empty($daftar)
<p>Belum ada data di daftar</p>
@else
<p>Daftar memiliki {{ count($daftar) }} data.</p>
@endempty

{{-- ── Loop Tabel ──────────────── --}}
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>IPK</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mahasiswas as $mhs)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->nama }}</td>
            <td>{{ $mhs->ipk }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- ── Forelse ─────────────────── --}}
<h3>Daftar Nama:</h3>
@forelse ($mahasiswas as $mhs)
<li>{{ $mhs->nama }}</li>
@empty
<p>Tidak ada data mahasiswa</p>
@endforelse

{{-- ── For Loop ────────────────── --}}
@for ($i = 1; $i <= 5; $i++)
    <small>Iterasi ke-{{ $i }}, </small>
    @endfor

    {{-- ── $loop variable ──────────── --}}
    <h3>Informasi Item:</h3>
    @foreach ($items as $item)
    <p>
        Item: {{ $item }} |
        Index: {{ $loop->index }} |
        Apakah Pertama? {{ $loop->first ? 'Ya' : 'Tidak' }} |
        Total: {{ $loop->count }}
    </p>
    @endforeach