<?php

namespace App\Http\Middleware;

use App\Models\RegistrationPeriod;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRegistrationPeriodOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        $period = RegistrationPeriod::current();

        if (!$period || !$period->isOpen()) {
            $message = (!$period || $period->isNotOpenedYet())
                ? 'Periode pendaftaran belum dibuka. Anda belum dapat mengisi data diri atau mengikuti asesmen.'
                : 'Periode pendaftaran telah ditutup. Pengisian/perubahan data tidak dapat dilakukan lagi.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 403);
            }

            return redirect()
                ->route('student.profile.index')
                ->with('period_locked', $message);
        }

        return $next($request);
    }
}