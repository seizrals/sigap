<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title>Masuk - SIGAP+ Gorontalo Utara</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_gorut.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo_gorut.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo_gorut.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen min-h-[100dvh] bg-gradient-to-br from-[#FFE9A8] via-[#FFD971] to-[#F59E0B] text-slate-800 antialiased flex flex-col justify-between py-4 xs:py-5 sm:py-6 px-4 xs:px-6 sm:px-8 lg:px-12 select-none overflow-x-hidden selection:bg-amber-600 selection:text-white">

    {{-- Header Top Bar (BPS & TPID Badges - Responsif Flexbox agar tidak tumpang tindih) --}}
    <header class="w-full max-w-6xl 2xl:max-w-7xl mx-auto flex items-center justify-between gap-2 shrink-0 px-1">
        <div class="flex items-center gap-1.5 sm:gap-2">
            <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl bg-white/70 backdrop-blur text-[11px] sm:text-xs font-bold text-slate-800 border border-white/80 shadow-xs">BPS</span>
            <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl bg-white/70 backdrop-blur text-[11px] sm:text-xs font-bold text-slate-800 border border-white/80 shadow-xs">TPID</span>
        </div>
        <div class="text-right">
            <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-orange-950/80 leading-tight">Tim Pengendali Inflasi Daerah</p>
            <p class="text-[9px] sm:text-[11px] font-semibold text-orange-900/70 leading-tight">Kabupaten Gorontalo Utara</p>
        </div>
    </header>

    {{-- Main Content (Grid 2 Kolom di Desktop, 1 Kolom Rapi dengan Jarak Sisi yang Jelas di Mobile/Foldable) --}}
    <main class="w-full max-w-6xl 2xl:max-w-7xl mx-auto my-auto py-3 xs:py-4 sm:py-6 flex items-center justify-center">
        <div class="w-full grid lg:grid-cols-2 gap-6 lg:gap-10 items-center">
            
            {{-- Kolom Kiri: Branding Tampilan Asli SIGAP+ (Desktop LG+) --}}
            <div class="hidden lg:block">
                <div class="bg-white/40 backdrop-blur-xl rounded-[32px] border-2 border-white/70 shadow-2xl shadow-amber-900/20 p-8 xl:p-10">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-xs font-bold uppercase tracking-wider text-amber-700 shadow-md mb-6 xl:mb-8">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Monitor TPID Real-Time
                    </span>
                    <h1 class="text-5xl xl:text-6xl font-black tracking-tight text-slate-900 mb-2">
                        SIGAP<span class="text-amber-600">+</span>
                    </h1>
                    <p class="text-xl xl:text-2xl font-bold text-amber-800 mb-5 xl:mb-6">Siaga Pangan Plus</p>
                    <p class="text-slate-700 leading-relaxed text-sm xl:text-base">
                        Sistem Informasi Pengendalian Inflasi Daerah untuk pemantauan Harga dan Stok Pangan, LPG, dan BBM Kabupaten Gorontalo Utara.
                    </p>
                </div>
            </div>

            {{-- Kolom Kanan: Card Login Asli dengan Margin Jarak Sisi yang Jelas (Tidak Habis Layar) --}}
            <div class="w-[calc(100%-1rem)] xs:w-[calc(100%-2rem)] sm:w-full max-w-md mx-auto lg:max-w-none bg-white rounded-[24px] xs:rounded-[28px] sm:rounded-[32px] shadow-2xl shadow-amber-900/20 border border-white/80 p-5 xs:p-6 sm:p-8 xl:p-10">
                
                {{-- Logo / Judul Ringkas Khusus Mobile & Foldable (< LG) --}}
                <div class="flex items-center gap-2.5 sm:hidden mb-3.5 pb-2.5 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 p-1 border border-amber-200/60 shadow-2xs shrink-0 flex items-center justify-center">
                        <img src="{{ asset('images/logo_gorut.png') }}" alt="Logo Gorut" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="font-extrabold text-slate-900 text-xs leading-none">SIGAP<span class="text-amber-600">+</span> Gorontalo Utara</p>
                        <p class="text-[10px] text-slate-500 font-medium">Siaga Pangan Plus</p>
                    </div>
                </div>

                <h2 class="text-lg sm:text-2xl font-bold text-slate-900 mb-1 leading-tight">Masuk ke Sistem</h2>
                <p class="text-xs sm:text-sm text-slate-500 mb-4 sm:mb-6">Silakan masuk sebagai petugas atau akses informasi publik.</p>

                {{-- Alert Error Validasi --}}
                @if($errors->any())
                    <div class="mb-3.5 p-2.5 sm:p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <span>{{ $errors->first('username') ?? 'Username atau password tidak sesuai.' }}</span>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Password</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        <input id="password" name="password" type="password" required placeholder="••••••••" class="w-full pl-12 pr-12 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition-all text-sm">
                        <button type="button" id="toggle-password" aria-label="Tampilkan password" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition">
                            <svg id="icon-eye" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg id="icon-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-3.5 sm:space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Username</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 sm:left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            <input name="username" 
                                   type="text" 
                                   value="{{ old('username') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username" 
                                   placeholder="Masukkan Username" 
                                   class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition-all text-xs sm:text-sm text-slate-800 placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Password</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 sm:left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                            <input id="login-password" 
                                   name="password" 
                                   type="password" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="••••••••" 
                                   class="w-full pl-10 sm:pl-12 pr-10 py-2.5 sm:py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition-all text-xs sm:text-sm text-slate-800 placeholder:text-slate-400">
                            <button type="button" 
                                    id="toggle-password-btn" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1" 
                                    aria-label="Tampilkan atau sembunyikan password">
                                <svg id="eye-icon" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <svg id="eye-slash-icon" class="w-4 h-4 sm:w-5 sm:h-5 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <label class="flex items-center gap-1.5 cursor-pointer text-slate-600 select-none">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-amber-500 focus:ring-amber-400">
                            <span class="text-[11px] sm:text-xs">Ingat saya</span>
                        </label>
                    </div>

                    <div class="space-y-2 pt-1">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 sm:py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs sm:text-sm font-bold shadow-lg shadow-amber-500/30 hover:from-amber-600 hover:to-amber-700 active:scale-[0.98] transition-all">
                            <span>LOGIN SISTEM</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>

                        <a href="{{ route('portal-publik') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 sm:py-2.5 rounded-xl border-2 border-slate-200 text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 transition-all">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                            <span>Akses Portal Publik</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    {{-- Footer Bottom Bar (Responsif agar tidak bertabrakan dengan kartu login) --}}
    <footer class="w-full max-w-6xl 2xl:max-w-7xl mx-auto flex items-center justify-center sm:justify-start gap-2 text-[11px] sm:text-xs font-semibold text-amber-950/80 shrink-0 pt-1">
        <svg class="w-4 h-4 shrink-0 text-amber-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
        <span>SIGAP+ GORONTALO UTARA &middot; Pemerintah Daerah 2026</span>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggle-password-btn');
            const passwordInput = document.getElementById('login-password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');

            if (toggleBtn && passwordInput && eyeIcon && eyeSlashIcon) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    eyeIcon.classList.toggle('hidden', isPassword);
                    eyeSlashIcon.classList.toggle('hidden', !isPassword);
                });
            }
    <div class="absolute bottom-6 left-8 flex items-center gap-2 text-xs font-semibold text-amber-900/70">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
        SIGAP+ GORONTALO UTARA &middot; Pemerintah Daerah 2026
    </div>

    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            document.getElementById('icon-eye').classList.toggle('hidden', show);
            document.getElementById('icon-eye-off').classList.toggle('hidden', !show);
            this.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>
</body>
</html>
