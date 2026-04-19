<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Aktor::where('role', '!=', 'admin');

        if ($request->role) {
            $query->where('role', $request->role);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('npm_nip', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->status === 'belum_verifikasi') {
            $query->where('verifikasi', false);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'npm_nip'  => 'required|string|max:20|unique:aktors,npm_nip',
            'email'    => 'required|email|unique:aktors,email',
            'role'     => 'required|in:admin,panitia,peserta',
            'jurusan'  => 'nullable|string|max:100',
            'no_hp'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Aktor::create([
            'nama'      => $request->nama,
            'npm_nip'   => $request->npm_nip,
            'email'     => $request->email,
            'role'      => $request->role,
            'jurusan'   => $request->jurusan,
            'no_hp'     => $request->no_hp,
            'password'  => Hash::make($request->password),
            'verifikasi' => true,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Aktor $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, Aktor $user)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'npm_nip' => 'required|string|max:20|unique:aktors,npm_nip,' . $user->id_user . ',id_user',
            'email'   => 'required|email|unique:aktors,email,' . $user->id_user . ',id_user',
            'role'    => 'required|in:admin,panitia,peserta',
            'jurusan' => 'nullable|string|max:100',
            'no_hp'   => 'nullable|string|max:20',
        ]);

        $data = $request->only(['nama', 'npm_nip', 'email', 'role', 'jurusan', 'no_hp']);
        if ($request->password) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function verifikasi(Aktor $user)
    {
        $user->update(['verifikasi' => !$user->verifikasi]);
        $msg = $user->verifikasi ? 'Panitia berhasil diverifikasi.' : 'Verifikasi panitia dicabut.';
        return redirect()->back()->with('success', $msg);
    }

    public function toggleActive(Aktor $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $msg = $user->is_active ? 'Akun diaktifkan.' : 'Akun dinonaktifkan.';
        return redirect()->back()->with('success', $msg);
    }

    public function destroy(Aktor $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
