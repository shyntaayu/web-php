{{-- File: resources/views/mahasiswa/create.blade.php --}}
@extends('layouts.public')
@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="form-container">
    <h2>Tambah Mahasiswa Baru</h2>

    <form action="{{ route('mahasiswa.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf {{-- Token keamanan WAJIB ada --}}

        <div class="form-group">
            <label for="nim">NIM <span class="required">*</span></label>
            <input type="text" id="nim" name="nim"
                value="{{ old('nim') }}"
                class="{{ $errors->has('nim') ? 'is-invalid' : '' }}">
            @error('nim')
            <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap <span class="required">*</span></label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}">
            @error('nama')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
            @error('email')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan <span class="required">*</span></label>
            <select id="jurusan" name="jurusan">
                <option value="">-- Pilih Jurusan --</option>
                @foreach (['SI' => 'Sistem Informasi', 'TI' => 'Teknik Informatika', 'MI' => 'Manajemen Informatika'] as $kode => $nama)
                <option value="{{ $kode }}" {{ old('jurusan') == $kode ? 'selected' : '' }}>
                    {{ $nama }}
                </option>
                @endforeach
            </select>
            @error('jurusan')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="foto">Foto Profil</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            @error('foto')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan</button>
            <a href="{{ route('mahasiswa.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection