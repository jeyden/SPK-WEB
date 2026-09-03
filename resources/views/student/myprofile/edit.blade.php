@extends('student.layouts.app') 

@section('title', 'Profil Saya - Sistem Pendukung Keputusan Pemilihan Jurusan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">
    
    <!-- Header -->
    <div class="mb-4 sm:mb-6 pb-3 border-b border-slate-200 dark:border-slate-800">
        <h2 class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Profil Saya</h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kelola informasi akun dan keamanan Anda</p>
    </div>

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-300 text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-check flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Notifikasi Error / Validasi -->
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-300 text-xs flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
            <span>Terjadi kesalahan input, periksa kembali form di bawah.</span>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')

        <!-- Preview & Upload Foto Profil -->
        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="relative shrink-0">
                @php
                    $currentAvatar = null;
                    $authUser = auth()->user();
                    
                    // Cek avatar menggunakan relasi student() yang sudah ada di Model User
                    if ($authUser->role === 'student' && $authUser->student && !empty($authUser->student->avatar)) {
                        $currentAvatar = asset('storage/' . $authUser->student->avatar);
                    } elseif (!empty($authUser->avatar)) {
                        $currentAvatar = asset('storage/' . $authUser->avatar);
                    }
                @endphp

                @if($currentAvatar)
                    <img src="{{ $currentAvatar }}" alt="Avatar" class="w-16 h-16 rounded-2xl object-cover shadow-md border border-slate-200 dark:border-slate-700">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        {{ strtoupper(substr($authUser->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Foto Profil</label>
                <input type="file" name="avatar" accept="image/png, image/jpeg, image/jpg"
                    class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition-all">
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                @error('avatar')
                    <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

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

        <!-- Email (Terkunci / Read-only) -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Email <span class="text-[10px] text-slate-400 font-normal lowercase">(tidak dapat diubah)</span></label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </span>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" disabled readonly
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-500 dark:text-slate-400 text-sm cursor-not-allowed select-none shadow-sm">
            </div>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Hubungi administrator jika ingin melakukan perubahan email.</p>
        </div>

        <hr class="border-slate-200 dark:border-slate-800 my-4">

        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Kosongkan bagian kata sandi di bawah jika Anda tidak ingin mengubahnya.</p>

        <!-- Password Saat Ini -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Kata Sandi Saat Ini</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input type="password" name="current_password" id="current_password"
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                    placeholder="••••••••">
            </div>
            @error('current_password')
                <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Baru -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Kata Sandi Baru</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-key text-sm"></i>
                </span>
                <input type="password" name="password" id="password"
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                    placeholder="••••••••">
            </div>
            @error('password')
                <span class="text-rose-500 dark:text-rose-400 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Konfirmasi Password Baru -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi Baru</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-check-double text-sm"></i>
                </span>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                    placeholder="••••••••">
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="pt-2">
            <button type="submit" 
                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>

</div>
@endsection