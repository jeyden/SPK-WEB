@extends('student.layouts.app')

@section('title', $article->title)

@section('content')

<div class="max-w-5xl mx-auto space-y-6 pb-16 px-0 sm:px-0">

    {{-- BACK --}}
    <div>
        <a
            href="{{ route('student.articles.index') }}"
            class="group inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-bold text-slate-600 shadow-sm transition-all duration-200 hover:-translate-x-0.5 hover:border-indigo-200 hover:text-indigo-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:border-indigo-500/30 dark:hover:text-indigo-400"
        >
            <i class="fa-solid fa-arrow-left text-[10px] transition-transform group-hover:-translate-x-0.5"></i>
            Kembali ke Artikel
        </a>
    </div>


    {{-- ARTICLE --}}
    <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        {{-- ARTICLE HEADER --}}
        <header class="relative overflow-hidden border-b border-slate-100 px-5 py-8 sm:px-8 sm:py-10 lg:px-12 dark:border-slate-800">

            {{-- Decorative Background --}}
            <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-indigo-500/[0.07] blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-28 left-1/3 h-64 w-64 rounded-full bg-violet-500/[0.06] blur-3xl"></div>

            {{-- Decorative Grid --}}
            <div
                class="pointer-events-none absolute inset-0 opacity-[0.035] dark:opacity-[0.025]"
                style="background-image: linear-gradient(#64748b 1px, transparent 1px), linear-gradient(90deg, #64748b 1px, transparent 1px); background-size: 32px 32px;"
            ></div>

            <div class="relative">

                {{-- CATEGORY / LABEL --}}
                <div class="mb-5 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white shadow-sm shadow-indigo-500/20">
                        <i class="fa-solid fa-book-open text-xs"></i>
                    </span>

                    <span class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Artikel & Panduan
                    </span>
                </div>


                {{-- META --}}
                <div class="mb-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-[10px] font-semibold text-slate-400 dark:text-slate-500">

                    <span class="inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar"></i>
                        {{ optional($article->published_at)->format('d M Y') }}
                    </span>

                    <span class="hidden h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-700 sm:block"></span>

                    <span class="inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-user"></i>
                        {{ optional($article->author)->name ?? 'Admin Guru BK' }}
                    </span>

                </div>


                {{-- TITLE --}}
                <h1 class="max-w-4xl text-2xl font-black leading-[1.2] tracking-tight text-slate-900 dark:text-white sm:text-3xl lg:text-4xl">
                    {{ $article->title }}
                </h1>

            </div>
        </header>


        {{-- ARTICLE BODY --}}
        <div class="px-5 py-7 sm:px-8 sm:py-9 lg:px-12 lg:py-10">

            <div class="article-content max-w-none text-sm leading-8 text-slate-600 dark:text-slate-300 sm:text-[15px]">

                {!! nl2br(e($article->content)) !!}

            </div>

        </div>

    </article>


    {{-- RELATED ARTICLES --}}
    @if($relatedArticles->count() > 0)

        <section class="space-y-4">

            {{-- SECTION HEADER --}}
            <div class="flex items-end justify-between px-1">

                <div>
                    <span class="text-[9px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">
                        Bacaan Lanjutan
                    </span>

                    <h2 class="mt-1 text-base font-black text-slate-900 dark:text-white sm:text-lg">
                        Artikel Terkait
                    </h2>
                </div>

                <a
                    href="{{ route('student.articles.index') }}"
                    class="hidden items-center gap-1.5 text-[10px] font-bold text-slate-400 transition-colors hover:text-indigo-600 dark:text-slate-500 dark:hover:text-indigo-400 sm:inline-flex"
                >
                    Lihat semua
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>

            </div>


            {{-- RELATED GRID --}}
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

                @foreach($relatedArticles as $related)

                    <a
                        href="{{ route('student.articles.show', $related->slug) }}"
                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-indigo-500/30"
                    >

                        {{-- Accent --}}
                        <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-indigo-500 to-violet-500 opacity-0 transition-opacity group-hover:opacity-100"></div>


                        <div class="flex items-start justify-between gap-3">

                            {{-- Icon --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-600 group-hover:text-white dark:bg-indigo-500/10 dark:text-indigo-400 dark:group-hover:bg-indigo-500 dark:group-hover:text-white">
                                <i class="fa-solid fa-file-lines text-xs"></i>
                            </div>

                            {{-- Arrow --}}
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-all group-hover:bg-indigo-600 group-hover:text-white dark:bg-slate-800 dark:text-slate-500 dark:group-hover:bg-indigo-500 dark:group-hover:text-white">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                            </div>

                        </div>


                        {{-- Title --}}
                        <h3 class="mt-4 line-clamp-2 text-xs font-extrabold leading-relaxed text-slate-800 transition-colors group-hover:text-indigo-600 dark:text-slate-100 dark:group-hover:text-indigo-400 sm:text-sm">
                            {{ $related->title }}
                        </h3>


                        {{-- Date --}}
                        <div class="mt-4 flex items-center gap-1.5 text-[9px] font-semibold text-slate-400 dark:text-slate-500">
                            <i class="fa-regular fa-calendar"></i>
                            {{ optional($related->published_at)->format('d M Y') }}
                        </div>

                    </a>

                @endforeach

            </div>

        </section>

    @endif

</div>


{{-- ARTICLE TYPOGRAPHY --}}
@push('styles')
<style>
    .article-content {
        overflow-wrap: anywhere;
        word-break: normal;
    }

    .article-content p {
        margin-bottom: 1.25rem;
    }

    .article-content strong {
        font-weight: 800;
        color: rgb(30 41 59);
    }

    .dark .article-content strong {
        color: rgb(248 250 252);
    }

    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4 {
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        font-weight: 800;
        line-height: 1.3;
        color: rgb(15 23 42);
    }

    .dark .article-content h1,
    .dark .article-content h2,
    .dark .article-content h3,
    .dark .article-content h4 {
        color: rgb(248 250 252);
    }

    .article-content ul,
    .article-content ol {
        margin: 1rem 0 1.25rem 1.25rem;
        padding-left: 1rem;
    }

    .article-content ul {
        list-style-type: disc;
    }

    .article-content ol {
        list-style-type: decimal;
    }

    .article-content li {
        margin-bottom: 0.5rem;
        padding-left: 0.25rem;
    }

    .article-content blockquote {
        margin: 1.5rem 0;
        border-left: 3px solid rgb(99 102 241);
        padding: 0.75rem 1rem;
        border-radius: 0 0.75rem 0.75rem 0;
        background: rgb(248 250 252);
        color: rgb(71 85 105);
    }

    .dark .article-content blockquote {
        background: rgb(15 23 42);
        color: rgb(148 163 184);
    }

    .article-content a {
        color: rgb(79 70 229);
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .dark .article-content a {
        color: rgb(129 140 248);
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin: 1.5rem auto;
    }

    .article-content table {
        display: block;
        width: 100%;
        overflow-x: auto;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }

    .article-content th,
    .article-content td {
        border: 1px solid rgb(226 232 240);
        padding: 0.625rem 0.75rem;
        text-align: left;
    }

    .dark .article-content th,
    .dark .article-content td {
        border-color: rgb(51 65 85);
    }

    @media (max-width: 640px) {
        .article-content {
            font-size: 0.875rem;
            line-height: 1.85;
        }

        .article-content h2 {
            font-size: 1.1rem;
        }

        .article-content h3 {
            font-size: 1rem;
        }
    }
</style>
@endpush

@endsection