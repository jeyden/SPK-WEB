<!DOCTYPE html>
<html lang="id" class="h-full overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistem Pendukung Keputusan Pemilihan Jurusan</title>
    
    <!-- FAVICON KUSTOM -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lottie Player CDN untuk Animasi JSON -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Mencegah pergeseran layout/bounce yang tidak diinginkan pada mobile browser */
        .hero-container {
            min-height: 100dvh;
        }
    </style>
</head>
<body class="h-full m-0 p-0 bg-slate-950 overflow-x-hidden">

    <!-- Loading Screen Overlay (Hidden secara default) -->
    <div id="loading-screen" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-950/90 backdrop-blur-md hidden opacity-0 transition-opacity duration-300 px-4">
        <!-- Container Animasi Lottie -->
        <div id="lottie-animation" class="w-36 h-36 sm:w-48 sm:h-48 mb-4"></div>
        <p class="text-white text-sm sm:text-base font-medium tracking-wide animate-pulse text-center">Memuat Halaman Login...</p>
    </div>

    <!-- Hero Section dengan Background Image bglanding.png -->
    <section class="relative hero-container w-full flex items-center justify-center bg-cover bg-center bg-no-repeat overflow-hidden py-12 px-4 sm:px-6 lg:px-8" style="background-image: url('{{ asset('img/bglanding.png') }}');">
        
        <!-- Gradient Overlay agar teks lebih kontras dan premium -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-900/80 to-slate-950/90 backdrop-blur-[2px]"></div>

        <!-- Konten Utama Hero Section -->
        <div class="relative z-10 max-w-4xl mx-auto w-full text-center flex flex-col items-center justify-center my-auto">
            
            <!-- Logo Instansi / Sekolah (Di atas kalimat) - Responsif untuk Mobile & Desktop -->
            <div class="mb-4 sm:mb-6 flex justify-center">
                <div class="p-3 sm:p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-2xl">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Instansi" class="h-14 sm:h-20 md:h-24 w-auto object-contain drop-shadow-md">
                </div>
            </div>

            <!-- Badge Kecil / Penanda Nama Sekolah -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-200 text-xs sm:text-sm font-medium mb-4 backdrop-blur-md shadow-inner text-center">
                <i class="fa-solid fa-school text-indigo-400 shrink-0"></i>
                <span class="truncate">SMA At-Tajdid Boarding School</span>
            </div>

            <!-- Judul Utama (Heading) - Skala ukuran disesuaikan agar tidak terpotong di layar kecil -->
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-snug sm:leading-tight mb-4 px-2">
                Temukan Masa Depanmu <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-sky-300 to-emerald-400">
                    Melalui Jurusan yang Tepat
                </span>
            </h1>

            <!-- Deskripsi Singkat -->
            <p class="max-w-xl mx-auto text-xs sm:text-base text-gray-300 font-light mb-8 leading-relaxed px-2">
                Sistem terintegrasi untuk membantu proses pemetaan potensi siswa dan memberikan rekomendasi jurusan perkuliahan berdasarkan data akademik serta karakteristik individu secara terstruktur.
            </p>

            <!-- Tombol Aksi / Login - Full width di mobile agar mudah ditekan (touch-friendly) -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto px-4 sm:px-0">
                @auth
                    <a href="{{ route('dashboard') }}" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-3.5 text-sm sm:text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all duration-300 transform active:scale-95 sm:hover:-translate-y-0.5">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Masuk ke Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" id="login-btn"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-3.5 text-sm sm:text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xl hover:shadow-indigo-500/40 transition-all duration-300 transform active:scale-95 sm:hover:-translate-y-0.5 border border-indigo-400/30">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Login ke Sistem</span>
                    </a>
                @endauth
            </div>

        </div>

    </section>

    <!-- Script untuk Mengatur Lottie Animation & Back-button Cache Fix -->
    <script>
        let animationInstance = null;

        // Inisialisasi Lottie Animation dari public/animation/loading.json
        function initLottie() {
            if (!animationInstance) {
                animationInstance = lottie.loadAnimation({
                    container: document.getElementById('lottie-animation'),
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: "{{ asset('animation/loading.json') }}"
                });
            }
        }

        // Tangani klik tombol login untuk memunculkan loading screen
        const loginBtn = document.getElementById('login-btn');
        if (loginBtn) {
            loginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetUrl = this.href;

                // Tampilkan loading screen dengan efek transisi opacity
                const loadingScreen = document.getElementById('loading-screen');
                loadingScreen.classList.remove('hidden');
                setTimeout(() => {
                    loadingScreen.classList.remove('opacity-0');
                }, 10);

                // Jalankan animasi lottie
                initLottie();
                if (animationInstance) {
                    animationInstance.play();
                }

                // Beri jeda sejenak agar animasi mulus terlihat sebelum pindah halaman
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 1300);
            });
        }

        // Mengatasi masalah stuck animasi saat user menekan tombol Back dari halaman login (bfcache fix)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (performance && performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                const loadingScreen = document.getElementById('loading-screen');
                if (loadingScreen) {
                    loadingScreen.classList.add('opacity-0');
                    loadingScreen.classList.add('hidden');
                }
                if (animationInstance) {
                    animationInstance.stop();
                }
            }
        });
    </script>
</body>
</html>