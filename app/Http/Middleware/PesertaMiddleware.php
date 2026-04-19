<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PesertaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('aktor')->check() || auth()->guard('aktor')->user()->role !== 'peserta') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            return redirect()->route('login')->with('error', 'Akses khusus Peserta.');
        }
        return $next($request);
    }
}
