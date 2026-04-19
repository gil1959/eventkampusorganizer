@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_desc', 'Platform digital terpadu untuk manajemen event kampus. Daftar, ikuti event, dan dapatkan e-sertifikat.')

@section('content')

{{-- ============================================================
     HERO SECTION
============================================================ --}}
<section class="hero">
    <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="hero-grid">
        <div class="hero-text">
            <div class="hero-badge" data-aos="fade-down">
                <i class="ri-star-line"></i>
                Platform Event Kampus Terpadu
            </div>
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">
                Kelola Event Kampus<br>dengan <span class="highlight">Sistem Modern</span>
            </h1>
            <p class="hero-desc" data-aos="fade-up" data-aos-delay="200">
                Satu platform untuk semua kebutuhan event kampus: dari pengajuan event, pendaftaran peserta, absensi digital, hingga penerbitan e-sertifikat otomatis.
            </p>
            <div class="hero-actions" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('landing.events') }}" class="btn btn-orange btn-lg btn-pill">
                    <i class="ri-calendar-event-line"></i>
                    Jelajahi Event
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-white btn-lg btn-pill">
                    <i class="ri-user-add-line"></i>
                    Daftar Sekarang
                </a>
            </div>

            <div class="hero-stats" data-aos="fade-up" data-aos-delay="400">
                <div class="hero-stat">
                    <div class="value" data-counter="{{ $stats['total_event'] }}">{{ $stats['total_event'] }}</div>
                    <div class="label">Event Aktif</div>
                </div>
                <div style="width:1px;height:40px;background:rgba(255,255,255,0.15);"></div>
                <div class="hero-stat">
                    <div class="value" data-counter="{{ $stats['total_peserta'] }}">{{ $stats['total_peserta'] }}</div>
                    <div class="label">Peserta Terdaftar</div>
                </div>
                <div style="width:1px;height:40px;background:rgba(255,255,255,0.15);"></div>
                <div class="hero-stat">
                    <div class="value" data-counter="{{ $stats['total_sertifik'] }}">{{ $stats['total_sertifik'] }}</div>
                    <div class="label">Sertifikat Terbit</div>
                </div>
            </div>
        </div>

        {{-- Hero Visual --}}
        <div class="hero-visual" data-aos="fade-left" data-aos-delay="200">
            <div class="hero-illustration">
                <div class="hero-cards-float">
                    <div class="floating-card">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                            <div style="width:36px;height:36px;background:rgba(255,92,0,0.3);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--orange-400);font-size:1rem;">
                                <i class="ri-calendar-event-fill"></i>
                            </div>
                            <div>
                                <div style="font-size:0.8rem;font-weight:700;color:white;">Seminar Nasional AI</div>
                                <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);">28 April 2026</div>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:0.72rem;color:rgba(255,255,255,0.6);">
                            <span><i class="ri-map-pin-line"></i> Aula Utama</span>
                            <span style="color:var(--orange-400);font-weight:600;">120 Kuota Tersisa</span>
                        </div>
                    </div>

                    <div class="floating-card">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
                            <span style="font-size:0.78rem;font-weight:600;color:white;">Status Pendaftar</span>
                            <span style="font-size:0.7rem;color:var(--orange-400);">Hari ini</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;">
                            <div style="text-align:center;padding:0.5rem;background:rgba(255,255,255,0.08);border-radius:6px;">
                                <div style="font-size:1.1rem;font-weight:800;color:white;">48</div>
                                <div style="font-size:0.65rem;color:rgba(255,255,255,0.5);">Terdaftar</div>
                            </div>
                            <div style="text-align:center;padding:0.5rem;background:rgba(255,255,255,0.08);border-radius:6px;">
                                <div style="font-size:1.1rem;font-weight:800;color:var(--orange-400);">32</div>
                                <div style="font-size:0.65rem;color:rgba(255,255,255,0.5);">Hadir</div>
                            </div>
                            <div style="text-align:center;padding:0.5rem;background:rgba(255,255,255,0.08);border-radius:6px;">
                                <div style="font-size:1.1rem;font-weight:800;color:#4ade80;">16</div>
                                <div style="font-size:0.65rem;color:rgba(255,255,255,0.5);">Sertifikat</div>
                            </div>
                        </div>
                    </div>

                    <div class="floating-card">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                            <i class="ri-award-fill" style="color:var(--orange-400);font-size:1rem;"></i>
                            <span style="font-size:0.8rem;font-weight:600;color:white;">E-Sertifikat Anda Siap</span>
                        </div>
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.5);margin-bottom:0.75rem;">Workshop UI/UX Design 2026</div>
                        <div style="display:flex;gap:0.5rem;">
                            <button style="flex:1;padding:0.5rem;background:var(--orange);border:none;border-radius:6px;color:white;font-size:0.72rem;font-weight:600;cursor:pointer;">
                                <i class="ri-download-line"></i> Unduh PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     STATS SECTION
