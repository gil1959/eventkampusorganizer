@extends('layouts.peserta')
@section('title', $event->nama_event)
@section('page_title', 'Detail Event')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('peserta.home') }}">Cari Event</a>
    <span>/</span>
    <span>{{ Str::limit($event->nama_event, 40) }}</span>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        {{-- Event Header --}}
        <div class="card" data-aos="fade-up">
            @if($event->poster)
                <img src="{{ asset('storage/'.$event->poster) }}" style="width:100%;max-height:320px;object-fit:cover;">
            @else
                <div style="height:220px;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;flex-direction:column;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);">
                    <i class="ri-calendar-event-line" style="font-size:3rem;margin-bottom:0.5rem;"></i>
                    <span style="font-size:0.85rem;">{{ $event->kategori->nama_kategori }}</span>
                </div>
            @endif
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
                    <span class="badge badge-navy">{{ $event->kategori->nama_kategori }}</span>
                    @if($event->biaya == 0)<span class="badge badge-success">Gratis</span>@endif
                </div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--navy);margin-bottom:1rem;">{{ $event->nama_event }}</h1>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem 1.5rem;margin-bottom:1.25rem;">
                    @foreach([
                        ['Tanggal', $event->tanggal->format('d M Y'), 'ri-calendar-line'],
                        ['Waktu', $event->jam ? substr($event->jam,0,5).' WIB' : 'Akan diumumkan', 'ri-time-line'],
                        ['Lokasi', $event->lokasi ?? 'Akan diumumkan', 'ri-map-pin-line'],
                        ['Narasumber', $event->narasumber ?? '-', 'ri-user-voice-line'],
                        ['Kuota', $event->kuota.' peserta', 'ri-group-line'],
                        ['Biaya', $event->biaya > 0 ? 'Rp '.number_format($event->biaya,0,',','.') : 'Gratis', 'ri-money-cny-circle-line'],
                    ] as $item)
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;">
                        <i class="{{ $item[2] }}" style="color:var(--orange);font-size:1rem;flex-shrink:0;"></i>
                        <div>
                            <div style="font-size:0.7rem;color:var(--gray-400);">{{ $item[0] }}</div>
                            <div style="font-weight:600;color:var(--gray-800);">{{ $item[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="divider"></div>
                <h3 style="font-size:0.95rem;font-weight:700;color:var(--navy);margin-bottom:0.75rem;">Tentang Event</h3>
                <p style="font-size:0.875rem;color:var(--gray-600);line-height:1.75;">{{ $event->deskripsi }}</p>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="position:sticky;top:1rem;">
        <div class="card" data-aos="fade-left">
            <div class="card-body">
                @php $kuotaSisa = $event->kuota - $event->pesertaTerdaftar()->count(); @endphp

                {{-- Quota Bar --}}
                <div style="margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;margin-bottom:0.5rem;">
                        <span style="color:var(--gray-600);">Kuota Terisi</span>
                        <span style="font-weight:700;color:var(--navy);">{{ $event->pesertaTerdaftar()->count() }}/{{ $event->kuota }}</span>
                    </div>
                    <div style="height:8px;background:var(--gray-100);border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $event->kuota > 0 ? min(100, ($event->pesertaTerdaftar()->count() / $event->kuota * 100)) : 0 }}%;background:linear-gradient(90deg,var(--navy),var(--orange));border-radius:4px;transition:width 0.5s;"></div>
                    </div>
                    <div style="font-size:0.72rem;color:{{ $kuotaSisa <= 0 ? 'var(--danger)' : ($kuotaSisa <= 10 ? 'var(--warning)' : 'var(--success)') }};margin-top:0.35rem;font-weight:600;">
                        {{ $kuotaSisa <= 0 ? 'Kuota penuh' : $kuotaSisa.' tempat tersisa' }}
                    </div>
                </div>

                <div style="font-size:1.5rem;font-weight:800;color:var(--orange);text-align:center;margin-bottom:1rem;">
                    {{ $event->biaya > 0 ? 'Rp '.number_format($event->biaya,0,',','.') : 'Gratis' }}
                </div>

                @if($sudahDaftar)
                    <div class="alert alert-success" style="margin-bottom:1rem;">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Anda sudah terdaftar di event ini</span>
                    </div>
                    <a href="{{ route('peserta.riwayat') }}" class="btn btn-outline w-full">
                        <i class="ri-history-line"></i> Lihat Riwayat
                    </a>
                @elseif($kuotaSisa <= 0)
                    <button class="btn btn-danger w-full" disabled>
                        <i class="ri-close-circle-line"></i> Kuota Penuh
                    </button>
                @else
                    <a href="{{ route('peserta.daftar.form', $event->id_event) }}"
                       class="btn btn-orange w-full btn-lg">
                        <i class="ri-calendar-check-line"></i>
                        {{ $event->biaya > 0 ? 'Daftar & Bayar' : 'Daftar Sekarang (Gratis)' }}
                    </a>
                @endif

                <div class="divider"></div>

                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.78rem;color:var(--gray-500);">
                        <i class="ri-user-line" style="color:var(--navy);"></i>
                        Diorganisir oleh <strong style="color:var(--navy);">{{ $event->panitia->nama }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
