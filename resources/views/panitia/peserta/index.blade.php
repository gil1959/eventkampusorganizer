@extends('layouts.panitia')
@section('title', 'Peserta Event')
@section('page_title', 'Manajemen Peserta')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Peserta: {{ Str::limit($event->nama_event, 35) }}</h1>
        <p class="page-subtitle">Kelola dan konfirmasi kehadiran peserta</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
        {{-- Tombol Export Langsung --}}
        <a href="{{ route('panitia.peserta.absensi', ['eventId' => $event->id_event, 'format' => 'pdf']) }}"
           class="btn btn-outline btn-sm" title="Unduh PDF">
            <i class="ri-file-pdf-line" style="color:#ef4444;"></i> PDF
        </a>
        <a href="{{ route('panitia.peserta.absensi', ['eventId' => $event->id_event, 'format' => 'csv']) }}"
           class="btn btn-outline btn-sm" title="Unduh CSV">
            <i class="ri-file-text-line" style="color:#22c55e;"></i> CSV
        </a>
        <a href="{{ route('panitia.peserta.absensi', ['eventId' => $event->id_event, 'format' => 'excel']) }}"
           class="btn btn-outline btn-sm" title="Unduh Excel">
            <i class="ri-table-line" style="color:#16a34a;"></i> Excel
        </a>
        @if($event->biaya > 0)
            <a href="{{ route('panitia.rekening.index', $event->id_event) }}" class="btn btn-outline btn-sm">
                <i class="ri-bank-card-line"></i> Rekening
            </a>
        @endif
        <a href="{{ route('panitia.events.show', $event->id_event) }}" class="btn btn-outline">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    @php
    $total     = $pendaftarans->count();
    $hadir     = $pendaftarans->where('status_kehadiran', 'hadir')->count();
    $terdaftar = $pendaftarans->where('status_kehadiran', 'pendaftar')->count();
    $terbayar  = $pendaftarans->where('status_kehadiran', 'terbayar')->count();
    $batal     = $pendaftarans->where('status_kehadiran', 'batal')->count();
    @endphp
    @foreach([
        ['Total', $total, 'ri-group-line', 'navy'],
        ['Terdaftar', $terdaftar, 'ri-calendar-check-line', 'orange'],
        ['Hadir', $hadir, 'ri-checkbox-circle-line', 'success'],
        ['Batal', $batal, 'ri-close-circle-line', 'danger'],
    ] as $i => $s)
    <div class="stat-card" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
        <div class="stat-icon stat-icon-{{ $s[3] }}"><i class="{{ $s[2] }}"></i></div>
        <div class="stat-value">{{ $s[1] }}</div>
        <div class="stat-label">{{ $s[0] }}</div>
    </div>
    @endforeach
</div>

<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">Daftar Peserta ({{ $total }})</div>
        @if($hadir > 0)
            <a href="{{ route('panitia.sertifikat.index', $event->id_event) }}" class="btn btn-outline btn-sm">
                <i class="ri-award-line"></i> Kelola Sertifikat ({{ $hadir }} hadir)
            </a>
        @endif
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peserta</th>
                        <th>NPM</th>
                        <th>Program Studi</th>
                        <th>No. Tiket</th>
                        <th>Tgl Daftar</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $i => $p)
                    <tr>
                        <td style="color:var(--gray-400);">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;align-items:center;justify-content:center;color:white;font-size:0.8rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($p->peserta->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.875rem;">{{ $p->peserta->nama }}</div>
                                    <div style="font-size:0.72rem;color:var(--gray-400);">{{ $p->peserta->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-family:monospace;font-size:0.82rem;font-weight:600;">{{ $p->peserta->npm_nip ?? '-' }}</td>
                        <td style="font-size:0.8rem;color:var(--gray-500);">{{ $p->peserta->jurusan ?? '-' }}</td>
                        <td style="font-family:monospace;font-size:0.75rem;color:var(--orange);">{{ $p->nomor_tiket }}</td>
                        <td style="font-size:0.78rem;white-space:nowrap;">
                            {{ $p->tanggal_daftar ? $p->tanggal_daftar->format('d M Y') : '-' }}
                        </td>
                        <td>
                            @if($p->pembayaran)
                                <span class="badge {{ $p->pembayaran->status_badge }}" style="font-size:0.68rem;">
                                    {{ $p->pembayaran->status_label }}
                                </span>
                                @if($p->pembayaran->metode === 'bayar_lokasi')
                                    <div style="font-size:0.68rem;color:var(--gray-400);margin-top:0.15rem;">Di Lokasi</div>
                                @endif
                            @elseif($p->event->biaya == 0)
                                <span class="badge badge-success" style="font-size:0.68rem;">Gratis</span>
                            @else
                                <span style="font-size:0.75rem;color:var(--gray-400);">Belum bayar</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('panitia.peserta.update-status', $p->id_pendaftaran) }}" style="display:flex;gap:0.4rem;">
                                @csrf @method('PATCH')
                                <select name="status_kehadiran" class="form-control" style="font-size:0.78rem;padding:0.3rem 0.5rem;width:auto;">
                                    <option value="pendaftar" {{ $p->status_kehadiran === 'pendaftar' ? 'selected' : '' }}>Terdaftar</option>
                                    <option value="terbayar"  {{ $p->status_kehadiran === 'terbayar'  ? 'selected' : '' }}>Terbayar</option>
                                    <option value="hadir"     {{ $p->status_kehadiran === 'hadir'     ? 'selected' : '' }}>Hadir</option>
                                    <option value="batal"     {{ $p->status_kehadiran === 'batal'     ? 'selected' : '' }}>Batal</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm btn-icon" title="Simpan">
                                    <i class="ri-save-line"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state" style="padding:2.5rem;">
                                <i class="ri-group-line"></i>
                                <h3>Belum ada peserta</h3>
                                <p>Peserta yang mendaftar event ini akan muncul di sini.</p>
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
