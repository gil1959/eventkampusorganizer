@extends('layouts.panitia')
@section('title', 'Kelola Sertifikat')
@section('page_title', 'Kelola Sertifikat')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Sertifikat Peserta</h1>
        <p class="page-subtitle">{{ $event->nama_event }}</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        @php
            $belumSertifikat = $pendaftarans->filter(fn($p) => !$p->sertifikat)->count();
        @endphp
        @if($belumSertifikat > 0)
            <form method="POST" action="{{ route('panitia.sertifikat.generate', $event->id_event) }}">
                @csrf
                <button type="submit" class="btn btn-orange"
                        onclick="return confirm('Generate {{ $belumSertifikat }} sertifikat untuk peserta yang belum memiliki?')">
                    <i class="ri-award-fill"></i> Generate Semua ({{ $belumSertifikat }})
                </button>
            </form>
        @endif
        <a href="{{ route('panitia.peserta.index', $event->id_event) }}" class="btn btn-outline">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;" data-aos="fade-down">
        <i class="ri-checkbox-circle-line"></i>
        <span>{{ session('success') }}</span>
        <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info" style="margin-bottom:1rem;" data-aos="fade-down">
        <i class="ri-information-line"></i>
        <span>{{ session('info') }}</span>
        <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
    </div>
@endif

{{-- Stats --}}
@php
$totalHadir      = $pendaftarans->count();
$sudahSertifikat = $pendaftarans->filter(fn($p) => $p->sertifikat)->count();
$belumSertifikat = $totalHadir - $sudahSertifikat;
@endphp
<div class="grid-3" style="margin-bottom:1.5rem;">
    <div class="stat-card" data-aos="fade-up">
        <div class="stat-icon stat-icon-navy"><i class="ri-group-line"></i></div>
        <div class="stat-value">{{ $totalHadir }}</div>
        <div class="stat-label">Peserta Hadir</div>
    </div>
    <div class="stat-card" data-aos="fade-up" data-aos-delay="80">
        <div class="stat-icon stat-icon-success"><i class="ri-award-line"></i></div>
        <div class="stat-value">{{ $sudahSertifikat }}</div>
        <div class="stat-label">Sertifikat Terbit</div>
    </div>
    <div class="stat-card" data-aos="fade-up" data-aos-delay="160">
        <div class="stat-icon stat-icon-warning"><i class="ri-time-line"></i></div>
        <div class="stat-value">{{ $belumSertifikat }}</div>
        <div class="stat-label">Belum Digenerate</div>
    </div>
</div>

@if($pendaftarans->isEmpty())
    <div class="card" data-aos="fade-up">
        <div class="card-body">
            <div class="empty-state" style="padding:3rem;">
                <i class="ri-award-line"></i>
                <h3>Belum ada peserta yang hadir</h3>
                <p>Sertifikat hanya tersedia untuk peserta dengan status "Hadir". Tandai kehadiran peserta di halaman manajemen peserta.</p>
                <a href="{{ route('panitia.peserta.index', $event->id_event) }}" class="btn btn-orange">
                    <i class="ri-group-line"></i> Kelola Peserta
                </a>
            </div>
        </div>
    </div>
@else
<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">Daftar Peserta yang Hadir</div>
        @if($belumSertifikat > 0)
            <span class="badge badge-warning">{{ $belumSertifikat }} belum ada sertifikat</span>
        @else
            <span class="badge badge-success"><i class="ri-check-line"></i> Semua sudah digenerate</span>
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
                        <th>No. Sertifikat</th>
                        <th>Tanggal Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftarans as $i => $p)
                    <tr>
                        <td style="color:var(--gray-400);">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($p->peserta->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.875rem;">{{ $p->peserta->nama }}</div>
                                    <div style="font-size:0.72rem;color:var(--gray-400);">{{ $p->peserta->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-family:monospace;font-size:0.82rem;">{{ $p->peserta->npm_nip ?? '-' }}</td>
                        <td>
                            @if($p->sertifikat)
                                <code style="font-size:0.78rem;background:var(--navy-ghost);padding:0.2rem 0.5rem;border-radius:4px;color:var(--navy);">
                                    {{ $p->sertifikat->nomor_sertifikat }}
                                </code>
                            @else
                                <span style="color:var(--gray-400);font-size:0.8rem;font-style:italic;">Belum digenerate</span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem;">
                            {{ $p->sertifikat ? $p->sertifikat->tanggal_terbit->format('d M Y') : '-' }}
                        </td>
                        <td>
                            <div style="display:flex;gap:0.4rem;">
                                @if($p->sertifikat)
                                    {{-- Unduh PDF --}}
                                    <a href="{{ route('panitia.sertifikat.preview', $p->sertifikat->id_sertifikat) }}"
                                       class="btn btn-orange btn-sm" title="Unduh PDF" target="_blank">
                                        <i class="ri-download-2-line"></i> PDF
                                    </a>
                                @else
                                    {{-- Generate untuk satu peserta --}}
                                    <form method="POST"
                                          action="{{ route('panitia.sertifikat.generate-satu', [$event->id_event, $p->id_pendaftaran]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-navy btn-sm" title="Generate sertifikat">
                                            <i class="ri-award-line"></i> Generate
                                        </button>
                                    </form>
                                @endif
                            </div>
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
