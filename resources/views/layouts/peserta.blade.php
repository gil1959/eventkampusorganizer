<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Peserta') | Event Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    @stack('styles')
</head>
<body>
@php $user = auth()->guard('aktor')->user(); @endphp

<div class="dashboard-layout">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="ri-calendar-event-fill"></i>
            </div>
            <div class="logo-text">
                <div class="brand-name">Event Kampus</div>
                <div class="brand-sub">Portal Peserta</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Menu</div>

            <a href="{{ route('peserta.home') }}"
               class="nav-item {{ request()->routeIs('peserta.home') ? 'active' : '' }}">
                <i class="ri-search-line nav-icon"></i>
                <span class="nav-label">Cari Event</span>
            </a>

            <a href="{{ route('peserta.riwayat') }}"
               class="nav-item {{ request()->routeIs('peserta.riwayat') ? 'active' : '' }}">
                <i class="ri-history-line nav-icon"></i>
                <span class="nav-label">Riwayat Pendaftaran</span>
            </a>

            <a href="{{ route('peserta.sertifikat') }}"
               class="nav-item {{ request()->routeIs('peserta.sertifikat') ? 'active' : '' }}">
                <i class="ri-award-line nav-icon"></i>
                <span class="nav-label">E-Sertifikat</span>
            </a>

            <div class="sidebar-section-title">Akun</div>

            <a href="{{ route('peserta.profile') }}"
               class="nav-item {{ request()->routeIs('peserta.profile') ? 'active' : '' }}">
                <i class="ri-user-settings-line nav-icon"></i>
                <span class="nav-label">Profil Saya</span>
            </a>

            <a href="{{ route('landing') }}" class="nav-item">
                <i class="ri-home-line nav-icon"></i>
                <span class="nav-label">Halaman Utama</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="nav-item" style="width:auto;cursor:pointer;background:none;border:none;text-align:left;">
                    <i class="ri-logout-box-r-line nav-icon"></i>
                    <span class="nav-label">Keluar</span>
                </button>
            </form>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info-sidebar">
                <div class="user-avatar">
                    @if($user->foto)
                        <img src="{{ asset('storage/'.$user->foto) }}" alt="{{ $user->nama }}">
                    @else
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    @endif
                </div>
                <div class="user-details">
                    <div class="user-name">{{ $user->nama }}</div>
                    <div class="user-role"><i class="ri-user-line"></i> Peserta</div>
                </div>
            </div>
        </div>
    </aside>

    <div class="main-content" id="mainContent">
        <header class="topbar">
            <div class="topbar-left">
                <button class="toggle-sidebar-btn" onclick="toggleSidebar()">
                    <i class="ri-menu-fold-line" id="toggleIcon"></i>
                </button>
                <div class="topbar-title">@yield('page_title', 'Portal Peserta')</div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('peserta.riwayat') }}" class="topbar-btn" title="Riwayat Pendaftaran">
                    <i class="ri-history-line"></i>
                </a>
                <div class="dropdown">
                    <button class="topbar-btn" onclick="toggleDropdown('pesertaDropdown')">
                        <i class="ri-user-line"></i>
                    </button>
                    <div class="dropdown-menu" id="pesertaDropdown" style="display:none;">
                        <div style="padding:0.75rem 1rem; border-bottom:1px solid var(--gray-100);">
                            <div style="font-size:0.8rem;font-weight:700;color:var(--navy);">{{ $user->nama }}</div>
                            <div style="font-size:0.7rem;color:var(--gray-500);">Peserta Event</div>
                        </div>
                        <a href="{{ route('peserta.profile') }}" class="dropdown-item">
                            <i class="ri-user-settings-line"></i> Edit Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger">
                                <i class="ri-logout-box-r-line"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="content-area" style="padding-bottom:0;">
                <div class="alert alert-success">
                    <i class="ri-checkbox-circle-line"></i>
                    <span>{{ session('success') }}</span>
                    <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="content-area" style="padding-bottom:0;">
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    <span>{{ session('error') }}</span>
                    <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
                </div>
            </div>
        @endif

        <main class="content-area">
            @yield('content')
        </main>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:99;"></div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 600, once: true, offset: 40 });
    let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    function applySidebarState() {
        if (sidebarCollapsed) { sidebar.classList.add('collapsed'); toggleIcon.className = 'ri-menu-unfold-line'; }
        else { sidebar.classList.remove('collapsed'); toggleIcon.className = 'ri-menu-fold-line'; }
    }
    applySidebarState();
    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-open');
            document.getElementById('sidebarOverlay').style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
        } else { sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed); applySidebarState(); }
    }
    function closeMobileSidebar() { sidebar.classList.remove('mobile-open'); document.getElementById('sidebarOverlay').style.display = 'none'; }
    function toggleDropdown(id) { const el = document.getElementById(id); el.style.display = el.style.display === 'none' ? 'block' : 'none'; }
    document.addEventListener('click', (e) => { if (!e.target.closest('.dropdown')) document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none'); });
</script>
@stack('scripts')
</body>
</html>
