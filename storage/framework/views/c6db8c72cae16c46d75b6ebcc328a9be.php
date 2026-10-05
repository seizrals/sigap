<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIGAP+ Gorontalo Utara</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#FFE9A8] via-[#FFD971] to-[#F59E0B] text-slate-800 antialiased flex items-center justify-center p-4 overflow-hidden">
    <div class="absolute top-6 left-8 flex items-center gap-2">
        <span class="px-3 py-1.5 rounded-xl bg-white/60 backdrop-blur text-xs font-semibold text-slate-700 border border-white/80">BPS</span>
        <span class="px-3 py-1.5 rounded-xl bg-white/60 backdrop-blur text-xs font-semibold text-slate-700 border border-white/80">TPID</span>
    </div>
    <div class="absolute top-6 right-8 text-right">
        <p class="text-[11px] font-bold uppercase tracking-wider text-orange-900/80">Tim Pengendali Inflasi Daerah</p>
        <p class="text-[11px] font-semibold text-orange-900/60">Kabupaten Gorontalo Utara</p>
    </div>

    <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-10 items-center">
        <div class="hidden lg:block">
            <div class="bg-white/40 backdrop-blur-xl rounded-[32px] border-2 border-white/70 shadow-2xl shadow-amber-900/20 p-10">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-xs font-bold uppercase tracking-wider text-amber-700 shadow-md mb-8">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Monitor TPID Real-Time
                </span>
                <h1 class="text-6xl font-black tracking-tight text-slate-900 mb-2">
                    SIGAP<span class="text-amber-600">+</span>
                </h1>
                <p class="text-2xl font-bold text-amber-800 mb-6">Siaga Pangan Plus</p>
                <p class="text-slate-700 leading-relaxed text-base">
                    Sistem Informasi Pengendalian Inflasi Daerah untuk pemantauan Harga dan Stok Pangan, LPG, dan BBM Kabupaten Gorontalo Utara.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-[32px] shadow-2xl shadow-amber-900/20 border border-white/80 p-10">
            <h2 class="text-2xl font-bold text-slate-900 mb-1">Masuk ke Sistem</h2>
            <p class="text-sm text-slate-500 mb-8">Silakan masuk sebagai petugas atau akses informasi publik.</p>

            <form method="POST" action="<?php echo e(route('login.post')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Username</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        <input name="username" type="text" required placeholder="Masukkan Username" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition-all text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Password</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        <input name="password" type="password" required placeholder="••••••••" class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition-all text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-white text-sm font-bold shadow-lg shadow-amber-500/30 hover:from-amber-600 hover:to-amber-700 transition-all">
                    LOGIN SISTEM
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>

                <a href="<?php echo e(route('portal-publik')); ?>" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-all">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    Akses Portal Publik
                </a>
            </form>
        </div>
    </div>

    <div class="absolute bottom-6 left-8 flex items-center gap-2 text-xs font-semibold text-amber-900/70">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
        SIGAP+ GORONTALO UTARA &middot; Pemerintah Daerah 2026
    </div>
</body>
</html>
<?php /**PATH D:\Badan Pusat Statistik\BPS Kabupaten Gorontalo Utara\sigap\resources\views/auth/login.blade.php ENDPATH**/ ?>