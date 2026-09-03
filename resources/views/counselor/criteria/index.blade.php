@extends('counselor.layouts.app')

@section('title', 'Kriteria Penilaian SAW')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">
    
    <!-- HEADER & ACTION BAR -->
    <div class="bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 dark:from-slate-900/80 dark:via-slate-900/60 dark:to-slate-950/90 p-5 sm:p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 backdrop-blur-xl shadow-xl shadow-slate-200/50 dark:shadow-none space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
        
        <!-- Teks Judul & Deskripsi -->
        <div class="space-y-1">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Kriteria Penilaian SAW (RIASEC)</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Rincian parameter kriteria asesmen minat dan bakat metode Simple Additive Weighting.</p>
        </div>
        
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABEL KRITERIA -->
    <div class="bg-white dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-soft">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider bg-slate-50 dark:bg-slate-950/40">
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-6">Dimensi RIASEC</th>
                        <th class="py-3.5 px-6">Keterangan / Karakteristik Profil</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-xs text-slate-600 dark:text-slate-300">
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors align-top">
                        <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">R</td>
                        <td class="py-4 px-6 font-bold text-slate-800 dark:text-white text-sm">Realistic (Faktual / Praktikal)</td>
                        <td class="py-4 px-6">Preferensi terhadap aktivitas fisik, mesin, alat, atau objek nyata</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors align-top">
                        <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">I</td>
                        <td class="py-4 px-6 font-bold text-slate-800 dark:text-white text-sm">Investigative (Intelektual / Analitis)</td>
                        <td class="py-4 px-6">Preferensi terhadap riset, investigasi, sains, dan pemecahan masalah analitis</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors align-top">
                        <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">A</td>
                        <td class="py-4 px-6 font-bold text-slate-800 dark:text-white text-sm">Artistic (Kreatif / Ekspresif)</td>
                        <td class="py-4 px-6">Preferensi terhadap seni, desain, sastra, dan kebebasan berekspresi</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors align-top">
                        <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">S</td>
                        <td class="py-4 px-6 font-bold text-slate-800 dark:text-white text-sm">Social (Sosial / Humanis)</td>
                        <td class="py-4 px-6">Preferensi terhadap interaksi sosial, mengajar, membantu, dan membimbing orang lain</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors align-top">
                        <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">E</td>
                        <td class="py-4 px-6 font-bold text-slate-800 dark:text-white text-sm">Enterprising (Kewirausahaan / Kepemimpinan)</td>
                        <td class="py-4 px-6">Preferensi terhadap kepemimpinan, persuasi, bisnis, dan manajemen target</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors align-top">
                        <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">C</td>
                        <td class="py-4 px-6 font-bold text-slate-800 dark:text-white text-sm">Conventional (Konvensional / Administratif)</td>
                        <td class="py-4 px-6">Preferensi terhadap keteraturan data, administrasi, ketelitian, dan sistematis</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection