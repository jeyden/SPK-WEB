<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form permintaan reset password (masukkan email).
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses pengiriman link/token reset password ke email user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Laravel akan otomatis mengecek apakah email terdaftar,
        // membuat token acak, menyimpannya (hashed) ke tabel
        // password_reset_tokens, lalu mengirim email notifikasi.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Tautan reset password telah dikirim ke email Anda. Silakan periksa kotak masuk (atau folder spam).');
        }

        // Pesan generik agar tidak membocorkan apakah email terdaftar atau tidak
        return back()->withErrors([
            'email' => 'Kami tidak dapat mengirim tautan reset ke email tersebut.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan form reset password (setelah user klik link dari email).
     */
    public function edit(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Proses pembaruan password baru ke database.
     */
    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Validasi token + update password secara aman lewat Password Broker bawaan Laravel
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password berhasil diperbarui. Silakan masuk menggunakan password baru Anda.');
        }

        return back()->withErrors([
            'email' => 'Token reset tidak valid atau sudah kedaluwarsa. Silakan ulangi permintaan reset password.',
        ])->onlyInput('email');
    }
}