<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pendaftaran;
use App\Models\RekeningPanitia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekeningController extends Controller
{
    private function peserta()
    {
        return Auth::guard('aktor')->user();
    }

    /**
     * Peserta bisa kelola rekening event yang mereka ikuti (sebagai panitia event mereka sendiri).
     * Note: Ini khusus untuk peserta yang JUGA berperan sebagai penyelenggara di sisi tertentu,
     * ATAU ini bisa dibatasi hanya untuk event di mana peserta adalah "narahubung".
     *
     * Dalam konteks sistem ini, peserta dapat menambah rekening untuk event
     * yang terdaftar milik event aktif (hanya bisa lihat + kelola milik event yang mereka daftar).
     */
    public function index($eventId)
    {
        $peserta = $this->peserta();

        // Pastikan peserta terdaftar di event ini
        $pendaftaran = Pendaftaran::where('id_event', $eventId)
            ->where('id_peserta', $peserta->id_user)
            ->whereIn('status_kehadiran', ['pendaftar', 'terbayar', 'hadir'])
            ->firstOrFail();

        $event = Event::with(['panitia', 'kategori'])->findOrFail($eventId);

        $rekenings = RekeningPanitia::where('id_event', $eventId)
            ->orderBy('is_active', 'desc')
            ->orderBy('tipe')
            ->get();

        return view('peserta.rekening', compact('event', 'pendaftaran', 'rekenings'));
    }

    /**
     * Tambah rekening baru untuk event
     */
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'tipe'           => 'required|in:bank,ewallet',
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:100',
        ]);

        $peserta = $this->peserta();
        // Verifikasi peserta terdaftar
        Pendaftaran::where('id_event', $eventId)
            ->where('id_peserta', $peserta->id_user)
            ->firstOrFail();

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

    /**
     * Toggle aktif/nonaktif
     */
    public function toggle(Request $request, $eventId, RekeningPanitia $rekening)
    {
        abort_if($rekening->id_event != $eventId, 403);

        $rekening->update(['is_active' => !$rekening->is_active]);
        $status = $rekening->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Rekening {$rekening->nama_bank} berhasil {$status}.");
    }

    /**
     * Hapus rekening
     */
    public function destroy($eventId, RekeningPanitia $rekening)
    {
        abort_if($rekening->id_event != $eventId, 403);
        $rekening->delete();
        return redirect()->back()->with('success', 'Rekening berhasil dihapus.');
    }
}
