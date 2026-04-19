@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')

@section('content')
{{-- Breadcrumb --}}
<div class="breadcrumb">
    <i class="ri-home-4-line"></i>
    <span>/</span>
    <span>Dashboard</span>
</div>

{{-- Welcome Banner --}}
<div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:var(--radius-xl);padding:1.75rem 2rem;margin-bottom:1.75rem;display:flex;align-items:center;justify-content:space-between;overflow:hidden;position:relative;" data-aos="fade-down">
    <div style="position:absolute;right:-40px;top:-40px;width:200px;height:200px;background:rgba(255,92,0,0.1);border-radius:50%;"></div>
    <div style="position:relative;z-index:1;">
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);font-weight:600;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.35rem;">Selamat Datang Kembali</div>
        <h2 style="font-size:1.4rem;font-weight:800;color:white;margin-bottom:0.35rem;">{{ auth()->guard('aktor')->user()->nama }}</h2>
        <p style="font-size:0.85rem;color:rgba(255,255,255,0.6);">
            <i class="ri-shield-star-line" style="margin-right:0.3rem;color:var(--orange);"></i>
            Administrator Kampus
        </p>
    </div>
    <div style="position:relative;z-index:1;text-align:right;">
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
        <div style="font-size:1.8rem;font-weight:800;color:var(--orange);">{{ now()->format('H:i') }}</div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid-4" style="margin-bottom:1.75rem;">
    @php
    $statItems = [
        ['label'=>'Total Pengguna', 'value'=>$stats['total_user'], 'icon'=>'ri-group-line', 'color'=>'navy', 'sub'=>$stats['total_peserta'].' peserta, '.$stats['total_panitia'].' panitia'],
        ['label'=>'Total Event', 'value'=>$stats['total_event'], 'icon'=>'ri-calendar-event-line', 'color'=>'orange', 'sub'=>$stats['event_aktif'].' aktif, '.$stats['event_selesai'].' selesai'],
        ['label'=>'Menunggu Validasi', 'value'=>$stats['event_menunggu'], 'icon'=>'ri-time-line', 'color'=>'warning', 'sub'=>'event belum diverifikasi'],
        ['label'=>'E-Sertifikat Terbit', 'value'=>$stats['total_sertifik'], 'icon'=>'ri-award-line', 'color'=>'success', 'sub'=>$stats['total_daftar'].' total pendaftaran'],
    ];
    @endphp
    @foreach($statItems as $i => $s)
    <div class="stat-card" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
        <div class="stat-icon stat-icon-{{ $s['color'] }}">
            <i class="{{ $s['icon'] }}"></i>
        </div>
        <div class="stat-value" data-counter="{{ $s['value'] }}">{{ $s['value'] }}</div>
        <div class="stat-label">{{ $s['label'] }}</div>
        <div style="font-size:0.7rem;color:var(--gray-400);margin-top:0.35rem;">{{ $s['sub'] }}</div>
    </div>
    @endforeach
</div>

