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

        @auth
        <section class="card">
            <div class="py-12 max-w-md mx-auto px-4">
                <div class="bg-white rounded-xl shadow p-6">

                    <h2 class="text-xl font-bold mb-6 text-center">📊 Penghitung Kunjungan</h2>

                    {{-- Flash message setelah reset --}}
                    @if(session('pesan'))
                    <div class="bg-green-50 border border-green-400 text-green-700
                            rounded-lg p-3 mb-5 text-sm text-center">
                        {{ session('pesan') }}
                    </div>
                    @endif

                    {{-- Kartu jumlah kunjungan --}}
                    <div class="bg-blue-50 rounded-xl p-6 text-center mb-4">
                        <p class="text-sm text-blue-400 mb-1">Total Kunjungan Anda</p>
                        <p class="text-6xl font-bold text-blue-600">{{ $jumlah }}</p>
                        <p class="text-blue-400 text-xs mt-2">kali membuka halaman ini</p>
                    </div>

                    {{-- Info detail --}}
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center
                            bg-gray-50 rounded-lg px-4 py-3 text-sm">
                            <span class="text-gray-500">🕐 Pertama kali berkunjung</span>
                            <span class="font-medium text-gray-700">{{ $pertama }}</span>
                        </div>
                        <div class="flex justify-between items-center
                            bg-gray-50 rounded-lg px-4 py-3 text-sm">
                            <span class="text-gray-500">🔄 Kunjungan terakhir</span>
                            <span class="font-medium text-gray-700">{{ $terakhir }}</span>
                        </div>
                        <div class="flex justify-between items-center
                            bg-gray-50 rounded-lg px-4 py-3 text-sm">
                            <span class="text-gray-500">👤 Login sebagai</span>
                            <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        </div>
                    </div>

                    {{-- Tombol reload dan reset --}}
                    <div class="flex gap-3">
                        <a href="{{ route('dashboard') }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white
                           font-semibold py-2 rounded-lg text-center text-sm transition">
                            🔃 Kunjungi Lagi
                        </a>

                        <form method="POST" action="{{ route('kunjungan.reset') }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Reset hitungan kunjungan?')"
                                class="w-full bg-red-50 hover:bg-red-100 text-red-500
                               border border-red-300 font-semibold py-2 rounded-lg
                               text-sm transition">
                                🗑️ Reset
                            </button>
                        </form>
                    </div>

                    {{-- Penjelasan cara kerja --}}
                    <div class="mt-6 bg-yellow-50 border border-yellow-200
                        rounded-lg p-4 text-xs text-yellow-700">
                        <p class="font-semibold mb-1">💡 Cara Kerja Session di Sini:</p>
                        <p>Data kunjungan disimpan di <strong>sisi server</strong> menggunakan session Laravel.
                            Browser Anda hanya menyimpan <strong>session ID</strong> (dalam cookie),
                            sedangkan data aslinya (jumlah, waktu) tersimpan di
                            <code class="bg-yellow-100 px-1 rounded">storage/framework/sessions/</code>.
                            Itulah mengapa data tetap ada meski Anda reload halaman.
                        </p>
                    </div>

                </div>
            </div>
        </section>
        @endauth

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

    <aside id="sidebar" style="width: 50%;">
        <section class="card" style="width: 100%; max-width: 100%; overflow: hidden; box-sizing: border-box;">

            <div class="daftar-info" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
                <div style="max-width: 100%;">
                    <h2 style="margin-bottom: 5px; font-size: 1.2rem;">⛅ Prakiraan Cuaca</h2>
                    <p id="lokasi-cuaca" style="color: #6b7280; font-size: 0.9rem; margin: 0; word-wrap: break-word;">Memuat lokasi...</p>
                </div>
                <div style="font-size: 0.8rem; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-weight: 600; white-space: nowrap;">
                    Hari Ini
                </div>
            </div>

            <div id="cuaca-container" style="display: flex; gap: 15px; overflow-x: auto; padding: 20px 0; scrollbar-width: thin; -webkit-overflow-scrolling: touch; width: 100%;">
                <div style="width: 100%; text-align: center; color: #6b7280;">Memuat data dari server BMKG... ⏳</div>
            </div>
        </section>


        <section class="card overflow-x" style="margin-top: 20px;">
            <div class="daftar-info">
                <h2>Daftar Universitas di Indonesia (Dari API)</h2>
            </div>
            <table class="data-table" id="table-universitas">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Universitas</th>
                        <th>Website</th>
                    </tr>
                </thead>
                <tbody id="tbody-universitas">
                    <tr>
                        <td colspan="3" style="text-align: center;">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="card" style="margin-top: 20px;">
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
    console.log('Script khusus Dashboard berhasil dimuat.---adad');
</script>
@endpush