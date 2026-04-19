<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    private function getAuthPanitia()
    {
        return Auth::guard('aktor')->user();
    }

    public function index()
    {
        $events = Event::with('kategori')
            ->where('id_panitia', $this->getAuthPanitia()->id_user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('panitia.events.index', compact('events'));
    }

    public function create()
    {
        $kategoris = Kategori::where('is_active', true)->get();
        return view('panitia.events.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_event'  => 'required|string|max:150',
            'deskripsi'   => 'required|string',
            'tanggal'     => 'required|date|after_or_equal:today',
            'jam'         => 'nullable|date_format:H:i',
            'lokasi'      => 'nullable|string|max:200',
            'kuota'       => 'required|integer|min:1',
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'narasumber'  => 'nullable|string|max:200',
            'biaya'       => 'nullable|numeric|min:0',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Simpan data event dengan biaya yang jelas (cast ke integer)
        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Event::create([
            'nama_event'  => $request->nama_event,
            'deskripsi'   => $request->deskripsi,
            'tanggal'     => $request->tanggal,
            'jam'         => $request->jam,
            'lokasi'      => $request->lokasi,
            'kuota'       => (int) $request->kuota,
            'biaya'       => (int) ($request->biaya ?? 0),
            'narasumber'  => $request->narasumber,
            'id_kategori' => $request->id_kategori,
            'id_panitia'  => $this->getAuthPanitia()->id_user,
            'status'      => 'menunggu',
            'poster'      => $posterPath,
        ]);

        return redirect()->route('panitia.events.index')
            ->with('success', 'Event berhasil diajukan dan menunggu persetujuan Admin.');
    }

    public function show(Event $event)
    {
        $this->authorize_event($event);
        $event->load(['kategori', 'pendaftarans.peserta']);
        return view('panitia.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorize_event($event);
        if (!in_array($event->status, ['draft', 'ditolak'])) {
            return redirect()->back()->with('error', 'Event yang sudah diajukan tidak dapat diedit. Hubungi Admin.');
        }
        $kategoris = Kategori::where('is_active', true)->get();
        return view('panitia.events.edit', compact('event', 'kategoris'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize_event($event);
        $request->validate([
            'nama_event'  => 'required|string|max:150',
            'deskripsi'   => 'required|string',
            'tanggal'     => 'required|date',
            'kuota'       => 'required|integer|min:1',
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['poster', '_token', '_method']);
        $data['status'] = 'menunggu';

        if ($request->hasFile('poster')) {
            if ($event->poster) Storage::disk('public')->delete($event->poster);
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);
        return redirect()->route('panitia.events.index')->with('success', 'Event berhasil diperbarui dan diajukan ulang.');
    }

    public function destroy(Event $event)
    {
        $this->authorize_event($event);

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
        
        $event->delete();

        return redirect()->route('panitia.events.index')
            ->with('success', "Event \"{$namaEvent}\" berhasil dihapus.");
    }

    private function authorize_event(Event $event)
    {
        if ($event->id_panitia !== $this->getAuthPanitia()->id_user) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
