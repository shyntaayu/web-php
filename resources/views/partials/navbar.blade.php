{{-- resources/views/partials/navbar.blade.php --}}
<nav class="main-navbar">
    <div class="nav-brand">Sistem Mahasiswa</div>
    <ul class="nav-links">
        {{-- Pastikan route 'dashboard' sudah ada di web.php --}}
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>

        {{-- Link ke Index Mahasiswa --}}
        <li><a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a></li>
        @auth
        <li><a href="#">{{ auth()->user()->name }}</a></li>
        <a href="{{ route('profile.edit') }}">Profil</a>
        <li><a href="#">Keluar</a></li>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Keluar</button>
        </form>
        @endauth
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