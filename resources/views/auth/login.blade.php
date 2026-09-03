<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pendukung Keputusan Pemilihan Jurusan</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(15,23,42,.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .grid-bg {
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 42px 42px;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-white">
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-cover bg-center px-4 py-6 sm:px-6 lg:px-10" style="background-image:url('{{ asset('img/bglanding.png') }}')">
    <div class="absolute inset-0 bg-slate-950/90"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900/90 to-indigo-950/80"></div>
    <div class="absolute inset-0 grid-bg"></div>

    <div class="relative z-10 w-full max-w-6xl">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-14 items-center">

            <!-- INFORMASI SISTEM -->
            <div class="hidden lg:block order-1">
                <div class="max-w-xl">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center p-3 shadow-xl">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Instansi" class="max-h-full w-auto object-contain">
                        </div>
                        <div class="h-10 w-px bg-white/20"></div>
                        <div>
                            <p class="text-xs uppercase tracking-[.2em] text-indigo-300 font-semibold">Sistem Pendukung Keputusan</p>
                            <p class="text-sm text-slate-300 mt-1">SMA At-Tajdid Boarding School</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-200 text-xs font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Platform Rekomendasi Jurusan Perkuliahan
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white tracking-tight leading-snug mb-4">
                        Temukan Masa Depanmu <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-sky-300 to-emerald-400">
                            Melalui Jurusan yang Tepat
                        </span>
                    </h1>

                    <p class="mt-6 text-sm xl:text-base text-slate-300 leading-7 max-w-lg">
                        Sistem terintegrasi untuk membantu proses pemetaan potensi siswa dan memberikan rekomendasi jurusan perkuliahan berdasarkan data akademik serta karakteristik individu secara terstruktur.
                    </p>

                    <div class="mt-8 grid grid-cols-3 gap-3 max-w-lg">
                        <div class="border-l border-indigo-400/40 pl-4">
                            <p class="text-lg font-bold text-white">01</p>
                            <p class="text-xs text-slate-400 mt-1">Data Akademik</p>
                        </div>
                        <div class="border-l border-indigo-400/40 pl-4">
                            <p class="text-lg font-bold text-white">02</p>
                            <p class="text-xs text-slate-400 mt-1">Analisis Potensi</p>
                        </div>
                        <div class="border-l border-indigo-400/40 pl-4">
                            <p class="text-lg font-bold text-white">03</p>
                            <p class="text-xs text-slate-400 mt-1">Rekomendasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM LOGIN -->
            <div class="order-1 lg:order-2 w-full max-w-md mx-auto">
                <div class="glass border border-white/10 rounded-3xl shadow-2xl shadow-black/40 overflow-hidden">
                    <div class="p-6 sm:p-8">

                        <!-- HEADER MOBILE -->
                        <div class="lg:hidden text-center mb-7">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center p-3 mb-4">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo Instansi" class="max-h-full w-auto object-contain">
                            </div>
                            <p class="text-xs uppercase tracking-[.18em] text-indigo-300 font-semibold">Sistem Pendukung Keputusan</p>
                            <p class="text-xs text-slate-400 mt-1">SMA At-Tajdid Boarding School</p>
                        </div>

                        <div class="mb-6">
                            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-2">Akses Sistem</p>
                            <h2 class="text-2xl font-bold text-white">Selamat Datang</h2>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1.5">Masuk menggunakan akun yang telah terdaftar.</p>
                        </div>

                        @if(session('success'))
                            <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-xs flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-200 text-xs flex items-center gap-3">
                                <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                                <span>Email atau kata sandi yang Anda masukkan salah.</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">EMAIL / USERNAME</label>
                                <div class="relative">
                                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@sekolah.sch.id"
                                        class="w-full pl-11 pr-4 py-3 bg-white/[.04] border border-white/10 rounded-xl text-white placeholder-slate-600 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">KATA SANDI</label>
                                <div class="relative">
                                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                                    <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="••••••••"
                                        class="w-full pl-11 pr-11 py-3 bg-white/[.04] border border-white/10 rounded-xl text-white placeholder-slate-600 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                                    <button type="button" onclick="togglePassword()" class="absolute right-0 top-0 h-full px-4 text-slate-500 hover:text-white transition" aria-label="Tampilkan password">
                                        <i id="passwordIcon" class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                                    <span class="text-xs text-slate-400">Ingat saya</span>
                                </label>
                                <a href="{{ route('password.request') }}" class="text-xs text-indigo-400 hover:text-indigo-300 transition">Lupa password?</a>
                            </div>

                            <button type="submit" class="w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-900/30 transition duration-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Masuk ke Sistem
                            </button>
                        </form>

                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-white/10"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-slate-900/80 px-3 text-[10px] uppercase tracking-widest text-slate-600">atau</span>
                            </div>
                        </div>

                        <p class="text-center text-xs text-slate-400">
                            Belum memiliki akun?
                            <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold ml-1 transition">Daftar di sini</a>
                        </p>
                    </div>
                </div>

                <p class="text-center text-[10px] text-slate-600 mt-5">
                    Sistem Pendukung Keputusan • SMA At-Tajdid Boarding School
                </p>
            </div>
        </div>
    </div>
</section>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');
        const visible = input.type === 'text';

        input.type = visible ? 'password' : 'text';
        icon.classList.toggle('fa-eye', visible);
        icon.classList.toggle('fa-eye-slash', !visible);
    }
</script>
</body>
</html>