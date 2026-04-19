@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Pengguna')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.users.index') }}">Pengguna</a><span>/</span><span>Edit</span>
</div>
<div class="page-header">
    <h1 class="page-title">Edit Pengguna</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="ri-arrow-left-line"></i> Kembali</a>
</div>
<div class="card" style="max-width:700px;" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">
            <i class="ri-user-settings-line"></i> Edit: {{ $user->nama }}
        </div>
        <span class="badge {{ $user->role === 'panitia' ? 'badge-orange' : ($user->role === 'admin' ? 'badge-navy' : 'badge-success') }}">
            {{ ucfirst($user->role) }}
        </span>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger"><i class="ri-error-warning-line"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>
        @endif
        <form method="POST" action="{{ route('admin.users.update', $user->id_user) }}">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <div class="input-group"><i class="ri-user-line input-icon"></i>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required></div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ $user->role === 'admin' ? 'NIP' : 'NPM' }} <span class="required">*</span></label>
                    <div class="input-group"><i class="ri-id-card-line input-icon"></i>
                    <input type="text" name="npm_nip" class="form-control" value="{{ old('npm_nip', $user->npm_nip) }}" required></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Program Studi</label>
                    <div class="input-group"><i class="ri-graduation-cap-line input-icon"></i>
                    <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $user->jurusan) }}"></div>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <div class="input-group"><i class="ri-mail-line input-icon"></i>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-control">
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (NIP)</option>
                        <option value="panitia" {{ old('role', $user->role) === 'panitia' ? 'selected' : '' }}>Panitia (NPM)</option>
                        <option value="peserta" {{ old('role', $user->role) === 'peserta' ? 'selected' : '' }}>Peserta (NPM)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <div class="input-group"><i class="ri-phone-line input-icon"></i>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}"></div>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Password Baru <span style="font-weight:400;color:var(--gray-400);">(kosongkan jika tidak diganti)</span></label>
                    <div class="input-group"><i class="ri-lock-password-line input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter"></div>
                </div>
            </div>
            <div class="divider"></div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-orange"><i class="ri-save-line"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
