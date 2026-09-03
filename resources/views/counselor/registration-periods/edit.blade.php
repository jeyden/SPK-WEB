@extends('counselor.layouts.app')

@section('title', 'Edit Periode Pendaftaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 pb-16">

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 px-5 py-5 sm:px-6">
        <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">Periode Pendaftaran</span>
        <h1 class="mt-2 text-lg font-black tracking-tight text-slate-900 dark:text-white sm:text-xl">Edit Periode</h1>
        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
            Status saat ini: <span class="font-semibold">{{ $period->statusLabel() }}</span>
        </p>
    </section>

    <div class="bg-white dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm px-5 py-6 sm:px-8">
        <form action="{{ route('counselor.registration-periods.update', $period) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">Tahun Akademik</label>
                <select name="academic_year"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="" disabled>Pilih tahun akademik</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year }}" {{ old('academic_year', $period->academic_year) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                @error('academic_year') <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $period->start_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    @error('start_date') <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $period->end_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    @error('end_date') <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">Keterangan (opsional)</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">{{ old('description', $period->description) }}</textarea>
                @error('description') <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('counselor.registration-periods.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-sm shadow-indigo-600/20 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection