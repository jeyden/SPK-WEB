<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\RegistrationPeriod;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        return view('student.dashboard', compact('student'));
    }

    public function index()
    {
        $user = Auth::user();
        $student = Student::firstOrNew(['user_id' => $user->id]);

        // Ambil status periode pendaftaran saat ini untuk ditampilkan di halaman Data Diri
        $period = RegistrationPeriod::current();

        return view('student.profile.index', compact('student', 'period'));
    }

    public function edit()
    {
        $user = Auth::user();
        $student = Student::firstOrNew(['user_id' => $user->id]);

        // Jaga-jaga: middleware 'registration.open' sudah menahan akses,
        // tapi cek ulang di sini agar backend tidak bergantung pada satu lapis saja.
        $period = RegistrationPeriod::current();
        if (!$period || !$period->isOpen()) {
            return redirect()->route('student.profile.index')
                ->with('period_locked', 'Pengisian data diri tidak dapat dilakukan saat ini.');
        }

        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi backend: pastikan periode masih dibuka saat data benar-benar disimpan.
        // Tahun akademik TIDAK lagi diinput manual oleh siswa — otomatis mengikuti
        // tahun akademik periode pendaftaran yang sedang aktif (dibuka) saat ini.
        $period = RegistrationPeriod::current();
        if (!$period || !$period->isOpen()) {
            return redirect()->route('student.profile.index')
                ->with('period_locked', 'Periode pendaftaran tidak dalam status dibuka. Perubahan data tidak dapat disimpan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:students,nisn,' . optional($user->student)->id,
            'class' => 'required|string|max:50',
            'high_school_major' => 'required|in:IPA,IPS',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 1. Update Nama di tabel users
        $user->update([
            'name' => $request->name,
        ]);

        // 2. Ambil data student yang sudah ada
        $student = Student::where('user_id', $user->id)->first();
        $avatarPath = $student ? $student->avatar : null;

        // 3. Proses Upload Avatar Baru
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada di disk public
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            // Simpan file baru dan pastikan path-nya bersih
            $avatarPath = $request->file('avatar')->store('student-avatars', 'public');
        }

        // 4. Update atau Buat data Student (tahun akademik & periode mengikuti periode aktif)
        $student = Student::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nisn' => $request->nisn,
                'class' => $request->class,
                'academic_year' => $period->academic_year,
                'high_school_major' => $request->high_school_major,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => $avatarPath,
                'profile_completed' => true,
                'registration_period_id' => $period->id,
            ]
        );

        // Cek apakah siswa sudah memiliki skor RIASEC (apakah masih dalam tahap onboarding asesmen)
        $hasRiasec = $student && $student->riasecScore;

        if (!$hasRiasec) {
            return redirect()->route('student.profile.index')
                ->with('success_onboarding', 'Profil berhasil disimpan! Silakan lanjut mengisi Asesmen RIASEC.');
        }

        // Kembali ke halaman index profil dengan pemberitahuan sukses biasa
        return redirect()->route('student.profile.index')
            ->with('success', 'Perubahan data diri berhasil disimpan!');
    }
}