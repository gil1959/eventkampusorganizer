<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Hapus event (admin bisa hapus event apapun)
     */
    public function destroy(Event $event)
    {
        // Hapus poster dari storage jika ada
        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $namaEvent = $event->nama_event;

        // Manual cascade delete untuk mencegah error MySQL Integrity Constraint Violation
        foreach ($event->pendaftarans as $pendaftaran) {
            \App\Models\Pembayaran::where('id_pendaftaran', $pendaftaran->id_pendaftaran)->delete();
            \App\Models\Sertifikat::where('id_pendaftaran', $pendaftaran->id_pendaftaran)->delete();
            $pendaftaran->delete();
        }
        \App\Models\RekeningPanitia::where('id_event', $event->id_event)->delete();
        
        $event->delete(); // hapus event

        return redirect()->route('admin.validasi.index')
            ->with('success', "Event \"{$namaEvent}\" berhasil dihapus.");
    }
}
