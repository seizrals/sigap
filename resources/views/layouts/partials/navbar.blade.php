<header class="h-16 sm:h-20 lg:h-24 bg-white border-b border-slate-200 flex items-center justify-between px-3 sm:px-5 lg:px-6 gap-2 sm:gap-4 shrink-0 relative overflow-hidden select-none">
    
    <!-- KIRI: Tombol Toggle Menu + Foto Pimpinan Kiri (Bupati) & Judul Halaman -->
    <div class="flex items-center gap-2 sm:gap-3.5 min-w-0">
        <!-- Tombol Toggle Sidebar / Navbar -->
        <button id="sidebar-toggle-btn" 
                type="button" 
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border border-slate-200 hover:bg-slate-100 flex items-center justify-center text-slate-600 hover:text-blue-600 transition-colors shrink-0 shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 active:scale-95"
                title="Buka / Tutup Menu Navigasi"
                aria-label="Toggle Menu Navigasi">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>

        <!-- Foto Bupati (Muncul di layar SM ke atas) -->
        <div class="hidden sm:flex h-16 sm:h-20 lg:h-24 shrink-0 items-end" title="Bupati Gorontalo Utara - Dr. H. Thariq Modanggu, M.Pd">
            <img src="{{ asset('images/bupati.png') }}" 
                 alt="Foto Bupati Gorontalo Utara" 
                 class="h-16 sm:h-20 lg:h-24 w-auto object-contain object-bottom drop-shadow-sm select-none pointer-events-none">
        </div>

        <div class="min-w-0">
            <h1 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 truncate">
                @yield('page-title', 'Monitor TPID Real-Time')
            </h1>
            <p class="hidden md:flex text-xs text-slate-500 items-center gap-1.5 mt-0.5 truncate">
                <svg class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                <span>Terakhir Diperbarui: <span class="font-medium text-slate-700">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY, HH:mm') }} WITA</span></span>
            </p>
        </div>
    </div>

    <!-- KANAN: Kondisi Dinamis Berdasarkan Route + Foto Pimpinan Kanan (Wabup) -->
    <div class="flex items-center gap-3 sm:gap-4 lg:gap-6 shrink-0 h-full">

        @if (request()->routeIs('ews.monitor-kabupaten'))
            <!-- Card Badge Sistem Validasi -->
            <div class="hidden sm:block bg-slate-50 border border-slate-200/80 rounded-2xl px-3 sm:px-5 py-1.5 sm:py-2 text-center shadow-xs">
                <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                    Sistem Validasi
                </span>
                <span class="block text-xs font-bold text-blue-600 italic">
                    Data Terverifikasi OPD & BPS
                </span>
            </div>

        @else
            {{-- Selamat Datang User --}}
            <div class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200 shadow-xs">
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none">Selamat Datang</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-900 mt-1">{{ Auth::user()->name }}</span>
                </div>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
            </div>
        @endif

        <!-- FOTO KANAN: Foto Wakil Bupati Gorontalo Utara (Muncul di layar MD ke atas) -->
        <div class="hidden md:flex h-16 sm:h-20 lg:h-24 shrink-0 items-end" title="Wakil Bupati Gorontalo Utara - Dra. Hj. Nurjana H. Yusuf, M.Si">
            <img src="{{ asset('images/wabup.png') }}" 
                 alt="Foto Wakil Bupati Gorontalo Utara" 
                 class="h-16 sm:h-20 lg:h-24 w-auto object-contain object-bottom drop-shadow-sm select-none pointer-events-none">
        </div>

    </div>

</header>
