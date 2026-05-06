{{-- resources/views/partials/navbar.blade.php --}}
<nav class="main-navbar">
    <div class="nav-brand">Sistem Mahasiswa</div>
    <ul class="nav-links">
        {{-- Pastikan route 'dashboard' sudah ada di web.php --}}
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>

        {{-- Link ke Index Mahasiswa --}}
        <li><a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a></li>

        <li><a href="#">Profil</a></li>
        <li><a href="#">Keluar</a></li>
    </ul>
</nav>

<style>
    .main-navbar {
        background: #333;
        color: white;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
    }

    .nav-links {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    .nav-links a:hover {
        color: #ffd700;
    }
</style>