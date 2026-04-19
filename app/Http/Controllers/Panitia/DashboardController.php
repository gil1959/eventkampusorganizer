<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pendaftaran;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $panitia = Auth::guard('aktor')->user();

        $events = Event::where('id_panitia', $panitia->id_user)->get();
        $eventIds = $events->pluck('id_event');

        $stats = [
            'total_event'   => $events->count(),
            'event_aktif'   => $events->where('status', 'aktif')->count(),
            'event_selesai' => $events->where('status', 'selesai')->count(),
            'event_draft'   => $events->where('status', 'draft')->count(),
            'total_peserta' => Pendaftaran::whereIn('id_event', $eventIds)->where('status_kehadiran', '!=', 'batal')->count(),
            'total_sertifik'=> Sertifikat::whereHas('pendaftaran', fn($q) => $q->whereIn('id_event', $eventIds))->count(),
        ];

        $eventTerbaru = Event::with('kategori')
            ->where('id_panitia', $panitia->id_user)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('panitia.dashboard', compact('stats', 'eventTerbaru'));
    }
}
