<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pendaftaran;
use App\Models\Sertifikat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    private function panitia()
    {
        return Auth::guard('aktor')->user();
    }

    public function index($eventId)
    {
        $panitia = $this->panitia();
        $event = Event::with('kategori')
            ->where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        $pendaftarans = Pendaftaran::with(['peserta', 'sertifikat'])
            ->where('id_event', $eventId)
            ->where('status_kehadiran', 'hadir')
            ->orderBy('tanggal_daftar')
            ->get();

        return view('panitia.sertifikat.index', compact('event', 'pendaftarans'));
    }

    /**
     * Generate sertifikat untuk semua peserta yang hadir dan belum punya sertifikat
     */
    public function generate(Request $request, $eventId)
    {
        $panitia = $this->panitia();
        $event = Event::with('kategori')
            ->where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        // Ambil peserta yang hadir tapi belum punya sertifikat
        $pendaftarans = Pendaftaran::with('peserta')
            ->where('id_event', $eventId)
            ->where('status_kehadiran', 'hadir')
            ->whereDoesntHave('sertifikat')
            ->get();

        if ($pendaftarans->isEmpty()) {
            return redirect()->back()->with('info', 'Semua peserta yang hadir sudah memiliki sertifikat.');
        }

        $generated = 0;
        foreach ($pendaftarans as $p) {
            // Format nomor: SERT/2026/XXXXXXXX
            $nomor = 'SERT/' . date('Y') . '/' . strtoupper(Str::random(8));

            // Pastikan nomor unik
            while (Sertifikat::where('nomor_sertifikat', $nomor)->exists()) {
                $nomor = 'SERT/' . date('Y') . '/' . strtoupper(Str::random(8));
            }

            $isi = "Diberikan kepada {$p->peserta->nama} atas partisipasinya dalam event "
                   . "{$event->nama_event} yang diselenggarakan pada "
                   . $event->tanggal->format('d F Y') . ".";

            Sertifikat::create([
                'id_pendaftaran'   => $p->id_pendaftaran,
                'nomor_sertifikat' => $nomor,
                'isi_sertifikat'   => $isi,
                'template_pdf'     => 'default',
                'tanggal_terbit'   => now(),
            ]);
            $generated++;
        }

        return redirect()->back()
            ->with('success', "{$generated} sertifikat berhasil digenerate. Peserta dapat mengunduh dari portal mereka.");
    }

    /**
     * Generate sertifikat untuk SATU peserta spesifik
     */
    public function generateSatu(Request $request, $eventId, $pendaftaranId)
    {
        $panitia = $this->panitia();
        $event = Event::with('kategori')
            ->where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        $pendaftaran = Pendaftaran::with(['peserta', 'sertifikat'])
            ->where('id_pendaftaran', $pendaftaranId)
            ->where('id_event', $eventId)
            ->where('status_kehadiran', 'hadir')
            ->firstOrFail();

        if ($pendaftaran->sertifikat) {
            return redirect()->back()->with('info', 'Sertifikat sudah ada untuk peserta ini.');
        }

        $nomor = 'SERT/' . date('Y') . '/' . strtoupper(Str::random(8));
        while (Sertifikat::where('nomor_sertifikat', $nomor)->exists()) {
            $nomor = 'SERT/' . date('Y') . '/' . strtoupper(Str::random(8));
        }

        $isi = "Diberikan kepada {$pendaftaran->peserta->nama} atas partisipasinya dalam event "
               . "{$event->nama_event} yang diselenggarakan pada "
               . $event->tanggal->format('d F Y') . ".";

        Sertifikat::create([
            'id_pendaftaran'   => $pendaftaran->id_pendaftaran,
            'nomor_sertifikat' => $nomor,
            'isi_sertifikat'   => $isi,
            'template_pdf'     => 'default',
            'tanggal_terbit'   => now(),
        ]);

        return redirect()->back()->with('success', "Sertifikat untuk {$pendaftaran->peserta->nama} berhasil digenerate.");
    }

    /**
     * Preview / Download PDF sertifikat
     */
    public function preview($sertifikatId)
    {
        $sertifikat = Sertifikat::with([
            'pendaftaran.peserta',
            'pendaftaran.event.kategori',
            'pendaftaran.event.panitia',
        ])->findOrFail($sertifikatId);

        // Sanitize nomor: hapus semua karakter non-alphanumeric kecuali - dan _
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

        // Tulis ke temp file lalu force download
        $tmpPath = tempnam(sys_get_temp_dir(), 'sertif_pdf_');
        file_put_contents($tmpPath, $content);

        return response()->download($tmpPath, $filename, [
            'Content-Type'  => 'application/pdf',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ])->deleteFileAfterSend(true);
    }
}
