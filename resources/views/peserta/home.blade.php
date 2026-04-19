@extends('layouts.peserta')
@section('title', 'Cari Event')
@section('page_title', 'Cari Event Kampus')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Jelajahi Event Kampus</h1>
        <p class="page-subtitle">Temukan event yang sesuai minat dan daftar sekarang</p>
    </div>
</div>

{{-- Filter Bar --}}
<div class="card" style="margin-bottom:1.5rem;" data-aos="fade-down">
    <div class="card-body" style="padding:1.25rem;">
        <form method="GET" action="{{ route('peserta.home') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:220px;">
                <label class="form-label" style="font-size:0.75rem;">Cari Event</label>
                <div class="input-group">
                    <i class="ri-search-line input-icon"></i>
                    <input type="text" name="search" class="form-control" placeholder="Nama event..." value="{{ request('search') }}">
                </div>
            </div>
            <div style="min-width:180px;">
                <label class="form-label" style="font-size:0.75rem;">Kategori</label>
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="ri-search-line"></i> Cari
            </button>
            @if(request()->hasAny(['search', 'kategori']))
                <a href="{{ route('peserta.home') }}" class="btn btn-outline">
                    <i class="ri-refresh-line"></i> Reset
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Event Grid --}}
@if($events->count() > 0)
<div class="grid-3" style="margin-bottom:2rem;">
    @foreach($events as $i => $event)
    <div data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 80 }}">
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
                @php
                    $kuotaSisa = $event->kuota - $event->pesertaTerdaftar()->count();
                @endphp
                @if($kuotaSisa <= 10 && $kuotaSisa > 0)
                    <div style="position:absolute;top:1rem;left:1rem;background:var(--warning);color:white;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:9999px;">Sisa {{ $kuotaSisa }}</div>
                @elseif($kuotaSisa <= 0)
                    <div style="position:absolute;top:1rem;left:1rem;background:var(--danger);color:white;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:9999px;">Penuh</div>
                @endif
            </div>
            <div class="event-card-body">
                <span class="badge badge-navy" style="margin-bottom:0.6rem;">{{ $event->kategori->nama_kategori ?? '-' }}</span>
                <h3 style="font-size:1rem;font-weight:700;color:var(--navy);margin-bottom:0.5rem;line-height:1.4;">{{ $event->nama_event }}</h3>
                <p style="font-size:0.8rem;color:var(--gray-500);line-height:1.5;margin-bottom:0.75rem;">{{ Str::limit($event->deskripsi, 90) }}</p>

                <div style="display:flex;flex-direction:column;gap:0.3rem;margin-top:auto;">
                    @if($event->lokasi)
                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--gray-500);">
                        <i class="ri-map-pin-line" style="color:var(--orange);"></i> {{ $event->lokasi }}
                    </div>
                    @endif
                    @if($event->jam)
                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--gray-500);">
                        <i class="ri-time-line" style="color:var(--navy);"></i> {{ substr($event->jam, 0, 5) }} WIB
                    </div>
                    @endif
                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--gray-500);">
                        <i class="ri-group-line" style="color:var(--navy);"></i>
                        {{ $event->pesertaTerdaftar()->count() }}/{{ $event->kuota }} peserta
                    </div>
                </div>
            </div>
            <div class="event-card-footer">
                <div style="font-size:0.85rem;font-weight:700;color:var(--orange);">
                    {{ $event->biaya > 0 ? 'Rp '.number_format($event->biaya,0,',','.') : 'Gratis' }}
                </div>
                <a href="{{ route('peserta.event.show', $event->id_event) }}" class="btn btn-primary btn-sm">
                    Detail <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="pagination-wrapper">
    {{ $events->withQueryString()->links() }}
</div>

@else
<div class="card" data-aos="fade-up">
    <div class="card-body">
        <div class="empty-state" style="padding:4rem 2rem;">
            <i class="ri-calendar-event-line"></i>
            <h3>Tidak ada event ditemukan</h3>
            <p>Coba ubah kata kunci pencarian atau filter kategori</p>
            <a href="{{ route('peserta.home') }}" class="btn btn-outline">
                <i class="ri-refresh-line"></i> Lihat Semua Event
            </a>
        </div>
    </div>
</div>
@endif
@endsection
