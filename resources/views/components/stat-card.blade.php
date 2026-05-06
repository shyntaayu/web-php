@props(['judul', 'nilai', 'ikon' => '', 'warna' => '#2563eb'])

<div class="card stat-card" style="border-top: 4px solid {{ $warna }}">
    <h4>{{ $ikon }} {{ $judul }}</h4>
    <p style="color: {{ $warna }}; font-weight: bold;">{{ $nilai }}</p>
</div>