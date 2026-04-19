<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Event Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
</head>
<body>
<div class="auth-page">
    {{-- Visual --}}
    <div class="auth-visual">
        <div class="auth-visual-content">
            <div class="auth-visual-icon">
                <i class="ri-user-add-line"></i>
            </div>
            <h2>Bergabunglah dengan Event Kampus</h2>
            <p>Buat akun dan nikmati kemudahan mengikuti dan mengorganisir event kampus secara digital.</p>
            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="ri-user-line"></i>
                    <span>Daftar sebagai Peserta untuk mengikuti event</span>
                </div>
                <div class="auth-feature-item">
                    <i class="ri-team-line"></i>
                    <span>Daftar sebagai Panitia untuk mengelola event</span>
                </div>
                <div class="auth-feature-item">
                    <i class="ri-award-line"></i>
                    <span>Dapatkan e-sertifikat digital setelah hadir</span>
                </div>
                <div class="auth-feature-item">
                    <i class="ri-shield-check-line"></i>
                    <span>Data Anda aman dan terlindungi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="auth-form-area">
        <div class="auth-form-content">
            <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--gray-500);font-size:0.85rem;margin-bottom:1.5rem;">
                <i class="ri-arrow-left-line"></i> Sudah punya akun? Masuk
            </a>

            <div class="auth-form-header">
                <h1>Buat Akun Baru</h1>
                <p>Isi formulir di bawah untuk mendaftar. Admin NIP dibuat oleh administrator sistem.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                {{-- Role Selection --}}
                <div class="form-group">
                    <label class="form-label">Daftar sebagai <span class="required">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                        <label id="rolePeserta" style="display:flex;align-items:center;gap:0.75rem;padding:0.85rem;border:2px solid var(--gray-200);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s;">
                            <input type="radio" name="role" value="peserta" {{ old('role', request()->get('role', 'peserta')) === 'peserta' ? 'checked' : '' }}
                                   onchange="updateRoleUI()" style="accent-color:var(--navy);width:18px;height:18px;">
                            <div>
                                <div style="font-size:0.85rem;font-weight:700;color:var(--navy);">
                                    <i class="ri-user-line" style="margin-right:0.25rem;"></i>Peserta
                                </div>
                                <div style="font-size:0.7rem;color:var(--gray-400);">Ikuti event kampus</div>
                            </div>
                        </label>
                        <label id="rolePanitia" style="display:flex;align-items:center;gap:0.75rem;padding:0.85rem;border:2px solid var(--gray-200);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s;">
                            <input type="radio" name="role" value="panitia" {{ old('role', request()->get('role')) === 'panitia' ? 'checked' : '' }}
                                   onchange="updateRoleUI()" style="accent-color:var(--orange);width:18px;height:18px;">
                            <div>
                                <div style="font-size:0.85rem;font-weight:700;color:var(--orange);">
                                    <i class="ri-team-line" style="margin-right:0.25rem;"></i>Panitia
                                </div>
                                <div style="font-size:0.7rem;color:var(--gray-400);">Kelola event kampus</div>
                            </div>
                        </label>
                    </div>
                    @error('role')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                {{-- NPM info --}}
                <div id="npmInfo" style="background:var(--navy-ghost);border:1px solid var(--navy-pale);border-radius:var(--radius-md);padding:0.75rem 1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;font-size:0.78rem;color:var(--navy);">
                    <i class="ri-information-line" style="font-size:1rem;flex-shrink:0;"></i>
                    <span id="npmInfoText">Identifikasi menggunakan <strong>NPM</strong> (Nomor Pokok Mahasiswa)</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="nama">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="ri-user-line input-icon"></i>
                            <input type="text" id="nama" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                   value="{{ old('nama') }}" placeholder="Nama lengkap sesuai KTM" required autofocus>
                        </div>
                        @error('nama')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="npm">NPM <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="ri-id-card-line input-icon"></i>
                            <input type="text" id="npm" name="npm" class="form-control {{ $errors->has('npm') ? 'is-invalid' : '' }}"
                                   value="{{ old('npm') }}" placeholder="NPM mahasiswa" required>
                        </div>
                        @error('npm')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jurusan">Program Studi</label>
                        <div class="input-group">
                            <i class="ri-graduation-cap-line input-icon"></i>
                            <input type="text" id="jurusan" name="jurusan" class="form-control"
                                   value="{{ old('jurusan') }}" placeholder="Teknik Informatika">
                        </div>
                    </div>

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="email">Email <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="ri-mail-line input-icon"></i>
                            <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email') }}" placeholder="nama@email.com" required>
                        </div>
                        @error('email')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="no_hp">No. HP</label>
                        <div class="input-group">
                            <i class="ri-phone-line input-icon"></i>
                            <input type="text" id="no_hp" name="no_hp" class="form-control"
                                   value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                        </div>
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

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                        <div class="input-group">
                            <i class="ri-lock-password-line input-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                   placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>

                {{-- Panitia warning --}}
                <div id="panitiaWarning" style="display:none; background:var(--warning-bg);border:1px solid #fde68a;border-radius:var(--radius-md);padding:0.75rem 1rem;margin-bottom:1.25rem;display:flex;align-items:start;gap:0.5rem;font-size:0.78rem;color:var(--warning);">
                    <i class="ri-time-line" style="font-size:1rem;flex-shrink:0;margin-top:2px;"></i>
                    <span>Akun Panitia memerlukan <strong>verifikasi oleh Admin</strong> sebelum dapat digunakan. Anda akan menerima konfirmasi setelah diverifikasi.</span>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg" style="margin-top:0.5rem;">
                    <i class="ri-user-add-line"></i>
                    Buat Akun
                </button>
            </form>

            <p style="text-align:center;font-size:0.875rem;color:var(--gray-500);margin-top:1.25rem;">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color:var(--orange);font-weight:700;">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>

<script>
    function updateRoleUI() {
        const peserta = document.querySelector('input[value="peserta"]').checked;
        const panitiaWarning = document.getElementById('panitiaWarning');
        const rolePesertaEl = document.getElementById('rolePeserta');
        const rolePanitiaEl = document.getElementById('rolePanitia');
        const npmInfoText = document.getElementById('npmInfoText');

        rolePesertaEl.style.borderColor = peserta ? 'var(--navy)' : 'var(--gray-200)';
        rolePanitiaEl.style.borderColor = !peserta ? 'var(--orange)' : 'var(--gray-200)';
        panitiaWarning.style.display = !peserta ? 'flex' : 'none';
        npmInfoText.innerHTML = 'Identifikasi menggunakan <strong>NPM</strong> (Nomor Pokok Mahasiswa)';
    }
    updateRoleUI();
</script>
</body>
</html>
