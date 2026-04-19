@extends('layouts.panitia')
@section('title', 'Event Saya')
@section('page_title', 'Event Saya')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Event Saya</h1>
        <p class="page-subtitle">Kelola semua event yang Anda buat</p>
    </div>
    <a href="{{ route('panitia.events.create') }}" class="btn btn-orange">
        <i class="ri-add-line"></i> Buat Event Baru
    </a>
</div>

<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">Daftar Event ({{ $events->total() }})</div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Peserta</th>
                        <th>Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $e)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <div style="width:40px;height:40px;border-radius:var(--radius);overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);">
                                    @if($e->poster)
                                        <img src="{{ asset('storage/'.$e->poster) }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <i class="ri-calendar-event-line"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.875rem;">{{ Str::limit($e->nama_event, 35) }}</div>
                                    @if($e->lokasi)<div style="font-size:0.72rem;color:var(--gray-400);"><i class="ri-map-pin-line"></i> {{ $e->lokasi }}</div>@endif
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-navy">{{ $e->kategori->nama_kategori ?? '-' }}</span></td>
                        <td style="font-size:0.8rem;white-space:nowrap;">{{ $e->tanggal->format('d M Y') }}</td>
                        <td style="font-size:0.8rem;">{{ $e->pesertaTerdaftar()->count() }}/{{ $e->kuota }}</td>
                        <td style="font-size:0.8rem;color:var(--orange);font-weight:600;">
                            {{ $e->biaya > 0 ? 'Rp '.number_format($e->biaya,0,',','.') : 'Gratis' }}
                        </td>
                        <td><span class="badge {{ $e->status_badge }}">{{ $e->status_label }}</span></td>
                        <td>
                            <div style="display:flex;gap:0.4rem;">
                                <a href="{{ route('panitia.events.show', $e->id_event) }}" class="btn btn-outline btn-sm btn-icon" title="Detail">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('panitia.peserta.index', $e->id_event) }}" class="btn btn-primary btn-sm btn-icon" title="Peserta">
                                    <i class="ri-group-line"></i>
                                </a>
                                <a href="{{ route('panitia.sertifikat.index', $e->id_event) }}" class="btn btn-outline btn-sm btn-icon" title="Sertifikat" style="color:var(--orange);border-color:var(--orange);">
                                    <i class="ri-award-line"></i>
                                </a>
                                @if($e->biaya > 0)
                                <a href="{{ route('panitia.rekening.index', $e->id_event) }}" class="btn btn-outline btn-sm btn-icon" title="Kelola Rekening Pembayaran" style="color:#7c3aed;border-color:#7c3aed;">
                                    <i class="ri-bank-card-line"></i>
                                </a>
                                @endif
                                @if(in_array($e->status, ['draft', 'ditolak']))
                                <a href="{{ route('panitia.events.edit', $e->id_event) }}" class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                @endif
                                <form method="POST" action="{{ route('panitia.events.destroy', $e->id_event) }}"
                                      onsubmit="return confirm('Yakin hapus event &quot;{{ addslashes($e->nama_event) }}&quot;? Semua data peserta, pembayaran, dan sertifikat akan ikut terhapus!')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus Event">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state" style="padding:3rem;">
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
        @if($events->hasPages())
        <div style="padding:1rem 1.25rem;border-top:1px solid var(--gray-100);">{{ $events->links() }}</div>
        @endif
    </div>
</div>
@endsection
