<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIJAM - Sistem Informasi Jadwal Mengajar')</title>
    <!-- Asumsi file CSS ada di folder public/css/style-proyek.css -->
    <link rel="stylesheet" href="{{ asset('css/style-proyek.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style-tasklist.css') }}">
    @stack('styles')

    {{-- Di <head> layout -- terapkan tema sebelum render untuk hindari flash --}}
    <script>
        // Baca cookie tema SEBELUM body render (mencegah 'flash of unstyled content')
        (function() {
            function getCookie(name) {
                const c = document.cookie.split(';').find(c => c.trim().startsWith(name + '='));
                return c ? decodeURIComponent(c.split('=')[1]) : null;
            }
            if (getCookie('tema') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

</head>

<body>

    <!-- 2. Sisipkan Partial Navbar -->
    @include('layouts.navigation')

    <!-- 4. Flash Session Message -->
    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- 1. Main Content Area -->
    @yield('content')

    <!-- Footer Master -->
    <footer class="main-footer">
        <div class="footer-col">
            <h4>Tentang SIJAM</h4>
            <p>Sistem manajemen jadwal mengajar dosen untuk efisiensi akademik.</p>
        </div>
        <div class="footer-col">
            <h4>Tautan Cepat</h4>
            <ul>
                <li><a href="#">Bantuan</a></li>
                <li><a href="#">Panduan</a></li>
                <li><a href="#">Kontak</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Sosial Media</h4>
            <p>@sijam_univ</p>
        </div>
    </footer>

    <!-- 8. Stack Scripts (Tempat meletakkan script per halaman) -->
    @stack('scripts')

</body>

</html>