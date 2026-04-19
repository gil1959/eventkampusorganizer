<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Aktor;
use App\Models\Pendaftaran;
use App\Models\Sertifikat;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $eventTerbaru = Event::with(['kategori', 'panitia'])
            ->where('status', 'aktif')
            ->orderBy('tanggal', 'asc')
            ->take(6)
            ->get();

        $kategoris = Kategori::withCount(['events' => function ($q) {
            $q->where('status', 'aktif');
        }])->where('is_active', true)->get();

        $stats = [
            'total_event'    => Event::where('status', 'aktif')->count(),
            'total_peserta'  => Aktor::where('role', 'peserta')->count(),
            'total_kategori' => Kategori::where('is_active', true)->count(),
            'total_sertifik' => Sertifikat::count(),
        ];

        return view('landing.index', compact('eventTerbaru', 'kategoris', 'stats'));
    }

    public function events(Request $request)
    {
        $query = Event::with(['kategori', 'panitia'])->where('status', 'aktif');

        if ($request->kategori) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->search) {
            $query->where('nama_event', 'like', '%' . $request->search . '%');
        }

        $events   = $query->orderBy('tanggal', 'asc')->paginate(9);
        $kategoris = Kategori::where('is_active', true)->get();

        return view('landing.events', compact('events', 'kategoris'));
    }

    public function eventDetail($id)
    {
        $event = Event::with(['kategori', 'panitia', 'pendaftarans'])->findOrFail($id);
        if ($event->status !== 'aktif') {
            abort(404);
        }
        return view('landing.event-detail', compact('event'));
    }
}