============================================================ --}}
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-icon-bg"><i class="ri-calendar-event-line"></i></div>
                <div class="stat-num" data-counter="{{ $stats['total_event'] }}">{{ $stats['total_event'] }}</div>
                <div class="stat-lbl">Event Aktif & Terdaftar</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-icon-bg"><i class="ri-group-line"></i></div>
                <div class="stat-num" data-counter="{{ $stats['total_peserta'] }}">{{ $stats['total_peserta'] }}</div>
                <div class="stat-lbl">Total Peserta Terdaftar</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-icon-bg"><i class="ri-price-tag-3-line"></i></div>
                <div class="stat-num" data-counter="{{ $stats['total_kategori'] }}">{{ $stats['total_kategori'] }}</div>
                <div class="stat-lbl">Kategori Event Tersedia</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-icon-bg"><i class="ri-award-line"></i></div>
                <div class="stat-num" data-counter="{{ $stats['total_sertifik'] }}">{{ $stats['total_sertifik'] }}</div>
                <div class="stat-lbl">Sertifikat Diterbitkan</div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FEATURES SECTION (3 ROLES)
============================================================ --}}
<section class="features-section" id="tentang">
    <div class="container">
        <div class="section-header">
            <div class="section-tag" data-aos="fade-down">
                <i class="ri-function-line"></i> Fitur Sistem
            </div>
            <h2 class="section-title" data-aos="fade-up">Dirancang untuk Semua Peran</h2>
            <p class="section-desc" data-aos="fade-up" data-aos-delay="100">
                Sistem kami menyediakan fitur spesifik untuk tiga jenis pengguna: Admin Kampus, Panitia Event, dan Peserta.
            </p>
        </div>

        <div class="grid-3">
            {{-- Admin --}}
            <div class="feature-card" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-icon">
                    <i class="ri-shield-star-line"></i>
                </div>
                <div class="badge badge-navy" style="margin-bottom:0.75rem;">
                    <i class="ri-id-card-line"></i> NIP
                </div>
                <h3>Admin Kampus</h3>
                <p>Manajemen sistem secara menyeluruh dengan akses penuh ke semua data dan fitur.</p>
                <ul class="feature-list">
                    <li>Dashboard statistik global real-time</li>
                    <li>Manajemen pengguna & verifikasi panitia</li>
                    <li>Manajemen kategori event</li>
                    <li>Validasi & persetujuan pengajuan event</li>
                    <li>Laporan dan rekap data</li>
                </ul>
            </div>

            {{-- Panitia --}}
            <div class="feature-card" data-aos="fade-up" data-aos-delay="150">
                <div class="feature-icon" style="background:linear-gradient(135deg,var(--orange),var(--orange-800));">
                    <i class="ri-team-line"></i>
                </div>
                <div class="badge badge-orange" style="margin-bottom:0.75rem;">
                    <i class="ri-id-card-line"></i> NPM
                </div>
                <h3>Panitia Event</h3>
                <p>Tools lengkap untuk membuat, mengelola, dan mengevaluasi event yang diorganisir.</p>
                <ul class="feature-list">
                    <li>Dashboard statistik event kelolaan</li>
                    <li>Buat & kelola event dengan poster</li>
                    <li>Manajemen peserta & absensi digital</li>
                    <li>Generate e-sertifikat otomatis</li>
                    <li>Cetak daftar hadir PDF</li>
                </ul>
            </div>

            {{-- Peserta --}}
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon" style="background:linear-gradient(135deg,#16a34a,#15803d);">
                    <i class="ri-user-line"></i>
                </div>
                <div class="badge badge-success" style="margin-bottom:0.75rem;">
                    <i class="ri-id-card-line"></i> NPM
                </div>
                <h3>Peserta</h3>
                <p>Temukan dan ikuti event kampus dengan mudah, dapatkan sertifikat digital.</p>
                <ul class="feature-list">
                    <li>Cari & filter event berdasarkan kategori</li>
                    <li>Pendaftaran event online</li>
                    <li>Riwayat & status pendaftaran</li>
                    <li>Unduh e-sertifikat PDF</li>
                    <li>Manajemen profil akun</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     KATEGORI SECTION
============================================================ --}}
@if($kategoris->count() > 0)
<section style="background:var(--navy-ghost); padding:5rem 0;">
    <div class="container">
        <div class="section-header">
            <div class="section-tag" data-aos="fade-down">
                <i class="ri-price-tag-3-line"></i> Kategori
            </div>
            <h2 class="section-title" data-aos="fade-up">Temukan Event Sesuai Minat</h2>
            <p class="section-desc" data-aos="fade-up" data-aos-delay="100">Berbagai jenis event tersedia untuk semua minat dan bidang studi.</p>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;justify-content:center;">
            @foreach($kategoris as $i => $k)
            <a href="{{ route('landing.events', ['kategori' => $k->id_kategori]) }}"
               data-aos="zoom-in" data-aos-delay="{{ $i * 50 }}"
               style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.75rem 1.25rem;background:var(--white);border:2px solid var(--gray-200);border-radius:var(--radius-full);font-size:0.875rem;font-weight:600;color:var(--navy);transition:all 0.25s;text-decoration:none;"
               onmouseover="this.style.background='var(--navy)';this.style.color='white';this.style.borderColor='var(--navy)';this.style.transform='translateY(-3px)';"
               onmouseout="this.style.background='var(--white)';this.style.color='var(--navy)';this.style.borderColor='var(--gray-200)';this.style.transform='translateY(0)';">
                <i class="ri-{{ $k->icon ?? 'bookmark-line' }}"></i>
                {{ $k->nama_kategori }}
                @if($k->events_count > 0)
                    <span style="background:var(--orange-100);color:var(--orange);font-size:0.7rem;padding:0.15rem 0.5rem;border-radius:9999px;font-weight:700;">{{ $k->events_count }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     EVENT TERBARU SECTION
============================================================ --}}
@if($eventTerbaru->count() > 0)
<section style="background:var(--white); padding:5rem 0;">
    <div class="container">
        <div class="section-header">
            <div class="section-tag" data-aos="fade-down">
                <i class="ri-calendar-event-line"></i> Event Terbaru
            </div>
            <h2 class="section-title" data-aos="fade-up">Event yang Sedang Berlangsung</h2>
            <p class="section-desc" data-aos="fade-up" data-aos-delay="100">Segera daftarkan diri Anda sebelum kuota penuh.</p>
        </div>

        <div class="grid-3" style="margin-bottom:2.5rem;">
            @foreach($eventTerbaru as $i => $event)
            <div data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="event-card">
                    <div class="event-card-img">
                        @if($event->poster)
                            <img src="{{ asset('storage/'.$event->poster) }}" alt="{{ $event->nama_event }}">
                        @else
                            <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.5rem;color:rgba(255,255,255,0.5);">
                                <i class="ri-calendar-event-line" style="font-size:2.5rem;"></i>
                                <span style="font-size:0.75rem;">{{ $event->kategori->nama_kategori ?? 'Event' }}</span>
                            </div>
                        @endif
                        <div class="event-date-badge">
                            <i class="ri-calendar-line"></i>
                            {{ $event->tanggal->format('d M Y') }}
                        </div>
                        @if($event->biaya == 0)
                            <div style="position:absolute;top:1rem;right:1rem;background:var(--success);color:white;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:9999px;">GRATIS</div>
                        @endif
                    </div>
                    <div class="event-card-body">
                        <div class="badge badge-navy" style="margin-bottom:0.6rem;">{{ $event->kategori->nama_kategori ?? '-' }}</div>
                        <h3 style="font-size:1rem;font-weight:700;color:var(--navy);margin-bottom:0.5rem;line-height:1.4;">{{ $event->nama_event }}</h3>
                        <p style="font-size:0.8rem;color:var(--gray-500);line-height:1.5;margin-bottom:0.75rem;">{{ Str::limit($event->deskripsi, 100) }}</p>

                        <div style="display:flex;flex-direction:column;gap:0.35rem;margin-top:auto;">
                            @if($event->lokasi)
                            <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--gray-500);">
                                <i class="ri-map-pin-line" style="color:var(--orange);"></i> {{ $event->lokasi }}
                            </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--gray-500);">
                                <i class="ri-group-line" style="color:var(--navy);"></i>
                                {{ $event->pesertaTerdaftar()->count() }}/{{ $event->kuota }} peserta
                            </div>
                        </div>
                    </div>
                    <div class="event-card-footer">
                        <div style="font-size:0.8rem;font-weight:700;color:var(--orange);">
                            {{ $event->biaya > 0 ? 'Rp ' . number_format($event->biaya, 0, ',', '.') : 'Gratis' }}
                        </div>
                        <a href="{{ route('peserta.event.show', $event->id_event) }}"
                           class="btn btn-primary btn-sm">
                            Detail <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center;" data-aos="fade-up">
            <a href="{{ route('landing.events') }}" class="btn btn-outline btn-lg">
                <i class="ri-calendar-event-line"></i>
                Lihat Semua Event
                <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     CARA KERJA SECTION
