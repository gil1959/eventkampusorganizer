<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pembayaran;
use App\Models\RekeningPanitia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /* ============================================================
       MONITORING PEMBAYARAN (semua event — admin bisa approve/decline)
       ============================================================ */

    public function index(Request $request)
    {
        $query = Pembayaran::with([
            'pendaftaran.peserta',
            'pendaftaran.event.panitia',
            'rekening',
        ]);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->metode) {
            $query->where('metode', $request->metode);
        }
        if ($request->event_id) {
            $query->whereHas('pendaftaran', fn ($q) => $q->where('id_event', $request->event_id));
        }

        $pembayarans = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'menunggu'     => Pembayaran::where('status', 'menunggu')->count(),
            'dikonfirmasi' => Pembayaran::where('status', 'dikonfirmasi')->count(),
            'ditolak'      => Pembayaran::where('status', 'ditolak')->count(),
            'total'        => Pembayaran::count(),
        ];

        $events = Event::where('biaya', '>', 0)
            ->whereIn('status', ['aktif', 'selesai'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans', 'stats', 'events'));
    }

    /* ============================================================
       APPROVE / DECLINE (admin bisa konfirmasi semua)
       ============================================================ */

    public function approve(Request $request, Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status'            => 'dikonfirmasi',
            'catatan_panitia'   => $request->catatan,
            'dikonfirmasi_oleh' => Auth::guard('aktor')->id(),
            'dikonfirmasi_at'   => now(),
        ]);

        $pembayaran->pendaftaran->update(['status_kehadiran' => 'terbayar']);

        $nama = $pembayaran->pendaftaran->peserta->nama ?? '-';
        return redirect()->back()->with('success', "Pembayaran {$nama} berhasil dikonfirmasi.");
    }

    public function decline(Request $request, Pembayaran $pembayaran)
    {
        $request->validate(['catatan' => 'required|string|max:500']);

        $pembayaran->update([
            'status'            => 'ditolak',
            'catatan_panitia'   => $request->catatan,
            'dikonfirmasi_oleh' => Auth::guard('aktor')->id(),
            'dikonfirmasi_at'   => now(),
        ]);

        $pembayaran->pendaftaran->update(['status_kehadiran' => 'menunggu']);

        return redirect()->back()->with('success', 'Pembayaran ditolak. Peserta dapat mengunggah ulang bukti.');
    }

    /* ============================================================
       KELOLA REKENING (admin manage rekening untuk semua event)
       ============================================================ */

    public function rekeningIndex($eventId)
    {
        $event     = Event::with(['panitia', 'kategori'])->findOrFail($eventId);
        $rekenings = RekeningPanitia::where('id_event', $eventId)
            ->orderBy('is_active', 'desc')
            ->orderBy('tipe')
            ->get();

        return view('admin.pembayaran.rekening', compact('event', 'rekenings'));
    }

    public function rekeningStore(Request $request, $eventId)
    {
        $request->validate([
            'tipe'           => 'required|in:bank,ewallet',
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:100',
        ]);

        Event::findOrFail($eventId);

        RekeningPanitia::create([
            'id_event'       => $eventId,
            'tipe'           => $request->tipe,
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => $request->atas_nama,
            'is_active'      => true,
        ]);

        return redirect()->back()->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function rekeningToggle($eventId, RekeningPanitia $rekening)
    {
        abort_if($rekening->id_event != $eventId, 403);
        $rekening->update(['is_active' => !$rekening->is_active]);
        $status = $rekening->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Rekening {$rekening->nama_bank} berhasil {$status}.");
    }

    public function rekeningDestroy($eventId, RekeningPanitia $rekening)
    {
        abort_if($rekening->id_event != $eventId, 403);
        $rekening->delete();
        return redirect()->back()->with('success', 'Rekening berhasil dihapus.');
    }
}
