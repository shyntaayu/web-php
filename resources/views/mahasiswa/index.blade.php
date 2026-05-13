{{-- File: resources/views/mahasiswa/index.blade.php --}}
@extends('layouts.public') {{-- pakai layout master --}}

@section('title', 'Data Mahasiswa') {{-- isi slot title --}}

@section('content') {{-- isi konten utama --}}
<div class="main-content">
    <div class="page-header">
        <h1>Data Mahasiswa</h1>

        <div class="mb-0">
            <input type="text" id="search"
                placeholder="Cari nama, NIM, atau prodi..."
                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span id="status" class="text-sm text-gray-400 mt-1 block"></span>
        </div>

        {{-- Loading indicator --}}
        <div id="loading" class="hidden text-center py-8">
            <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-500 rounded-full border-t-transparent"></div>
            <p class="text-gray-500 mt-2">Mencari...</p>
        </div>


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

    <table class="tabel-data" id="hasil">
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
</div>

{{-- Pagination --}}
{{-- $mahasiswas->links() --}}
@endsection

@push('scripts')
<script src="{{ asset('js/script-mahasiswa.js') }}"></script>
<script>
    // Konfirmasi sebelum hapus
    document.querySelectorAll('form[method=DELETE]').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm('Yakin hapus data ini?')) e.preventDefault();
        });
    });
</script>
@endpush