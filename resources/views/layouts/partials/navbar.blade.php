<header class="h-20 bg-white border-b border-slate-200 flex items-center px-6 gap-6 shrink-0">
    <div class="min-w-0">
        <h1 class="text-lg font-bold text-slate-900 truncate">@yield('page-title', 'Monitor TPID Real-Time')</h1>
        <p class="text-xs text-slate-500 flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
            <span>Terakhir Diperbarui: <span class="font-medium">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY, HH:mm') }} WITA</span></span>
        </p>
    </div>

<!-- Wrapper utama agar badge status turun ke bawah header dan tidak terpotong -->
    <div class="mt-4 flex flex-wrap items-center justify-start gap-3 w-full">
<!-- Kontainer Status Pangan, LPG, BBM -->
    <div class="flex flex-wrap items-center gap-2 px-3 py-1.5 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm">
<!-- status pangan -->
    <div class="flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-slate-50 border border-slate-200">
        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-xl">
            <span class="inline-block w-2 h-2 rounded-full bg-status-waspada animate-pulse"></span>
            <span class="text-[11px] font-semibold text-slate-700">STATUS PANGAN</span>
            <span class="text-[11px] font-bold text-status-waspada">WASPADA</span>
        </div>
<!-- status LPG -->
        <div class="w-px h-4 bg-slate-200"></div>
        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-xl">
            <span class="inline-block w-2 h-2 rounded-full bg-status-bahaya animate-pulse"></span>
            <span class="text-[11px] font-semibold text-slate-700">STATUS LPG</span>
            <span class="text-[11px] font-bold text-status-bahaya">BAHAYA</span>
        </div>
<!-- status BBM -->
        <div class="w-px h-4 bg-slate-200"></div>
        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-xl">
            <span class="inline-block w-2 h-2 rounded-full bg-status-aman animate-pulse"></span>
            <span class="text-[11px] font-semibold text-slate-700">STATUS BBM</span>
            <span class="text-[11px] font-bold text-status-aman">AMAN</span>
        </div>
    </div>
</div>

    <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sigap-accent text-white text-sm font-semibold shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 10.5h.008v.008h-.008V10.5zm-3.75 0h.008v.008h-.008V10.5zm-3.75 0h.008v.008h-.008V10.5z"/></svg>
        Cetak Laporan
    </button>

    <button class="relative w-10 h-10 rounded-xl border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-status-bahaya ring-2 ring-white"></span>
    </button>
</header>
