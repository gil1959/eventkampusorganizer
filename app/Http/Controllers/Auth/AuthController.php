<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aktor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('aktor')->check()) {
            return $this->redirectByRole(Auth::guard('aktor')->user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        $aktor = Aktor::where('email', $request->email)->first();

        if (!$aktor) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        if (!Hash::check($request->password, $aktor->password)) {
            return back()->withErrors(['password' => 'Password tidak sesuai.'])->withInput();
        }

        if (!$aktor->is_active) {
            return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi Admin.'])->withInput();
        }

        Auth::guard('aktor')->login($aktor, $request->has('remember'));

        $request->session()->regenerate();

        return $this->redirectByRole($aktor->role);
    }

    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin'   => redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Admin!'),
            'panitia' => redirect()->route('panitia.dashboard')->with('success', 'Selamat datang, Panitia!'),
            'peserta' => redirect()->route('peserta.home')->with('success', 'Selamat datang!'),
            default   => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        Auth::guard('aktor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
