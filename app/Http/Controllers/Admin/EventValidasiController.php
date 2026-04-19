<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventValidasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['kategori', 'panitia']);

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['menunggu', 'aktif', 'ditolak']);
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.validasi.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['kategori', 'panitia', 'pendaftarans.peserta']);
        return view('admin.validasi.show', compact('event'));
    }

    public function approve(Request $request, Event $event)
    {
        $event->update([
            'status'        => 'aktif',
            'catatan_admin' => $request->catatan,
        ]);
        return redirect()->route('admin.validasi.index')->with('success', "Event \"{$event->nama_event}\" berhasil disetujui.");
    }

    public function reject(Request $request, Event $event)
    {
        $request->validate(['catatan' => 'required|string|max:500']);
        $event->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan,
        ]);
        return redirect()->route('admin.validasi.index')->with('success', "Event \"{$event->nama_event}\" telah ditolak.");
    }
}
