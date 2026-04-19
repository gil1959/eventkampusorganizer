@extends('layouts.panitia')
@section('title', 'Monitoring Pembayaran')
@section('page_title', 'Monitoring Pembayaran')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Monitoring Pembayaran</h1>
        <p class="page-subtitle">Pantau dan konfirmasi pembayaran dari semua event Anda</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    @foreach([
        ['Total', $stats['total'], 'ri-receipt-line', 'navy'],
        ['Menunggu', $stats['menunggu'], 'ri-time-line', 'warning'],
        ['Dikonfirmasi', $stats['dikonfirmasi'], 'ri-checkbox-circle-line', 'success'],
        ['Ditolak', $stats['ditolak'], 'ri-close-circle-line', 'danger'],
    ] as $i => $s)
    <div class="stat-card" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
        <div class="stat-icon stat-icon-{{ $s[3] }}"><i class="{{ $s[2] }}"></i></div>
        <div class="stat-value">{{ $s[1] }}</div>
        <div class="stat-label">{{ $s[0] }}</div>
    </div>
    @endforeach
</div>

{{-- Kelola Rekening per Event --}}
<div class="card" style="margin-bottom:1.5rem;" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">
            <i class="ri-bank-card-line" style="color:var(--orange);"></i>
            Kelola Rekening per Event
        </div>
        <span style="font-size:0.78rem;color:var(--gray-400);">Pilih event untuk menambah/mengelola rekening/e-wallet pembayaran</span>
    </div>
    <div class="card-body" style="padding:0.75rem 1rem;">
        @if($events->isEmpty())
            <div style="padding:1.5rem;text-align:center;color:var(--gray-400);font-size:0.85rem;">
                Belum ada event berbayar yang aktif.
            </div>
        @else
            <div style="display:grid;gap:0.6rem;">
                @foreach($events as $ev)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1rem;
                            border:1px solid var(--gray-100);border-radius:var(--radius-md);background:var(--gray-50);">
                    <div>
                        <div style="font-weight:600;font-size:0.875rem;">{{ $ev->nama_event }}</div>
                        <div style="font-size:0.7rem;color:var(--gray-400);">
                            {{ $ev->tanggal->format('d M Y') }} &bull;
                            Biaya: <strong style="color:var(--orange);">Rp {{ number_format($ev->biaya, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('panitia.rekening.index', $ev->id_event) }}"
                       class="btn btn-navy btn-sm">
                        <i class="ri-bank-card-line"></i> Kelola Rekening
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Tabel Pembayaran --}}
<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">Semua Transaksi</div>
        @if($stats['menunggu'] > 0)
            <span class="badge badge-warning">{{ $stats['menunggu'] }} menunggu konfirmasi</span>
        @endif
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Event</th>
                        <th>Metode</th>
                        <th>Rekening Tujuan</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $p)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;">
                                {{ $p->pendaftaran->peserta->nama ?? '-' }}
                            </div>
                            <div style="font-size:0.72rem;color:var(--gray-400);">
                                {{ $p->pendaftaran->peserta->email ?? '' }}
                            </div>
                        </td>
                        <td style="font-size:0.8rem;">
                            {{ Str::limit($p->pendaftaran->event->nama_event ?? '-', 30) }}
                        </td>
                        <td>
                            <span class="badge {{ $p->metode === 'transfer' ? 'badge-navy' : 'badge-info' }}">
                                <i class="{{ $p->metode === 'transfer' ? 'ri-bank-line' : 'ri-map-pin-line' }}"></i>
                                {{ $p->metode_label }}
                            </span>
                        </td>
                        <td style="font-size:0.8rem;">
                            @if($p->bank_atau_ewallet)
                                <div style="font-weight:600;">{{ $p->bank_atau_ewallet }}</div>
                                <div style="font-size:0.72rem;color:var(--gray-400);font-family:monospace;">{{ $p->nomor_tujuan }}</div>
                            @else
                                <span style="color:var(--gray-400);">-</span>
                            @endif
                        </td>
                        <td style="font-weight:700;color:var(--navy);">
                            Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($p->bukti_transfer)
                                <a href="{{ asset('storage/'.$p->bukti_transfer) }}" target="_blank"
                                   class="btn btn-outline btn-sm" title="Lihat bukti transfer">
                                    <i class="ri-image-line"></i> Lihat
                                </a>
                            @else
                                <span style="color:var(--gray-400);font-size:0.78rem;">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $p->status_badge }}">{{ $p->status_label }}</span>
                            @if($p->tanggal_bayar)
                                <div style="font-size:0.68rem;color:var(--gray-400);margin-top:0.2rem;">
                                    {{ $p->tanggal_bayar->format('d M Y') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($p->status === 'menunggu')
                                <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                                    <form method="POST" action="{{ route('panitia.pembayaran.approve', $p->id_pembayaran) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Konfirmasi pembayaran ini?')"
                                                title="Konfirmasi">
                                            <i class="ri-check-line"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" title="Tolak"
                                            onclick="openDecline({{ $p->id_pembayaran }})">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            @elseif($p->status === 'dikonfirmasi')
                                <div style="display:flex;align-items:center;gap:0.3rem;color:var(--success);font-size:0.78rem;font-weight:600;">
                                    <i class="ri-checkbox-circle-line"></i> Selesai
                                </div>
                            @else
                                <div style="display:flex;align-items:center;gap:0.3rem;color:var(--danger);font-size:0.78rem;font-weight:600;">
                                    <i class="ri-close-circle-line"></i> Ditolak
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state" style="padding:3rem;">
                                <i class="ri-receipt-line"></i>
                                <h3>Belum ada transaksi</h3>
                                <p>Pembayaran dari peserta event Anda akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembayarans->hasPages())
            <div style="padding:1rem 1.25rem;border-top:1px solid var(--gray-100);">
                {{ $pembayarans->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Tolak --}}
<div id="declineModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:var(--radius-xl);padding:2rem;width:90%;max-width:440px;box-shadow:var(--shadow-xl);">
        <h3 style="color:var(--navy);margin-bottom:1rem;"><i class="ri-close-circle-line" style="color:var(--danger);"></i> Tolak Pembayaran</h3>
        <form id="declineForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
                <textarea name="catatan" class="form-control" rows="3"
                          placeholder="Contoh: Nominal tidak sesuai, bukti tidak jelas..." required></textarea>
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeDecline()">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="ri-close-circle-line"></i> Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDecline(id) {
    const modal = document.getElementById('declineModal');
    const form  = document.getElementById('declineForm');
    form.action = `/panitia/pembayaran/${id}/decline`;
    modal.style.display = 'flex';
}
function closeDecline() {
    document.getElementById('declineModal').style.display = 'none';
}
</script>
@endpush
