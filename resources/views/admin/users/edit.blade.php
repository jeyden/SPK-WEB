@extends('admin.layouts.app') 

@section('title', 'Edit Pengguna - Sistem Pendukung Keputusan Pemilihan Jurusan')

@section('content')
<div class="w-full max-w-2xl mx-auto px-3 sm:px-6 py-4">
    
    <!-- Header -->
    <div class="mb-4 sm:mb-6 pb-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Edit Pengguna</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi akun dan hak akses pengguna ini</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Notifikasi Error / Validasi -->
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-300 text-xs flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
            <span>Terjadi kesalahan input, periksa kembali form di bawah.</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Nama Lengkap -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-user text-sm"></i>
                </span>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
            </div>
            @error('name')
                <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </span>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
            </div>
            @error('email')
                <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Role / Hak Akses -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Role / Hak Akses</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </span>
                <select name="role" required
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="counselor" {{ old('role', $user->role) === 'counselor' ? 'selected' : '' }}>Guru BK (Counselor)</option>
                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Siswa (Student)</option>
                </select>
            </div>
            @error('role')
                <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <hr class="border-slate-200 dark:border-slate-800 my-4">

        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Kosongkan bagian kata sandi di bawah jika Anda tidak ingin mengubah atau mereset sandi pengguna ini.</p>

        <!-- Password Baru (Opsional) -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Kata Sandi Baru (Opsional)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-key text-sm"></i>
                </span>
                <input type="password" name="password" id="password"
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                    placeholder="Minimal 6 karakter">
            </div>
            @error('password')
                <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tombol Simpan -->
        <div class="pt-2 flex items-center gap-3">
            <button type="submit" 
                class="flex-1 py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
            <a href="{{ route('admin.users.index') }}" 
                class="py-3 px-4 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-all text-sm text-center">
                Batal
            </a>
        </div>
    </form>

</div>
@endsection