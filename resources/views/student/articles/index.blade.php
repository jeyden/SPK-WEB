@extends('student.layouts.app')

@section('title', 'Artikel & Panduan Kampus')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

    {{-- HEADER --}}
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-40 w-40 rounded-full bg-violet-500/10 blur-3xl"></div>

        <div class="relative flex flex-col gap-4 px-5 py-5 sm:px-6 md:flex-row md:items-center md:justify-between">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                        <i class="fa-solid fa-book-open text-xs"></i>
                    </span>

                    <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Pusat Informasi
                    </span>
                </div>

                <h1 class="mt-2 text-lg font-black tracking-tight text-slate-900 dark:text-white sm:text-xl">
                    Artikel & Panduan Pendidikan
                </h1>

                <p class="mt-1 max-w-2xl text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Informasi seputar jurusan, perkuliahan, dan persiapan karier.
                </p>
            </div>

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('student.articles.index') }}" class="flex w-full gap-2 md:w-auto">
                <div class="relative flex-1 md:w-56">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari artikel..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-8 pr-3 text-xs text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    Cari
                </button>
            </form>
        </div>
    </section>


    {{-- ARTICLE GRID --}}
    <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($articles as $article)

            {{-- SELURUH CARD DAPAT DIKLIK --}}
            <a
                href="{{ route('student.articles.show', $article->slug) }}"
                class="group relative flex min-h-[250px] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-500/5 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-500/40"
            >

                {{-- Accent --}}
                <div class="pointer-events-none absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-indigo-500 via-violet-500 to-fuchsia-500 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

                <div class="flex h-full flex-1 flex-col p-4 sm:p-5">

                    {{-- META --}}
                    <div class="flex items-center justify-between gap-2">
                        <span class="flex items-center gap-1.5 text-[9px] font-semibold text-slate-400 dark:text-slate-500">
                            <i class="fa-regular fa-calendar"></i>
                            {{ optional($article->published_at)->format('d M Y') }}
                        </span>

                        <span class="max-w-[120px] truncate rounded-md bg-indigo-50 px-2 py-1 text-[8px] font-bold text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                            {{ optional($article->author)->name ?? 'Admin Guru BK' }}
                        </span>
                    </div>


                    {{-- ICON --}}
                    <div class="mt-5 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition-all duration-300 group-hover:bg-indigo-600 group-hover:text-white group-hover:shadow-md group-hover:shadow-indigo-500/20 dark:bg-slate-800 dark:text-slate-400 dark:group-hover:bg-indigo-500">
                        <i class="fa-solid fa-file-lines text-sm"></i>
                    </div>


                    {{-- TITLE --}}
                    <h2 class="mt-4 line-clamp-2 text-sm font-black leading-snug text-slate-900 transition-colors group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400">
                        {{ $article->title }}
                    </h2>


                    {{-- EXCERPT --}}
                    <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                        {{ Str::limit(strip_tags($article->content), 105) }}
                    </p>


                    {{-- BOTTOM --}}
                    <div class="mt-auto flex items-center justify-between pt-5">

                        <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Artikel Pendidikan
                        </span>

                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold text-indigo-600 transition-all duration-200 group-hover:gap-2.5 dark:text-indigo-400">
                            Baca
                            <i class="fa-solid fa-arrow-right text-[8px] transition-transform duration-200 group-hover:translate-x-0.5"></i>
                        </span>

                    </div>

                </div>

            </a>

        @empty

            {{-- EMPTY STATE --}}
            <div class="col-span-full flex min-h-[180px] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-5 dark:border-slate-700 dark:bg-slate-900">

                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                        <i class="fa-solid fa-newspaper text-lg"></i>
                    </div>

                    <p class="mt-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
                        Belum ada artikel yang dipublikasikan.
                    </p>
                </div>

            </div>

        @endforelse

    </section>


    {{-- PAGINATION --}}
    @if($articles->hasPages())
        <div class="pt-1">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection