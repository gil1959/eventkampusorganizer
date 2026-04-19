<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_desc', 'Sistem Informasi Manajemen Event Kampus - Platform pengelolaan event, pendaftaran, dan sertifikasi kampus modern.')">
    <title>@yield('title', 'Event Kampus') | Sistem Informasi Manajemen Event Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar transparent" id="mainNavbar">
        <div class="navbar-inner">
            <a href="{{ route('landing') }}" class="navbar-logo">
                <div class="logo-icon">
                    <i class="ri-calendar-event-fill"></i>
                </div>
                <div>
                    <div class="logo-text">Event Kampus</div>
                    <div class="logo-sub">Sistem Manajemen Event</div>
                </div>
            </a>

            <ul class="navbar-menu" id="navMenu">
                <li><a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('landing.events') }}" class="{{ request()->routeIs('landing.events') ? 'active' : '' }}">Event</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>

            <div class="navbar-actions">
                @auth('aktor')
                    @php $role = auth()->guard('aktor')->user()->role; @endphp
                    @if($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-white btn-sm">
                            <i class="ri-dashboard-line"></i> Dashboard
                        </a>
                    @elseif($role === 'panitia')
                        <a href="{{ route('panitia.dashboard') }}" class="btn btn-outline-white btn-sm">
                            <i class="ri-dashboard-line"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('peserta.home') }}" class="btn btn-outline-white btn-sm">
                            <i class="ri-user-line"></i> Portal
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-white btn-sm">
                        <i class="ri-login-box-line"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-orange btn-sm">
                        <i class="ri-user-add-line"></i> Daftar
                    </a>
                @endauth
                <button class="hamburger" id="hamburgerBtn" onclick="toggleMobileMenu()">
                    <i class="ri-menu-3-line"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div class="mobile-menu" id="mobileMenu" style="display:none; background:var(--navy); padding:1rem 1.5rem; border-top:1px solid rgba(255,255,255,0.1);">
            <ul style="list-style:none; display:flex; flex-direction:column; gap:0.5rem;">
                <li><a href="{{ route('landing') }}" style="color:rgba(255,255,255,0.85); display:block; padding:0.6rem 0; font-size:0.9rem;">Beranda</a></li>
                <li><a href="{{ route('landing.events') }}" style="color:rgba(255,255,255,0.85); display:block; padding:0.6rem 0; font-size:0.9rem;">Event</a></li>
                <li><a href="#tentang" style="color:rgba(255,255,255,0.85); display:block; padding:0.6rem 0; font-size:0.9rem;">Tentang</a></li>
                <li><a href="#kontak" style="color:rgba(255,255,255,0.85); display:block; padding:0.6rem 0; font-size:0.9rem;">Kontak</a></li>
                @guest('aktor')
                <li style="display:flex; gap:0.5rem; margin-top:0.5rem;">
                    <a href="{{ route('login') }}" class="btn btn-outline-white btn-sm" style="flex:1; text-align:center;">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-orange btn-sm" style="flex:1; text-align:center;">Daftar</a>
                </li>
                @endguest
            </ul>
        </div>
    </nav>

    @if(session('success'))
    <div style="position:fixed;top:80px;right:1.5rem;z-index:999;max-width:360px;">
        <div class="alert alert-success" id="flashAlert">
            <i class="ri-checkbox-circle-line"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="this.closest('.alert').parentElement.remove()"><i class="ri-close-line"></i></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div style="position:fixed;top:80px;right:1.5rem;z-index:999;max-width:360px;">
        <div class="alert alert-danger">
            <i class="ri-error-warning-line"></i>
            <span>{{ session('error') }}</span>
            <button class="alert-close" onclick="this.closest('.alert').parentElement.remove()"><i class="ri-close-line"></i></button>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div style="position:fixed;top:80px;right:1.5rem;z-index:999;max-width:360px;">
        <div class="alert alert-info">
            <i class="ri-information-line"></i>
            <span>{{ session('info') }}</span>
            <button class="alert-close" onclick="this.closest('.alert').parentElement.remove()"><i class="ri-close-line"></i></button>
        </div>
    </div>
    @endif

    @yield('content')

    {{-- Footer --}}
    <footer class="footer" id="kontak">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-icon">
                            <i class="ri-calendar-event-fill"></i>
                        </div>
                        <div>
                            <div class="brand-name">Event Kampus</div>
                            <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">Sistem Manajemen Event</div>
                        </div>
                    </div>
                    <p>Platform digital terpadu untuk manajemen event kampus. Dari pendaftaran hingga penerbitan e-sertifikat, semua dalam satu sistem yang mudah digunakan.</p>
                    <div class="footer-social">
                        <a href="#" aria-label="Instagram"><i class="ri-instagram-line"></i></a>
                        <a href="#" aria-label="Twitter"><i class="ri-twitter-x-line"></i></a>
                        <a href="#" aria-label="Facebook"><i class="ri-facebook-fill"></i></a>
                        <a href="#" aria-label="YouTube"><i class="ri-youtube-line"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('landing') }}"><i class="ri-arrow-right-s-line"></i> Beranda</a></li>
                        <li><a href="{{ route('landing.events') }}"><i class="ri-arrow-right-s-line"></i> Semua Event</a></li>
                        <li><a href="#tentang"><i class="ri-arrow-right-s-line"></i> Tentang Kami</a></li>
                        <li><a href="#kontak"><i class="ri-arrow-right-s-line"></i> Kontak</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Akun</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('login') }}"><i class="ri-arrow-right-s-line"></i> Masuk</a></li>
                        <li><a href="{{ route('register') }}?role=peserta"><i class="ri-arrow-right-s-line"></i> Daftar Peserta</a></li>
                        <li><a href="{{ route('register') }}?role=panitia"><i class="ri-arrow-right-s-line"></i> Daftar Panitia</a></li>
                    </ul>
                </div>
                <div class="footer-col" id="tentang-footer">
                    <h4>Kontak</h4>
                    <div class="footer-contact-item">
                        <i class="ri-map-pin-line"></i>
                        <span>Jl. Kampus Raya No. 1, Kota Pendidikan, Indonesia</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="ri-mail-line"></i>
                        <span>info@eventkampus.id</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="ri-phone-line"></i>
                        <span>+62 21 1234 5678</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} Event Kampus. Semua hak dilindungi.</span>
                <span>Dibuat dengan <i class="ri-heart-fill" style="color:var(--orange);"></i> untuk kemajuan kampus</span>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // AOS Init
        AOS.init({
            duration: 700,
            once: true,
            offset: 60,
            easing: 'ease-out-cubic',
        });

        // Navbar Scroll
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.remove('transparent');
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.add('transparent');
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile Menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        // Auto-hide flash after 4s
        setTimeout(() => {
            const flash = document.getElementById('flashAlert');
            if (flash) flash.closest('div').remove();
        }, 4000);
    </script>
    @stack('scripts')
</body>
</html>
