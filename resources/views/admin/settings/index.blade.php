@extends('admin.layouts.app')

@section('title', 'Konfigurasi Sistem')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Konfigurasi Sistem</h2>
            <p class="text-xs text-slate-400 mt-1">Atur parameter dasar, informasi aplikasi, dan pengaturan global sistem.</p>
        </div>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs">
        {{ session('success') }}
    </div>
    @endif

    <!-- Form Pengaturan -->
    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 shadow-lg backdrop-blur-xl">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            @php
                // Mapping key pengaturan dasar jika data di database masih kosong
                $settingsMap = $settings->pluck('value', 'key')->toArray();
                
                $defaultSettings = [
                    'app_name' => $settingsMap['app_name'] ?? 'Sistem Pendukung Keputusan Pemilihan Kampus',
                    'school_name' => $settingsMap['school_name'] ?? 'SMA Negeri 1 Contoh',
                    'maintenance_mode' => $settingsMap['maintenance_mode'] ?? 'off',
                ];
            @endphp

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nama Aplikasi / Sistem</label>
                <input type="text" name="app_name" value="{{ old('app_name', $defaultSettings['app_name']) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nama Instansi / Sekolah</label>
                <input type="text" name="school_name" value="{{ old('school_name', $defaultSettings['school_name']) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Mode Pemeliharaan (Maintenance)</label>
                <select name="maintenance_mode" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
                    <option value="off" {{ $defaultSettings['maintenance_mode'] == 'off' ? 'selected' : '' }}>Nonaktif (Sistem Normal)</option>
                    <option value="on" {{ $defaultSettings['maintenance_mode'] == 'on' ? 'selected' : '' }}>Aktif (Maintenance)</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/30 transition-all">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection