<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('events')->orderBy('nama_kategori')->paginate(15);
        return view('admin.kategoris.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori',
            'deskripsi'     => 'nullable|string',
            'icon'          => 'nullable|string|max:50',
        ]);

        Kategori::create($request->only(['nama_kategori', 'deskripsi', 'icon']));
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $kategori->id_kategori . ',id_kategori',
            'deskripsi'     => 'nullable|string',
            'icon'          => 'nullable|string|max:50',
        ]);

        $kategori->update($request->only(['nama_kategori', 'deskripsi', 'icon']));
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->events()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki event.');
        }
        $kategori->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