============================================================ --}}
<section class="how-section">
    <div class="container">
        <div class="section-header">
            <div class="section-tag" data-aos="fade-down">
                <i class="ri-route-line"></i> Cara Kerja
            </div>
            <h2 class="section-title" data-aos="fade-up">Mudah dalam 4 Langkah</h2>
            <p class="section-desc" data-aos="fade-up" data-aos-delay="100">
                Dari pendaftaran akun hingga mendapatkan e-sertifikat digital.
            </p>
        </div>

        <div class="steps-grid">
            <div class="step-item" data-aos="fade-up" data-aos-delay="0">
                <div class="step-number">1</div>
                <span class="step-icon"><i class="ri-user-add-line"></i></span>
                <h4>Buat Akun</h4>
                <p>Daftar sebagai Peserta dengan NPM dan data mahasiswa Anda.</p>
            </div>
            <div class="step-item" data-aos="fade-up" data-aos-delay="100">
                <div class="step-number">2</div>
                <span class="step-icon"><i class="ri-search-line"></i></span>
                <h4>Temukan Event</h4>
                <p>Cari dan filter event berdasarkan kategori atau tanggal pelaksanaan.</p>
            </div>
            <div class="step-item" data-aos="fade-up" data-aos-delay="200">
                <div class="step-number">3</div>
                <span class="step-icon"><i class="ri-calendar-check-line"></i></span>
                <h4>Daftar & Hadir</h4>
                <p>Klik daftar, simpan nomor tiket, dan hadir saat pelaksanaan event.</p>
            </div>
            <div class="step-item" data-aos="fade-up" data-aos-delay="300">
                <div class="step-number">4</div>
                <span class="step-icon"><i class="ri-award-line"></i></span>
                <h4>Unduh Sertifikat</h4>
                <p>Setelah event selesai, unduh e-sertifikat digital langsung dari portal.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     CTA SECTION
