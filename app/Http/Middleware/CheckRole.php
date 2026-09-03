<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan pengguna sudah login
        if (! $request->user()) {
            abort(401, 'Unauthorized.');
        }

        // Periksa apakah role pengguna saat ini ada di dalam daftar roles yang diizinkan
        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized action. You do not have permission to access this page.');
        }

        return $next($request);
    }
}