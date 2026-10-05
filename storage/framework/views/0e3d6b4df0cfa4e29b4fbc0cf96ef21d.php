<aside class="w-64 bg-sigap-primary text-slate-100 flex flex-col shrink-0 border-r border-slate-800">
    <div class="h-20 flex items-center gap-3 px-5 border-b border-slate-800">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-sigap-accent to-sigap-secondary flex items-center justify-center font-bold text-white shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-1.348c-1.263 0-2.421-.96-2.655-2.236z"/></svg>
        </div>
        <div>
            <p class="font-bold tracking-tight">SIAGA PANGAN</p>
            <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Gorontalo Utara</p>
        </div>
    </div>

    <nav class="flex-1 px-3 py-5 space-y-6 overflow-y-auto">
        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-[0.15em]">Dashboard Monitor</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('dashboard') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        <span>Monitor TPID</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-[0.15em]">Input Data</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?php echo e(route('input.pangan')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('input.pangan') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                        <span>Pangan Strategis</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('input.lpg')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('input.lpg') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z"/></svg>
                        <span>LPG</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('input.bbm')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('input.bbm') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>BBM</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('input.iph')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('input.iph') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        <span>IPH Mingguan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-[0.15em]">Early Warning</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?php echo e(route('ews.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('ews.index') || request()->routeIs('ews.*') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        <span>Lapor Darurat (EWS)</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('ews.monitor-kabupaten')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('ews.monitor-kabupaten') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
                        <span>Monitor Kabupaten</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-[0.15em]">Lainnya</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?php echo e(route('saran-publik.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('saran-publik.*') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                        <span>Beri Saran (Publik)</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('validasi.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs('validasi.*') ? 'bg-sigap-accent text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-sigap-primary-light hover:text-white'); ?>">
                        <svg class="w-5 h-5 shrink-0 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Validasi OPD</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="p-4 border-t border-slate-800 bg-sigap-primary-light/50">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center font-bold text-sm text-white ring-2 ring-slate-700">
                A
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold truncate">Admin Kabupaten</p>
                <p class="text-[11px] text-slate-400 truncate">TPID Gorut</p>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="hidden" id="logout-form"><?php echo csrf_field(); ?></form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-xl text-sm font-semibold text-red-400 border border-red-900/60 hover:bg-red-900/30 hover:text-red-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
            Log Out
        </a>
    </div>
</aside>
<?php /**PATH D:\Badan Pusat Statistik\BPS Kabupaten Gorontalo Utara\sigap\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>