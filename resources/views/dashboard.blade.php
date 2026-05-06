{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="dashboard-container">
    <div class="welcome-banner" style="background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h1>Selamat Datang di Sistem Akademik! 👋</h1>
        <p>Hari ini adalah {{ date('d F Y') }}. Apa yang ingin Anda lakukan hari ini?</p>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        {{-- Box Statistik (Hanya contoh statis) --}}
        <div class="card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center;">
            <h3>Total Mahasiswa</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #007bff;">150</p>
            <a href="{{ route('mahasiswa.index') }}">Lihat Semua &rarr;</a>
        </div>

        <div class="card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center;">
            <h3>Jurusan</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #28a745;">5</p>
            <small>Fakultas Teknik & Ekonomi</small>
        </div>

        <div class="card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center;">
            <h3>Rata-rata IPK</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #ffc107;">3.45</p>
            <small>Tahun Akademik 2026</small>
        </div>
    </div>
</div>
@endsection