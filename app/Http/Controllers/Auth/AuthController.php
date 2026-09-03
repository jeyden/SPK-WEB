<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login dengan validasi dan redirect berbasis role.
     */
    public function store(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cek proses login (attempt)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect sesuai role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Berhasil masuk sebagai Admin.');
            } 
            
            if ($user->role === 'counselor') {
                return redirect()->intended(route('counselor.dashboard'))
                    ->with('success', 'Berhasil masuk sebagai Guru BK.');
            } 
            
            if ($user->role === 'student') {
                $student = $user->student;
                
                // Cek apakah data diri (profil) dan asesmen RIASEC sudah lengkap
                $hasProfile = $student && $student->profile_completed;
                $hasRiasec = $student && $student->riasecScore;

                // Jika profil atau asesmen belum lengkap, arahkan ke onboarding/edit profil
                if (!$hasProfile || !$hasRiasec) {
                    return redirect()->route('student.onboarding')
                        ->with('error', 'Silakan lengkapi data diri dan asesmen terlebih dahulu.');
                }

                // Jika sudah lengkap, arahkan ke dashboard siswa
                return redirect()->intended(route('student.dashboard'))
                    ->with('success', 'Berhasil masuk sebagai Siswa.');
            }

            // Fallback jika role tidak terdaftar
            Auth::logout();
            return back()->withErrors([
                'email' => 'Role pengguna tidak valid atau tidak ditemukan.',
            ])->onlyInput('email');
        }

        // Jika gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function createRegister(Request $request)
    {
        // Amankan halaman register: Jika ada sesi yang nyangkut, paksa logout dulu
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.register'); 
    }

    /**
     * Proses penyimpanan registrasi user baru.
     */
    public function storeRegister(Request $request)
    {
        // Pastikan tidak ada sesi aktif yang menempel saat submit registrasi
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Otomatis sebagai siswa
        ]);

        return redirect('/login')->with('success', 'Akun berhasil didaftarkan. Silakan masuk menggunakan akun Anda.');
    }

    /**
     * Proses logout.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah keluar dari sistem.');
    }
}