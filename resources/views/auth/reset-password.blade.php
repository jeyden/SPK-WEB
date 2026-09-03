<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Pendukung Keputusan Pemilihan Jurusan</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{font-family:'Inter',sans-serif}
        .academic-title{font-family:'Playfair Display',serif}
        .glass{background:rgba(15,23,42,.82);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px)}
        .grid-bg{background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:42px 42px}
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-x-hidden">

    <!-- Background Image & Gradient Overlay -->
    <div class="fixed inset-0 bg-cover bg-center pointer-events-none" style="background-image: url('{{ asset('img/bglanding.png') }}');"></div>
    <div class="fixed inset-0 bg-slate-950/90 pointer-events-none"></div>
    <div class="fixed inset-0 bg-gradient-to-br from-slate-950 via-slate-900/90 to-indigo-950/80 pointer-events-none"></div>
    <div class="fixed inset-0 grid-bg pointer-events-none"></div>

    <!-- MAIN FORM CONTAINER (CENTERED) -->
    <div class="relative z-10 w-full max-w-md my-auto">
        <div class="glass border border-white/10 rounded-3xl shadow-2xl shadow-indigo-950/40 overflow-hidden">
            <div class="p-6 sm:p-8">

                <!-- HEADER FORM & LOGO -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center p-3 mb-3 shadow-lg shadow-indigo-500/10">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Instansi" class="max-h-full w-auto object-contain">
                    </div>
                    <p class="text-xs uppercase tracking-[.2em] text-indigo-300 font-semibold">SMA At-Tajdid Boarding School</p>
                    <h2 class="text-2xl font-bold text-white tracking-tight mt-1">Atur Ulang Password</h2>
                    <p class="text-xs text-slate-400 mt-1">Buat password baru yang kuat untuk akun Anda</p>
                </div>

                <!-- Notifikasi Error Umum -->
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-200 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf

                    <!-- Token Tersembunyi -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Input Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">ALAMAT EMAIL</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                            <input type="email" name="email" value="{{ old('email', $email) }}" required autofocus autocomplete="email" placeholder="nama@sekolah.sch.id"
                                class="w-full pl-11 pr-4 py-2.5 bg-white/[.04] border border-white/10 rounded-xl text-white placeholder-slate-600 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                        </div>
                        @error('email')
                            <span class="text-rose-400 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">PASSWORD BARU</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                            <input type="password" name="password" id="password" required autocomplete="new-password" placeholder="••••••••"
                                class="w-full pl-11 pr-11 py-2.5 bg-white/[.04] border border-white/10 rounded-xl text-white placeholder-slate-600 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                            <button type="button" onclick="togglePassword('password', 'passwordIcon')" class="absolute right-0 top-0 h-full px-4 text-slate-500 hover:text-white transition" aria-label="Tampilkan password">
                                <i id="passwordIcon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-rose-400 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">KONFIRMASI PASSWORD BARU</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock-open absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                                class="w-full pl-11 pr-11 py-2.5 bg-white/[.04] border border-white/10 rounded-xl text-white placeholder-slate-600 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                            <button type="button" onclick="togglePassword('password_confirmation', 'confirmIcon')" class="absolute right-0 top-0 h-full px-4 text-slate-500 hover:text-white transition" aria-label="Tampilkan konfirmasi password">
                                <i id="confirmIcon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-900/40 hover:shadow-indigo-600/30 transition duration-200 flex items-center justify-center gap-2 mt-2">
                        <i class="fa-solid fa-rotate"></i>
                        Perbarui Password
                    </button>
                </form>

                <div class="relative my-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-slate-900/80 px-3 text-[10px] uppercase tracking-widest text-slate-500">atau</span>
                    </div>
                </div>

                <p class="text-center text-xs text-slate-400">
                    Batal reset?
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold ml-1 transition">Kembali ke Login</a>
                </p>
            </div>
        </div>

        <p class="text-center text-[10px] text-slate-500 mt-4">
            Sistem Pendukung Keputusan • SMA At-Tajdid Boarding School
        </p>
    </div>

    <!-- Script Toggle Password Visibility -->
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            icon.classList.toggle('fa-eye', visible);
            icon.classList.toggle('fa-eye-slash', !visible);
        }
    </script>
</body>
</html>