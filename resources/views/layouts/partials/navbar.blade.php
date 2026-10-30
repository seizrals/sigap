<header class="h-24 bg-white border-b border-slate-200 flex items-center justify-between px-6 gap-4 shrink-0 relative overflow-hidden">
    
    <!-- KIRI: Tombol Toggle Menu + Foto Pimpinan Kiri (Bupati) & Judul Halaman -->
    <div class="flex items-center gap-3.5 min-w-0">
        <!-- Tombol Toggle Sidebar / Navbar -->
        <button id="sidebar-toggle-btn" 
                type="button" 
                class="w-10 h-10 rounded-xl border border-slate-200 hover:bg-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 transition-colors shrink-0 shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 active:scale-95"
                title="Buka / Tutup Menu Navigasi"
                aria-label="Toggle Menu Navigasi">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>

        <div class="h-24 shrink-0 flex items-end">
            <img src="{{ asset('images/bupati.png') }}" 
                 alt="Foto Bupati Gorontalo Utara" 
                 class="h-24 w-auto object-contain object-bottom drop-shadow-sm select-none pointer-events-none"
                 title="Bupati Gorontalo Utara - Dr. H. Thariq Modanggu, M.Pd">
        </div>
        <div class="min-w-0">
            <h1 class="text-lg font-bold text-slate-900 truncate">
                @yield('page-title', 'Monitor TPID Real-Time')
            </h1>
            <p class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                <svg class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                <span>Terakhir Diperbarui: <span class="font-medium text-slate-700">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY, HH:mm') }} WITA</span></span>
            </p>
        </div>
    </div>

    <!-- KANAN: Kondisi Dinamis Berdasarkan Route + Foto Pimpinan Kanan (Wabup) -->
    <div class="flex items-center gap-3 shrink-0">

        @if (request()->routeIs('ews.monitor-kabupaten'))
            <!-- Card Badge Sistem Validasi -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl px-5 py-2 text-center shadow-xs">
                <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                    Sistem Validasi
                </span>
                <span class="block text-xs font-bold text-blue-600 italic">
                    Data Terverifikasi OPD & BPS
                </span>
            </div>

        @else
            <!-- Badge Status Pangan, LPG, BBM (Sinkron Otomatis dengan Data Real-Time) -->
            @php
                $statuses = $headerStatuses ?? \App\Services\SystemStatusService::getHeaderStatuses();
            @endphp
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-slate-50 border border-slate-200 shadow-xs">
                <!-- Status Pangan -->
                <div class="flex items-center gap-1.5 px-2 py-1" title="Status Pangan: {{ $statuses['pangan']['status'] }}">
                    <span class="inline-block w-2 h-2 rounded-full {{ $statuses['pangan']['dot_class'] }} animate-pulse"></span>
                    <span class="text-[11px] font-semibold text-slate-700">STATUS PANGAN</span>
                    <span class="text-[11px] font-bold {{ $statuses['pangan']['text_class'] }}">{{ $statuses['pangan']['status'] }}</span>
                </div>

                <div class="w-px h-4 bg-slate-200"></div>

                <!-- Status LPG -->
                <div class="flex items-center gap-1.5 px-2 py-1" title="Status LPG: {{ $statuses['lpg']['status'] }}">
                    <span class="inline-block w-2 h-2 rounded-full {{ $statuses['lpg']['dot_class'] }} animate-pulse"></span>
                    <span class="text-[11px] font-semibold text-slate-700">STATUS LPG</span>
                    <span class="text-[11px] font-bold {{ $statuses['lpg']['text_class'] }}">{{ $statuses['lpg']['status'] }}</span>
                </div>

                <div class="w-px h-4 bg-slate-200"></div>

                <!-- Status BBM -->
                <div class="flex items-center gap-1.5 px-2 py-1" title="Status BBM: {{ $statuses['bbm']['status'] }}">
                    <span class="inline-block w-2 h-2 rounded-full {{ $statuses['bbm']['dot_class'] }} animate-pulse"></span>
                    <span class="text-[11px] font-semibold text-slate-700">STATUS BBM</span>
                    <span class="text-[11px] font-bold {{ $statuses['bbm']['text_class'] }}">{{ $statuses['bbm']['status'] }}</span>
                </div>
            </div>

            <!-- Tombol Notifikasi (Lonceng) - Digeser ke tengah (sebelum foto kanan) -->
            <div class="mr-2">
                <button class="relative w-10 h-10 rounded-xl border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-status-bahaya ring-2 ring-white"></span>
                </button>
            </div>
        @endif

        <!-- FOTO KANAN: Foto Wakil Bupati Gorontalo Utara -->
        <div class="h-24 shrink-0 flex items-end">
            <img src="{{ asset('images/wabup.png') }}" 
                 alt="Foto Wakil Bupati Gorontalo Utara" 
                 class="h-24 w-auto object-contain object-bottom drop-shadow-sm select-none pointer-events-none"
                 title="Wakil Bupati Gorontalo Utara - Dra. Hj. Nurjana H. Yusuf, M.Si">
        </div>

    </div>

</header>
