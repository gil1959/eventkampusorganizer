@extends('layouts.panitia')
@section('title', 'Dashboard Panitia')
@section('page_title', 'Dashboard Panitia')

@section('content')
<div class="breadcrumb">
    <i class="ri-home-4-line"></i>
    <span>/</span>
    <span>Dashboard</span>
</div>

{{-- Welcome Banner --}}
<div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:var(--radius-xl);padding:1.75rem 2rem;margin-bottom:1.75rem;display:flex;align-items:center;justify-content:space-between;overflow:hidden;position:relative;" data-aos="fade-down">
    <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;background:rgba(255,92,0,0.1);border-radius:50%;"></div>
    <div style="position:relative;z-index:1;">
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.35rem;">Selamat Datang</div>
        <h2 style="font-size:1.4rem;font-weight:800;color:white;margin-bottom:0.35rem;">{{ auth()->guard('aktor')->user()->nama }}</h2>
        <p style="font-size:0.85rem;color:rgba(255,255,255,0.6);">
            <i class="ri-team-line" style="margin-right:0.3rem;color:var(--orange);"></i>
            Panitia Event
        </p>
    </div>
    <a href="{{ route('panitia.events.create') }}" class="btn btn-orange" style="position:relative;z-index:1;">
        <i class="ri-add-line"></i> Buat Event Baru
    </a>
</div>

{{-- Stats --}}
<div class="grid-3" style="margin-bottom:1.75rem;">
    @php
    $statItems = [
        ['label'=>'Total Event Saya', 'value'=>$stats['total_event'], 'icon'=>'ri-calendar-event-line', 'color'=>'navy', 'sub'=>$stats['event_aktif'].' sedang aktif'],
        ['label'=>'Total Peserta', 'value'=>$stats['total_peserta'], 'icon'=>'ri-group-line', 'color'=>'orange', 'sub'=>'dari semua event saya'],
        ['label'=>'Sertifikat Diterbitkan', 'value'=>$stats['total_sertifik'], 'icon'=>'ri-award-line', 'color'=>'success', 'sub'=>'e-sertifikat digital'],
    ];
    @endphp
    @foreach($statItems as $i => $s)
    <div class="stat-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
        <div class="stat-icon stat-icon-{{ $s['color'] }}">
            <i class="{{ $s['icon'] }}"></i>
        </div>
        <div class="stat-value" data-counter="{{ $s['value'] }}">{{ $s['value'] }}</div>
        <div class="stat-label">{{ $s['label'] }}</div>
        <div style="font-size:0.7rem;color:var(--gray-400);margin-top:0.35rem;">{{ $s['sub'] }}</div>
    </div>
    @endforeach
</div>

{{-- Event Status Summary --}}
<div class="grid-4" style="margin-bottom:1.75rem;">
    @php
    $statusItems = [
        ['label'=>'Draft', 'val'=>$stats['event_draft'], 'badge'=>'badge-draft', 'icon'=>'ri-file-list-2-line'],
        ['label'=>'Aktif', 'val'=>$stats['event_aktif'], 'badge'=>'badge-success', 'icon'=>'ri-checkbox-circle-line'],
        ['label'=>'Selesai', 'val'=>$stats['event_selesai'], 'badge'=>'badge-secondary', 'icon'=>'ri-flag-2-line'],
        ['label'=>'Menunggu', 'val'=>$stats['total_event'] - $stats['event_aktif'] - $stats['event_selesai'] - $stats['event_draft'], 'badge'=>'badge-warning', 'icon'=>'ri-time-line'],
    ];
    @endphp
    @foreach($statusItems as $i => $s)
    <div style="background:var(--white);border-radius:var(--radius-lg);padding:1.25rem;border:1px solid var(--gray-100);text-align:center;" data-aos="zoom-in" data-aos-delay="{{ $i * 80 }}">
        <i class="{{ $s['icon'] }}" style="font-size:1.4rem;color:var(--navy);display:block;margin-bottom:0.5rem;"></i>
        <div style="font-size:1.5rem;font-weight:800;color:var(--navy);">{{ $s['val'] }}</div>
        <span class="badge {{ $s['badge'] }}" style="margin-top:0.4rem;">{{ $s['label'] }}</span>
    </div>
    @endforeach
</div>

{{-- Event Terbaru --}}
<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">Event Terbaru Saya</div>
        <a href="{{ route('panitia.events.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Kategori</th>
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
                        <td style="font-size:0.8rem;white-space:nowrap;">{{ $e->tanggal->format('d M Y') }}</td>
                        <td style="font-size:0.8rem;">{{ $e->pesertaTerdaftar()->count() }}/{{ $e->kuota }}</td>
                        <td><span class="badge {{ $e->status_badge }}">{{ $e->status_label }}</span></td>
                        <td>
                            <div style="display:flex;gap:0.4rem;">
                                <a href="{{ route('panitia.events.show', $e->id_event) }}" class="btn btn-primary btn-sm btn-icon" title="Detail">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('panitia.peserta.index', $e->id_event) }}" class="btn btn-outline btn-sm btn-icon" title="Peserta">
                                    <i class="ri-group-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state" style="padding:2.5rem;">
                                <i class="ri-calendar-event-line"></i>
                                <h3>Belum ada event</h3>
                                <a href="{{ route('panitia.events.create') }}" class="btn btn-orange">
                                    <i class="ri-add-line"></i> Buat Event Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
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
        const t = parseInt(el.dataset.counter); if (!t) return;
        let c = 0; const inc = Math.ceil(t / 50);
        const timer = setInterval(() => { c = Math.min(c + inc, t); el.textContent = c; if (c >= t) clearInterval(timer); }, 20);
    });
</script>
@endpush
