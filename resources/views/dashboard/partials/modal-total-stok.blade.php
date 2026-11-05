{{-- Modal Total Stok Pangan Daerah (Kondisi Terakhir di Seluruh Kecamatan) --}}
{{-- Responsive across Mobile, Foldables, Tablet, Desktop, Smart Display --}}
<div id="modalTotalStokDaerah" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" onclick="closeTotalStokModal()"></div>

    <div class="min-h-full flex items-center justify-center p-1.5 xs:p-2.5 sm:p-4 md:p-6 2xl:p-8">
        <div class="relative w-full max-w-full sm:max-w-3xl md:max-w-4xl lg:max-w-5xl 2xl:max-w-7xl 3xl:max-w-[1700px] bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[96dvh] sm:max-h-[92vh] 2xl:max-h-[88vh] transition-all">
            
            {{-- Header Modal --}}
            <div class="px-3.5 xs:px-4 sm:px-6 2xl:px-8 py-3 xs:py-3.5 sm:py-5 border-b border-slate-100 flex items-center justify-between gap-3 sm:gap-4 bg-gradient-to-r from-slate-50 via-white to-blue-50/40 shrink-0">
                <div class="flex items-center gap-2.5 xs:gap-3 2xl:gap-4 min-w-0">
                    <div class="w-8 h-8 xs:w-9 xs:h-9 sm:w-11 sm:h-11 2xl:w-14 2xl:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/25 shrink-0">
                        <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 2xl:w-7 2xl:h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm xs:text-base sm:text-lg 2xl:text-2xl font-black text-slate-900 tracking-tight leading-snug truncate" id="modal-title">
                            Total Stok Pangan Daerah
                        </h3>
                        <p class="text-[10px] xs:text-[11px] sm:text-xs 2xl:text-sm text-slate-500 font-medium truncate">
                            Akumulasi stok ketersediaan pangan kondisi terakhir di 11 Kecamatan Kab. Gorontalo Utara
                        </p>
                    </div>
                </div>

                <button type="button" onclick="closeTotalStokModal()" class="w-8 h-8 xs:w-9 xs:h-9 2xl:w-11 2xl:h-11 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition shrink-0 cursor-pointer text-lg xs:text-xl 2xl:text-2xl font-bold leading-none" title="Tutup Modal">
                    &times;
                </button>
            </div>

            {{-- Ringkasan 4 Card Total Stok Daerah & Search Filter --}}
            <div class="px-3.5 xs:px-4 sm:px-6 2xl:px-8 py-3 sm:py-4 2xl:py-5 bg-slate-50/70 border-b border-slate-100 shrink-0 space-y-3 sm:space-y-4">
                {{-- 4 Card Ringkasan: 2 Kolom di Mobile & Foldables, 4 Kolom di Tablet & Desktop/Smart Display --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 xs:gap-2.5 sm:gap-3.5 2xl:gap-5">
                    {{-- Card 1: Komoditas Pantauan --}}
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white rounded-xl sm:rounded-2xl p-2.5 xs:p-3 sm:p-4 2xl:p-5 shadow-md shadow-blue-500/20 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1 xs:mb-1.5">
                                <span class="text-[8px] xs:text-[9px] sm:text-[10px] 2xl:text-xs font-extrabold uppercase tracking-wider text-blue-100 truncate">Komoditas Pantauan</span>
                                <div class="w-5 h-5 xs:w-6 xs:h-6 2xl:w-7 2xl:h-7 rounded-md xs:rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5 2xl:w-4 2xl:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 xs:gap-1.5 mt-0.5">
                                <span class="text-xl xs:text-2xl sm:text-3xl 2xl:text-4xl font-black tracking-tight text-white">{{ $stokPanganRingkasan['totalKomoditas'] ?? count($totalStokDaerah) }}</span>
                                <span class="text-[10px] xs:text-[11px] 2xl:text-sm font-bold text-blue-200">Komoditas</span>
                            </div>
                        </div>
                        <p class="text-[9px] xs:text-[10px] 2xl:text-xs text-blue-100/80 mt-2 sm:mt-2.5 pt-1.5 sm:pt-2 border-t border-white/10 flex items-center justify-between gap-1 truncate">
                            <span class="truncate">Terdata: <strong>{{ $stokPanganRingkasan['totalKomoditasTerdata'] ?? 0 }}</strong></span>
                            <span class="hidden xs:inline">Pangan Pokok</span>
                        </p>
                    </div>

                    {{-- Card 2: Kondisi Aman --}}
                    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 text-white rounded-xl sm:rounded-2xl p-2.5 xs:p-3 sm:p-4 2xl:p-5 shadow-md shadow-emerald-500/20 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1 xs:mb-1.5">
                                <span class="text-[8px] xs:text-[9px] sm:text-[10px] 2xl:text-xs font-extrabold uppercase tracking-wider text-emerald-100 truncate">Kondisi Aman</span>
                                <div class="w-5 h-5 xs:w-6 xs:h-6 2xl:w-7 2xl:h-7 rounded-md xs:rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5 2xl:w-4 2xl:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.746 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <span class="text-xl xs:text-2xl sm:text-3xl 2xl:text-4xl font-black tracking-tight text-white">{{ $stokPanganRingkasan['komoditasAmanCount'] ?? 0 }}</span>
                                <span class="text-[10px] xs:text-[11px] 2xl:text-sm font-bold text-emerald-200">Komoditas</span>
                            </div>
                        </div>
                        <p class="text-[9px] xs:text-[10px] 2xl:text-xs text-emerald-100/80 mt-2 sm:mt-2.5 pt-1.5 sm:pt-2 border-t border-white/10 truncate">
                            Ketersediaan aman seluruh wilayah
                        </p>
                    </div>

                    {{-- Card 3: Perlu Perhatian --}}
                    <div class="bg-gradient-to-br from-amber-500 via-amber-600 to-orange-700 text-white rounded-xl sm:rounded-2xl p-2.5 xs:p-3 sm:p-4 2xl:p-5 shadow-md shadow-amber-500/20 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1 xs:mb-1.5">
                                <span class="text-[8px] xs:text-[9px] sm:text-[10px] 2xl:text-xs font-extrabold uppercase tracking-wider text-amber-100 truncate">Perlu Perhatian</span>
                                <div class="w-5 h-5 xs:w-6 xs:h-6 2xl:w-7 2xl:h-7 rounded-md xs:rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5 2xl:w-4 2xl:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <span class="text-xl xs:text-2xl sm:text-3xl 2xl:text-4xl font-black tracking-tight text-white">{{ $stokPanganRingkasan['komoditasWaspadaKritisCount'] ?? 0 }}</span>
                                <span class="text-[10px] xs:text-[11px] 2xl:text-sm font-bold text-amber-200">Komoditas</span>
                            </div>
                        </div>
                        <p class="text-[9px] xs:text-[10px] 2xl:text-xs text-amber-100/80 mt-2 sm:mt-2.5 pt-1.5 sm:pt-2 border-t border-white/10 flex items-center justify-between gap-1">
                            <span>{{ $stokPanganRingkasan['komoditasKritisCount'] ?? 0 }} Kritis</span>
                            <span>{{ $stokPanganRingkasan['komoditasWaspadaCount'] ?? 0 }} Waspada</span>
                        </p>
                    </div>

                    {{-- Card 4: Total Stok Daerah & Cakupan Wilayah --}}
                    <div class="bg-gradient-to-br from-slate-800 via-slate-900 to-blue-950 text-white rounded-xl sm:rounded-2xl p-2.5 xs:p-3 sm:p-4 2xl:p-5 shadow-md shadow-slate-900/25 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-20 sm:h-20 bg-blue-500/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1 xs:mb-1.5">
                                <span class="text-[8px] xs:text-[9px] sm:text-[10px] 2xl:text-xs font-extrabold uppercase tracking-wider text-slate-300 truncate">Total Stok Daerah</span>
                                <div class="w-5 h-5 xs:w-6 xs:h-6 2xl:w-7 2xl:h-7 rounded-md xs:rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <span class="text-xl xs:text-2xl sm:text-3xl 2xl:text-4xl font-black tracking-tight text-white">11</span>
                                <span class="text-[10px] xs:text-[11px] 2xl:text-sm font-bold text-amber-300">Kecamatan</span>
                            </div>
                        </div>
                        <p class="text-[9px] xs:text-[10px] 2xl:text-xs text-slate-300 mt-2 sm:mt-2.5 pt-1.5 sm:pt-2 border-t border-white/10 flex items-center justify-between gap-1 truncate">
                            <span class="truncate">Kab. Gorontalo Utara</span>
                            <span class="text-amber-300 font-semibold shrink-0">100% Terpantau</span>
                        </p>
                    </div>
                </div>

                {{-- Kolom Pencarian Cepat --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 xs:pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5 xs:w-4 xs:h-4 2xl:w-5 2xl:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <input type="text" id="modalStokSearch" onkeyup="filterModalKomoditas(this.value)" placeholder="Cari komoditas pangan (Beras, Cabai, Minyak, Bawang)..." class="w-full bg-white border border-slate-200 rounded-xl pl-8 xs:pl-9 pr-8 xs:pr-9 py-2 sm:py-2.5 2xl:py-3.5 text-xs sm:text-sm 2xl:text-base font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs placeholder:text-slate-400 transition">
                    <button type="button" id="modalStokSearchClear" onclick="clearModalSearch()" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 text-xs 2xl:text-sm font-bold cursor-pointer" title="Hapus pencarian">
                        &times;
                    </button>
                </div>
            </div>

            {{-- Body List Komoditas & Rincian Kecamatan --}}
            <div class="p-2.5 xs:p-3.5 sm:p-6 2xl:p-8 overflow-y-auto space-y-2.5 sm:space-y-3 2xl:space-y-4 flex-1 custom-scrollbar" id="modalStokListWrapper">
                @forelse($totalStokDaerah as $item)
                    @php
                        $statusKey = strtolower($item['status_ketersediaan'] ?? 'belum_ada');
                        $badgeClass = match($statusKey) {
                            'aman' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'waspada' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'kritis' => 'bg-rose-100 text-rose-800 border-rose-200',
                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                        $statusText = match($statusKey) {
                            'aman' => 'Aman',
                            'waspada' => 'Waspada',
                            'kritis' => 'Kritis',
                            default => 'Belum Ada Data',
                        };
                    @endphp

                    <div class="modal-komoditas-row border border-slate-200 rounded-xl sm:rounded-2xl bg-white hover:border-slate-300 transition shadow-2xs overflow-hidden" data-nama="{{ strtolower($item['nama_komoditas']) }}">
                        {{-- Baris Ringkasan Komoditas --}}
                        <div class="p-3 xs:p-3.5 sm:p-4 2xl:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 xs:gap-3 2xl:gap-4 bg-white cursor-pointer select-none hover:bg-slate-50/50 transition" onclick="toggleModalRincian({{ $item['komoditas_id'] }})">
                            <div class="flex items-center gap-2.5 xs:gap-3 2xl:gap-4 min-w-0 flex-1">
                                <div class="w-8 h-8 xs:w-9 xs:h-9 2xl:w-11 2xl:h-11 rounded-lg xs:rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs xs:text-sm 2xl:text-base shrink-0 border border-blue-100">
                                    {{ substr($item['nama_komoditas'], 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs xs:text-sm sm:text-base 2xl:text-lg font-extrabold text-slate-900 truncate">
                                        {{ $item['nama_komoditas'] }}
                                    </h4>
                                    <p class="text-[10px] xs:text-[11px] 2xl:text-xs text-slate-400 font-medium truncate">
                                        Satuan: <span class="font-bold text-slate-600">{{ $item['satuan'] }}</span> &bull; Terakhir: {{ $item['tanggal_terakhir'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-2 xs:gap-3 sm:gap-4 md:gap-6 2xl:gap-8 shrink-0 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
                                {{-- Total Stok Daerah --}}
                                <div class="text-left sm:text-right">
                                    <span class="text-[8px] xs:text-[9px] 2xl:text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Total Stok</span>
                                    <p class="text-xs xs:text-sm sm:text-base 2xl:text-lg font-black text-slate-900 leading-tight">
                                        {{ number_format((float) $item['total_stok'], 0, ',', '.') }}
                                        <span class="text-[10px] xs:text-xs 2xl:text-sm font-bold text-slate-500">{{ $item['satuan'] }}</span>
                                    </p>
                                </div>

                                {{-- Pelaporan Kecamatan --}}
                                <div class="hidden md:block text-right">
                                    <span class="text-[8px] xs:text-[9px] 2xl:text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Cakupan Wilayah</span>
                                    <p class="text-[11px] xs:text-xs 2xl:text-sm font-extrabold text-slate-700">
                                        {{ $item['kecamatan_melaporkan'] }} / {{ $item['total_kecamatan'] }} Kec.
                                    </p>
                                </div>

                                {{-- Status Ketersediaan --}}
                                <div class="shrink-0">
                                    <span class="inline-block text-[9px] xs:text-[10px] 2xl:text-xs font-bold px-2 xs:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg border {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </span>
                                </div>

                                {{-- Tombol Chevron Accordion --}}
                                <button type="button" class="w-6 h-6 xs:w-7 xs:h-7 2xl:w-8 2xl:h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-500 flex items-center justify-center transition shrink-0" title="Lihat Rincian Kecamatan">
                                    <svg id="modal-icon-{{ $item['komoditas_id'] }}" class="w-3.5 h-3.5 xs:w-4 xs:h-4 2xl:w-5 2xl:h-5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Accordion Panel: Rincian 11 Kecamatan --}}
                        <div id="modal-rincian-{{ $item['komoditas_id'] }}" class="hidden border-t border-slate-100 bg-slate-50/70 p-2.5 xs:p-3 sm:p-4 2xl:p-6">
                            <div class="flex flex-col xs:flex-row xs:items-center justify-between gap-1 mb-2.5 2xl:mb-4">
                                <p class="text-[10px] xs:text-[11px] 2xl:text-sm font-extrabold text-slate-500 uppercase tracking-wider">
                                    Rincian Stok Kondisi Terakhir per Kecamatan (11 Wilayah)
                                </p>
                                <span class="text-[9px] xs:text-[10px] 2xl:text-xs text-slate-400 font-medium">
                                    Total Daerah: <strong class="text-slate-800">{{ number_format((float) $item['total_stok'], 0, ',', '.') }} {{ $item['satuan'] }}</strong>
                                </span>
                            </div>

                            {{-- Grid Kecamatan: 1 Kolom di Foldables (<380px), 2 di Mobile, 3 di Tablet, 4 di Desktop, 6 di Smart Display (2K+) --}}
                            <div class="grid grid-cols-1 min-[380px]:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-6 gap-1.5 xs:gap-2 sm:gap-2.5 2xl:gap-3">
                                @foreach($item['rincian'] as $kecRow)
                                    @php
                                        $kecStatusKey = strtolower($kecRow['status_ketersediaan'] ?? 'belum_ada');
                                        $kecDot = match($kecStatusKey) {
                                            'aman' => 'bg-emerald-500',
                                            'waspada' => 'bg-amber-500',
                                            'kritis' => 'bg-rose-500',
                                            default => 'bg-slate-300',
                                        };
                                        $hasStok = $kecRow['stok'] !== null;
                                    @endphp
                                    <div class="bg-white p-2 xs:p-2.5 sm:p-3 2xl:p-3.5 rounded-lg sm:rounded-xl border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <span class="font-bold text-[11px] xs:text-xs 2xl:text-sm text-slate-800 truncate" title="{{ $kecRow['nama_kecamatan'] }}">
                                                {{ $kecRow['nama_kecamatan'] }}
                                            </span>
                                            <span class="w-1.5 h-1.5 xs:w-2 xs:h-2 2xl:w-2.5 2xl:h-2.5 rounded-full {{ $kecDot }} shrink-0" title="Status: {{ ucfirst($kecStatusKey) }}"></span>
                                        </div>
                                        <div class="mt-0.5 xs:mt-1">
                                            @if($hasStok)
                                                <p class="text-xs xs:text-sm 2xl:text-base font-black text-slate-900 leading-tight">
                                                    {{ number_format((float) $kecRow['stok'], 0, ',', '.') }}
                                                    <span class="text-[9px] xs:text-[10px] 2xl:text-xs font-bold text-slate-500">{{ $item['satuan'] }}</span>
                                                </p>
                                                <div class="flex items-center justify-between text-[8px] xs:text-[9px] 2xl:text-[11px] text-slate-400 mt-0.5">
                                                    <span>{{ $kecRow['tanggal_update'] ?? '-' }}</span>
                                                    <span class="font-semibold text-slate-600 capitalize">{{ $kecStatusKey }}</span>
                                                </div>
                                            @else
                                                <p class="text-[11px] xs:text-xs 2xl:text-sm font-semibold text-slate-400 italic">Belum Ada Data</p>
                                                <span class="text-[8px] xs:text-[9px] text-slate-300">-</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs sm:text-sm">
                        Belum ada data komoditas pangan yang dapat ditampilkan.
                    </div>
                @endforelse
            </div>

            {{-- Footer Modal --}}
            <div class="px-3.5 xs:px-4 sm:px-6 2xl:px-8 py-2.5 xs:py-3 sm:py-3.5 2xl:py-4.5 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-2.5 sm:gap-3 shrink-0">
                <p class="text-[10px] xs:text-[11px] 2xl:text-xs text-slate-500 text-center sm:text-left">
                    <span class="font-bold text-slate-700">* Catatan:</span> Total dihitung dari laporan stok terakhir di setiap kecamatan Kabupaten Gorontalo Utara.
                </p>
                <button type="button" onclick="closeTotalStokModal()" class="w-full sm:w-auto px-5 py-2 2xl:py-2.5 2xl:px-7 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs 2xl:text-sm transition shadow-sm cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
