<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentSubjectScore;
use App\Models\StudentAssessment;
use App\Models\StudentRiasecScore;
// TODO: ganti dengan namespace model hasil perhitungan SAW yang sebenarnya.
// Contoh nama yang umum dipakai: CalculationResult, SawResult, StudentRanking.
// Model inilah yang menghasilkan koleksi $rankings di controller kalkulasi konselor.
use App\Models\CalculationResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,counselor,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,counselor,student',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::transaction(function () use ($user) {
            // Cek apakah user yang dihapus memiliki relasi data sebagai Student
            $student = Student::where('user_id', $user->id)->first();

            if ($student) {
                // 1. Hapus nilai mata pelajaran siswa
                StudentSubjectScore::where('student_id', $student->id)->delete();

                // 2. Hapus data penilaian / asesmen siswa
                StudentAssessment::where('student_id', $student->id)->delete();

                // 3. Hapus skor RIASEC siswa (jika ada)
                if (class_exists(StudentRiasecScore::class)) {
                    StudentRiasecScore::where('student_id', $student->id)->delete();
                }

                // 4. BARU: Hapus hasil perhitungan/perangkingan SAW milik siswa ini.
                //    Ini yang tadinya hilang -> menyebabkan baris ranking "yatim"
                //    (student_id menunjuk ke student yang sudah tidak ada), lalu
                //    memicu error "Attempt to read property 'user' on null" di
                //    views/counselor/calculation/index.blade.php.
                if (class_exists(CalculationResult::class)) {
                    CalculationResult::where('student_id', $student->id)->delete();
                }

                // 5. Hapus data profil siswa itu sendiri
                $student->delete();
            }

            // 6. Terakhir, hapus akun utamanya di tabel users
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Pengguna beserta seluruh data nilai, asesmen, dan hasil perhitungan terkait berhasil dihapus.');
    }
}