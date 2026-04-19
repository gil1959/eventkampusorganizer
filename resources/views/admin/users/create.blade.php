@extends('layouts.admin')
@section('title', 'Tambah Pengguna')
@section('page_title', 'Tambah Pengguna Baru')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}"><i class="ri-home-4-line"></i></a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}">Pengguna</a>
    <span>/</span>
    <span>Tambah Baru</span>
</div>

<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Pengguna</h1>
        <p class="page-subtitle">Buat akun baru untuk admin, panitia, atau peserta</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

<div class="card" style="max-width:700px;" data-aos="fade-up">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:0.6rem;font-weight:700;color:var(--navy);">
            <i class="ri-user-add-line"></i> Form Pengguna Baru
        </div>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="ri-error-warning-line"></i>
                <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Role Akun <span class="required">*</span></label>
                    <select name="role" id="roleSelect" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" onchange="updateNpmLabel()">
                        <option value="">Pilih role...</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Kampus (NIP)</option>
                        <option value="panitia" {{ old('role') === 'panitia' ? 'selected' : '' }}>Panitia Event (NPM)</option>
                        <option value="peserta" {{ old('role') === 'peserta' ? 'selected' : '' }}>Peserta (NPM)</option>
                    </select>
                    @error('role')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="nama">Nama Lengkap <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-user-line input-icon"></i>
                        <input type="text" id="nama" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                               value="{{ old('nama') }}" placeholder="Nama lengkap" required>
                    </div>
                    @error('nama')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="npm_nip" id="npmNipLabel">NPM / NIP <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-id-card-line input-icon"></i>
                        <input type="text" id="npm_nip" name="npm_nip" class="form-control {{ $errors->has('npm_nip') ? 'is-invalid' : '' }}"
                               value="{{ old('npm_nip') }}" placeholder="Nomor identitas" required>
                    </div>
                    @error('npm_nip')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jurusan">Program Studi</label>
                    <div class="input-group">
                        <i class="ri-graduation-cap-line input-icon"></i>
                        <input type="text" id="jurusan" name="jurusan" class="form-control"
                               value="{{ old('jurusan') }}" placeholder="Prodi / Bidang kerja">
                    </div>
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="email">Email <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-mail-line input-icon"></i>
                        <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}" placeholder="email@kampus.id" required>
                    </div>
                    @error('email')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-lock-password-line input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               placeholder="Min. 8 karakter" required>
                    </div>
                    @error('password')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-lock-password-line input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                               placeholder="Ulangi password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="no_hp">No. HP</label>
                    <div class="input-group">
                        <i class="ri-phone-line input-icon"></i>
                        <input type="text" id="no_hp" name="no_hp" class="form-control"
                               value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>

            <div class="divider"></div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-orange">
                    <i class="ri-save-line"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateNpmLabel() {
        const role = document.getElementById('roleSelect').value;
        const label = document.getElementById('npmNipLabel');
        const placeholder = document.getElementById('npm_nip');
        if (role === 'admin') {
            label.innerHTML = 'NIP <span class="required">*</span>';
            placeholder.placeholder = 'Nomor Induk Pegawai (NIP)';
        } else {
            label.innerHTML = 'NPM <span class="required">*</span>';
            placeholder.placeholder = 'Nomor Pokok Mahasiswa (NPM)';
        }
    }
    updateNpmLabel();
</script>
@endsection
