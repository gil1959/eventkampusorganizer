@extends('layouts.peserta')
@section('title', 'E-Sertifikat')
@section('page_title', 'E-Sertifikat Saya')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">E-Sertifikat Saya</h1>
        <p class="page-subtitle">Unduh sertifikat digital dari event yang telah Anda hadiri</p>
    </div>
</div>

@if($sertifikats->count() > 0)
<div class="grid-3">
    @foreach($sertifikats as $i => $s)
    <div class="card" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
        <div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));padding:1.5rem;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;background:rgba(255,92,0,0.15);border-radius:50%;"></div>
            <i class="ri-award-fill" style="font-size:3rem;color:var(--orange);display:block;margin-bottom:0.5rem;position:relative;z-index:1;"></i>
            <div style="font-size:0.65rem;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.1em;position:relative;z-index:1;">Sertifikat Kehadiran</div>
        </div>
        <div class="card-body">
            <h3 style="font-size:0.95rem;font-weight:700;color:var(--navy);margin-bottom:0.5rem;line-height:1.4;">
                {{ $s->pendaftaran->event->nama_event }}
            </h3>
            <div style="display:flex;flex-direction:column;gap:0.3rem;margin-bottom:1rem;">
                <div style="font-size:0.75rem;color:var(--gray-500);">
                    <i class="ri-calendar-line" style="margin-right:0.3rem;"></i>
                    {{ $s->pendaftaran->event->tanggal->format('d M Y') }}
                </div>
                <div style="font-size:0.75rem;color:var(--gray-500);">
                    <i class="ri-award-line" style="margin-right:0.3rem;color:var(--orange);"></i>
                    No. {{ $s->nomor_sertifikat }}
                </div>
                <div style="font-size:0.75rem;color:var(--gray-500);">
                    <i class="ri-time-line" style="margin-right:0.3rem;"></i>
                    Diterbitkan {{ $s->tanggal_terbit->format('d M Y') }}
                </div>
            </div>
            <a href="{{ route('peserta.sertifikat.download', $s->id_sertifikat) }}" class="btn btn-orange w-full">
                <i class="ri-download-line"></i> Unduh PDF
            </a>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card" data-aos="fade-up">
    <div class="card-body">
        <div class="empty-state" style="padding:4rem 2rem;">
            <i class="ri-award-line"></i>
            <h3>Belum Ada Sertifikat</h3>
            <p>Sertifikat akan tersedia setelah Anda hadir dalam event dan panitia mengkonfirmasi kehadiran.</p>
            <a href="{{ route('peserta.home') }}" class="btn btn-orange">
                <i class="ri-search-line"></i> Cari Event
            </a>
        </div>
    </div>
</div>
@endif
@endsection
