@extends('layouts.app')
@section('title', 'Jelajahi Event Kampus')

@section('content')
<div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));padding:3rem 0 2rem;">
    <div class="container">
        <div style="text-align:center;color:white;">
            <div style="font-size:0.75rem;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:0.5rem;">Temukan Event Terbaik</div>
            <h1 style="font-size:2rem;font-weight:800;margin-bottom:0.75rem;">Jelajahi Event Kampus</h1>
            <p style="color:rgba(255,255,255,0.65);max-width:480px;margin:0 auto;">Pilih dan daftarkan diri pada event yang sesuai dengan minat dan kebutuhan akademik Anda.</p>
        </div>
    </div>
</div>

<div class="container" style="padding:2rem 1rem;">
    {{-- Filter --}}
    <div class="card" style="margin-bottom:1.75rem;" data-aos="fade-down">
        <div class="card-body" style="padding:1.25rem;">
            <form method="GET" action="{{ route('landing.events') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
                <div style="flex:1;min-width:220px;">
                    <label class="form-label" style="font-size:0.75rem;">Nama Event</label>
                    <div class="input-group">
                        <i class="ri-search-line input-icon"></i>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama event..." value="{{ request('search') }}">
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
                @if(request()->hasAny(['search','kategori']))
                    <a href="{{ route('landing.events') }}" class="btn btn-outline">
                        <i class="ri-refresh-line"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if($events->count() > 0)
    <div class="grid-3" style="margin-bottom:2rem;">
        @foreach($events as $i => $event)
        <div data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 80 }}">
            <div class="event-card">
                <div class="event-card-img">
                    @if($event->poster)
                        <img src="{{ asset('storage/'.$event->poster) }}" alt="{{ $event->nama_event }}">
                    @else
                        <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.5rem;color:rgba(255,255,255,0.4);">
                            <i class="ri-calendar-event-line" style="font-size:2.5rem;"></i>
                        </div>
                    @endif
                    <div class="event-date-badge"><i class="ri-calendar-line"></i> {{ $event->tanggal->format('d M Y') }}</div>
                    @if($event->biaya == 0)
                        <div style="position:absolute;top:1rem;right:1rem;background:var(--success);color:white;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:9999px;">GRATIS</div>
                    @endif
                    @php $kuotaSisa = $event->kuota - $event->pesertaTerdaftar()->count(); @endphp
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
                    <div style="display:flex;flex-direction:column;gap:0.3rem;">
                        @if($event->lokasi)
                        <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--gray-500);">
                            <i class="ri-map-pin-line" style="color:var(--orange);"></i> {{ $event->lokasi }}
                        </div>
                        @endif
                        <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;color:var(--gray-500);">
                            <i class="ri-group-line" style="color:var(--navy);"></i> {{ $event->pesertaTerdaftar()->count() }}/{{ $event->kuota }} peserta
                        </div>
                    </div>
                </div>
                <div class="event-card-footer">
                    <div style="font-size:0.85rem;font-weight:700;color:var(--orange);">
                        {{ $event->biaya > 0 ? 'Rp '.number_format($event->biaya,0,',','.') : 'Gratis' }}
                    </div>
                    <a href="{{ route('landing.event.detail', $event->id_event) }}" class="btn btn-primary btn-sm">
                        Detail <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="pagination-wrapper">{{ $events->withQueryString()->links() }}</div>
    @else
    <div class="card" data-aos="fade-up">
        <div class="card-body">
            <div class="empty-state" style="padding:4rem 2rem;">
                <i class="ri-calendar-event-line"></i>
                <h3>Tidak Ada Event</h3>
                <p>Belum ada event yang tersedia saat ini. Coba lagi nanti.</p>
                <a href="{{ route('landing.events') }}" class="btn btn-outline">
                    <i class="ri-refresh-line"></i> Lihat Semua
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
