@extends('layouts.admin')
@section('title', 'Validasi Event')
@section('page_title', 'Validasi Pengajuan Event')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}"><i class="ri-home-4-line"></i></a>
    <span>/</span>
    <span>Validasi Event</span>
</div>

<div class="page-header">
    <div>
        <h1 class="page-title">Validasi Pengajuan Event</h1>
        <p class="page-subtitle">Tinjau dan setujui atau tolak pengajuan event dari panitia</p>
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:1.5rem;" data-aos="fade-down">
    <div class="card-body" style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <span style="font-size:0.85rem;font-weight:600;color:var(--gray-600);">Filter Status:</span>
            @foreach([''=>'Semua', 'menunggu'=>'Menunggu', 'aktif'=>'Aktif', 'ditolak'=>'Ditolak', 'selesai'=>'Selesai'] as $val => $lbl)
            <a href="{{ route('admin.validasi.index', $val ? ['status' => $val] : []) }}"
               class="btn btn-sm {{ request('status') === $val || (!request('status') && $val === '') ? 'btn-primary' : 'btn-outline' }}">
                {{ $lbl }}
                @if($val === 'menunggu')
                    @php $cnt = \App\Models\Event::where('status','menunggu')->count(); @endphp
                    @if($cnt > 0)<span class="nav-badge" style="background:var(--orange);margin-left:0.3rem;">{{ $cnt }}</span>@endif
                @endif
            </a>
            @endforeach
        </form>
    </div>
</div>

{{-- Event List --}}
<div style="display:flex;flex-direction:column;gap:1rem;">
    @forelse($events as $i => $e)
    <div class="card" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
        <div class="card-body" style="padding:1.25rem;">
            <div style="display:flex;align-items:start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div style="display:flex;gap:1rem;min-width:0;flex:1;">
                    {{-- Poster --}}
                    <div style="width:80px;height:80px;border-radius:var(--radius-md);overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);font-size:1.75rem;">
                        @if($e->poster)
                            <img src="{{ asset('storage/'.$e->poster) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="ri-calendar-event-line"></i>
                        @endif
                    </div>
                    <div style="min-width:0;">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;">
                            <h3 style="font-size:1rem;font-weight:700;color:var(--navy);">{{ $e->nama_event }}</h3>
                            <span class="badge {{ $e->status_badge }}">{{ $e->status_label }}</span>
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:1rem;font-size:0.78rem;color:var(--gray-500);margin-bottom:0.4rem;">
                            <span><i class="ri-user-line" style="color:var(--navy);margin-right:0.25rem;"></i>{{ $e->panitia->nama }}</span>
                            <span><i class="ri-price-tag-3-line" style="color:var(--navy);margin-right:0.25rem;"></i>{{ $e->kategori->nama_kategori }}</span>
                            <span><i class="ri-calendar-line" style="color:var(--navy);margin-right:0.25rem;"></i>{{ $e->tanggal->format('d M Y') }}</span>
                            @if($e->lokasi)<span><i class="ri-map-pin-line" style="color:var(--orange);margin-right:0.25rem;"></i>{{ $e->lokasi }}</span>@endif
                            <span><i class="ri-group-line" style="color:var(--navy);margin-right:0.25rem;"></i>Kuota {{ $e->kuota }}</span>
                        </div>
                        <p style="font-size:0.8rem;color:var(--gray-500);">{{ Str::limit($e->deskripsi, 120) }}</p>
                        @if($e->catatan_admin)
                            <div style="background:var(--warning-bg);border:1px solid #fde68a;border-radius:var(--radius);padding:0.5rem 0.75rem;font-size:0.78rem;color:var(--warning);margin-top:0.5rem;">
                                <i class="ri-information-line"></i> Catatan Admin: {{ $e->catatan_admin }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;flex-direction:column;gap:0.5rem;flex-shrink:0;">
                    <a href="{{ route('admin.validasi.show', $e->id_event) }}" class="btn btn-outline btn-sm">
                        <i class="ri-eye-line"></i> Detail
                    </a>
                    @if($e->status === 'menunggu')
                    <button onclick="openApprove({{ $e->id_event }}, '{{ addslashes($e->nama_event) }}')" class="btn btn-success btn-sm">
                        <i class="ri-check-line"></i> Setujui
                    </button>
                    <button onclick="openReject({{ $e->id_event }}, '{{ addslashes($e->nama_event) }}')" class="btn btn-danger btn-sm">
                        <i class="ri-close-line"></i> Tolak
                    </button>
                    @endif
                    {{-- Hapus Event (admin bisa hapus event apapun) --}}
                    <form method="POST" action="{{ route('admin.events.destroy', $e->id_event) }}"
                          onsubmit="return confirm('HAPUS EVENT {{ addslashes(strtoupper($e->nama_event)) }}?\n\nSemua data peserta, pembayaran, dan sertifikat terkait akan ikut terhapus secara permanen!')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">
                            <i class="ri-delete-bin-line"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body">
            <div class="empty-state" style="padding:4rem;">
                <i class="ri-calendar-event-line"></i>
                <h3>Tidak ada event</h3>
                <p>Tidak ada event dengan status yang dipilih</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($events->hasPages())
<div class="pagination-wrapper">{{ $events->withQueryString()->links() }}</div>
@endif

{{-- Approve Modal --}}
<div class="modal-backdrop" id="approveModal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3>Setujui Event</h3>
            <button onclick="closeModal('approveModal')" style="background:none;border:none;color:white;cursor:pointer;font-size:1.2rem;"><i class="ri-close-line"></i></button>
        </div>
        <form method="POST" id="approveForm">
            @csrf
            <div class="modal-body">
                <p style="color:var(--gray-600);margin-bottom:1rem;">Anda akan menyetujui event: <strong id="approveEventName"></strong></p>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan atau info tambahan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('approveModal')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-success"><i class="ri-check-line"></i> Setujui Event</button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal-backdrop" id="rejectModal" style="display:none;">
    <div class="modal">
        <div class="modal-header" style="background:linear-gradient(135deg,var(--danger),#b91c1c);">
            <h3>Tolak Event</h3>
            <button onclick="closeModal('rejectModal')" style="background:none;border:none;color:white;cursor:pointer;font-size:1.2rem;"><i class="ri-close-line"></i></button>
        </div>
        <form method="POST" id="rejectForm">
            @csrf
            <div class="modal-body">
                <p style="color:var(--gray-600);margin-bottom:1rem;">Tolak event: <strong id="rejectEventName"></strong></p>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan event ini..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('rejectModal')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="ri-close-line"></i> Tolak Event</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApprove(id, name) {
        document.getElementById('approveEventName').textContent = name;
        document.getElementById('approveForm').action = '/admin/validasi/' + id + '/approve';
        document.getElementById('approveModal').style.display = 'flex';
    }
    function openReject(id, name) {
        document.getElementById('rejectEventName').textContent = name;
        document.getElementById('rejectForm').action = '/admin/validasi/' + id + '/reject';
        document.getElementById('rejectModal').style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
@endsection
