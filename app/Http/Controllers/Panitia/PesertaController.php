<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    private function panitia()
    {
        return Auth::guard('aktor')->user();
    }

    public function index($eventId)
    {
        $panitia = $this->panitia();
        $event = Event::where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        $pendaftarans = Pendaftaran::with(['peserta', 'pembayaran'])
            ->where('id_event', $eventId)
            ->orderBy('tanggal_daftar')
            ->get();

        return view('panitia.peserta.index', compact('event', 'pendaftarans'));
    }

    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate(['status_kehadiran' => 'required|in:pendaftar,terbayar,hadir,batal']);
        $pendaftaran->update(['status_kehadiran' => $request->status_kehadiran]);
        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    /**
     * Export absensi - mendukung PDF, CSV, Excel
     */
    public function exportAbsensi(Request $request, $eventId)
    {
        $panitia = $this->panitia();
        $event = Event::with(['kategori', 'panitia'])
            ->where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        $format = $request->get('format', 'pdf');

        $pendaftarans = Pendaftaran::with('peserta')
            ->where('id_event', $eventId)
            ->where('status_kehadiran', '!=', 'batal')
            ->orderBy('tanggal_daftar')
            ->get();

        return match ($format) {
            'csv'   => $this->exportCsv($event, $pendaftarans),
            'excel' => $this->exportExcel($event, $pendaftarans),
            default => $this->exportPdf($event, $pendaftarans),
        };
    }

    private function exportPdf($event, $pendaftarans)
    {
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '-', \Illuminate\Support\Str::slug($event->nama_event));
        $filename = 'absensi-' . $safeName . '.pdf';

        // Bersihkan output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }

        $pdf = Pdf::loadView('pdf.absensi', compact('event', 'pendaftarans'))
            ->setPaper('A4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false)
            ->setOption('defaultFont', 'dejavusans');

        $pdf->render();
        $content = $pdf->output();

        // Tulis ke temp file lalu force download
        $tmpPath = tempnam(sys_get_temp_dir(), 'absensi_pdf_');
        file_put_contents($tmpPath, $content);

        return response()->download($tmpPath, $filename, [
            'Content-Type'  => 'application/pdf',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export CSV (RFC 4180 compliant, UTF-8 BOM untuk Excel)
     */
    private function exportCsv($event, $pendaftarans)
    {
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '-', \Illuminate\Support\Str::slug($event->nama_event));
        $filename = 'absensi-' . $safeName . '.csv';

        // Build CSV
        $handle = fopen('php://temp', 'w+');
        fputs($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Daftar Hadir Peserta Event'], ';');
        fputcsv($handle, ['Nama Event', $event->nama_event], ';');
        fputcsv($handle, ['Kategori', optional($event->kategori)->nama_kategori ?? '-'], ';');
        fputcsv($handle, ['Tanggal', $event->tanggal->format('d M Y')], ';');
        fputcsv($handle, ['Lokasi', $event->lokasi ?? '-'], ';');
        fputcsv($handle, ['Total Peserta', $pendaftarans->count()], ';');
        fputcsv($handle, [], ';');

        fputcsv($handle, ['No', 'Nama Peserta', 'NPM', 'Program Studi', 'No. HP', 'No. Tiket', 'Status', 'Tanggal Daftar'], ';');

        foreach ($pendaftarans as $i => $p) {
            fputcsv($handle, [
                $i + 1,
                $p->peserta->nama ?? '-',
                $p->peserta->npm_nip ?? '-',
                $p->peserta->jurusan ?? '-',
                $p->peserta->no_hp ?? '-',
                $p->nomor_tiket ?? '',
                $p->status_label ?? '',
                $p->tanggal_daftar ? \Carbon\Carbon::parse($p->tanggal_daftar)->format('d/m/Y H:i') : '-',
            ], ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        $tmpPath = tempnam(sys_get_temp_dir(), 'absensi_csv_');
        file_put_contents($tmpPath, $content);

        return response()->download($tmpPath, $filename, [
            'Content-Type'  => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ])->deleteFileAfterSend(true);
    }

    private function exportExcel($event, $pendaftarans)
    {
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '-', \Illuminate\Support\Str::slug($event->nama_event));
        $filename = 'absensi-' . $safeName . '.xls';
        
        $html = $this->buildExcelHtml($event, $pendaftarans);

        $tmpPath = tempnam(sys_get_temp_dir(), 'absensi_xls_');
        file_put_contents($tmpPath, $html);

        return response()->download($tmpPath, $filename, [
            'Content-Type'  => 'application/vnd.ms-excel; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ])->deleteFileAfterSend(true);
    }

    private function buildExcelHtml($event, $pendaftarans): string
    {
        $rows = '';
        foreach ($pendaftarans as $i => $p) {
            $no      = $i + 1;
            $nama    = htmlspecialchars($p->peserta->nama ?? '');
            $npm     = htmlspecialchars($p->peserta->npm_nip ?? '-');
            $jurusan = htmlspecialchars($p->peserta->jurusan ?? '-');
            $noHp    = htmlspecialchars($p->peserta->no_hp ?? '-');
            $tiket   = htmlspecialchars($p->nomor_tiket ?? '');
            $status  = htmlspecialchars($p->status_label ?? '');
            $tgl     = $p->tanggal_daftar
                ? \Carbon\Carbon::parse($p->tanggal_daftar)->format('d/m/Y H:i')
                : '-';

            $rows .= "<tr>
                <td style='text-align:center;'>$no</td>
                <td><b>$nama</b></td>
                <td style='mso-number-format:\"\@\";'>$npm</td>
                <td>$jurusan</td>
                <td style='mso-number-format:\"\@\";'>$noHp</td>
                <td style='mso-number-format:\"\@\";'>$tiket</td>
                <td>$status</td>
                <td>$tgl</td>
                <td style='width:120px;'></td>
            </tr>\n";
        }

        $namaEvent = htmlspecialchars($event->nama_event);
        $kategori  = htmlspecialchars($event->kategori->nama_kategori ?? '-');
        $tanggal   = $event->tanggal->format('d M Y');
        $lokasi    = htmlspecialchars($event->lokasi ?? '-');
        $total     = $pendaftarans->count();
        $dicetak   = now()->format('d/m/Y H:i');

        return <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="UTF-8">
<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>
<x:ExcelWorksheet><x:Name>Daftar Hadir</x:Name>
<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->
<style>
  body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
  table { border-collapse: collapse; width: 100%; }
  th { background-color: #000080; color: white; border: 1px solid #cccccc; padding: 6px 10px; font-size: 10pt; font-weight: bold; }
  td { border: 1px solid #cccccc; padding: 5px 8px; font-size: 10pt; vertical-align: middle; }
  tr:nth-child(even) td { background-color: #f0f0f8; }
  .info-row td { border: none; font-size: 10pt; }
  .title-row td { font-size: 14pt; font-weight: bold; color: #000080; border: none; }
  .blank td { border: none; }
</style>
</head>
<body>
<table>
  <tr class="title-row"><td colspan="9">Daftar Hadir Peserta Event</td></tr>
  <tr class="info-row"><td width="140"><b>Nama Event</b></td><td colspan="8">$namaEvent</td></tr>
  <tr class="info-row"><td><b>Kategori</b></td><td colspan="8">$kategori</td></tr>
  <tr class="info-row"><td><b>Tanggal</b></td><td colspan="8">$tanggal</td></tr>
  <tr class="info-row"><td><b>Lokasi</b></td><td colspan="8">$lokasi</td></tr>
  <tr class="info-row"><td><b>Total Peserta</b></td><td colspan="8">$total peserta</td></tr>
  <tr class="info-row"><td><b>Dicetak</b></td><td colspan="8">$dicetak</td></tr>
  <tr class="blank"><td colspan="9"></td></tr>
  <thead>
  <tr>
    <th width="40">No</th>
    <th width="180">Nama Peserta</th>
    <th width="120">NPM</th>
    <th width="160">Program Studi</th>
    <th width="120">No. HP</th>
    <th width="140">No. Tiket</th>
    <th width="100">Status</th>
    <th width="130">Tanggal Daftar</th>
    <th width="120">Tanda Tangan</th>
  </tr>
  </thead>
  <tbody>
  $rows
  </tbody>
</table>
</body>
</html>
HTML;
    }
}
