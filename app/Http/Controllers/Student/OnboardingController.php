<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        // Cek status kelengkapan data diri dan tes RIASEC
        $hasProfile = $student && $student->profile_completed;
        $hasRiasec = $student && $student->riasecScore()->exists();

        // Jika keduanya sudah lengkap, langsung lempar ke dashboard utama
        if ($hasProfile && $hasRiasec) {
            return redirect()->route('student.dashboard');
        }

        return view('student.onboarding.index', compact('student', 'hasProfile', 'hasRiasec'));
    }
}