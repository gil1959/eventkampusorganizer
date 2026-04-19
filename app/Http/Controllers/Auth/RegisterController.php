<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aktor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        if (Auth::guard('aktor')->check()) {
            return redirect()->route('peserta.home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'npm'      => 'required|string|max:20|unique:aktors,npm_nip',
            'email'    => 'required|email|unique:aktors,email',
            'jurusan'  => 'nullable|string|max:100',
            'no_hp'    => 'nullable|string|max:20',
            'role'     => 'required|in:peserta,panitia',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'npm.required'       => 'NPM wajib diisi.',
            'npm.unique'         => 'NPM sudah terdaftar.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'role.required'      => 'Pilih role akun.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $aktor = Aktor::create([
            'nama'      => $request->nama,
            'npm_nip'   => $request->npm,
            'email'     => $request->email,
            'jurusan'   => $request->jurusan,
            'no_hp'     => $request->no_hp,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'verifikasi' => $request->role === 'peserta' ? true : false,
            'is_active' => true,
        ]);

        Auth::guard('aktor')->login($aktor);

        if ($aktor->role === 'panitia') {
            Auth::guard('aktor')->logout();
            return redirect()->route('login')->with('info', 'Pendaftaran Panitia berhasil! Tunggu verifikasi dari Admin sebelum dapat login.');
        }

        return redirect()->route('peserta.home')->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }
}
