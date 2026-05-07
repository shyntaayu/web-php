{{-- File: resources/views/mahasiswa/index.blade.php --}}
@extends('layouts.app') {{-- pakai layout master --}}

@section('title', 'Data Mahasiswa') {{-- isi slot title --}}

@section('content') {{-- isi konten utama --}}
<div class="page-header">
    <h1>Data Mahasiswa</h1>
    <a href="{{ route('mahasiswa.create') }}" class="btn-primary">
        + Tambah Mahasiswa
    </a>

    <form action="{{ route('mahasiswa.restore-all') }}" method="POST" style="display: inline-block; margin-left: 10px;">
        @csrf
        <button type="submit" class="btn-secondary"
            style="background-color: #22c55e; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;"
            onclick="return confirm('Apakah Anda yakin ingin memulihkan SEMUA data yang terhapus?')">
            Pulihkan Semua Data
        </button>
    </form>
</div>

<table class="tabel-data">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Jurusan</th>
            <th>IPK</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($mahasiswas as $mhs)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->nama }}</td>
            <td>{{ $mhs->jurusan }}</td>
            <td>{{ number_format($mhs->ipk, 2) }}</td>
            <td>
                <a href="{{ route('mahasiswa.edit', $mhs->id) }}">Edit</a>
                <form action="{{ route('mahasiswa.destroy', $mhs->id) }}"
                    method="DELETE" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Belum ada data mahasiswa</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
{{-- $mahasiswas->links() --}}
@endsection

@push('scripts')
<script>
    // Konfirmasi sebelum hapus
    document.querySelectorAll('form[method=DELETE]').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm('Yakin hapus data ini?')) e.preventDefault();
        });
    });
</script>
@endpush