{{-- Quick Actions --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.75rem;">
    {{-- Panitia Menunggu Verifikasi --}}
    <div class="card" data-aos="fade-right">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <i class="ri-user-follow-line" style="color:var(--orange);font-size:1.1rem;"></i>
                <span style="font-weight:700;color:var(--navy);">Panitia Menunggu Verifikasi</span>
            </div>
            @if($panitiaMenunggu->count() > 0)
                <span class="badge badge-orange">{{ $panitiaMenunggu->count() }} baru</span>
            @endif
        </div>
        <div class="card-body" style="padding:0;">
            @if($panitiaMenunggu->count() === 0)
                <div class="empty-state" style="padding:2rem;">
                    <i class="ri-checkbox-circle-line" style="color:var(--success);"></i>
                    <p>Semua panitia sudah terverifikasi</p>
                </div>
            @else
                @foreach($panitiaMenunggu as $p)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1.25rem;border-bottom:1px solid var(--gray-100);gap:0.75rem;">
                    <div style="display:flex;align-items:center;gap:0.6rem;min-width:0;">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--orange-600));display:flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($p->nama, 0, 1)) }}
                        </div>
                        <div style="min-width:0;">
                            <div style="font-size:0.85rem;font-weight:600;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->nama }}</div>
                            <div style="font-size:0.72rem;color:var(--gray-400);">{{ $p->jurusan ?? $p->email }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.users.verifikasi', $p->id_user) }}" style="flex-shrink:0;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="ri-check-line"></i> Verifikasi
                        </button>
                    </form>
                </div>
                @endforeach
                <div style="padding:0.75rem 1.25rem;">
                    <a href="{{ route('admin.users.index', ['status' => 'belum_verifikasi']) }}" class="btn btn-outline btn-sm w-full">
                        Lihat Semua <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Event Menunggu Persetujuan --}}
    <div class="card" data-aos="fade-left">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <i class="ri-shield-check-line" style="color:var(--navy);font-size:1.1rem;"></i>
                <span style="font-weight:700;color:var(--navy);">Event Menunggu Persetujuan</span>
            </div>
            @if($eventMenunggu->count() > 0)
                <span class="badge badge-navy">{{ $eventMenunggu->count() }} pending</span>
            @endif
        </div>
        <div class="card-body" style="padding:0;">
            @if($eventMenunggu->count() === 0)
                <div class="empty-state" style="padding:2rem;">
                    <i class="ri-checkbox-circle-line" style="color:var(--success);"></i>
                    <p>Tidak ada event yang menunggu persetujuan</p>
                </div>
            @else
                @foreach($eventMenunggu as $e)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1.25rem;border-bottom:1px solid var(--gray-100);gap:0.75rem;">
                    <div style="min-width:0;">
                        <div style="font-size:0.85rem;font-weight:600;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $e->nama_event }}</div>
                        <div style="font-size:0.72rem;color:var(--gray-400);">
                            {{ $e->panitia->nama }} &bull; {{ $e->tanggal->format('d M Y') }}
                        </div>
                    </div>
                    <a href="{{ route('admin.validasi.show', $e->id_event) }}" class="btn btn-primary btn-sm" style="flex-shrink:0;">
                        <i class="ri-eye-line"></i> Review
                    </a>
                </div>
                @endforeach
                <div style="padding:0.75rem 1.25rem;">
                    <a href="{{ route('admin.validasi.index') }}" class="btn btn-outline btn-sm w-full">
                        Lihat Semua <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Event Terbaru --}}
<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:0.6rem;">
            <i class="ri-calendar-event-line" style="color:var(--navy);font-size:1.1rem;"></i>
            <span style="font-weight:700;color:var(--navy);">Event Terbaru</span>
        </div>
        <a href="{{ route('admin.validasi.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Kategori</th>
                        <th>Panitia</th>
                        <th>Tanggal</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventTerbaru as $e)
                    <tr>
                        <td style="font-weight:600;">{{ Str::limit($e->nama_event, 40) }}</td>
                        <td><span class="badge badge-navy">{{ $e->kategori->nama_kategori ?? '-' }}</span></td>
                        <td style="font-size:0.8rem;">{{ $e->panitia->nama }}</td>
                        <td style="font-size:0.8rem;white-space:nowrap;">{{ $e->tanggal->format('d M Y') }}</td>
                        <td style="font-size:0.8rem;">{{ $e->kuota }}</td>
                        <td><span class="badge {{ $e->status_badge }}">{{ $e->status_label }}</span></td>
                        <td>
                            <a href="{{ route('admin.validasi.show', $e->id_event) }}" class="btn btn-primary btn-sm btn-icon" title="Detail">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state" style="padding:2rem;">Belum ada event</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-counter]').forEach(el => {
        const target = parseInt(el.dataset.counter);
        if (!target) return;
        let cur = 0;
        const inc = Math.ceil(target / 50);
        const timer = setInterval(() => {
            cur = Math.min(cur + inc, target);
            el.textContent = cur;
            if (cur >= target) clearInterval(timer);
        }, 20);
    });
</script>
@endpush
