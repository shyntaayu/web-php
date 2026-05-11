<header id="header">
    <div class="logo-area">
        <div class="logo-placeholder">SJ</div>
        <h1>SIJAM <small>v1.0</small></h1>
    </div>
    <nav>
        <ul class="nav-menu">
            <li><a href="{{ url('/') }}" class="nav-link">Dashboard</a></li>
            <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang</a></li>
            <li><a href="{{ url('/kontak') }}" class="nav-link">Kontak</a></li>
            <li><a href="{{ url('/mahasiswa') }}" class="nav-link">Mahasiswa</a></li>
            <li><a href="{{ url('/profil') }}" class="nav-link">{{ Auth::user()->name }}</a></li>

        </ul>
    </nav>
</header>