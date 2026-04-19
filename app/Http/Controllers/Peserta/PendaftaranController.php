<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\RekeningPanitia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    private function peserta()
    {
        return Auth::guard('aktor')->user();
    }

    /* ============================================================
       HALAMAN DAFTAR (event gratis langsung, berbayar tampilkan form)
       ============================================================ */

    /**
     * Tampilkan halaman pendaftaran.
     * Gratis  → form konfirmasi sederhana
     * Berbayar → form lengkap: pilih rekening/metode + upload bukti
     */
    public function formDaftar($eventId)
    {
        $peserta = $this->peserta();
        $event   = Event::with(['kategori', 'panitia'])->where('id_event', $eventId)->where('status', 'aktif')->firstOrFail();

        // Cek sudah daftar (non-batal)
        $existing = Pendaftaran::where('id_event', $eventId)
            ->where('id_peserta', $peserta->id_user)
            ->where('status_kehadiran', '!=', 'batal')
            ->first();

        if ($existing) {
            return redirect()->route('peserta.riwayat')->with('info', 'Anda sudah terdaftar di event ini.');
        }

        if ($event->kuota_sisa <= 0) {
            return redirect()->back()->with('error', 'Kuota event sudah penuh.');
        }

        // Untuk event berbayar, ambil daftar rekening aktif
        $rekenings = collect();
        if ($event->biaya > 0) {
            $rekenings = RekeningPanitia::where('id_event', $eventId)
                ->where('is_active', true)
                ->orderBy('tipe')
                ->get();
        }

        return view('peserta.daftar', compact('event', 'rekenings'));
    }

    /**
     * Proses submit pendaftaran.
     *
     * Gratis  → langsung buat Pendaftaran (status: pendaftar), redirect sukses
     * Berbayar → buat Pendaftaran (status: menunggu_konfirmasi) + Pembayaran (status: menunggu)
     *            redirect ke riwayat dengan pesan menunggu konfirmasi panitia
     */
    public function daftar(Request $request, $eventId)
    {
        $peserta = $this->peserta();
        $event   = Event::where('id_event', $eventId)->where('status', 'aktif')->firstOrFail();

        // Cek apakah sudah ada record
        $existing = Pendaftaran::where('id_event', $eventId)
            ->where('id_peserta', $peserta->id_user)
            ->first();

        if ($existing) {
            if ($existing->status_kehadiran !== 'batal') {
                return redirect()->route('peserta.riwayat')->with('info', 'Anda sudah terdaftar di event ini.');
            }
        }

        if ($event->biaya <= 0) {
            // ===== EVENT GRATIS — langsung daftar =====
            if ($existing) {
                $existing->update([
                    'status_kehadiran' => 'pendaftar',
                    'nomor_tiket'      => 'TKT-' . strtoupper(Str::random(10)),
                    'tanggal_daftar'   => now(),
                ]);
            } else {
                if ($event->kuota_sisa <= 0) {
                    return redirect()->back()->with('error', 'Kuota event sudah penuh.');
                }
                Pendaftaran::create([
                    'id_event'         => $eventId,
                    'id_peserta'       => $peserta->id_user,
                    'status_kehadiran' => 'pendaftar',
                    'nomor_tiket'      => 'TKT-' . strtoupper(Str::random(10)),
                    'tanggal_daftar'   => now(),
                ]);
            }
            return redirect()->route('peserta.riwayat')
                ->with('success', 'Pendaftaran berhasil! Selamat bergabung.');
        }

        // ===== EVENT BERBAYAR — validasi form pembayaran =====
        $metode = $request->input('metode');
        if (!in_array($metode, ['transfer', 'bayar_lokasi'])) {
            return redirect()->back()->with('error', 'Pilih metode pembayaran terlebih dahulu.')->withInput();
        }

        if ($metode === 'transfer') {
            $request->validate([
                'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:3072',
                'id_rekening'    => 'nullable|exists:rekening_panitias,id_rekening',
            ], [
                'bukti_transfer.required' => 'Foto bukti transfer wajib diunggah.',
                'bukti_transfer.max'      => 'Ukuran foto maksimal 3MB.',
            ]);
        }

        DB::transaction(function () use ($request, $event, $peserta, $eventId, $existing) {
            // Buat / update pendaftaran dengan status menunggu_konfirmasi
            if ($existing && $existing->status_kehadiran === 'batal') {
                $existing->update([
                    'status_kehadiran' => 'menunggu',
                    'nomor_tiket'      => 'TKT-' . strtoupper(Str::random(10)),
                    'tanggal_daftar'   => now(),
                ]);
                $pendaftaran = $existing;
            } else {
                $pendaftaran = Pendaftaran::create([
                    'id_event'         => $eventId,
                    'id_peserta'       => $peserta->id_user,
                    'status_kehadiran' => 'menunggu',
                    'nomor_tiket'      => 'TKT-' . strtoupper(Str::random(10)),
                    'tanggal_daftar'   => now(),
                ]);
            }

            // Hapus pembayaran lama jika ada (re-submit setelah ditolak)
            if ($pendaftaran->pembayaran) {
                if ($pendaftaran->pembayaran->bukti_transfer) {
                    Storage::disk('public')->delete($pendaftaran->pembayaran->bukti_transfer);
                }
                $pendaftaran->pembayaran->delete();
            }

            $rekening = null;
            $buktiPath = null;
            $metode    = request()->input('metode');

            if ($metode === 'transfer') {
                if (request()->hasFile('bukti_transfer')) {
                    $buktiPath = request()->file('bukti_transfer')->store('bukti-pembayaran', 'public');
                }
                if (request()->id_rekening) {
                    $rekening = RekeningPanitia::find(request()->id_rekening);
                }
            }

            Pembayaran::create([
                'id_pendaftaran'    => $pendaftaran->id_pendaftaran,
                'metode'            => $metode,
                'id_rekening'       => $rekening?->id_rekening,
                'bank_atau_ewallet' => $rekening?->nama_bank,
                'nomor_tujuan'      => $rekening?->nomor_rekening,
                'atas_nama'         => $rekening?->atas_nama,
                'jumlah'            => $event->biaya,
                'bukti_transfer'    => $buktiPath,
                'status'            => 'menunggu',
                'tanggal_bayar'     => now(),
            ]);
        });

        $msg = request()->input('metode') === 'transfer'
            ? 'Pendaftaran dikirim. Menunggu konfirmasi pembayaran dari panitia.'
            : 'Pendaftaran tercatat. Silakan bayar di lokasi saat event berlangsung.';

        return redirect()->route('peserta.riwayat')->with('success', $msg);
    }

    /* ============================================================
       RIWAYAT PENDAFTARAN
       ============================================================ */

    public function riwayat()
    {
        $pendaftarans = Pendaftaran::with(['event.kategori', 'sertifikat', 'pembayaran'])
            ->where('id_peserta', $this->peserta()->id_user)
            ->orderBy('tanggal_daftar', 'desc')
            ->paginate(10);

        return view('peserta.riwayat', compact('pendaftarans'));
    }

    /* ============================================================
       BATALKAN PENDAFTARAN
       ============================================================ */

    public function batalkan(Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->id_peserta !== $this->peserta()->id_user) {
            abort(403);
        }
        if (in_array($pendaftaran->status_kehadiran, ['hadir', 'pendaftar'])) {
            if ($pendaftaran->status_kehadiran === 'hadir') {
                return redirect()->back()->with('error', 'Tidak dapat membatalkan pendaftaran yang sudah hadir.');
            }
        }
        $pendaftaran->update(['status_kehadiran' => 'batal']);
        return redirect()->back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }
}
