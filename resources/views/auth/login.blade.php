<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Event Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
</head>
<body>
<div class="auth-page">
    {{-- Visual Side --}}
    <div class="auth-visual">
        <div class="auth-visual-content">
            <div class="auth-visual-icon">
                <i class="ri-calendar-event-fill"></i>
            </div>
            <h2>Selamat Datang Kembali</h2>
            <p>Masuk ke sistem manajemen event kampus untuk mengelola kegiatan organisasi Anda.</p>

            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="ri-shield-check-line"></i>
                    <span>Sistem multi-role: Admin, Panitia, dan Peserta</span>
                </div>
                <div class="auth-feature-item">
                    <i class="ri-award-line"></i>
                    <span>Generate e-sertifikat otomatis setelah event</span>
                </div>
                <div class="auth-feature-item">
                    <i class="ri-calendar-check-line"></i>
                    <span>Kelola event dari pengajuan hingga pelaporan</span>
                </div>
                <div class="auth-feature-item">
                    <i class="ri-group-line"></i>
                    <span>Manajemen peserta dan absensi digital</span>
                </div>
            </div>

            <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,0.1);">
                <p style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-bottom:0.75rem;">Akun Demo:</p>
                <div style="display:grid;gap:0.5rem;">
                    <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:rgba(255,255,255,0.6);">
                        <span>Admin: admin@eventkampus.id</span><span>admin123</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:rgba(255,255,255,0.6);">
                        <span>Panitia: budi@eventkampus.id</span><span>panitia123</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:rgba(255,255,255,0.6);">
                        <span>Peserta: ahmad@eventkampus.id</span><span>peserta123</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Side --}}
    <div class="auth-form-area">
        <div class="auth-form-content">
            <a href="{{ route('landing') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--gray-500);font-size:0.85rem;margin-bottom:1.5rem;transition:color 0.2s;">
                <i class="ri-arrow-left-line"></i> Kembali ke Beranda
            </a>

            <div class="auth-form-header">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:white;font-size:1.2rem;">
                        <i class="ri-login-box-line"></i>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--orange);">Event Kampus</div>
                        <div style="font-size:0.8rem;color:var(--gray-500);">Sistem Informasi Manajemen</div>
                    </div>
                </div>
                <h1>Masuk ke Akun</h1>
                <p>Masukkan kredensial Anda untuk mengakses sistem.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="ri-information-line"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="ri-mail-line" style="margin-right:0.3rem;"></i>Alamat Email
                        <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <i class="ri-mail-line input-icon"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                            autocomplete="email"
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="ri-lock-password-line" style="margin-right:0.3rem;"></i>Password
                        <span class="required">*</span>
                    </label>
                    <div class="input-group" style="position:relative;">
                        <i class="ri-lock-password-line input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                            style="padding-right:3rem;"
                        >
                        <button type="button" onclick="togglePassword()" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:1.1rem;padding:0;" title="Tampilkan/sembunyikan password">
                            <i class="ri-eye-line" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.875rem;color:var(--gray-600);">
                        <input type="checkbox" name="remember" style="accent-color:var(--navy);width:16px;height:16px;">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg" id="loginBtn">
                    <i class="ri-login-box-line"></i>
                    Masuk ke Sistem
                </button>
            </form>

            <div class="divider"></div>

            <p style="text-align:center;font-size:0.875rem;color:var(--gray-500);">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color:var(--orange);font-weight:700;transition:color 0.2s;">
                    Daftar sekarang
                </a>
            </p>

            {{-- Role indicators --}}
            <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--gray-100);">
                <p style="font-size:0.72rem;text-align:center;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.75rem;">Akses Berdasarkan Peran</p>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;">
                    <div style="text-align:center;padding:0.6rem;background:var(--navy-ghost);border-radius:var(--radius);border:1px solid var(--navy-pale);">
                        <i class="ri-shield-star-line" style="color:var(--navy);display:block;font-size:1.1rem;margin-bottom:0.25rem;"></i>
                        <span style="font-size:0.7rem;font-weight:600;color:var(--navy);">Administrator</span>
                    </div>
                    <div style="text-align:center;padding:0.6rem;background:var(--orange-100);border-radius:var(--radius);border:1px solid rgba(255,92,0,0.2);">
                        <i class="ri-team-line" style="color:var(--orange);display:block;font-size:1.1rem;margin-bottom:0.25rem;"></i>
                        <span style="font-size:0.7rem;font-weight:600;color:var(--orange);">Panitia</span>
                    </div>
                    <div style="text-align:center;padding:0.6rem;background:var(--success-bg);border-radius:var(--radius);border:1px solid #bbf7d0;">
                        <i class="ri-user-line" style="color:var(--success);display:block;font-size:1.1rem;margin-bottom:0.25rem;"></i>
                        <span style="font-size:0.7rem;font-weight:600;color:var(--success);">Peserta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'ri-eye-off-line';
        } else {
            pwd.type = 'password';
            icon.className = 'ri-eye-line';
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i> Memproses...';
        btn.disabled = true;
    });
</script>
<style>
    @keyframes spin { from {transform:rotate(0deg);} to {transform:rotate(360deg);} }
</style>
</body>
</html>
