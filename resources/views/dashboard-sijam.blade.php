@extends('layouts.public') <!-- 3. Extend Layout -->

@section('title', 'Dashboard - SIJAM')

@section('content')
<section class="hero-section">
    <div class="hero-overlay">
        <h2 id="judul">Selamat Datang, Dosen Pengajar</h2>
        <p>Kelola jadwal kuliah dan materi dalam satu platform terintegrasi.</p>
    </div>
</section>

<div class="container layout-wrapper">
    <main>
        <section class="grid-container">
            <!-- 6. Array Data Dummy & Forelse Loop -->
            @php
            $statistik = [
            ['judul' => 'Mata Kuliah', 'nilai' => '8 Aktif', 'ikon' => '📚', 'warna' => '#2563eb'],
            ['judul' => 'Sedang Jalan', 'nilai' => '2 Berlangsung', 'ikon' => '⏳', 'warna' => '#f59e0b'],
            ['judul' => 'Akan Datang', 'nilai' => '4 Jadwal', 'ikon' => '🗓️', 'warna' => '#3b82f6'],
            ['judul' => 'Selesai', 'nilai' => '12 Selesai', 'ikon' => '✅', 'warna' => '#22c55e'],
            ];
            @endphp

            @forelse($statistik as $stat)
            <!-- Memanggil Komponen Blade (poin 5) -->
            <x-stat-card
                :judul="$stat['judul']"
                :nilai="$stat['nilai']"
                :ikon="$stat['ikon']"
                :warna="$stat['warna']" />
            @empty
            <div class="card">
                <p>Belum ada data statistik.</p>
            </div>
            @endforelse
        </section>

        <section class="card">
            <h2>Input Jadwal Baru</h2>
            <!-- Form di-handle oleh JavaScript, tidak butuh action/method PHP -->
            <form class="form-grid" id="form-matkul">
                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <input type="text" class="form-input" id="mata-kuliah" placeholder="Contoh: Pemrograman Web">
                    <span class='error' id='error-mata-kuliah'></span>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-input" id="status">
                        <option value="">-- Pilih Status --</option>
                        <option value="Akan Diajar">Akan Diajar</option>
                        <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                    <span class='error' id='error-status'></span>
                </div>
                <button type="submit" class="btn-submit btn">Simpan Jadwal</button>
            </form>
        </section>

        <section class="card overflow-x">
            <div class="daftar-info">
                <h2>Daftar Mengajar</h2>
                <form class="search-form">
                    <input type="text" placeholder="Cari jadwal atau mata kuliah..." class="search-input">
                    <button type="submit" class="btn-search">Cari</button>
                </form>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Diisi via JS LocalStorage -->
                </tbody>
            </table>
        </section>
    </main>

    <aside id="sidebar">
        <section class="card">
            <h3>Filter Status</h3>
            <div class="filter-group">
                <label class="custom-checkbox">
                    <input type="checkbox" checked value="Semua" id="filter-semua">
                    <span class="checkmark"></span> Semua Jadwal
                </label>
                <label class="custom-checkbox">
                    <input type="checkbox" value="Aktif">
                    <span class="checkmark"></span> Aktif
                </label>
                <label class="custom-checkbox">
                    <input type="checkbox" value="Selesai">
                    <span class="checkmark"></span> Selesai
                </label>
                <label class="custom-checkbox">
                    <input type="checkbox" value="Dibatalkan">
                    <span class="checkmark"></span> Dibatalkan
                </label>
            </div>
        </section>
    </aside>
</div>
@endsection

<!-- 8. Push Scripts khusus untuk halaman Dashboard -->
@push('scripts')
<!-- Asumsi file JS ada di folder public/js/script.js -->
<script src="{{ asset('js/script.js') }}"></script>
<script>
    console.log('Script khusus Dashboard berhasil dimuat.');
</script>
@endpush