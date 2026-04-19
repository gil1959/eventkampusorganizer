<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktor;
use App\Models\Event;
use App\Models\Pendaftaran;
use App\Models\Sertifikat;
use App\Models\Kategori;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_user'     => Aktor::where('role', '!=', 'admin')->count(),
            'total_panitia'  => Aktor::where('role', 'panitia')->count(),
            'total_peserta'  => Aktor::where('role', 'peserta')->count(),
            'total_event'    => Event::count(),
            'event_aktif'    => Event::where('status', 'aktif')->count(),
            'event_menunggu' => Event::where('status', 'menunggu')->count(),
            'event_selesai'  => Event::where('status', 'selesai')->count(),
            'total_daftar'   => Pendaftaran::count(),
            'total_sertifik' => Sertifikat::count(),
            'total_kategori' => Kategori::count(),
        ];

        $eventTerbaru = Event::with(['kategori', 'panitia'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $panitiaMenunggu = Aktor::where('role', 'panitia')
            ->where('verifikasi', false)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $eventMenunggu = Event::with(['kategori', 'panitia'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'eventTerbaru', 'panitiaMenunggu', 'eventMenunggu'));
    }
}
