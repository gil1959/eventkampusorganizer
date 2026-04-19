<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\RekeningPanitia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    private function peserta()
    {
        return Auth::guard('aktor')->user();
    }

    /**
     * Halaman pilih metode pembayaran untuk event berbayar
     */
    public function pilih($eventId)
    {
        $event = Event::where('id_event', $eventId)->where('status', 'aktif')->firstOrFail();

        if ($event->biaya <= 0) {
            return redirect()->route('peserta.home')->with('error', 'Event ini gratis.');
        }

        $pendaftaran = Pendaftaran::with('pembayaran')
            ->where('id_event', $eventId)
            ->where('id_peserta', $this->peserta()->id_user)
            ->whereIn('status_kehadiran', ['pendaftar', 'terbayar'])
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('peserta.event.show', $eventId)
                ->with('error', 'Anda belum terdaftar pada event ini.');
        }

        if ($pendaftaran->pembayaran && $pendaftaran->pembayaran->status === 'dikonfirmasi') {
            return redirect()->route('peserta.riwayat')
                ->with('info', 'Pembayaran Anda sudah dikonfirmasi.');
        }

        // Hanya tampilkan rekening yang aktif
        $rekenings = RekeningPanitia::where('id_event', $eventId)
            ->where('is_active', true)
            ->orderBy('tipe')
            ->get();

        return view('peserta.bayar-pilih', compact('event', 'pendaftaran', 'rekenings'));
    }

    /**
     * Peserta memilih bayar di lokasi
     */
    public function bayarLokasi(Request $request, $eventId)
    {
        $peserta = $this->peserta();
        $pendaftaran = Pendaftaran::where('id_event', $eventId)
            ->where('id_peserta', $peserta->id_user)
            ->firstOrFail();

        if ($pendaftaran->pembayaran) {
            return redirect()->route('peserta.riwayat')->with('info', 'Pembayaran sudah tercatat.');
        }

        $event = $pendaftaran->event;

        Pembayaran::create([
            'id_pendaftaran' => $pendaftaran->id_pendaftaran,
            'metode'         => 'bayar_lokasi',
            'jumlah'         => $event->biaya,
            'status'         => 'menunggu',
        ]);

        return redirect()->route('peserta.riwayat')
            ->with('success', 'Pendaftaran berhasil! Bayar langsung ke panitia di hari event.');
    }

    /**
     * Halaman upload bukti transfer (setelah pilih rekening tujuan)
     */
    public function transfer($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['event', 'pembayaran'])
            ->where('id_pendaftaran', $pendaftaranId)
            ->where('id_peserta', $this->peserta()->id_user)
            ->firstOrFail();

        // Rekening aktif dari event ini
        $rekenings = RekeningPanitia::where('id_event', $pendaftaran->id_event)
            ->where('is_active', true)
            ->orderBy('tipe')
            ->get();

        if ($rekenings->isEmpty()) {
            return redirect()->route('peserta.pembayaran.pilih', $pendaftaran->id_event)
                ->with('error', 'Panitia belum menambahkan rekening. Silakan pilih metode lain atau hubungi panitia.');
        }

        return view('peserta.bayar-transfer', compact('pendaftaran', 'rekenings'));
    }

    /**
     * Upload bukti transfer
     */
    public function uploadBukti(Request $request, $pendaftaranId)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'id_rekening'    => 'nullable|exists:rekening_panitias,id_rekening',
        ], [
            'bukti_transfer.required' => 'Foto bukti transfer wajib diunggah.',
            'bukti_transfer.max'      => 'Ukuran foto maksimal 3MB.',
        ]);

        $peserta = $this->peserta();
        $pendaftaran = Pendaftaran::with('event')
            ->where('id_pendaftaran', $pendaftaranId)
            ->where('id_peserta', $peserta->id_user)
            ->firstOrFail();

        $event = $pendaftaran->event;

        // Cari info rekening yang dipilih
        $rekening = null;
        if ($request->id_rekening) {
            $rekening = RekeningPanitia::find($request->id_rekening);
        }

        $path = $request->file('bukti_transfer')->store('bukti-pembayaran', 'public');

        $data = [
            'id_pendaftaran'    => $pendaftaran->id_pendaftaran,
            'metode'            => 'transfer',
            'id_rekening'       => $rekening?->id_rekening,
            'bank_atau_ewallet' => $rekening?->nama_bank,
            'nomor_tujuan'      => $rekening?->nomor_rekening,
            'atas_nama'         => $rekening?->atas_nama,
            'jumlah'            => $event->biaya,
            'bukti_transfer'    => $path,
            'status'            => 'menunggu',
            'tanggal_bayar'     => now(),
        ];

        if ($pendaftaran->pembayaran) {
            // Hapus bukti lama
            if ($pendaftaran->pembayaran->bukti_transfer) {
                Storage::disk('public')->delete($pendaftaran->pembayaran->bukti_transfer);
            }
            $pendaftaran->pembayaran->update($data);
        } else {
            Pembayaran::create($data);
        }

        return redirect()->route('peserta.riwayat')
            ->with('success', 'Bukti transfer berhasil dikirim. Menunggu konfirmasi panitia.');
    }
}
