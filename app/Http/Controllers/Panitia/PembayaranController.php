<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pembayaran;
use App\Models\RekeningPanitia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    private function panitia()
    {
        return Auth::guard('aktor')->user();
    }

    /* ============================================================
       MONITORING PEMBAYARAN
       ============================================================ */

    public function monitoring()
    {
        $panitia  = $this->panitia();
        $eventIds = Event::where('id_panitia', $panitia->id_user)->pluck('id_event');

        $pembayarans = Pembayaran::with([
            'pendaftaran.peserta',
            'pendaftaran.event',
            'rekening',
        ])
            ->whereHas('pendaftaran', fn ($q) => $q->whereIn('id_event', $eventIds))
            ->latest()
            ->paginate(20);

        $stats = [
            'menunggu'     => Pembayaran::whereHas('pendaftaran', fn ($q) => $q->whereIn('id_event', $eventIds))->where('status', 'menunggu')->count(),
            'dikonfirmasi' => Pembayaran::whereHas('pendaftaran', fn ($q) => $q->whereIn('id_event', $eventIds))->where('status', 'dikonfirmasi')->count(),
            'ditolak'      => Pembayaran::whereHas('pendaftaran', fn ($q) => $q->whereIn('id_event', $eventIds))->where('status', 'ditolak')->count(),
            'total'        => Pembayaran::whereHas('pendaftaran', fn ($q) => $q->whereIn('id_event', $eventIds))->count(),
        ];

        // Ambil event berbayar yang dimiliki panitia untuk card "Kelola Rekening"
        $events = Event::where('id_panitia', $panitia->id_user)
            ->where('biaya', '>', 0)
            ->whereIn('status', ['aktif', 'selesai'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('panitia.pembayaran.monitoring', compact('pembayarans', 'stats', 'events'));
    }

    /* ============================================================
       PEMBAYARAN PER-EVENT
       ============================================================ */

    public function index($eventId)
    {
        $panitia = $this->panitia();
        $event   = Event::where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        $pembayarans = Pembayaran::with(['pendaftaran.peserta', 'rekening'])
            ->whereHas('pendaftaran', fn ($q) => $q->where('id_event', $eventId))
            ->latest()
            ->get();

        $stats = [
            'menunggu'     => $pembayarans->where('status', 'menunggu')->count(),
            'dikonfirmasi' => $pembayarans->where('status', 'dikonfirmasi')->count(),
            'ditolak'      => $pembayarans->where('status', 'ditolak')->count(),
            'total'        => $pembayarans->count(),
        ];

        return view('panitia.pembayaran.index', compact('event', 'pembayarans', 'stats'));
    }

    /* ============================================================
       APPROVE / DECLINE
       ============================================================ */

    public function approve(Request $request, Pembayaran $pembayaran)
    {
        $this->otorisasi($pembayaran);

        $pembayaran->update([
            'status'            => 'dikonfirmasi',
            'catatan_panitia'   => $request->catatan,
            'dikonfirmasi_oleh' => $this->panitia()->id_user,
            'dikonfirmasi_at'   => now(),
        ]);

        // Update pendaftaran ke terbayar
        $pembayaran->pendaftaran->update(['status_kehadiran' => 'terbayar']);

        $nama = $pembayaran->pendaftaran->peserta->nama ?? '-';
        return redirect()->back()->with('success', "Pembayaran {$nama} berhasil dikonfirmasi. Status peserta diperbarui.");
    }

    public function decline(Request $request, Pembayaran $pembayaran)
    {
        $request->validate(['catatan' => 'required|string|max:500']);
        $this->otorisasi($pembayaran);

        $pembayaran->update([
            'status'            => 'ditolak',
            'catatan_panitia'   => $request->catatan,
            'dikonfirmasi_oleh' => $this->panitia()->id_user,
            'dikonfirmasi_at'   => now(),
        ]);

        // Kembalikan pendaftaran ke menunggu agar peserta bisa upload ulang
        $pembayaran->pendaftaran->update(['status_kehadiran' => 'menunggu']);

        return redirect()->back()->with('success', 'Pembayaran ditolak. Peserta dapat mengunggah ulang bukti transfer.');
    }

    /* ============================================================
       REKENING / E-WALLET
       ============================================================ */

    public function rekeningIndex($eventId)
    {
        $panitia = $this->panitia();
        $event   = Event::where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        $rekenings = RekeningPanitia::where('id_event', $eventId)
            ->orderBy('is_active', 'desc')
            ->orderBy('tipe')
            ->get();

        return view('panitia.rekening.index', compact('event', 'rekenings'));
    }

    public function rekeningStore(Request $request, $eventId)
    {
        $request->validate([
            'tipe'           => 'required|in:bank,ewallet',
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:100',
        ]);

        $panitia = $this->panitia();
        $event   = Event::where('id_event', $eventId)
            ->where('id_panitia', $panitia->id_user)
            ->firstOrFail();

        RekeningPanitia::create([
            'id_event'       => $event->id_event,
            'tipe'           => $request->tipe,
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => $request->atas_nama,
            'is_active'      => true,
        ]);

        return redirect()->back()->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function rekeningToggle(Request $request, $eventId, RekeningPanitia $rekening)
    {
        $panitia = $this->panitia();
        abort_if($rekening->id_event != $eventId, 403);
        abort_if(
            !Event::where('id_event', $eventId)->where('id_panitia', $panitia->id_user)->exists(),
            403
        );

        $rekening->update(['is_active' => !$rekening->is_active]);
        $status = $rekening->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Rekening {$rekening->nama_bank} berhasil {$status}.");
    }

    public function rekeningDestroy($eventId, RekeningPanitia $rekening)
    {
        $panitia = $this->panitia();
        abort_if($rekening->id_event != $eventId, 403);
        abort_if(
            !Event::where('id_event', $eventId)->where('id_panitia', $panitia->id_user)->exists(),
            403
        );

        $rekening->delete();
        return redirect()->back()->with('success', 'Rekening berhasil dihapus.');
    }

    /* ============================================================
       Helper
       ============================================================ */

    /**
     * Pastikan panitia yang logged-in adalah panitia dari event pembayaran tsb.
     * Gunakan == (loose comparison) untuk menghindari type mismatch int vs string.
     */
    private function otorisasi(Pembayaran $pembayaran): void
    {
        $pembayaran->loadMissing('pendaftaran.event');
        $idPanitiaLogin = (int) $this->panitia()->id_user;
        $idPanitiaEvent = (int) optional(optional($pembayaran->pendaftaran)->event)->id_panitia;

        abort_if(!$idPanitiaEvent || $idPanitiaLogin !== $idPanitiaEvent, 403, 'Anda tidak memiliki akses untuk pembayaran ini.');
    }
}
