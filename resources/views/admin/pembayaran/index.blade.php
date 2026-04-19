@extends('layouts.admin')
@section('title', 'Monitor Pembayaran')
@section('page_title', 'Monitor Pembayaran')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Monitor Pembayaran</h1>
        <p class="page-subtitle">Konfirmasi dan pantau seluruh transaksi pembayaran peserta</p>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success" data-aos="fade-down">
        <i class="ri-checkbox-circle-line"></i>
        <span>{{ session('success') }}</span>
        <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
    </div>
@endif

{{-- Stats --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    @foreach([
        ['Total Transaksi', $stats['total'],        'ri-receipt-line',         'navy'],
        ['Menunggu',        $stats['menunggu'],      'ri-time-line',            'warning'],
        ['Dikonfirmasi',    $stats['dikonfirmasi'],  'ri-checkbox-circle-line', 'success'],
        ['Ditolak',         $stats['ditolak'],       'ri-close-circle-line',    'danger'],
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
        <span style="font-size:0.78rem;color:var(--gray-400);">Pilih event untuk menambah/mengelola rekening/e-wallet</span>
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
                            Panitia: {{ optional($ev->panitia)->nama ?? '-' }} &bull;
                            {{ $ev->tanggal->format('d M Y') }} &bull;
                            Biaya: <strong style="color:var(--orange);">Rp {{ number_format($ev->biaya, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('admin.pembayaran.rekening.index', $ev->id_event) }}"
                       class="btn btn-navy btn-sm">
                        <i class="ri-bank-card-line"></i> Kelola Rekening
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:1.5rem;" data-aos="fade-down">
    <div class="card-body" style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <select name="event_id" class="form-control" style="width:auto;min-width:200px;">
                <option value="">Semua Event</option>
                @foreach($events as $ev)
                    <option value="{{ $ev->id_event }}" {{ request('event_id') == $ev->id_event ? 'selected' : '' }}>
                        {{ Str::limit($ev->nama_event, 40) }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="form-control" style="width:auto;">
                <option value="">Semua Status</option>
                <option value="menunggu"     {{ request('status')==='menunggu'     ? 'selected' : '' }}>Menunggu</option>
                <option value="dikonfirmasi" {{ request('status')==='dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="ditolak"      {{ request('status')==='ditolak'      ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="metode" class="form-control" style="width:auto;">
                <option value="">Semua Metode</option>
                <option value="transfer"     {{ request('metode')==='transfer'     ? 'selected' : '' }}>Transfer</option>
                <option value="bayar_lokasi" {{ request('metode')==='bayar_lokasi' ? 'selected' : '' }}>Bayar di Lokasi</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="ri-filter-line"></i> Filter</button>
            @if(request()->hasAny(['status','metode','event_id']))
                <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline btn-sm">
                    <i class="ri-refresh-line"></i> Reset
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="card" data-aos="fade-up">
    <div class="card-header">
        <div style="font-weight:700;color:var(--navy);">Daftar Transaksi</div>
        @if($stats['menunggu'] > 0)
            <span class="badge badge-warning"><i class="ri-time-line"></i> {{ $stats['menunggu'] }} menunggu konfirmasi</span>
        @endif
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrapper" style="border:none;border-radius:0;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Event</th>
                        <th>Panitia</th>
                        <th>Metode</th>
                        <th>Rekening Tujuan</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $p)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;">{{ $p->pendaftaran->peserta->nama ?? '-' }}</div>
                            <div style="font-size:0.72rem;color:var(--gray-400);">{{ $p->pendaftaran->peserta->npm_nip ?? '' }}</div>
                        </td>
                        <td style="font-size:0.82rem;max-width:150px;">
                            {{ Str::limit(optional($p->pendaftaran->event)->nama_event ?? '-', 28) }}
                            <a href="{{ route('admin.pembayaran.rekening.index', optional($p->pendaftaran->event)->id_event) }}"
                               style="display:block;font-size:0.68rem;color:var(--navy);margin-top:0.1rem;"
                               title="Kelola rekening event ini">
                                <i class="ri-bank-card-line"></i> Rekening
                            </a>
                        </td>
                        <td style="font-size:0.82rem;color:var(--gray-500);">
                            {{ optional($p->pendaftaran->event->panitia)->nama ?? '-' }}
                        </td>
                        <td>
                            <span class="badge {{ $p->metode === 'transfer' ? 'badge-navy' : 'badge-orange' }}">
                                <i class="{{ $p->metode === 'transfer' ? 'ri-bank-line' : 'ri-map-pin-line' }}"></i>
                                {{ $p->metode_label }}
                            </span>
                        </td>
                        <td style="font-size:0.8rem;">
                            @if($p->bank_atau_ewallet)
                                <div style="font-weight:600;">{{ $p->bank_atau_ewallet }}</div>
                                <div style="font-family:monospace;font-size:0.72rem;color:var(--gray-500);">{{ $p->nomor_tujuan }}</div>
                            @else
                                <span style="color:var(--gray-400);">-</span>
                            @endif
                        </td>
                        <td style="font-weight:700;color:var(--orange);">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($p->bukti_transfer)
                                <a href="{{ asset('storage/'.$p->bukti_transfer) }}" target="_blank"
                                   class="btn btn-outline btn-sm btn-icon" title="Lihat bukti">
                                    <i class="ri-image-line"></i>
                                </a>
                            @else
                                <span style="font-size:0.75rem;color:var(--gray-400);">
                                    {{ $p->metode === 'bayar_lokasi' ? 'Bayar di Lokasi' : 'Tidak ada' }}
                                </span>
                            @endif
                        </td>
                        <td style="font-size:0.75rem;color:var(--gray-500);">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <span class="badge {{ $p->status_badge }}">{{ $p->status_label }}</span>
                            @if($p->catatan_panitia)
                                <div style="font-size:0.65rem;color:var(--gray-400);margin-top:0.2rem;"
                                     title="{{ $p->catatan_panitia }}">
                                    <i class="ri-chat-1-line"></i> Ada catatan
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($p->status === 'menunggu')
                                <div style="display:flex;gap:0.35rem;">
                                    {{-- Approve --}}
                                    <form method="POST" action="{{ route('admin.pembayaran.approve', $p->id_pembayaran) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm btn-icon"
                                                title="Konfirmasi Pembayaran"
                                                onclick="return confirm('Konfirmasi pembayaran ini?')">
                                            <i class="ri-check-line"></i>
                                        </button>
                                    </form>
                                    {{-- Decline --}}
                                    <button type="button" class="btn btn-danger btn-sm btn-icon"
                                            title="Tolak Pembayaran"
                                            onclick="openDecline({{ $p->id_pembayaran }})">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            @elseif($p->status === 'dikonfirmasi')
                                <span style="color:var(--success);font-size:0.78rem;font-weight:600;">
                                    <i class="ri-checkbox-circle-line"></i> Selesai
                                </span>
                            @else
                                <span style="color:var(--danger);font-size:0.78rem;font-weight:600;">
                                    <i class="ri-close-circle-line"></i> Ditolak
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state" style="padding:2.5rem;">
                                <i class="ri-receipt-line"></i>
                                <h3>Belum ada data pembayaran</h3>
                                <p>Pembayaran dari peserta akan muncul di sini setelah mereka mendaftar event berbayar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembayarans->hasPages())
            <div style="padding:1rem 1.25rem;border-top:1px solid var(--gray-100);">
                {{ $pembayarans->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Tolak --}}
<div id="declineModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:var(--radius-xl);padding:2rem;width:90%;max-width:440px;box-shadow:var(--shadow-xl);">
        <h3 style="color:var(--navy);margin-bottom:1rem;">
            <i class="ri-close-circle-line" style="color:var(--danger);"></i> Tolak Pembayaran
        </h3>
        <form id="declineForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
                <textarea name="catatan" class="form-control" rows="3"
                          placeholder="Contoh: Nominal tidak sesuai, bukti transfer tidak jelas..." required></textarea>
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
    form.action = `/admin/pembayaran/${id}/decline`;
    modal.style.display = 'flex';
}
function closeDecline() {
    document.getElementById('declineModal').style.display = 'none';
}
// Tutup modal kalau klik area luar
document.getElementById('declineModal').addEventListener('click', function(e) {
    if (e.target === this) closeDecline();
});
</script>
@endpush
