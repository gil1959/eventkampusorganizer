<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PanitiaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->guard('aktor')->user();
        if (!$user || $user->role !== 'panitia') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->route('login')->with('error', 'Akses khusus Panitia Event.');
        }
        if (!$user->verifikasi) {
            auth()->guard('aktor')->logout();
            return redirect()->route('login')->with('error', 'Akun Panitia Anda belum diverifikasi oleh Admin.');
        }
        return $next($request);
    }
}
