<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->guard('aktor')->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== $role) {
            // Redirect sesuai role masing-masing
            return match ($user->role) {
                'admin'   => redirect()->route('admin.dashboard')->with('error', 'Anda tidak punya akses ke halaman tersebut.'),
                'panitia' => redirect()->route('panitia.dashboard')->with('error', 'Anda tidak punya akses ke halaman tersebut.'),
                'peserta' => redirect()->route('peserta.home')->with('error', 'Anda tidak punya akses ke halaman tersebut.'),
                default   => redirect('/'),
            };
        }

        // Cek verifikasi untuk panitia
        if ($role === 'panitia' && !$user->verifikasi) {
            auth()->guard('aktor')->logout();
            return redirect()->route('login')->with('error', 'Akun panitia Anda belum diverifikasi oleh Admin. Mohon tunggu konfirmasi.');
        }

        return $next($request);
    }
}
