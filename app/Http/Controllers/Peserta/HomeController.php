<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['kategori', 'panitia'])->where('status', 'aktif');

        if ($request->kategori) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->search) {
            $query->where('nama_event', 'like', '%' . $request->search . '%');
        }

        $events    = $query->orderBy('tanggal', 'asc')->paginate(9);
        $kategoris = Kategori::where('is_active', true)->get();

        return view('peserta.home', compact('events', 'kategoris'));
    }
}
