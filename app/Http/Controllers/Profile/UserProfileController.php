<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Student; // Pastikan model Student di-import

class UserProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return view('admin.myprofile.edit', compact('user'));
        } elseif ($user->role === 'counselor') {
            return view('counselor.myprofile.edit', compact('user'));
        } elseif ($user->role === 'student') {
            return view('student.myprofile.edit', compact('user'));
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            // Email tidak divalidasi 'required' agar aman saat input di-disable
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->nip = $request->nip; 

        // Logika Upload Avatar Berdasarkan Role
        if ($request->hasFile('avatar')) {
            if ($user->role === 'student') {
                // Berikan nilai default untuk semua kolom yang wajib (NOT NULL) di tabel students
                $student = Student::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nisn' => '0000000000', 
                        'class' => 'Belum Diisi',
                        'high_school_major' => 'Belum Diisi' // Wajib ditambahkan karena sifatnya NOT NULL di database
                    ]
                );

                // Hapus avatar lama di storage jika ada
                if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
                    Storage::disk('public')->delete($student->avatar);
                }

                $path = $request->file('avatar')->store('student-avatars', 'public');
                $student->avatar = $path; 
                $student->save();
            } else {
                // Untuk Admin & Counselor simpan di tabel users
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('profiles', 'public');
                $user->avatar = $path;
            }
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}