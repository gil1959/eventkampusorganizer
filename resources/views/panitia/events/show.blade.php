@extends('layouts.panitia')
@section('title', $event->nama_event)
@section('page_title', 'Detail Event')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ Str::limit($event->nama_event, 40) }}</h1>
        <p class="page-subtitle">Detail dan manajemen event</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        @if(in_array($event->status, ['draft','ditolak']))
            <a href="{{ route('panitia.events.edit', $event->id_event) }}" class="btn btn-orange">
                <i class="ri-edit-line"></i> Edit Event
            </a>
        @endif
        <a href="{{ route('panitia.peserta.index', $event->id_event) }}" class="btn btn-navy">
            <i class="ri-group-line"></i> Peserta
        </a>
        @if($event->biaya > 0)
            <a href="{{ route('panitia.pembayaran.index', $event->id_event) }}" class="btn btn-navy">
                <i class="ri-money-dollar-circle-line"></i> Pembayaran
            </a>
            <a href="{{ route('panitia.rekening.index', $event->id_event) }}" class="btn btn-outline">
                <i class="ri-bank-card-line"></i> Rekening
            </a>
        @endif
        <a href="{{ route('panitia.sertifikat.index', $event->id_event) }}" class="btn btn-outline">
            <i class="ri-award-line"></i> Sertifikat
        </a>
        <a href="{{ route('panitia.events.index') }}" class="btn btn-outline">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
        <form method="POST" action="{{ route('panitia.events.destroy', $event->id_event) }}"
              onsubmit="return confirm('Yakin hapus event ini secara permanen? Semua data peserta, pembayaran, dan sertifikat akan ikut terhapus!')" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="ri-delete-bin-line"></i> Hapus Event
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" data-aos="fade-down">
        <i class="ri-checkbox-circle-line"></i>
        <span>{{ session('success') }}</span>
        <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
    </div>
@endif

@if($event->status === 'ditolak' && $event->catatan_admin)
    <div class="alert alert-danger" data-aos="fade-down">
        <i class="ri-close-circle-line"></i>
        <div>
            <strong>Event Ditolak Admin</strong><br>
            Alasan: {{ $event->catatan_admin }}
        </div>
    </div>
@endif

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" data-aos="fade-up">
    {{-- Info Utama --}}
    <div>
        <div class="card" style="margin-bottom:1.25rem;">
            <div class="card-header">
                <div style="font-weight:700;color:var(--navy);">
                    <i class="ri-information-line" style="color:var(--orange);"></i> Informasi Event
                </div>
                <span class="badge {{ $event->status_badge }}">{{ $event->status_label }}</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Nama Event</div>
                        <div style="font-weight:600;">{{ $event->nama_event }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Kategori</div>
                        <div style="font-weight:600;">{{ $event->kategori->nama_kategori ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Tanggal</div>
                        <div style="font-weight:600;">{{ $event->tanggal->format('d M Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Waktu</div>
                        <div style="font-weight:600;">{{ $event->jam ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Lokasi</div>
                        <div style="font-weight:600;">{{ $event->lokasi ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Narasumber</div>
                        <div style="font-weight:600;">{{ $event->narasumber ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Kuota</div>
                        <div style="font-weight:600;">{{ $event->kuota }} peserta</div>
                    </div>
                    <div>
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.3rem;">Biaya</div>
                        <div style="font-weight:700;color:{{ $event->biaya > 0 ? 'var(--orange)' : 'var(--success)' }};">
                            {{ $event->biaya > 0 ? 'Rp ' . number_format($event->biaya, 0, ',', '.') : 'Gratis' }}
                        </div>
                    </div>
                </div>

                @if($event->deskripsi)
                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--gray-100);">
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-400);margin-bottom:0.5rem;">Deskripsi</div>
                        <div style="font-size:0.875rem;line-height:1.6;color:var(--gray-600);">{{ $event->deskripsi }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar: Stats + Poster --}}
    <div>
        {{-- Stats peserta --}}
        <div class="card" style="margin-bottom:1.25rem;">
            <div class="card-header">
                <div style="font-weight:700;color:var(--navy);">Statistik Peserta</div>
            </div>
            <div class="card-body">
                @php
                    $totalPeserta  = $event->pendaftarans->where('status_kehadiran', '!=', 'batal')->count();
                    $pesertaHadir  = $event->pendaftarans->where('status_kehadiran', 'hadir')->count();
                    $pesertaBatal  = $event->pendaftarans->where('status_kehadiran', 'batal')->count();
                    $kuotaSisa     = $event->kuota - $totalPeserta;
                @endphp
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                    @foreach([
                        ['Terdaftar', $totalPeserta, 'ri-group-line', 'var(--navy)'],
                        ['Hadir', $pesertaHadir, 'ri-checkbox-circle-line', 'var(--success)'],
                        ['Kuota Sisa', $kuotaSisa, 'ri-ticket-line', 'var(--orange)'],
                        ['Batal', $pesertaBatal, 'ri-close-circle-line', 'var(--danger)'],
                    ] as $s)
                    <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:0.75rem;text-align:center;">
                        <div style="font-size:1.4rem;font-weight:800;color:{{ $s[3] }};">{{ $s[1] }}</div>
                        <div style="font-size:0.72rem;color:var(--gray-500);">{{ $s[0] }}</div>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('panitia.peserta.index', $event->id_event) }}"
                   class="btn btn-navy w-full" style="margin-top:1rem;">
                    <i class="ri-group-line"></i> Kelola Peserta
                </a>
            </div>
        </div>

        {{-- Poster --}}
        @if($event->poster)
        <div class="card">
            <div class="card-header">
                <div style="font-weight:700;color:var(--navy);">Poster Event</div>
            </div>
            <div style="padding:0.75rem;">
                <img src="{{ $event->poster_url }}" alt="Poster {{ $event->nama_event }}"
                     style="width:100%;border-radius:var(--radius-md);object-fit:cover;max-height:250px;">
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Daftar Peserta (preview 5) --}}
@if($event->pendaftarans->count() > 0)
<div class="card" style="margin-top:1.25rem;" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">Peserta Terdaftar (Preview)</div>
        <a href="{{ route('panitia.peserta.index', $event->id_event) }}" class="btn btn-outline btn-sm">
            Lihat Semua <i class="ri-arrow-right-line"></i>
        </a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr><th>Nama</th><th>No. Tiket</th><th>Status</th><th>Tanggal Daftar</th></tr>
                </thead>
                <tbody>
                    @foreach($event->pendaftarans->take(5) as $p)
                    <tr>
                        <td style="font-weight:600;">{{ $p->peserta->nama ?? '-' }}</td>
                        <td style="font-family:monospace;font-size:0.8rem;">{{ $p->nomor_tiket }}</td>
                        <td><span class="badge {{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                        <td style="font-size:0.8rem;color:var(--gray-500);">
                            {{ $p->tanggal_daftar ? \Carbon\Carbon::parse($p->tanggal_daftar)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
