@extends('layouts.peserta')
@section('title', 'Pilih Metode Pembayaran')
@section('page_title', 'Pembayaran')

@section('content')
<div style="max-width:680px;margin:0 auto;">
    {{-- Header Event --}}
    <div style="background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:var(--radius-xl);padding:1.5rem 2rem;margin-bottom:1.5rem;color:white;" data-aos="fade-down">
        <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.5);margin-bottom:0.3rem;">Pembayaran Event</div>
        <h2 style="font-size:1.25rem;font-weight:800;margin-bottom:0.5rem;">{{ $event->nama_event }}</h2>
        <div style="display:flex;gap:1rem;font-size:0.82rem;color:rgba(255,255,255,0.7);">
            <span><i class="ri-calendar-line"></i> {{ $event->tanggal->format('d M Y') }}</span>
            <span><i class="ri-map-pin-line"></i> {{ $event->lokasi ?? '-' }}</span>
        </div>
        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.85rem;color:rgba(255,255,255,0.7);">Total Biaya Pendaftaran</span>
            <span style="font-size:1.5rem;font-weight:800;color:var(--orange);">Rp {{ number_format($event->biaya, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($pendaftaran->pembayaran && $pendaftaran->pembayaran->status === 'menunggu')
        <div class="alert alert-warning" data-aos="fade-down">
            <i class="ri-time-line"></i>
            <div>
                <strong>Pembayaran Menunggu Konfirmasi</strong><br>
                Bukti transfer Anda sudah diterima. Silakan tunggu konfirmasi dari panitia.
                <a href="{{ route('peserta.riwayat') }}" class="btn btn-outline btn-sm" style="margin-top:0.5rem;">
                    <i class="ri-history-line"></i> Lihat Riwayat
                </a>
            </div>
        </div>
    @elseif($pendaftaran->pembayaran && $pendaftaran->pembayaran->status === 'ditolak')
        <div class="alert alert-danger" data-aos="fade-down">
            <i class="ri-close-circle-line"></i>
            <div>
                <strong>Pembayaran Ditolak</strong><br>
                @if($pendaftaran->pembayaran->catatan_panitia)
                    Alasan: {{ $pendaftaran->pembayaran->catatan_panitia }}<br>
                @endif
                Silakan upload ulang bukti transfer yang valid.
            </div>
        </div>
    @endif

    {{-- Pilih Metode --}}
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--navy);margin-bottom:1rem;" data-aos="fade-up">
        <i class="ri-bank-card-line" style="color:var(--orange);"></i>
        Pilih Metode Pembayaran
    </h2>

    <div style="display:grid;gap:1rem;" data-aos="fade-up" data-aos-delay="100">
        {{-- Transfer --}}
        @if($rekenings->isNotEmpty())
        <label for="opt-transfer" style="cursor:pointer;">
            <input type="radio" id="opt-transfer" name="metode" value="transfer" class="sr-only" style="display:none;">
            <div class="metode-card" onclick="selectMetode('transfer')" id="card-transfer"
                 style="border:2px solid var(--gray-200);border-radius:var(--radius-lg);padding:1.5rem;transition:all 0.3s;background:white;">
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div style="width:50px;height:50px;border-radius:var(--radius);background:var(--navy-pale);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--navy);">
                        <i class="ri-bank-line"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:1rem;color:var(--navy);">Transfer Bank / E-Wallet</div>
                        <div style="font-size:0.8rem;color:var(--gray-500);">{{ $rekenings->count() }} rekening tersedia</div>
                    </div>
                    <div style="width:22px;height:22px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;" id="radio-transfer">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--navy);display:none;" id="dot-transfer"></div>
                    </div>
                </div>
                {{-- Preview rekening --}}
                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--gray-100);display:grid;gap:0.5rem;">
                    @foreach($rekenings->take(3) as $r)
                    <div style="display:flex;align-items:center;gap:0.6rem;font-size:0.8rem;color:var(--gray-600);">
                        <i class="{{ $r->tipe_icon }}" style="color:var(--navy);"></i>
                        <strong>{{ $r->nama_bank }}</strong>
                        <span style="font-family:monospace;">{{ $r->nomor_rekening }}</span>
                        <span style="color:var(--gray-400);">a/n {{ $r->atas_nama }}</span>
                    </div>
                    @endforeach
                    @if($rekenings->count() > 3)
                        <div style="font-size:0.75rem;color:var(--gray-400);">+{{ $rekenings->count() - 3 }} rekening lainnya</div>
                    @endif
                </div>
                <a href="{{ route('peserta.pembayaran.transfer', $pendaftaran->id_pendaftaran) }}"
                   id="btn-transfer"
                   class="btn btn-navy w-full" style="margin-top:1rem;display:none;">
                    <i class="ri-arrow-right-line"></i> Lanjut Transfer
                </a>
            </div>
        </label>
        @endif

        {{-- Bayar di Lokasi --}}
        <label for="opt-lokasi" style="cursor:pointer;">
            <input type="radio" id="opt-lokasi" name="metode" value="lokasi" class="sr-only" style="display:none;">
            <div class="metode-card" onclick="selectMetode('lokasi')" id="card-lokasi"
                 style="border:2px solid var(--gray-200);border-radius:var(--radius-lg);padding:1.5rem;transition:all 0.3s;background:white;">
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div style="width:50px;height:50px;border-radius:var(--radius);background:var(--orange-100);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--orange);">
                        <i class="ri-map-pin-line"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:1rem;color:var(--navy);">Bayar di Lokasi</div>
                        <div style="font-size:0.8rem;color:var(--gray-500);">Bayar langsung ke panitia saat event berlangsung</div>
                    </div>
                    <div style="width:22px;height:22px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;" id="radio-lokasi">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--orange);display:none;" id="dot-lokasi"></div>
                    </div>
                </div>
                <form method="POST" action="{{ route('peserta.pembayaran.bayar-lokasi', $event->id_event) }}"
                      id="form-lokasi" style="display:none;margin-top:1rem;">
                    @csrf
                    <div class="alert alert-warning" style="margin-bottom:1rem;">
                        <i class="ri-information-line"></i>
                        <span>Pastikan Anda hadir dan membawa uang tunai sebesar <strong>Rp {{ number_format($event->biaya, 0, ',', '.') }}</strong></span>
                    </div>
                    <button type="submit" class="btn btn-orange w-full">
                        <i class="ri-check-double-line"></i> Konfirmasi Bayar di Lokasi
                    </button>
                </form>
            </div>
        </label>
    </div>

    <div style="margin-top:1rem;text-align:center;">
        <a href="{{ route('peserta.riwayat') }}" style="color:var(--gray-400);font-size:0.82rem;">
            <i class="ri-arrow-left-line"></i> Kembali ke Riwayat
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectMetode(metode) {
    // Reset semua card
    ['transfer', 'lokasi'].forEach(m => {
        const card = document.getElementById('card-' + m);
        const dot  = document.getElementById('dot-' + m);
        if (card) {
            card.style.borderColor = 'var(--gray-200)';
            card.style.background  = 'white';
        }
        if (dot) dot.style.display = 'none';
    });

    // Sembunyikan tombol aksi
    const btnTransfer = document.getElementById('btn-transfer');
    const formLokasi  = document.getElementById('form-lokasi');
    if (btnTransfer) btnTransfer.style.display = 'none';
    if (formLokasi)  formLokasi.style.display  = 'none';

    // Aktifkan yang dipilih
    const card = document.getElementById('card-' + metode);
    const dot  = document.getElementById('dot-' + metode);

    if (metode === 'transfer') {
        card.style.borderColor = 'var(--navy)';
        card.style.background  = 'var(--navy-ghost)';
        if (dot) dot.style.display = 'block';
        if (btnTransfer) btnTransfer.style.display = 'flex';
    } else {
        card.style.borderColor = 'var(--orange)';
        card.style.background  = 'var(--orange-100)';
        if (dot) dot.style.display = 'block';
        if (formLokasi) formLokasi.style.display = 'block';
    }
}
</script>
@endpush
