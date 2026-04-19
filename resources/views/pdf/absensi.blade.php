<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir - {{ $event->nama_event }}</title>
    <style>
        @page { margin: 15mm 20mm; size: A4 portrait; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; }

        /* ===== HEADER ===== */
        table.header-tbl { width: 100%; border-collapse: collapse; margin-bottom: 12px; border-bottom: 3px solid #000080; padding-bottom: 10px; }
        table.header-tbl td { vertical-align: middle; padding-bottom: 10px; }
        .logo-box { width: 36px; height: 36px; background: #FF5C00; border-radius: 6px; text-align: center; color: white; font-size: 14px; font-weight: 900; line-height: 36px; }
        .org-name { font-size: 14px; font-weight: 900; color: #000080; text-transform: uppercase; letter-spacing: 1px; }
        .org-sub  { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .header-right { text-align: right; font-size: 8px; color: #94a3b8; }

        /* ===== JUDUL ===== */
        .doc-title { text-align: center; margin: 10px 0 14px; }
        .doc-title h1 { font-size: 13px; font-weight: 900; color: #000080; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 3px; }
        .doc-title p  { font-size: 8px; color: #64748b; }

        /* ===== INFO EVENT ===== */
        table.info-tbl { width: 100%; border-collapse: collapse; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; margin-bottom: 14px; }
        table.info-tbl td { padding: 6px 10px; vertical-align: top; }
        .info-label { font-size: 8px; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 2px; }
        .info-value { font-size: 10px; font-weight: 700; color: #1e293b; }

        /* ===== TABEL PESERTA ===== */
        table.peserta-tbl { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.peserta-tbl thead tr { background: #000080; }
        table.peserta-tbl thead th { color: white; font-size: 8px; font-weight: 700; padding: 7px 8px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #000080; text-align: left; }
        table.peserta-tbl tbody tr:nth-child(even) { background: #f8fafc; }
        table.peserta-tbl tbody td { padding: 7px 8px; font-size: 9.5px; vertical-align: middle; border: 1px solid #e2e8f0; }
        .ttd-cell { height: 36px; border-bottom: 1px solid #94a3b8; }

        /* ===== FOOTER ===== */
        table.footer-tbl { width: 100%; border-collapse: collapse; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 12px; }
        table.footer-tbl td { padding-top: 12px; vertical-align: bottom; }
        .print-info { font-size: 8px; color: #94a3b8; }
        .sign-col { text-align: center; }
        .sign-line { width: 120px; height: 1px; background: #94a3b8; margin: 40px auto 4px; }
        .sign-name { font-size: 10px; font-weight: 700; color: #1e293b; }
        .sign-role { font-size: 8px; color: #64748b; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <table class="header-tbl">
        <tr>
            <td width="40" style="padding-right:8px;">
                <div class="logo-box">EK</div>
            </td>
            <td>
                <div class="org-name">Event Kampus</div>
                <div class="org-sub">Sistem Informasi Manajemen Event</div>
            </td>
            <td class="header-right">
                Dicetak: {{ now()->format('d/m/Y H:i') }}<br>
                Halaman 1 dari 1
            </td>
        </tr>
    </table>

    {{-- JUDUL --}}
    <div class="doc-title">
        <h1>Daftar Hadir Peserta</h1>
        <p>Dokumen resmi kehadiran &mdash; harap ditandatangani oleh setiap peserta</p>
    </div>

    {{-- INFO EVENT --}}
    <table class="info-tbl">
        <tr>
            <td width="20%">
                <div class="info-label">Nama Event</div>
                <div class="info-value">{{ $event->nama_event }}</div>
            </td>
            <td width="15%">
                <div class="info-label">Kategori</div>
                <div class="info-value">{{ optional($event->kategori)->nama_kategori ?? '-' }}</div>
            </td>
            <td width="15%">
                <div class="info-label">Tanggal</div>
                <div class="info-value">{{ $event->tanggal->format('d M Y') }}</div>
            </td>
            <td width="25%">
                <div class="info-label">Lokasi</div>
                <div class="info-value">{{ $event->lokasi ?? 'Akan Diumumkan' }}</div>
            </td>
            <td width="25%">
                <div class="info-label">Total Peserta</div>
                <div class="info-value">{{ $pendaftarans->count() }} orang</div>
            </td>
        </tr>
    </table>

    {{-- TABEL PESERTA --}}
    <table class="peserta-tbl">
        <thead>
            <tr>
                <th width="25px">No</th>
                <th width="22%">Nama Peserta</th>
                <th width="14%">NPM</th>
                <th width="20%">Program Studi</th>
                <th width="14%">No. Tiket</th>
                <th>Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftarans as $i => $p)
            <tr>
                <td style="text-align:center;color:#94a3b8;">{{ $i + 1 }}</td>
                <td style="font-weight:600;">{{ $p->peserta->nama ?? '-' }}</td>
                <td style="font-family:monospace;">{{ $p->peserta->npm_nip ?? '-' }}</td>
                <td style="color:#64748b;">{{ $p->peserta->jurusan ?? '-' }}</td>
                <td style="font-family:monospace;font-size:8px;color:#FF5C00;">{{ $p->nomor_tiket }}</td>
                <td><div class="ttd-cell"></div></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#94a3b8;padding:20px;">
                    Belum ada peserta terdaftar
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <table class="footer-tbl">
        <tr>
            <td>
                <div class="print-info">
                    Dokumen ini dicetak oleh sistem Event Kampus pada {{ now()->isoFormat('D MMMM Y') }}.
                </div>
            </td>
            <td class="sign-col" width="160px">
                <div class="sign-line"></div>
                <div class="sign-name">{{ optional($event->panitia)->nama ?? '-' }}</div>
                <div class="sign-role">Ketua Pelaksana</div>
            </td>
        </tr>
    </table>

</body>
</html>
