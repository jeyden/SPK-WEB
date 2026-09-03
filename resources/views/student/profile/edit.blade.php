@extends('student.layouts.app')

@section('title', 'Edit Profil & Data Diri Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Header Halaman -->
    <div class="bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Data Diri</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui data diri dan informasi akademik Anda.</p>
        </div>
        <a href="{{ route('student.profile.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition-all">
            Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-sm flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Edit Profil -->
    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <!-- Bagian Foto Profil -->
        <div class="space-y-1.5 pb-5 border-b border-slate-200 dark:border-slate-800">
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-2">Foto Profil Siswa</label>
            <div class="flex items-center gap-4">
                <!-- Preview Foto -->
                <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex-shrink-0 flex items-center justify-center">
                    @php
                        $avatarPath = $student->avatar ?? ($student->user->avatar ?? null);
                    @endphp

                    @if(!empty($avatarPath))
                        <img src="{{ asset('storage/' . $avatarPath) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        <span class="text-slate-500 dark:text-slate-400 text-base font-bold">
                            {{ strtoupper(substr($student->user->name ?? ($user->name ?? 'S'), 0, 1)) }}
                        </span>
                    @endif
                </div>
                
                <!-- Input File -->
                <div class="flex-1">
                    <input type="file" name="avatar" accept="image/png, image/jpeg, image/jpg"
                        class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition-all">
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Format yang diizinkan: JPG, JPEG, PNG (Maks. 2MB)</span>
                </div>
            </div>
            @error('avatar') <span class="text-[10px] text-rose-600 dark:text-rose-400 block mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Nama Lengkap -->
            <div class="space-y-1.5 md:col-span-2">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $student->user->name ?? '') }}" placeholder="Masukkan nama lengkap Anda" required
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500">
                @error('name') <span class="text-[10px] text-rose-600 dark:text-rose-400 block">{{ $message }}</span> @enderror
            </div>

            <!-- NISN -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                    NISN <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" placeholder="Contoh: 0081234567" required
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500">
                @error('nisn') <span class="text-[10px] text-rose-600 dark:text-rose-400 block">{{ $message }}</span> @enderror
            </div>

            <!-- Kelas -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Kelas <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="class" value="{{ old('class', $student->class) }}" placeholder="Contoh: XII IPA 1" required
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500">
                @error('class') <span class="text-[10px] text-rose-600 dark:text-rose-400 block">{{ $message }}</span> @enderror
            </div>

            <!-- Asal Jurusan Sekolah -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Asal Jurusan Sekolah <span class="text-rose-500">*</span>
                </label>
                <select name="high_school_major" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Jurusan Sekolah --</option>
                    <option value="IPA" {{ old('high_school_major', $student->high_school_major) == 'IPA' ? 'selected' : '' }}>IPA</option>
                    <option value="IPS" {{ old('high_school_major', $student->high_school_major) == 'IPS' ? 'selected' : '' }}>IPS</option>
                </select>
                @error('high_school_major') <span class="text-[10px] text-rose-600 dark:text-rose-400 block">{{ $message }}</span> @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Jenis Kelamin <span class="text-rose-500">*</span>
                </label>
                <select name="gender" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender') <span class="text-[10px] text-rose-600 dark:text-rose-400 block">{{ $message }}</span> @enderror
            </div>

            <!-- No Telepon -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" placeholder="Contoh: 081234567890"
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500">
                @error('phone') <span class="text-[10px] text-rose-600 dark:text-rose-400 block">{{ $message }}</span> @enderror
            </div>

            <!-- Alamat -->
            <div class="space-y-1.5 md:col-span-2">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Alamat Lengkap</label>
                <textarea name="address" rows="3" placeholder="Masukkan alamat domisili saat ini..."
                    class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-indigo-500">{{ old('address', $student->address) }}</textarea>
                @error('address') <span class="text-[10px] text-rose-600 dark:text-rose-400 block">{{ $message }}</span> @enderror
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-end pt-4 border-t border-slate-200 dark:border-slate-800">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan Profil</span>
            </button>
        </div>
    </form>
</div>
@endsection