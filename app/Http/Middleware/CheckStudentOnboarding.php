<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentOnboarding
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user && $user->role === 'student') {
            // Izinkan siswa mengakses menu profil atau artikel kapan saja selama proses onboarding
            if ($request->routeIs('student.profile.*') || $request->routeIs('student.articles.*')) {
                return $next($request);
            }

            $student = $user->student;

            $hasProfile = $student && $student->profile_completed;
            $hasRiasec = $student && $student->riasecScore;

            // Jika profil belum lengkap, paksa ke onboarding/profil
            if (!$hasProfile) {
                if (!$request->routeIs('student.onboarding')) {
                    return redirect()->route('student.onboarding')
                        ->with('error', 'Silakan lengkapi Data Diri Anda terlebih dahulu.');
                }
            } 
            // Jika profil sudah lengkap, tapi RIASEC belum, cegah akses dashboard & rekomendasi
            elseif (!$hasRiasec) {
                if (!$request->routeIs('student.onboarding') && !$request->routeIs('student.riasec.*')) {
                    return redirect()->route('student.onboarding')
                        ->with('error', 'Silakan selesaikan Asesmen RIASEC terlebih dahulu.');
                }
            }
        }

        return $next($request);
    }
}