============================================================ --}}
<section class="cta-section">
    <div class="container">
        <div class="cta-inner" data-aos="zoom-in">
            <div style="display:inline-flex;align-items:center;gap:0.5rem;background:rgba(255,92,0,0.2);border:1px solid rgba(255,92,0,0.3);color:var(--orange-400);font-size:0.75rem;font-weight:700;padding:0.4rem 1rem;border-radius:9999px;margin-bottom:1.5rem;letter-spacing:0.06em;text-transform:uppercase;">
                <i class="ri-fire-line"></i> Bergabung Sekarang
            </div>
            <h2>Siap Memulai Perjalanan Event Kampus?</h2>
            <p>Ribuan mahasiswa sudah bergabung. Daftarkan diri Anda dan mulai ikuti event kampus pilihan.</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-orange btn-lg btn-pill">
                    <i class="ri-user-add-line"></i>
                    Daftar sebagai Peserta
                </a>
                <a href="{{ route('register') }}?role=panitia" class="btn btn-outline-white btn-lg btn-pill">
                    <i class="ri-team-line"></i>
                    Daftar sebagai Panitia
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Counter Animation
    function animateCounter(el) {
        const target = parseInt(el.dataset.counter || el.textContent);
        if (isNaN(target) || target === 0) return;
        let current = 0;
        const inc = Math.ceil(target / 60);
        const timer = setInterval(() => {
            current = Math.min(current + inc, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 25);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('[data-counter]');
                counters.forEach(animateCounter);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.stats-grid, .hero-stats').forEach(el => observer.observe(el));
</script>
@endpush
