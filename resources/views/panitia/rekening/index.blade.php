@extends('layouts.panitia')
@section('title', 'Kelola Rekening')
@section('page_title', 'Kelola Rekening & E-Wallet')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Rekening & E-Wallet</h1>
        <p class="page-subtitle">{{ $event->nama_event }}</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <a href="{{ route('panitia.events.show', $event->id_event) }}" class="btn btn-outline">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" data-aos="fade-down">
        <i class="ri-checkbox-circle-line"></i>
        <span>{{ session('success') }}</span>
        <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
    </div>
@endif

<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:1.5rem;" data-aos="fade-up">
    {{-- Form Tambah Rekening --}}
    <div class="card" style="align-self:start;">
        <div class="card-header">
            <div style="font-weight:700;color:var(--navy);">
                <i class="ri-add-circle-line" style="color:var(--orange);"></i>
                Tambah Rekening
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('panitia.rekening.store', $event->id_event) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tipe <span class="required">*</span></label>
                    <select name="tipe" class="form-control" id="tipeSelect" onchange="updateLabel()">
                        <option value="bank">Bank Transfer</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" id="namaBankLabel">Nama Bank <span class="required">*</span></label>
                    <input type="text" name="nama_bank" class="form-control" id="namaBankInput"
                           placeholder="Contoh: BCA, BRI, GoPay, OVO"
                           value="{{ old('nama_bank') }}" required>
                    @error('nama_bank')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" id="nomorLabel">Nomor Rekening <span class="required">*</span></label>
                    <input type="text" name="nomor_rekening" class="form-control"
                           placeholder="Contoh: 1234567890"
                           value="{{ old('nomor_rekening') }}" required>
                    @error('nomor_rekening')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Atas Nama <span class="required">*</span></label>
                    <input type="text" name="atas_nama" class="form-control"
                           placeholder="Nama pemilik rekening"
                           value="{{ old('atas_nama') }}" required>
                    @error('atas_nama')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-orange w-full">
                    <i class="ri-save-line"></i> Simpan Rekening
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Rekening --}}
    <div>
        <div class="card">
            <div class="card-header">
                <div style="font-weight:700;color:var(--navy);">
                    <i class="ri-bank-card-line" style="color:var(--navy);"></i>
                    Rekening Tersimpan ({{ $rekenings->count() }})
                </div>
                @if($rekenings->where('is_active', true)->count() > 0)
                    <span class="badge badge-success">{{ $rekenings->where('is_active', true)->count() }} aktif</span>
                @endif
            </div>
            @if($rekenings->isEmpty())
                <div class="card-body">
                    <div class="empty-state" style="padding:2rem;">
                        <i class="ri-bank-line"></i>
                        <h3>Belum ada rekening</h3>
                        <p>Tambahkan rekening/e-wallet agar peserta bisa melakukan transfer.</p>
                    </div>
                </div>
            @else
                <div style="padding:0.75rem 1rem;display:flex;flex-direction:column;gap:0.75rem;">
                    @foreach($rekenings as $r)
                    <div style="border:2px solid {{ $r->is_active ? 'var(--navy-pale)' : 'var(--gray-100)' }};border-radius:var(--radius-md);padding:1rem 1.25rem;background:{{ $r->is_active ? 'var(--navy-ghost)' : 'var(--gray-50)' }};display:flex;align-items:center;justify-content:space-between;gap:1rem;transition:all 0.3s;">
                        <div style="display:flex;align-items:center;gap:0.75rem;min-width:0;">
                            <div style="width:42px;height:42px;border-radius:var(--radius);background:{{ $r->is_active ? 'var(--navy)' : 'var(--gray-200)' }};display:flex;align-items:center;justify-content:center;color:white;font-size:1.2rem;flex-shrink:0;transition:all 0.3s;">
                                <i class="{{ $r->tipe_icon }}"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.875rem;color:{{ $r->is_active ? 'var(--navy)' : 'var(--gray-400)' }};">
                                    {{ $r->nama_bank }}
                                    <span style="font-size:0.68rem;font-weight:500;background:{{ $r->tipe === 'ewallet' ? 'var(--orange-100)' : 'var(--navy-pale)' }};color:{{ $r->tipe === 'ewallet' ? 'var(--orange)' : 'var(--navy)' }};padding:0.1rem 0.4rem;border-radius:4px;margin-left:0.3rem;">{{ $r->tipe_label }}</span>
                                </div>
                                <div style="font-family:monospace;font-size:0.82rem;color:var(--gray-600);margin-top:0.1rem;">
                                    {{ $r->nomor_rekening }}
                                </div>
                                <div style="font-size:0.72rem;color:var(--gray-400);">
                                    a/n {{ $r->atas_nama }}
                                </div>
                            </div>
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                            {{-- Toggle Aktif --}}
                            <form method="POST" action="{{ route('panitia.rekening.toggle', [$event->id_event, $r->id_rekening]) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $r->is_active ? 'btn-success' : 'btn-outline' }}"
                                        title="{{ $r->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="{{ $r->is_active ? 'ri-eye-line' : 'ri-eye-off-line' }}"></i>
                                    {{ $r->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('panitia.rekening.destroy', [$event->id_event, $r->id_rekening]) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"
                                        title="Hapus"
                                        onclick="return confirm('Hapus rekening {{ $r->nama_bank }}?')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="alert alert-navy" style="margin-top:1rem;">
            <i class="ri-information-line"></i>
            <div>
                <strong>Catatan:</strong> Hanya rekening dengan status <strong>Aktif</strong> yang akan ditampilkan kepada peserta saat proses pembayaran transfer.
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateLabel() {
    const tipe = document.getElementById('tipeSelect').value;
    const namaLabel = document.getElementById('namaBankLabel');
    const nomorLabel = document.getElementById('nomorLabel');
    const namaInput = document.getElementById('namaBankInput');

    if (tipe === 'ewallet') {
        namaLabel.childNodes[0].textContent = 'Nama E-Wallet ';
        nomorLabel.childNodes[0].textContent = 'Nomor HP / ID E-Wallet ';
        namaInput.placeholder = 'Contoh: GoPay, OVO, DANA, ShopeePay';
    } else {
        namaLabel.childNodes[0].textContent = 'Nama Bank ';
        nomorLabel.childNodes[0].textContent = 'Nomor Rekening ';
        namaInput.placeholder = 'Contoh: BCA, BRI, BNI, Mandiri';
    }
}
</script>
@endpush
