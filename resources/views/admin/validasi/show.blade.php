@extends('layouts.admin')
@section('title', 'Detail Event')
@section('page_title', 'Detail Event')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.validasi.index') }}">Validasi Event</a>
    <span>/</span>
    <span>{{ Str::limit($event->nama_event, 30) }}</span>
</div>

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $event->nama_event }}</h1>
        <span class="badge {{ $event->status_badge }}" style="font-size:0.85rem;padding:0.4rem 0.85rem;">{{ $event->status_label }}</span>
    </div>
    <div style="display:flex;gap:0.75rem;">
        @if($event->status === 'menunggu')
            <button onclick="document.getElementById('approveModal').style.display='flex'" class="btn btn-success">
                <i class="ri-check-line"></i> Setujui Event
            </button>
            <button onclick="document.getElementById('rejectModal').style.display='flex'" class="btn btn-danger">
                <i class="ri-close-line"></i> Tolak Event
            </button>
        @endif
        <a href="{{ route('admin.validasi.index') }}" class="btn btn-outline">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <div class="card" data-aos="fade-up">
            <div class="card-header"><div style="font-weight:700;color:var(--navy);">Informasi Event</div></div>
            <div class="card-body">
                @if($event->poster)
                    <img src="{{ asset('storage/'.$event->poster) }}" style="width:100%;max-height:300px;object-fit:cover;border-radius:var(--radius-md);margin-bottom:1.25rem;">
                @endif
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem 2rem;margin-bottom:1.25rem;">
                    @foreach([
                        ['Kategori', $event->kategori->nama_kategori ?? '-', 'ri-price-tag-3-line'],
                        ['Tanggal', $event->tanggal->format('d M Y'), 'ri-calendar-line'],
                        ['Jam', $event->jam ? substr($event->jam, 0, 5).' WIB' : '-', 'ri-time-line'],
                        ['Lokasi', $event->lokasi ?? '-', 'ri-map-pin-line'],
                        ['Kuota', $event->kuota.' peserta', 'ri-group-line'],
                        ['Biaya', $event->biaya > 0 ? 'Rp '.number_format($event->biaya,0,',','.') : 'Gratis', 'ri-money-cny-circle-line'],
                        ['Narasumber', $event->narasumber ?? '-', 'ri-user-voice-line'],
                        ['Diajukan oleh', $event->panitia->nama.' (NPM: '.$event->panitia->npm_nip.')', 'ri-team-line'],
                    ] as $item)
                    <div>
                        <div style="font-size:0.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.2rem;">{{ $item[0] }}</div>
                        <div style="font-size:0.875rem;color:var(--gray-800);font-weight:600;display:flex;align-items:center;gap:0.3rem;">
                            <i class="{{ $item[2] }}" style="color:var(--navy);"></i>{{ $item[1] }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div>
                    <div style="font-size:0.72rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;">Deskripsi Event</div>
                    <p style="font-size:0.875rem;color:var(--gray-700);line-height:1.7;">{{ $event->deskripsi }}</p>
                </div>
                @if($event->catatan_admin)
                    <div style="margin-top:1rem;background:var(--warning-bg);border:1px solid #fde68a;border-radius:var(--radius-md);padding:0.75rem 1rem;font-size:0.82rem;color:var(--warning);">
                        <strong>Catatan Admin:</strong> {{ $event->catatan_admin }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Peserta --}}
        @if($event->pendaftarans->count() > 0)
        <div class="card" data-aos="fade-up">
            <div class="card-header">
                <div style="font-weight:700;color:var(--navy);">Peserta Terdaftar ({{ $event->pendaftarans->count() }})</div>
            </div>
            <div class="card-body" style="padding:0;">
                <table class="styled-table">
                    <thead><tr><th>Nama</th><th>NPM</th><th>Status</th><th>Terdaftar</th></tr></thead>
                    <tbody>
                        @foreach($event->pendaftarans as $p)
                        <tr>
                            <td style="font-weight:600;">{{ $p->peserta->nama }}</td>
                            <td style="font-family:monospace;">{{ $p->peserta->npm_nip }}</td>
                            <td><span class="badge {{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                            <td style="font-size:0.78rem;">{{ $p->tanggal_daftar->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Side info --}}
    <div class="card" data-aos="fade-left">
        <div class="card-header"><div style="font-weight:700;color:var(--navy);">Ringkasan</div></div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div style="text-align:center;padding:1rem;background:var(--navy-ghost);border-radius:var(--radius-md);">
                    <div style="font-size:2rem;font-weight:800;color:var(--navy);">{{ $event->pendaftarans->count() }}</div>
                    <div style="font-size:0.78rem;color:var(--gray-500);">Total Pendaftar</div>
                </div>
                <div style="text-align:center;padding:1rem;background:var(--orange-100);border-radius:var(--radius-md);">
                    <div style="font-size:2rem;font-weight:800;color:var(--orange);">{{ $event->kuota_sisa }}</div>
                    <div style="font-size:0.78rem;color:var(--gray-500);">Kuota Tersisa</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Approve Modal --}}
<div class="modal-backdrop" id="approveModal" style="display:none;">
    <div class="modal">
        <div class="modal-header"><h3>Setujui Event</h3><button onclick="document.getElementById('approveModal').style.display='none'" style="background:none;border:none;color:white;cursor:pointer;font-size:1.2rem;"><i class="ri-close-line"></i></button></div>
        <form method="POST" action="{{ route('admin.validasi.approve', $event->id_event) }}">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-0"><label class="form-label">Catatan (opsional)</label><textarea name="catatan" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" onclick="document.getElementById('approveModal').style.display='none'" class="btn btn-outline">Batal</button><button type="submit" class="btn btn-success"><i class="ri-check-line"></i> Setujui</button></div>
        </form>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal-backdrop" id="rejectModal" style="display:none;">
    <div class="modal">
        <div class="modal-header" style="background:linear-gradient(135deg,var(--danger),#b91c1c);"><h3>Tolak Event</h3><button onclick="document.getElementById('rejectModal').style.display='none'" style="background:none;border:none;color:white;cursor:pointer;font-size:1.2rem;"><i class="ri-close-line"></i></button></div>
        <form method="POST" action="{{ route('admin.validasi.reject', $event->id_event) }}">
            @csrf
            <div class="modal-body"><div class="form-group mb-0"><label class="form-label">Alasan Penolakan <span class="required">*</span></label><textarea name="catatan" class="form-control" rows="3" required></textarea></div></div>
            <div class="modal-footer"><button type="button" onclick="document.getElementById('rejectModal').style.display='none'" class="btn btn-outline">Batal</button><button type="submit" class="btn btn-danger"><i class="ri-close-line"></i> Tolak</button></div>
        </form>
    </div>
</div>
@endsection
