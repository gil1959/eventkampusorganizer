<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function show($id)
    {
        $event = Event::with(['kategori', 'panitia', 'pendaftarans'])
            ->where('status', 'aktif')
            ->findOrFail($id);

        $sudahDaftar = false;
        if (auth()->guard('aktor')->check()) {
            $sudahDaftar = $event->pendaftarans()
                ->where('id_peserta', auth()->guard('aktor')->id())
                ->where('status_kehadiran', '!=', 'batal')
                ->exists();
        }

        return view('peserta.event-detail', compact('event', 'sudahDaftar'));
    }
}
