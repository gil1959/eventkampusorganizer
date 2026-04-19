@extends('layouts.peserta')
@section('title', 'Riwayat Pendaftaran')
@section('page_title', 'Riwayat Pendaftaran')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Riwayat Pendaftaran</h1>
        <p class="page-subtitle">Semua event yang pernah Anda daftarkan</p>
    </div>
    <a href="{{ route('peserta.home') }}" class="btn btn-orange">
        <i class="ri-search-line"></i> Cari Event Baru
    </a>
</div>

@if($pendaftarans->count() > 0)
<div style="display:flex;flex-direction:column;gap:1rem;">
    @foreach($pendaftarans as $i => $p)
    <div class="card" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
        <div class="card-body" style="padding:1.25rem;">
            <div style="display:flex;align-items:start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div style="display:flex;gap:1rem;min-width:0;flex:1;">
                    <div style="width:70px;height:70px;border-radius:var(--radius-md);overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);font-size:1.5rem;">
                        @if($p->event->poster)
                            <img src="{{ asset('storage/'.$p->event->poster) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="ri-calendar-event-line"></i>
                        @endif
                    </div>
                    <div style="min-width:0;">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.35rem;flex-wrap:wrap;">
                            <h3 style="font-size:1rem;font-weight:700;color:var(--navy);">{{ $p->event->nama_event }}</h3>
                            <span class="badge {{ $p->status_badge }}">{{ $p->status_label }}</span>
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;font-size:0.78rem;color:var(--gray-500);margin-bottom:0.35rem;">
                            <span><i class="ri-calendar-line" style="color:var(--navy);margin-right:0.2rem;"></i>{{ $p->event->tanggal->format('d M Y') }}</span>
                            @if($p->event->lokasi)<span><i class="ri-map-pin-line" style="color:var(--orange);margin-right:0.2rem;"></i>{{ $p->event->lokasi }}</span>@endif
                            <span><i class="ri-price-tag-3-line" style="margin-right:0.2rem;"></i>{{ $p->event->kategori->nama_kategori ?? '-' }}</span>
                        </div>
                        @if($p->nomor_tiket)
                            <div style="background:var(--navy-ghost);border:1px solid var(--navy-pale);border-radius:var(--radius);padding:0.35rem 0.75rem;display:inline-flex;align-items:center;gap:0.4rem;font-size:0.75rem;font-family:monospace;font-weight:600;color:var(--navy);">
                                <i class="ri-ticket-line"></i> {{ $p->nomor_tiket }}
                            </div>
                        @endif
                        <div style="font-size:0.72rem;color:var(--gray-400);margin-top:0.35rem;">
                            Didaftarkan: {{ $p->tanggal_daftar->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:0.5rem;align-items:flex-end;flex-shrink:0;">
                    @if($p->status_kehadiran === 'hadir' && $p->sertifikat)
                        <a href="{{ route('peserta.sertifikat.download', $p->sertifikat->id_sertifikat) }}" class="btn btn-success btn-sm">
                            <i class="ri-download-line"></i> Unduh Sertifikat
                        </a>
                    @elseif($p->status_kehadiran === 'hadir')
                        <span class="badge badge-info" style="font-size:0.75rem;padding:0.4rem 0.75rem;">Sertifikat belum tersedia</span>
                    @elseif($p->status_kehadiran !== 'batal')
                        <a href="{{ route('peserta.event.show', $p->id_event) }}" class="btn btn-outline btn-sm">
                            <i class="ri-eye-line"></i> Lihat Event
                        </a>
                        <form method="POST" action="{{ route('peserta.pendaftaran.batal', $p->id_pendaftaran) }}"
                              onsubmit="return confirm('Batalkan pendaftaran event ini?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="ri-close-circle-line"></i> Batalkan
                            </button>
                        </form>
                    @else
                        <span class="badge badge-danger" style="font-size:0.75rem;padding:0.4rem 0.75rem;">Pendaftaran Dibatalkan</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="pagination-wrapper">{{ $pendaftarans->links() }}</div>

@else
<div class="card" data-aos="fade-up">
    <div class="card-body">
        <div class="empty-state" style="padding:4rem 2rem;">
            <i class="ri-history-line"></i>
            <h3>Belum Ada Riwayat Pendaftaran</h3>
            <p>Anda belum pernah mendaftar event. Mulai cari event yang menarik!</p>
            <a href="{{ route('peserta.home') }}" class="btn btn-orange">
                <i class="ri-search-line"></i> Jelajahi Event
            </a>
        </div>
    </div>
</div>
@endif
@endsection
