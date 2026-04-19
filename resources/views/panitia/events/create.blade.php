@extends('layouts.panitia')
@section('title', 'Buat Event Baru')
@section('page_title', 'Buat Event Baru')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('panitia.dashboard') }}"><i class="ri-home-4-line"></i></a>
    <span>/</span>
    <a href="{{ route('panitia.events.index') }}">Event Saya</a>
    <span>/</span>
    <span>Buat Baru</span>
</div>

<div class="page-header">
    <div>
        <h1 class="page-title">Buat Event Baru</h1>
        <p class="page-subtitle">Isi detail event yang akan diajukan untuk persetujuan admin</p>
    </div>
    <a href="{{ route('panitia.events.index') }}" class="btn btn-outline">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

<div class="card" style="max-width:800px;" data-aos="fade-up">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:0.6rem;font-weight:700;color:var(--navy);">
            <i class="ri-calendar-event-line"></i> Form Pengajuan Event
        </div>
        <div class="badge badge-warning"><i class="ri-time-line"></i> Perlu Persetujuan Admin</div>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="ri-error-warning-line"></i>
                <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
            </div>
        @endif

        <form method="POST" action="{{ route('panitia.events.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="nama_event">Nama Event <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-calendar-event-line input-icon"></i>
                        <input type="text" id="nama_event" name="nama_event" class="form-control {{ $errors->has('nama_event') ? 'is-invalid' : '' }}"
                               value="{{ old('nama_event') }}" placeholder="Nama lengkap event" required>
                    </div>
                    @error('nama_event')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="id_kategori">Kategori <span class="required">*</span></label>
                    <select id="id_kategori" name="id_kategori" class="form-control {{ $errors->has('id_kategori') ? 'is-invalid' : '' }}" required>
                        <option value="">Pilih kategori...</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal">Tanggal Event <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-calendar-line input-icon"></i>
                        <input type="date" id="tanggal" name="tanggal" class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}"
                               value="{{ old('tanggal') }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                    @error('tanggal')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jam">Jam Mulai</label>
                    <div class="input-group">
                        <i class="ri-time-line input-icon"></i>
                        <input type="time" id="jam" name="jam" class="form-control" value="{{ old('jam') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="lokasi">Lokasi</label>
                    <div class="input-group">
                        <i class="ri-map-pin-line input-icon"></i>
                        <input type="text" id="lokasi" name="lokasi" class="form-control"
                               value="{{ old('lokasi') }}" placeholder="Gedung, ruangan, atau link online">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kuota">Kuota Peserta <span class="required">*</span></label>
                    <div class="input-group">
                        <i class="ri-group-line input-icon"></i>
                        <input type="number" id="kuota" name="kuota" class="form-control {{ $errors->has('kuota') ? 'is-invalid' : '' }}"
                               value="{{ old('kuota', 100) }}" min="1" required>
                    </div>
                    @error('kuota')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="biaya">Biaya Pendaftaran (Rp)</label>
                    <div class="input-group">
                        <i class="ri-money-cny-circle-line input-icon"></i>
                        <input type="number" id="biaya" name="biaya" class="form-control"
                               value="{{ old('biaya', 0) }}" min="0" step="1000" placeholder="0 = gratis">
                    </div>
                    <div class="form-hint">Kosongkan atau isi 0 jika event gratis</div>
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="narasumber">Narasumber / Pembicara</label>
                    <div class="input-group">
                        <i class="ri-user-voice-line input-icon"></i>
                        <input type="text" id="narasumber" name="narasumber" class="form-control"
                               value="{{ old('narasumber') }}" placeholder="Nama narasumber atau pembicara">
                    </div>
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="deskripsi">Deskripsi Event <span class="required">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                              placeholder="Jelaskan detail event, agenda, dan informasi penting lainnya..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="poster">Poster Event</label>
                    <input type="file" id="poster" name="poster" class="form-control" accept="image/*" onchange="previewPoster(this)">
                    <div class="form-hint">Format JPG, PNG, WEBP. Maksimal 2MB.</div>
                    <div id="posterPreview" style="display:none;margin-top:0.75rem;">
                        <img id="previewImg" style="max-height:200px;border-radius:var(--radius-md);border:1px solid var(--gray-200);">
                    </div>
                    @error('poster')<div class="form-error"><i class="ri-error-warning-line"></i> {{ $message }}</div>@enderror
                </div>
            </div>

            <div style="background:var(--warning-bg);border:1px solid #fde68a;border-radius:var(--radius-md);padding:0.85rem 1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;color:var(--warning);">
                <i class="ri-information-line" style="font-size:1rem;flex-shrink:0;"></i>
                Event yang diajukan akan berstatus <strong>"Menunggu Persetujuan"</strong> sampai disetujui oleh Admin Kampus.
            </div>

            <div class="divider"></div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                <a href="{{ route('panitia.events.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-orange">
                    <i class="ri-send-plane-line"></i> Ajukan Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewPoster(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('posterPreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
