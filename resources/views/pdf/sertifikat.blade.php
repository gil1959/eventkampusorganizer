<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat - {{ $sertifikat->nomor_sertifikat }}</title>
    <style>
        @page { margin: 0; size: A4 landscape; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            background: white;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            position: relative;
        }

        /* Border tipis dalam */
        .cert-border {
            position: absolute;
            top: 10mm; left: 10mm; right: 10mm; bottom: 10mm;
            border: 2px solid #3b82f6;
            z-index: 10;
        }

        /* ==== DEKORASI SUDUT ==== */
        /* Kiri Atas (Poligonal style menggunakan border) */
        .deco-tl { position:absolute; top:0; left:0; width:0; height:0; border-style:solid; border-width: 70mm 80mm 0 0; border-color: #1e3a8a transparent transparent transparent; z-index: 1; }
        .deco-tl-2 { position:absolute; top:0; left:0; width:0; height:0; border-style:solid; border-width: 50mm 60mm 0 0; border-color: #3b82f6 transparent transparent transparent; z-index: 2; }
        
        /* Kanan Bawah */
        .deco-br { position:absolute; bottom:0; right:0; width:0; height:0; border-style:solid; border-width: 0 0 70mm 80mm; border-color: transparent transparent #1e3a8a transparent; z-index: 1; }
        .deco-br-2 { position:absolute; bottom:0; right:0; width:0; height:0; border-style:solid; border-width: 0 0 50mm 60mm; border-color: transparent transparent #3b82f6 transparent; z-index: 2; }

        /* ==== CONTENT ==== */
        .cert-content {
            position: absolute;
            top: 25mm; left: 20mm; right: 20mm;
            text-align: center;
            z-index: 20;
        }

        .cert-title-main {
            font-size: 46pt;
            font-weight: normal;
            color: #1e3a8a;
            letter-spacing: 12px;
            margin-bottom: 0;
            text-transform: uppercase;
        }
        .cert-title-sub {
            font-size: 26pt;
            font-weight: normal;
            color: #475569;
            letter-spacing: 5px;
            margin-top: -5px;
            text-transform: uppercase;
            margin-bottom: 12mm;
        }

        .cert-given-to {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #64748b;
            margin-bottom: 5mm;
        }

        .cert-name {
            font-size: 52pt;
            font-weight: normal;
            color: #1e3a8a;
            margin-bottom: 8mm;
            letter-spacing: 2px;
        }

        /* Garis Divider Emas */
        .cert-divider-wrap {
            width: 100%;
            text-align: center;
            margin-bottom: 8mm;
        }
        .cert-divider {
            width: 180mm;
            border-top: 2px solid #eab308;
            border-bottom: 1px solid #eab308;
            height: 3px;
            margin: 0 auto;
        }

        .cert-achievement-label {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #334155;
            margin-bottom: 2mm;
        }

        .cert-event-name {
            font-size: 20pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 3mm;
        }

        .cert-date {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #475569;
        }

        /* ==== FOOTER (Tanda Tangan) ==== */
        .cert-footer {
            position: absolute;
            bottom: 20mm; left: 30mm; right: 30mm;
            z-index: 20;
        }

        /* Tabel untuk Layout Tanda Tangan krn DomPDF tidak men-support flexbox dengan sempurna */
        .signature-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        
        .signature-td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .sign-line { 
            width: 60mm; 
            border-top: 2px solid #eab308; 
            margin: 0 auto 3mm; 
        }
        
        .sign-name { 
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt; 
            font-weight: bold; 
            color: #1e293b; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            margin-bottom: 1mm;
        }
        
        .sign-role { 
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt; 
            color: #64748b; 
        }
        
        /* Kode Sertifikat Kiri Bawah */
        .cert-code {
            position: absolute;
            bottom: 13mm;
            left: 14mm;
            font-family: monospace;
            font-size: 8pt;
            color: #94a3b8;
            z-index: 25;
        }
    </style>
</head>
<body>

    {{-- Dekorasi Sudut --}}
    <div class="deco-tl"></div>
    <div class="deco-tl-2"></div>
    <div class="deco-br"></div>
    <div class="deco-br-2"></div>

    {{-- Kotak Border Outline --}}
    <div class="cert-border"></div>

    {{-- Nomor Sertifikat --}}
    <div class="cert-code">ID: {{ str_replace('/', '-', $sertifikat->nomor_sertifikat) }}</div>

    {{-- Konten Utama --}}
    <div class="cert-content">
        <div class="cert-title-main">SERTIFIKAT</div>
        <div class="cert-title-sub">PENGHARGAAN</div>

        <div class="cert-given-to">Diberikan Kepada:</div>
        
        <div class="cert-name">{{ $sertifikat->pendaftaran->peserta->nama }}</div>

        <div class="cert-divider-wrap">
            <div class="cert-divider"></div>
        </div>

        <div class="cert-achievement-label">Atas partipasi sebagai Peserta dalam acara</div>
        
        <div class="cert-event-name">{{ $sertifikat->pendaftaran->event->nama_event }}</div>
        
        <div class="cert-date">
            Pada Tanggal {{ $sertifikat->pendaftaran->event->tanggal->isoFormat('D MMMM Y') }}
        </div>
    </div>

    {{-- Footer Tanda Tangan Menggunakan Tabel --}}
    <div class="cert-footer">
        <table class="signature-table">
            <tr>
                <td class="signature-td">
                    <div style="height: 25mm;"></div> {{-- Ruang kosong untuk tanda tangan (opsional) --}}
                    <div class="sign-line"></div>
                    <div class="sign-name">{{ $sertifikat->pendaftaran->event->panitia->nama }}</div>
                    <div class="sign-role">Ketua Pelaksana</div>
                </td>
                <td class="signature-td">
                    <div style="height: 25mm;"></div>
                    <div class="sign-line"></div>
                    <div class="sign-name">Administrator Kampus</div>
                    <div class="sign-role">Wakil Ketua Pelaksana</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
