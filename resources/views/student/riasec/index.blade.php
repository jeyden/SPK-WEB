@extends('student.layouts.app')

@section('title', 'Asesmen Minat & Bakat RIASEC')

@section('content')
<div class="mx-auto max-w-5xl space-y-5 pb-16">

    {{-- HEADER --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 p-5 text-white shadow-lg sm:p-7">
        <div class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-44 w-44 rounded-full bg-violet-300/10 blur-3xl"></div>

        <div class="relative flex items-start justify-between gap-5">
            <div class="min-w-0">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest">
                    <i class="fa-solid fa-brain"></i>
                    Asesmen RIASEC
                </span>

                <h1 class="mt-3 text-xl font-black tracking-tight sm:text-2xl">
                    Tes Minat Karir
                </h1>

                <p class="mt-2 max-w-2xl text-xs leading-6 text-indigo-100 sm:text-sm">
                    Pilih jawaban yang paling menggambarkan tingkat ketertarikan atau kecocokan diri Anda pada setiap pernyataan.
                    Asesmen terdiri dari 24 pernyataan yang mencakup 6 dimensi RIASEC.
                </p>
            </div>

            <div class="hidden h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/20 bg-white/10 sm:flex">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
        </div>

        <div class="relative mt-5 grid grid-cols-3 gap-2 sm:max-w-md">
            <div class="rounded-xl border border-white/10 bg-white/10 p-3">
                <p class="text-[8px] font-bold uppercase tracking-wider text-indigo-100">Pernyataan</p>
                <p class="mt-1 text-lg font-black">24</p>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/10 p-3">
                <p class="text-[8px] font-bold uppercase tracking-wider text-indigo-100">Dimensi</p>
                <p class="mt-1 text-lg font-black">6</p>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/10 p-3">
                <p class="text-[8px] font-bold uppercase tracking-wider text-indigo-100">Skala</p>
                <p class="mt-1 text-lg font-black">1–5</p>
            </div>
        </div>
    </section>

    {{-- PROGRESS --}}
    <div class="sticky top-2 z-40">
        <div class="rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-sm backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/95">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">
                        Progress Asesmen
                    </p>
                    <p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400">
                        Jawab seluruh pernyataan
                    </p>
                </div>
                <span id="progressText" class="text-xs font-black text-indigo-600 dark:text-indigo-400">
                    0 / {{ $questions->count() }} soal
                </span>
            </div>

            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div id="progressBar" class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-300" style="width:0%"></div>
            </div>
        </div>
    </div>

    <form action="{{ route('student.riasec.store') }}" method="POST" id="riasecForm">
        @csrf

        @php
            $dimensionLabels = [
                'R' => 'Realistic',
                'I' => 'Investigative',
                'A' => 'Artistic',
                'S' => 'Social',
                'E' => 'Enterprising',
                'C' => 'Conventional',
            ];
            $dimensionDescriptions = [
                'R' => 'Praktis, teknis, dan berorientasi pada perangkat',
                'I' => 'Analitis, logis, dan berorientasi pada pemecahan masalah',
                'A' => 'Kreatif, ekspresif, dan berorientasi pada karya',
                'S' => 'Sosial, komunikatif, dan senang membantu',
                'E' => 'Memimpin, berinisiatif, dan berorientasi pada peluang',
                'C' => 'Terstruktur, teliti, dan berorientasi pada data',
            ];
            $dimensionOrder = ['R', 'I', 'A', 'S', 'E', 'C'];
            $previousCategory = null;
            $previousIndicator = null;
            $labels = [
                1 => 'Sangat Tidak Setuju',
                2 => 'Tidak Setuju',
                3 => 'Netral',
                4 => 'Setuju',
                5 => 'Sangat Setuju',
            ];
        @endphp

        <div id="questionContainer" class="space-y-4">
            @foreach($questions as $index => $q)
                @php
                    $category = strtoupper($q->category);
                    $indicator = (int) $q->indicator;
                    $indicatorName = $q->indicator_name ?? 'Indikator '.$indicator;
                    $isNewDimension = $category !== $previousCategory;
                    $isNewIndicator = $isNewDimension || $indicator !== $previousIndicator;
                    $dimensionNumber = array_search($category, $dimensionOrder) + 1;
                @endphp

                <div class="question-card {{ $index >= 10 ? 'question-hidden' : '' }}" data-index="{{ $index }}">

                    {{-- DIMENSI --}}
                    @if($isNewDimension)
                        <div class="mb-2 flex items-center gap-3 px-1 pt-1">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-xs font-black text-white shadow-sm">
                                {{ $category }}
                            </span>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-[9px] font-black uppercase tracking-[0.16em] text-indigo-500 dark:text-indigo-400">
                                        Dimensi {{ $dimensionNumber }}/6
                                    </p>
                                    <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                    <p class="text-xs font-black text-slate-800 dark:text-slate-200">
                                        {{ $dimensionLabels[$category] ?? $category }}
                                    </p>
                                </div>
                                <p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400">
                                    {{ $dimensionDescriptions[$category] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- INDIKATOR + SOAL --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        @if($isNewIndicator)
                            <div class="border-b border-indigo-100 bg-indigo-50/60 px-4 py-3 dark:border-indigo-900/40 dark:bg-indigo-950/20 sm:px-5">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-white text-[9px] font-black text-indigo-600 shadow-sm ring-1 ring-indigo-100 dark:bg-slate-900 dark:text-indigo-400 dark:ring-indigo-900">
                                        {{ $indicator }}
                                    </span>
                                    <div>
                                        <p class="text-[8px] font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-400">
                                            Indikator {{ $indicator }}
                                        </p>
                                        <p class="text-xs font-black text-indigo-950 dark:text-indigo-100">
                                            {{ $indicatorName }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="p-4 sm:p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xs font-black text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $index + 1 }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold leading-6 text-slate-800 dark:text-slate-100 sm:text-[15px]">
                                        {{ $q->question }}
                                    </p>

                                    <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="group relative cursor-pointer">
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $i }}" required class="peer sr-only answer-input">

                                                <div class="flex min-h-[58px] flex-row items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 transition-all group-hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:border-slate-700 dark:bg-slate-800/70 dark:peer-checked:border-indigo-500 dark:peer-checked:bg-indigo-950/40 sm:min-h-[72px] sm:flex-col sm:justify-center sm:gap-0">
                                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-xs font-black text-slate-600 transition dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 sm:mb-1">
                                                        {{ $i }}
                                                    </span>
                                                    <span class="text-left text-[9px] font-semibold leading-tight text-slate-500 transition peer-checked:text-indigo-600 dark:text-slate-400 dark:peer-checked:text-indigo-400 sm:text-center">
                                                        {{ $labels[$i] }}
                                                    </span>
                                                </div>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $previousCategory = $category;
                    $previousIndicator = $indicator;
                @endphp
            @endforeach
        </div>

        {{-- LOAD MORE --}}
        <div id="loadMoreTrigger" class="flex h-16 items-center justify-center">
            <div id="loadingIndicator" class="hidden items-center gap-2 text-xs font-semibold text-slate-400">
                <i class="fa-solid fa-spinner fa-spin text-indigo-500"></i>
                Memuat soal berikutnya...
            </div>
        </div>

        {{-- SELESAI --}}
        <div id="completeIndicator" class="mt-2 hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/50 dark:bg-emerald-950/20">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white">
                    <i class="fa-solid fa-check text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-black text-emerald-800 dark:text-emerald-300">
                        Seluruh soal telah ditampilkan
                    </p>
                    <p class="mt-0.5 text-[10px] text-emerald-700 dark:text-emerald-400">
                        Pastikan seluruh pernyataan telah dijawab sebelum mengirim asesmen.
                    </p>
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div id="submitSection" class="mt-4 hidden">
            <button type="submit" id="submitButton" class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 sm:ml-auto sm:w-auto">
                <span>Simpan & Kirim Asesmen</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </button>
        </div>
    </form>
</div>

<style>
.question-hidden{display:none!important}.question-card{animation:questionAppear .35s ease-out}@keyframes questionAppear{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
    const cards=Array.from(document.querySelectorAll('.question-card'));
    const total=cards.length,batchSize=10;
    const trigger=document.getElementById('loadMoreTrigger');
    const loading=document.getElementById('loadingIndicator');
    const complete=document.getElementById('completeIndicator');
    const submitSection=document.getElementById('submitSection');
    const progressText=document.getElementById('progressText');
    const progressBar=document.getElementById('progressBar');
    const form=document.getElementById('riasecForm');
    const submitButton=document.getElementById('submitButton');
    let visible=Math.min(batchSize,total),loadingBatch=false;

    function showBatch(){
        if(loadingBatch||visible>=total){finishLoading();return}
        loadingBatch=true;
        loading.classList.remove('hidden');
        loading.classList.add('flex');
        setTimeout(function(){
            const start=visible,end=Math.min(visible+batchSize,total);
            for(let i=start;i<end;i++)cards[i].classList.remove('question-hidden');
            visible=end;
            loading.classList.add('hidden');
            loading.classList.remove('flex');
            loadingBatch=false;
            if(visible>=total)finishLoading();
        },300);
    }

    function finishLoading(){
        loading.classList.add('hidden');
        loading.classList.remove('flex');
        complete.classList.remove('hidden');
        submitSection.classList.remove('hidden');
        observer.disconnect();
    }

    function updateProgress(){
        const answered=document.querySelectorAll('.answer-input:checked').length;
        const percentage=total?Math.round(answered/total*100):0;
        progressText.textContent=answered+' / '+total+' soal';
        progressBar.style.width=percentage+'%';
    }

    document.querySelectorAll('.answer-input').forEach(input=>{
        input.addEventListener('change',updateProgress);
    });

    const observer=new IntersectionObserver(entries=>{
        entries.forEach(entry=>{
            if(entry.isIntersecting&&!loadingBatch&&visible<total)showBatch();
        });
    },{root:null,rootMargin:'0px 0px 500px 0px',threshold:0});

    if(visible<total)observer.observe(trigger);
    else finishLoading();

    form.addEventListener('submit',function(event){
        const answered=document.querySelectorAll('.answer-input:checked').length;
        if(answered<total){
            event.preventDefault();
            let firstUnanswered=null;

            for(let i=0;i<cards.length;i++){
                if(!cards[i].querySelector('.answer-input:checked')){
                    firstUnanswered=cards[i];
                    for(let j=0;j<=i;j++)cards[j].classList.remove('question-hidden');
                    break;
                }
            }

            if(firstUnanswered){
                firstUnanswered.scrollIntoView({behavior:'smooth',block:'center'});
                const box=firstUnanswered.querySelector('.rounded-2xl.border');
                if(box){
                    box.classList.add('ring-2','ring-red-400');
                    setTimeout(()=>box.classList.remove('ring-2','ring-red-400'),2000);
                }
            }
            return;
        }

        submitButton.disabled=true;
        submitButton.classList.add('cursor-not-allowed','opacity-70');
        submitButton.querySelector('span').textContent='Menyimpan Asesmen...';
    });

    updateProgress();
});
</script>
@endsection