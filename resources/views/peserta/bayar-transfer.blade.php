@extends('layouts.peserta')
@section('title', 'Upload Bukti Transfer')
@section('page_title', 'Upload Bukti Pembayaran')

@section('content')
<div style="max-width:680px;margin:0 auto;">

    {{-- Event & Nominal --}}
    <div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:var(--radius-xl);padding:1.5rem 2rem;margin-bottom:1.5rem;color:white;" data-aos="fade-down">
        <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.5);margin-bottom:0.3rem;">Upload Bukti Transfer</div>
        <h2 style="font-size:1.2rem;font-weight:800;margin-bottom:0.4rem;">{{ $pendaftaran->event->nama_event }}</h2>
        <div style="font-size:0.82rem;color:rgba(255,255,255,0.6);">
            <i class="ri-calendar-line"></i> {{ $pendaftaran->event->tanggal->format('d M Y') }}
        </div>
        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,0.2);">
            <div style="font-size:0.78rem;color:rgba(255,255,255,0.6);">Total yang harus ditransfer:</div>
            <div style="font-size:1.6rem;font-weight:800;color:var(--orange);">
                Rp {{ number_format($pendaftaran->event->biaya, 0, ',', '.') }}
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" data-aos="fade-down">
            <i class="ri-error-warning-line"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Pilih Rekening Tujuan --}}
    <div class="card" data-aos="fade-up" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div style="font-weight:700;color:var(--navy);">
                <i class="ri-bank-card-line" style="color:var(--orange);"></i>
                Rekening Tujuan Transfer
            </div>
        </div>
        <div style="padding:1rem;">
            @if($rekenings->isEmpty())
                <div class="alert alert-warning">
                    <i class="ri-information-line"></i>
                    <span>Panitia belum menambahkan rekening. Hubungi panitia untuk informasi lebih lanjut.</span>
                </div>
            @else
                <div style="display:grid;gap:0.75rem;" id="rekeningList">
                    @foreach($rekenings as $r)
                    <label style="cursor:pointer;">
                        <input type="radio" name="preview_rekening" value="{{ $r->id_rekening }}"
                               onchange="selectRekening({{ $r->id_rekening }})"
                               {{ $loop->first ? 'checked' : '' }} style="display:none;">
                        <div id="card-rek-{{ $r->id_rekening }}"
                             onclick="selectRekening({{ $r->id_rekening }})"
                             style="border:2px solid {{ $loop->first ? 'var(--navy)' : 'var(--gray-200)' }};
                                    background:{{ $loop->first ? 'var(--navy-ghost)' : 'white' }};
                                    border-radius:var(--radius-md);padding:0.85rem 1rem;
                                    display:flex;align-items:center;gap:0.75rem;transition:all 0.25s;">
                            <div style="width:40px;height:40px;border-radius:var(--radius);
                                        background:{{ $loop->first ? 'var(--navy)' : 'var(--gray-100)' }};
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:1.2rem;color:{{ $loop->first ? 'white' : 'var(--gray-500)' }};
                                        flex-shrink:0;transition:all 0.25s;" id="icon-rek-{{ $r->id_rekening }}">
                                <i class="{{ $r->tipe_icon }}"></i>
                            </div>
                            <div style="flex:1;">
                                <div style="font-weight:700;font-size:0.9rem;">
                                    {{ $r->nama_bank }}
                                    <span style="font-size:0.65rem;font-weight:500;
                                                 background:{{ $r->tipe === 'ewallet' ? 'var(--orange-100)' : 'var(--navy-pale)' }};
                                                 color:{{ $r->tipe === 'ewallet' ? 'var(--orange)' : 'var(--navy)' }};
                                                 padding:0.1rem 0.4rem;border-radius:4px;margin-left:0.25rem;">
                                        {{ $r->tipe_label }}
                                    </span>
                                </div>
                                <div style="font-family:monospace;font-size:0.9rem;font-weight:700;color:var(--navy);margin-top:0.15rem;">
                                    {{ $r->nomor_rekening }}
                                </div>
                                <div style="font-size:0.72rem;color:var(--gray-500);">a/n {{ $r->atas_nama }}</div>
                            </div>
                            <div id="check-rek-{{ $r->id_rekening }}" style="color:var(--navy);font-size:1.2rem;display:{{ $loop->first ? 'block' : 'none' }};">
                                <i class="ri-checkbox-circle-fill"></i>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Form Upload Bukti --}}
    <div class="card" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header">
            <div style="font-weight:700;color:var(--navy);">
                <i class="ri-upload-cloud-line" style="color:var(--orange);"></i>
                Upload Bukti Transfer
            </div>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ route('peserta.pembayaran.upload', $pendaftaran->id_pendaftaran) }}"
                  enctype="multipart/form-data"
                  id="uploadForm">
                @csrf

                {{-- Rekening yang dipilih (hidden) --}}
                <input type="hidden" name="id_rekening" id="selectedRekening"
                       value="{{ $rekenings->first()?->id_rekening }}">

                <div class="form-group">
                    <label class="form-label">
                        <i class="ri-image-line" style="margin-right:0.3rem;"></i>
                        Foto Bukti Transfer <span class="required">*</span>
                    </label>

                    {{-- Upload Area --}}
                    <div id="uploadArea"
                         style="border:2px dashed var(--gray-300);border-radius:var(--radius-lg);padding:2rem;text-align:center;cursor:pointer;transition:all 0.3s;background:var(--gray-50);"
                         onclick="document.getElementById('buktiFile').click()"
                         ondragover="event.preventDefault();this.style.borderColor='var(--navy)';"
                         ondragleave="this.style.borderColor='var(--gray-300)';"
                         ondrop="handleDrop(event)">
                        <div id="uploadPlaceholder">
                            <i class="ri-upload-cloud-2-line" style="font-size:2.5rem;color:var(--gray-300);display:block;margin-bottom:0.5rem;"></i>
                            <div style="font-weight:600;color:var(--gray-600);">Klik atau seret foto ke sini</div>
                            <div style="font-size:0.78rem;color:var(--gray-400);margin-top:0.3rem;">JPG, JPEG, PNG — Maks. 3MB</div>
                        </div>
                        <div id="uploadPreview" style="display:none;">
                            <img id="previewImg" src="" alt="Preview" style="max-height:200px;border-radius:var(--radius);object-fit:contain;">
                            <div style="font-size:0.78rem;color:var(--success);margin-top:0.5rem;font-weight:600;">
                                <i class="ri-checkbox-circle-line"></i> Foto dipilih
                            </div>
                        </div>
                    </div>

                    <input type="file" id="buktiFile" name="bukti_transfer"
                           accept="image/jpg,image/jpeg,image/png"
                           style="display:none;" onchange="previewFile(this)">

                    @error('bukti_transfer')
                        <div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-navy" style="margin-bottom:1.5rem;">
                    <i class="ri-information-line"></i>
                    <div>
                        Pastikan foto bukti transfer menampilkan: <strong>nominal, tanggal</strong>, dan <strong>nomor rekening tujuan</strong> dengan jelas.
                    </div>
                </div>

                <button type="submit" class="btn btn-orange w-full btn-lg" id="submitBtn">
                    <i class="ri-send-plane-line"></i> Kirim Bukti Transfer
                </button>
            </form>
        </div>
    </div>

    <div style="margin-top:1rem;text-align:center;">
        <a href="{{ route('peserta.pembayaran.pilih', $pendaftaran->id_event) }}" style="color:var(--gray-400);font-size:0.82rem;">
            <i class="ri-arrow-left-line"></i> Kembali ke Pilih Metode
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Simpan id_rekening aktif
const rekeningIds = {!! $rekenings->pluck('id_rekening')->toJson() !!};

function selectRekening(id) {
    document.getElementById('selectedRekening').value = id;

    rekeningIds.forEach(rid => {
        const card  = document.getElementById('card-rek-' + rid);
        const icon  = document.getElementById('icon-rek-' + rid);
        const check = document.getElementById('check-rek-' + rid);
        if (!card) return;

        if (rid == id) {
            card.style.borderColor  = 'var(--navy)';
            card.style.background   = 'var(--navy-ghost)';
            icon.style.background   = 'var(--navy)';
            icon.style.color        = 'white';
            check.style.display     = 'block';
        } else {
            card.style.borderColor  = 'var(--gray-200)';
            card.style.background   = 'white';
            icon.style.background   = 'var(--gray-100)';
            icon.style.color        = 'var(--gray-500)';
            check.style.display     = 'none';
        }
    });
}

function previewFile(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadPlaceholder').style.display = 'none';
        document.getElementById('uploadPreview').style.display     = 'block';
        document.getElementById('uploadArea').style.borderColor    = 'var(--navy)';
        document.getElementById('uploadArea').style.background     = 'var(--navy-ghost)';
    };
    reader.readAsDataURL(file);
}

function handleDrop(event) {
    event.preventDefault();
    const file   = event.dataTransfer.files[0];
    const input  = document.getElementById('buktiFile');
    const dt     = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    previewFile(input);
    event.currentTarget.style.borderColor = 'var(--navy)';
}

document.getElementById('uploadForm').addEventListener('submit', () => {
    const btn  = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;"></i> Mengirim...';
    btn.disabled  = true;
});
</script>
<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
</style>
@endpush
