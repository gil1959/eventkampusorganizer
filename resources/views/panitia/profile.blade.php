@extends('layouts.panitia')
@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Profil Saya</h1>
        <p class="page-subtitle">Kelola informasi akun dan keamanan</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem;align-items:start;" data-aos="fade-up">
    {{-- Profile Card --}}
    <div class="card">
        <div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));padding:2rem;text-align:center;">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--orange-600));display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;margin:0 auto 1rem;overflow:hidden;border:3px solid rgba(255,255,255,0.2);">
                @if($user->foto)
                    <img src="{{ asset('storage/'.$user->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                @endif
            </div>
            <div style="font-size:1rem;font-weight:700;color:white;">{{ $user->nama }}</div>
            <div style="font-size:0.78rem;color:rgba(255,255,255,0.6);margin-top:0.25rem;">{{ $user->email }}</div>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                    <span style="color:var(--gray-500);font-weight:500;">Program Studi</span>
                    <span style="font-weight:600;color:var(--gray-700);">{{ $user->jurusan ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                    <span style="color:var(--gray-500);font-weight:500;">No. HP</span>
                    <span style="font-weight:600;color:var(--gray-700);">{{ $user->no_hp ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                    <span style="color:var(--gray-500);font-weight:500;">Role</span>
                    <span class="badge badge-orange"><i class="ri-team-line"></i> Panitia</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                    <span style="color:var(--gray-500);font-weight:500;">Verifikasi</span>
                    @if($user->verifikasi)
                        <span class="badge badge-success"><i class="ri-check-line"></i> Terverifikasi</span>
                    @else
                        <span class="badge badge-warning"><i class="ri-time-line"></i> Menunggu</span>
                    @endif
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                    <span style="color:var(--gray-500);font-weight:500;">Bergabung</span>
                    <span style="font-weight:600;color:var(--gray-700);">{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Form --}}
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:0.6rem;font-weight:700;color:var(--navy);">
                <i class="ri-user-settings-line"></i> Edit Informasi Profil
            </div>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('panitia.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="ri-user-line input-icon"></i>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Program Studi</label>
                        <div class="input-group">
                            <i class="ri-graduation-cap-line input-icon"></i>
                            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $user->jurusan) }}" placeholder="Program Studi">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP</label>
                        <div class="input-group">
                            <i class="ri-phone-line input-icon"></i>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-hint">JPG/PNG, maks 1MB. Biarkan kosong jika tidak ingin diubah.</div>
                    </div>
                </div>

                <div class="divider"></div>
                <div style="font-size:0.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;">
                    <i class="ri-lock-password-line"></i> Ubah Password
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <i class="ri-lock-line input-icon"></i>
                            <input type="password" name="current_password" class="form-control" placeholder="Password saat ini">
                        </div>
                        @error('current_password')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <i class="ri-lock-password-line input-icon"></i>
                            <input type="password" name="new_password" class="form-control" placeholder="Min. 8 karakter">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <i class="ri-lock-password-line input-icon"></i>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-orange">
                        <i class="ri-save-line"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
