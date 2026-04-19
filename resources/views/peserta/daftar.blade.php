@extends('layouts.peserta')
@section('title', 'Daftar Event')
@section('page_title', 'Pendaftaran Event')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Formulir Pendaftaran</h1>
        <p class="page-subtitle">{{ $event->nama_event }}</p>
    </div>
    <a href="{{ route('peserta.event.show', $event->id_event) }}" class="btn btn-outline">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger" data-aos="fade-down">
        <i class="ri-close-circle-line"></i>
        <div>
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
        <button class="alert-close" onclick="this.closest('.alert').remove()"><i class="ri-close-line"></i></button>
    </div>
@endif

{{-- Info Event Banner --}}
<div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));
            border-radius:var(--radius-lg);padding:1.5rem;margin-bottom:1.75rem;
            display:grid;grid-template-columns:1fr auto;gap:1rem;align-items:center;" data-aos="fade-down">
    <div>
        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.1em;
                    color:rgba(255,255,255,0.5);margin-bottom:0.4rem;">Event Kampus</div>
        <div style="font-weight:800;font-size:1.15rem;color:white;margin-bottom:0.75rem;">
            {{ $event->nama_event }}
        </div>
        <div style="display:flex;gap:1.25rem;flex-wrap:wrap;">
            <span style="font-size:0.82rem;color:rgba(255,255,255,0.7);display:flex;align-items:center;gap:0.4rem;">
                <i class="ri-calendar-line"></i>
                {{ $event->tanggal->isoFormat('D MMMM Y') }}
            </span>
            <span style="font-size:0.82rem;color:rgba(255,255,255,0.7);display:flex;align-items:center;gap:0.4rem;">
                <i class="ri-map-pin-line"></i>
                {{ $event->lokasi ?? 'Lokasi belum ditentukan' }}
            </span>
            <span style="font-size:0.82rem;color:rgba(255,255,255,0.7);display:flex;align-items:center;gap:0.4rem;">
                <i class="ri-group-line"></i>
                Sisa Kuota: {{ $event->kuota_sisa }}
            </span>
        </div>
    </div>
    <div style="text-align:right;">
        @if($event->biaya > 0)
            <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);text-transform:uppercase;">Biaya</div>
            <div style="font-size:1.5rem;font-weight:900;color:var(--orange);">
                Rp {{ number_format($event->biaya, 0, ',', '.') }}
            </div>
        @else
            <div style="background:rgba(255,255,255,0.1);border-radius:var(--radius);
                        padding:0.5rem 1rem;color:white;font-weight:700;font-size:0.9rem;">
                <i class="ri-gift-line" style="color:var(--orange);"></i> Gratis
            </div>
        @endif
    </div>
</div>

