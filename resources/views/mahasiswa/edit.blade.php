{{-- File: resources/views/mahasiswa/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Mahasiswa')

@section('content')
<div class="form-container">
    <h2>Edit Mahasiswa</h2>

    <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nim">NIM <span class="required">*</span></label>
            <input type="text" id="nim" name="nim"
                value="{{ old('nim', $mahasiswa->nim) }}"
                class="{{ $errors->has('nim') ? 'is-invalid' : '' }}">
            @error('nim')
            <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap <span class="required">*</span></label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}">
            @error('nama')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->email) }}">
            @error('email')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan <span class="required">*</span></label>
            <select id="jurusan" name="jurusan">
                <option value="">-- Pilih Jurusan --</option>
                @foreach (['SI' => 'Sistem Informasi', 'TI' => 'Teknik Informatika', 'MI' => 'Manajemen Informatika', 'AK' => 'Akuntansi'] as $kode => $nama)
                <option value="{{ $kode }}" {{ old('jurusan', $mahasiswa->jurusan) == $kode ? 'selected' : '' }}>
                    {{ $nama }}
                </option>
                @endforeach
            </select>
            @error('jurusan')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="foto">Foto Profil</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small>Kosongkan jika tidak ingin mengubah foto.</small>
            @error('foto')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('mahasiswa.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection