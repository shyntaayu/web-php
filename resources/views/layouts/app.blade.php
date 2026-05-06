{{-- File: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAK App') — Sistem Informasi Akademik</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles') {{-- child bisa inject CSS di sini --}}
</head>

<body>
    {{-- Navbar --}}
    <!-- <nav class="navbar">
        <a href="{{ route('dashboard') }}">SIAK</a>
        <ul>
            <li><a href="{{ route('mahasiswa.index') }}">Mahasiswa</a></li>
            <li><a href="{{ route('mahasiswa.index') }}">Mata Kuliah</a></li>
            <li><a href="{{ route('mahasiswa.index') }}">KRS</a></li>
        </ul>
    </nav> -->

    @include('partials.navbar')

    {{-- Flash Message --}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- Konten Halaman --}}
    <main class="container">
        @yield('content') {{-- child page inject konten di sini --}}
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} SIAK Universitas</p>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts') {{-- child bisa inject JS di sini --}}
</body>

</html>