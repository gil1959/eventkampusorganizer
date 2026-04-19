<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Event Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    @stack('styles')
</head>
<body>
@php $user = auth()->guard('aktor')->user(); @endphp

<div class="dashboard-layout" id="dashboardLayout">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="ri-calendar-event-fill"></i>
            </div>
            <div class="logo-text">
                <div class="brand-name">Event Kampus</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ri-dashboard-line nav-icon"></i>
                <span class="nav-label">Dashboard</span>
            </a>

            <div class="sidebar-section-title">Manajemen</div>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="ri-group-line nav-icon"></i>
                <span class="nav-label">Pengguna</span>
                @php $pendingPanitia = \App\Models\Aktor::where('role','panitia')->where('verifikasi',false)->count(); @endphp
                @if($pendingPanitia > 0)
                    <span class="nav-badge">{{ $pendingPanitia }}</span>
                @endif
            </a>

            <a href="{{ route('admin.kategoris.index') }}"
               class="nav-item {{ request()->routeIs('admin.kategoris.*') ? 'active' : '' }}">
                <i class="ri-price-tag-3-line nav-icon"></i>
                <span class="nav-label">Kategori</span>
            </a>

            <a href="{{ route('admin.validasi.index') }}"
               class="nav-item {{ request()->routeIs('admin.validasi.*') ? 'active' : '' }}">
                <i class="ri-shield-check-line nav-icon"></i>
                <span class="nav-label">Validasi Event</span>
                @php $pendingEvent = \App\Models\Event::where('status','menunggu')->count(); @endphp
                @if($pendingEvent > 0)
                    <span class="nav-badge">{{ $pendingEvent }}</span>
                @endif
            </a>

            <a href="{{ route('admin.pembayaran.index') }}"
               class="nav-item {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                <i class="ri-receipt-line nav-icon"></i>
                <span class="nav-label">Monitor Pembayaran</span>
            </a>

            <div class="sidebar-section-title">Sistem</div>

            <a href="{{ route('landing') }}" class="nav-item">
                <i class="ri-home-line nav-icon"></i>
                <span class="nav-label">Halaman Publik</span>
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
                    <div class="user-role">
                        <i class="ri-shield-star-line"></i> Administrator
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="toggle-sidebar-btn" onclick="toggleSidebar()" id="sidebarToggle">
                    <i class="ri-menu-fold-line" id="toggleIcon"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
                </div>
            </div>
            <div class="topbar-right">
                @include('partials.topbar-alerts')
                <div class="dropdown">
                    <button class="topbar-btn" onclick="toggleDropdown('adminDropdown')">
                        <i class="ri-user-line"></i>
                    </button>
                    <div class="dropdown-menu" id="adminDropdown" style="display:none;">
                        <div style="padding:0.75rem 1rem; border-bottom:1px solid var(--gray-100);">
                            <div style="font-size:0.8rem;font-weight:700;color:var(--navy);">{{ $user->nama }}</div>
                            <div style="font-size:0.7rem;color:var(--gray-500);">Administrator Sistem</div>
                        </div>
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

        {{-- Flash Messages --}}
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

{{-- Mobile Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:99;"></div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 600, once: true, offset: 40 });

    let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = document.getElementById('toggleIcon');

    function applySidebarState() {
        if (sidebarCollapsed) {
            sidebar.classList.add('collapsed');
            toggleIcon.className = 'ri-menu-unfold-line';
        } else {
            sidebar.classList.remove('collapsed');
            toggleIcon.className = 'ri-menu-fold-line';
        }
    }
    applySidebarState();

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-open');
            document.getElementById('sidebarOverlay').style.display =
                sidebar.classList.contains('mobile-open') ? 'block' : 'none';
        } else {
            sidebarCollapsed = !sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
            applySidebarState();
        }
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('mobile-open');
        document.getElementById('sidebarOverlay').style.display = 'none';
    }

    function toggleDropdown(id) {
        const el = document.getElementById(id);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        }
    });
</script>
@stack('scripts')
</body>
</html>