<form method="POST" action="{{ route('peserta.daftar', $event->id_event) }}"
      enctype="multipart/form-data" id="formDaftar">
    @csrf

    @if($event->biaya <= 0)
        {{-- ===== EVENT GRATIS ===== --}}
        <div class="card" data-aos="fade-up">
            <div class="card-header">
                <div style="font-weight:700;color:var(--navy);">
                    <i class="ri-check-circle-line" style="color:var(--orange);"></i>
                    Konfirmasi Pendaftaran
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-success" style="margin-bottom:1.25rem;">
                    <i class="ri-gift-line"></i>
                    <div>
                        Event ini <strong>gratis</strong>. Pendaftaran akan langsung dikonfirmasi setelah submit.
                    </div>
                </div>
                <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:1.25rem;margin-bottom:1.5rem;">
                    <div style="font-size:0.8rem;color:var(--gray-500);margin-bottom:0.5rem;">Anda akan mendaftar sebagai:</div>
                    <div style="font-weight:700;font-size:1rem;">{{ Auth::guard('aktor')->user()->nama }}</div>
                    <div style="font-size:0.82rem;color:var(--gray-400);">{{ Auth::guard('aktor')->user()->email }}</div>
                </div>
                <button type="submit" class="btn btn-orange w-full" style="font-size:1rem;padding:0.85rem;">
                    <i class="ri-login-circle-line"></i>
                    Daftar Sekarang (Gratis)
                </button>
            </div>
        </div>

    @else
        {{-- ===== EVENT BERBAYAR ===== --}}

        {{-- Langkah 1: Pilih Metode --}}
        <div class="card" style="margin-bottom:1.25rem;" data-aos="fade-up">
            <div class="card-header">
                <div style="font-weight:700;color:var(--navy);">
                    <i class="ri-bank-card-line" style="color:var(--orange);"></i>
                    Pilih Metode Pembayaran
                </div>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" id="methodeGrid">
                    {{-- Transfer --}}
                    <label for="metodeTransfer"
                           style="border:2px solid var(--gray-200);border-radius:var(--radius-md);
                                  padding:1.25rem;cursor:pointer;transition:all 0.2s;display:block;"
                           id="labelTransfer"
                           onmouseover="this.style.borderColor='var(--navy)'"
                           onmouseout="checkHover('transfer',this)">
                        <input type="radio" name="metode" value="transfer" id="metodeTransfer"
                               onchange="switchMetode('transfer')" style="display:none;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:44px;height:44px;border-radius:var(--radius);
                                        background:var(--navy-pale);display:flex;align-items:center;
                                        justify-content:center;color:var(--navy);font-size:1.3rem;">
                                <i class="ri-bank-transfer-line"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;">Transfer Bank / E-Wallet</div>
                                <div style="font-size:0.75rem;color:var(--gray-400);">Upload bukti transfer</div>
                            </div>
                        </div>
                    </label>

                    {{-- Bayar di Lokasi --}}
                    <label for="metodeLokasi"
                           style="border:2px solid var(--gray-200);border-radius:var(--radius-md);
                                  padding:1.25rem;cursor:pointer;transition:all 0.2s;display:block;"
                           id="labelLokasi"
                           onmouseover="this.style.borderColor='var(--navy)'"
                           onmouseout="checkHover('lokasi',this)">
                        <input type="radio" name="metode" value="bayar_lokasi" id="metodeLokasi"
                               onchange="switchMetode('lokasi')" style="display:none;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:44px;height:44px;border-radius:var(--radius);
                                        background:rgba(255,92,0,0.1);display:flex;align-items:center;
                                        justify-content:center;color:var(--orange);font-size:1.3rem;">
                                <i class="ri-store-line"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;">Bayar di Lokasi</div>
                                <div style="font-size:0.75rem;color:var(--gray-400);">Bayar saat event berlangsung</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Panel Transfer --}}
        <div id="panelTransfer" style="display:none;" data-aos="fade-up">

            {{-- Pilih Rekening Tujuan --}}
            @if($rekenings->isNotEmpty())
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-header">
                    <div style="font-weight:700;color:var(--navy);">
                        <i class="ri-bank-line" style="color:var(--orange);"></i>
                        Rekening / E-Wallet Tujuan
                    </div>
                </div>
                <div class="card-body">
                    <p style="font-size:0.85rem;color:var(--gray-500);margin-bottom:1rem;">
                        Pilih salah satu rekening di bawah ini untuk melakukan transfer:
                    </p>
                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        @foreach($rekenings as $rek)
                        <label for="rek{{ $rek->id_rekening }}"
                               style="border:2px solid var(--gray-150);border-radius:var(--radius-md);
                                      padding:1rem 1.25rem;cursor:pointer;transition:all 0.2s;display:flex;
                                      align-items:center;gap:1rem;"
                               class="rekening-label">
                            <input type="radio" name="id_rekening" value="{{ $rek->id_rekening }}"
                                   id="rek{{ $rek->id_rekening }}"
                                   onchange="highlightRekening(this)"
                                   style="accent-color:var(--navy);width:18px;height:18px;">
                            <div style="width:42px;height:42px;border-radius:var(--radius);
                                        background:var(--navy);display:flex;align-items:center;
                                        justify-content:center;color:white;font-size:1.2rem;flex-shrink:0;">
                                <i class="{{ $rek->tipe_icon }}"></i>
                            </div>
                            <div style="flex:1;">
                                <div style="font-weight:700;font-size:0.9rem;">
                                    {{ $rek->nama_bank }}
                                    <span style="font-size:0.65rem;background:var(--navy-pale);
                                                 color:var(--navy);padding:0.1rem 0.45rem;
                                                 border-radius:4px;margin-left:0.25rem;font-weight:600;">
                                        {{ $rek->tipe_label }}
                                    </span>
                                </div>
                                <div style="font-family:monospace;font-size:1rem;font-weight:800;
                                            color:var(--navy);margin:0.2rem 0;">
                                    {{ $rek->nomor_rekening }}
                                </div>
                                <div style="font-size:0.75rem;color:var(--gray-400);">a/n {{ $rek->atas_nama }}</div>
                            </div>
                            <div style="font-weight:800;font-size:1.1rem;color:var(--orange);white-space:nowrap;">
                                Rp {{ number_format($event->biaya, 0, ',', '.') }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-navy" style="margin-bottom:1.25rem;">
                <i class="ri-information-line"></i>
                <div>Belum ada rekening tujuan yang dikonfigurasi panitia. Silakan hubungi panitia event.</div>
            </div>
            @endif

            {{-- Upload Bukti Transfer --}}
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-header">
                    <div style="font-weight:700;color:var(--navy);">
                        <i class="ri-upload-cloud-line" style="color:var(--orange);"></i>
                        Upload Bukti Transfer
                    </div>
                    <span style="font-size:0.75rem;color:var(--gray-400);">Format: JPG, PNG — Maks. 3MB</span>
                </div>
                <div class="card-body">
                    {{-- Drop Zone --}}
                    <div id="dropZone" style="border:2px dashed var(--gray-200);border-radius:var(--radius-md);
                                              padding:2.5rem;text-align:center;cursor:pointer;
                                              transition:all 0.3s;background:var(--gray-50);"
                         onclick="document.getElementById('buktiFoto').click()"
                         ondragover="this.style.borderColor='var(--orange)';this.style.background='rgba(255,92,0,0.04)';event.preventDefault()"
                         ondragleave="this.style.borderColor='var(--gray-200)';this.style.background='var(--gray-50)'"
                         ondrop="handleDrop(event)">
                        <div id="dropContent">
                            <i class="ri-image-add-line" style="font-size:2.5rem;color:var(--gray-300);"></i>
                            <div style="font-weight:600;color:var(--gray-500);margin:0.5rem 0 0.25rem;">
                                Klik atau seret foto bukti transfer ke sini
                            </div>
                            <div style="font-size:0.75rem;color:var(--gray-400);">JPG, PNG hingga 3MB</div>
                        </div>
                        <div id="previewWrap" style="display:none;">
                            <img id="previewImg" src="" alt="Preview"
                                 style="max-height:200px;border-radius:var(--radius);object-fit:contain;">
                            <div style="font-size:0.78rem;color:var(--success);margin-top:0.5rem;font-weight:600;">
                                <i class="ri-checkbox-circle-line"></i> Foto terpilih
                            </div>
                        </div>
                    </div>
                    <input type="file" id="buktiFoto" name="bukti_transfer"
                           accept="image/jpeg,image/png" style="display:none;"
                           onchange="previewFoto(this)">
                    <button type="button" onclick="document.getElementById('buktiFoto').click()"
                            class="btn btn-outline btn-sm" style="margin-top:0.75rem;">
                        <i class="ri-camera-line"></i> Pilih Foto
                    </button>
                </div>
            </div>
        </div>

        {{-- Panel Bayar di Lokasi --}}
        <div id="panelLokasi" style="display:none;" data-aos="fade-up">
            <div class="card" style="margin-bottom:1.25rem;">
                <div class="card-body">
                    <div class="alert alert-navy">
                        <i class="ri-store-line"></i>
                        <div>
                            <strong>Bayar di Lokasi</strong><br>
                            Pendaftaran Anda akan dicatat sebagai <strong>menunggu konfirmasi</strong>.
                            Siapkan uang tunai sebesar
                            <strong>Rp {{ number_format($event->biaya, 0, ',', '.') }}</strong>
                            saat event berlangsung.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div id="submitArea" style="display:none;" data-aos="fade-up">
            <div class="card">
                <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
                    <div style="font-size:0.82rem;color:var(--gray-500);">
                        <i class="ri-information-line" style="color:var(--navy);"></i>
                        Pendaftaran akan berstatus <strong>Menunggu Konfirmasi</strong> hingga disetujui panitia.
                    </div>
                    <button type="submit" id="btnSubmit" class="btn btn-orange"
                            style="font-size:1rem;padding:0.85rem 2rem;min-width:200px;">
                        <i class="ri-send-plane-line"></i>
                        <span id="btnText">Kirim Pendaftaran</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</form>
@endsection

@push('scripts')
<script>
let selectedMetode = null;

function switchMetode(metode) {
    selectedMetode = metode;

    // Reset border labels
    document.getElementById('labelTransfer').style.borderColor = 'var(--gray-200)';
    document.getElementById('labelLokasi').style.borderColor   = 'var(--gray-200)';

    if (metode === 'transfer') {
        document.getElementById('labelTransfer').style.borderColor = 'var(--navy)';
        document.getElementById('labelTransfer').style.background  = 'var(--navy-ghost)';
        document.getElementById('labelLokasi').style.background    = 'white';
        document.getElementById('panelTransfer').style.display     = 'block';
        document.getElementById('panelLokasi').style.display       = 'none';
        document.getElementById('btnText').textContent             = 'Kirim Pendaftaran + Bukti Transfer';
    } else {
        document.getElementById('labelLokasi').style.borderColor   = 'var(--orange)';
        document.getElementById('labelLokasi').style.background    = 'rgba(255,92,0,0.04)';
        document.getElementById('labelTransfer').style.background  = 'white';
        document.getElementById('panelTransfer').style.display     = 'none';
        document.getElementById('panelLokasi').style.display       = 'block';
        document.getElementById('btnText').textContent             = 'Kirim Pendaftaran';
    }
    document.getElementById('submitArea').style.display = 'block';
}

function checkHover(metode, el) {
    const active = selectedMetode === metode;
    el.style.borderColor = active ? (metode === 'transfer' ? 'var(--navy)' : 'var(--orange)') : 'var(--gray-200)';
}

function highlightRekening(radio) {
    document.querySelectorAll('.rekening-label').forEach(l => {
        l.style.borderColor = 'var(--gray-150)';
        l.style.background  = 'white';
    });
    radio.closest('label').style.borderColor = 'var(--navy)';
    radio.closest('label').style.background  = 'var(--navy-ghost)';
}

function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('dropContent').style.display = 'none';
            document.getElementById('previewWrap').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function handleDrop(e) {
    e.preventDefault();
    const dt   = e.dataTransfer;
    const file = dt.files[0];
    if (file && file.type.startsWith('image/')) {
        const inputEl = document.getElementById('buktiFoto');
        const dTrans  = new DataTransfer();
        dTrans.items.add(file);
        inputEl.files = dTrans.files;
        previewFoto(inputEl);
    }
    e.currentTarget.style.borderColor = 'var(--gray-200)';
    e.currentTarget.style.background  = 'var(--gray-50)';
}

// Validasi sebelum submit
document.getElementById('formDaftar')?.addEventListener('submit', function(e) {
    @if($event->biaya > 0)
    const metode = document.querySelector('input[name="metode"]:checked')?.value;
    if (!metode) {
        e.preventDefault();
        alert('Pilih metode pembayaran terlebih dahulu.');
        return;
    }
    if (metode === 'transfer') {
        const bukti = document.getElementById('buktiFoto').files.length;
        if (!bukti) {
            e.preventDefault();
            alert('Upload bukti transfer terlebih dahulu.');
            return;
        }
    }
    const btn = document.getElementById('btnSubmit');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line"></i> Memproses...';
    }
    @endif
});
</script>
@endpush
