<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikats = Sertifikat::with(['pendaftaran.event'])
            ->whereHas('pendaftaran', fn ($q) => $q->where('id_peserta', Auth::guard('aktor')->id()))
            ->orderBy('tanggal_terbit', 'desc')
            ->get();

        return view('peserta.sertifikat', compact('sertifikats'));
    }

    public function download($id)
    {
        $sertifikat = Sertifikat::with([
            'pendaftaran.peserta',
            'pendaftaran.event.kategori',
            'pendaftaran.event.panitia',
        ])
            ->where('id_sertifikat', $id)
            ->whereHas('pendaftaran', fn ($q) => $q->where('id_peserta', Auth::guard('aktor')->id()))
            ->firstOrFail();

        // Sanitize: hapus semua karakter selain alphanumeric, dash, underscore
        $safeNomor = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $sertifikat->nomor_sertifikat);
        $filename  = 'sertifikat-' . $safeNomor . '.pdf';

        // Bersihkan output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }

        $pdf = Pdf::loadView('pdf.sertifikat', compact('sertifikat'))
            ->setPaper('A4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false)
            ->setOption('defaultFont', 'dejavusans');

        $pdf->render();
        $content = $pdf->output();

        // Temp file → force download yang reliable di semua environment
        $tmpPath = tempnam(sys_get_temp_dir(), 'sertif_pdf_');
        file_put_contents($tmpPath, $content);

        return response()->download($tmpPath, $filename, [
            'Content-Type'  => 'application/pdf',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ])->deleteFileAfterSend(true);
    }
}
