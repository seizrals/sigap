{{-- Modal Total Stok Pangan Daerah (Kondisi Terakhir di Seluruh Kecamatan) --}}
<div id="modalTotalStokDaerah" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" onclick="closeTotalStokModal()"></div>

    <div class="min-h-full flex items-center justify-center p-3 sm:p-5">
        <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh] transition-all">
            
            {{-- Header Modal --}}
            <div class="px-5 sm:px-7 py-4 sm:py-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-gradient-to-r from-slate-50 via-white to-blue-50/40 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/25 shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-snug truncate" id="modal-title">
                            Total Stok Pangan Daerah
                        </h3>
                        <p class="text-xs text-slate-500 font-medium truncate">
                            Akumulasi stok ketersediaan pangan kondisi terakhir di 11 Kecamatan Kab. Gorontalo Utara
                        </p>
                    </div>
                </div>

                <button type="button" onclick="closeTotalStokModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition shrink-0 cursor-pointer text-xl font-bold leading-none" title="Tutup Modal">
                    &times;
                </button>
            </div>

            {{-- Ringkasan 4 Card Total Stok Daerah & Search Filter --}}
            <div class="px-5 sm:px-7 py-4 bg-slate-50/70 border-b border-slate-100 shrink-0 space-y-4">
                {{-- 4 Card Ringkasan Dipindahkan ke Popup --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-3.5">
                    {{-- Card 1: Komoditas Pantauan --}}
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white rounded-2xl p-3.5 sm:p-4 shadow-md shadow-blue-500/20 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-blue-100">Komoditas Pantauan</span>
                                <div class="w-6 h-6 rounded-lg bg-white/15 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <span class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $stokPanganRingkasan['totalKomoditas'] ?? count($totalStokDaerah) }}</span>
                                <span class="text-[11px] font-bold text-blue-200">Komoditas</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-blue-100/80 mt-2.5 pt-2 border-t border-white/10 flex items-center justify-between">
                            <span>Terdata: <strong>{{ $stokPanganRingkasan['totalKomoditasTerdata'] ?? 0 }}</strong> komoditas</span>
                            <span>Pangan Pokok</span>
                        </p>
                    </div>

                    {{-- Card 2: Kondisi Aman --}}
                    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 text-white rounded-2xl p-3.5 sm:p-4 shadow-md shadow-emerald-500/20 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-emerald-100">Kondisi Aman</span>
                                <div class="w-6 h-6 rounded-lg bg-white/15 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <span class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $stokPanganRingkasan['komoditasAmanCount'] ?? 0 }}</span>
                                <span class="text-[11px] font-bold text-emerald-200">Komoditas</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-emerald-100/80 mt-2.5 pt-2 border-t border-white/10">
                            Ketersediaan aman di seluruh wilayah
                        </p>
                    </div>

                    {{-- Card 3: Perlu Perhatian --}}
                    <div class="bg-gradient-to-br from-amber-500 via-amber-600 to-orange-700 text-white rounded-2xl p-3.5 sm:p-4 shadow-md shadow-amber-500/20 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-amber-100">Perlu Perhatian</span>
                                <div class="w-6 h-6 rounded-lg bg-white/15 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <span class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $stokPanganRingkasan['komoditasWaspadaKritisCount'] ?? 0 }}</span>
                                <span class="text-[11px] font-bold text-amber-200">Komoditas</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-amber-100/80 mt-2.5 pt-2 border-t border-white/10 flex items-center justify-between">
                            <span>{{ $stokPanganRingkasan['komoditasKritisCount'] ?? 0 }} Kritis</span>
                            <span>{{ $stokPanganRingkasan['komoditasWaspadaCount'] ?? 0 }} Waspada</span>
                        </p>
                    </div>

                    {{-- Card 4: Total Stok Daerah & Cakupan Wilayah --}}
                    <div class="bg-gradient-to-br from-slate-800 via-slate-900 to-blue-950 text-white rounded-2xl p-3.5 sm:p-4 shadow-md shadow-slate-900/25 relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-blue-500/10 rounded-full blur-lg pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-slate-300">Total Stok Daerah</span>
                                <div class="w-6 h-6 rounded-lg bg-white/15 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1.5 mt-0.5">
                                <span class="text-2xl sm:text-3xl font-black tracking-tight text-white">11</span>
                                <span class="text-[11px] font-bold text-amber-300">Kecamatan</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-300 mt-2.5 pt-2 border-t border-white/10 flex items-center justify-between">
                            <span>Kab. Gorontalo Utara</span>
                            <span class="text-amber-300 font-semibold">100% Terpantau</span>
                        </p>
                    </div>
                </div>

                {{-- Kolom Pencarian Cepat --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <input type="text" id="modalStokSearch" onkeyup="filterModalKomoditas(this.value)" placeholder="Cari nama komoditas pangan (misal: Beras, Cabai Rawit, Minyak, Bawang)..." class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs sm:text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs placeholder:text-slate-400 transition">
                </div>
            </div>

            {{-- Body List Komoditas & Rincian Kecamatan --}}
            <div class="p-4 sm:p-7 overflow-y-auto space-y-3 flex-1" id="modalStokListWrapper">
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

                    <div class="modal-komoditas-row border border-slate-200 rounded-2xl bg-white hover:border-slate-300 transition shadow-2xs overflow-hidden" data-nama="{{ strtolower($item['nama_komoditas']) }}">
                        {{-- Baris Ringkasan Komoditas --}}
                        <div class="p-3.5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white cursor-pointer select-none" onclick="toggleModalRincian({{ $item['komoditas_id'] }})">
                            <div class="flex items-start sm:items-center gap-3 min-w-0 flex-1">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-sm shrink-0 border border-blue-100">
                                    {{ substr($item['nama_komoditas'], 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm sm:text-base font-extrabold text-slate-900 truncate">
                                        {{ $item['nama_komoditas'] }}
                                    </h4>
                                    <p class="text-[11px] text-slate-400 font-medium">
                                        Satuan: <span class="font-bold text-slate-600">{{ $item['satuan'] }}</span> &bull; Terakhir: {{ $item['tanggal_terakhir'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-3 sm:gap-6 shrink-0">
                                {{-- Total Stok Daerah --}}
                                <div class="text-left sm:text-right">
                                    <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Total Stok Daerah</span>
                                    <p class="text-sm sm:text-base font-black text-slate-900">
                                        {{ number_format((float) $item['total_stok'], 0, ',', '.') }}
                                        <span class="text-xs font-bold text-slate-500">{{ $item['satuan'] }}</span>
                                    </p>
                                </div>

                                {{-- Pelaporan Kecamatan --}}
                                <div class="hidden md:block text-right">
                                    <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Cakupan Wilayah</span>
                                    <p class="text-xs font-extrabold text-slate-700">
                                        {{ $item['kecamatan_melaporkan'] }} / {{ $item['total_kecamatan'] }} Kec.
                                    </p>
                                </div>

                                {{-- Status Ketersediaan --}}
                                <div class="shrink-0">
                                    <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-lg border {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </span>
                                </div>

                                {{-- Tombol Chevron Accordion --}}
                                <button type="button" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-500 flex items-center justify-center transition" title="Lihat Rincian Kecamatan">
                                    <svg id="modal-icon-{{ $item['komoditas_id'] }}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Accordion Panel: Rincian 11 Kecamatan --}}
                        <div id="modal-rincian-{{ $item['komoditas_id'] }}" class="hidden border-t border-slate-100 bg-slate-50/70 p-3.5 sm:p-4">
                            <div class="flex items-center justify-between mb-2.5">
                                <p class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                                    Rincian Stok Kondisi Terakhir per Kecamatan (11 Wilayah)
                                </p>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    Total Daerah: <strong class="text-slate-800">{{ number_format((float) $item['total_stok'], 0, ',', '.') }} {{ $item['satuan'] }}</strong>
                                </span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
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
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <span class="font-bold text-xs text-slate-800 truncate" title="{{ $kecRow['nama_kecamatan'] }}">
                                                {{ $kecRow['nama_kecamatan'] }}
                                            </span>
                                            <span class="w-2 h-2 rounded-full {{ $kecDot }} shrink-0" title="Status: {{ ucfirst($kecStatusKey) }}"></span>
                                        </div>
                                        <div class="mt-1">
                                            @if($hasStok)
                                                <p class="text-xs sm:text-sm font-black text-slate-900">
                                                    {{ number_format((float) $kecRow['stok'], 0, ',', '.') }}
                                                    <span class="text-[10px] font-bold text-slate-500">{{ $item['satuan'] }}</span>
                                                </p>
                                                <div class="flex items-center justify-between text-[9px] text-slate-400 mt-0.5">
                                                    <span>{{ $kecRow['tanggal_update'] ?? '-' }}</span>
                                                    <span class="font-semibold text-slate-600 capitalize">{{ $kecStatusKey }}</span>
                                                </div>
                                            @else
                                                <p class="text-xs font-semibold text-slate-400 italic">Belum Ada Data</p>
                                                <span class="text-[9px] text-slate-300">-</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm">
                        Belum ada data komoditas pangan yang dapat ditampilkan.
                    </div>
                @endforelse
            </div>

            {{-- Footer Modal --}}
            <div class="px-5 sm:px-7 py-3.5 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
                <p class="text-[11px] text-slate-500">
                    <span class="font-bold text-slate-700">* Catatan:</span> Total dihitung dari laporan stok terakhir di setiap kecamatan Kabupaten Gorontalo Utara.
                </p>
                <button type="button" onclick="closeTotalStokModal()" class="w-full sm:w-auto px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition shadow-sm cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
