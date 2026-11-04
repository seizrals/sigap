{{-- Card Total Stok Pangan Daerah (Kondisi Terakhir Seluruh Kecamatan Saat Komoditas Tertentu Dipilih) --}}
@if(!empty($stokPanganRingkasan['selectedKomoditasStok']))
    @php
        $item = $stokPanganRingkasan['selectedKomoditasStok'];
        $statusKey = strtolower($item['status_ketersediaan'] ?? 'belum_ada');
        $bgGradient = match($statusKey) {
            'aman' => 'from-emerald-600 via-teal-600 to-emerald-700 shadow-emerald-500/20',
            'waspada' => 'from-amber-500 via-amber-600 to-orange-600 shadow-amber-500/20',
            'kritis' => 'from-rose-600 via-rose-700 to-red-700 shadow-rose-500/20',
            default => 'from-slate-700 via-slate-800 to-slate-900 shadow-slate-900/20',
        };
        $statusBadge = match($statusKey) {
            'aman' => 'bg-emerald-400/20 text-emerald-100 border-emerald-300/30',
            'waspada' => 'bg-amber-300/20 text-amber-100 border-amber-300/30',
            'kritis' => 'bg-rose-400/20 text-rose-100 border-rose-300/30',
            default => 'bg-slate-500/30 text-slate-200 border-slate-400/20',
        };
        $statusText = match($statusKey) {
            'aman' => 'Aman',
            'waspada' => 'Waspada',
            'kritis' => 'Kritis',
            default => 'Belum Ada Data',
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 sm:gap-4 items-stretch mb-5">
        {{-- Card Utama: Total Stok Komoditas Terpilih --}}
        <div class="lg:col-span-6 2xl:col-span-5 bg-gradient-to-br {{ $bgGradient }} rounded-2xl sm:rounded-3xl p-5 sm:p-6 text-white shadow-lg relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 backdrop-blur-md text-[10px] font-extrabold uppercase tracking-wider text-white border border-white/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        Total Stok Daerah
                    </span>
                    <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-lg border {{ $statusBadge }} backdrop-blur-sm">
                        {{ $statusText }}
                    </span>
                </div>

                <p class="text-xs sm:text-sm font-medium text-white/80">Kondisi Terakhir di Seluruh Kecamatan</p>
                <h3 class="text-lg sm:text-xl font-black text-white mt-0.5 tracking-tight truncate" title="{{ $item['nama_komoditas'] }}">
                    {{ $item['nama_komoditas'] }}
                </h3>

                <div class="mt-3 sm:mt-4 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl 2xl:text-5xl font-black tracking-tight drop-shadow-sm">
                        {{ number_format((float) $item['total_stok'], 0, ',', '.') }}
                    </span>
                    <span class="text-base sm:text-lg font-bold text-white/90">
                        {{ $item['satuan'] }}
                    </span>
                </div>
            </div>

            <div class="relative z-10 pt-4 mt-3 border-t border-white/15 flex flex-wrap items-center justify-between gap-2 text-xs text-white/85">
                <div class="flex items-center gap-1.5 font-semibold text-[11px] sm:text-xs">
                    <svg class="w-4 h-4 text-white/80 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span>{{ $item['kecamatan_melaporkan'] }} dari {{ $item['total_kecamatan'] }} Kecamatan Terdata</span>
                </div>
                <span class="text-[10px] text-white/70">Update: {{ $item['tanggal_terakhir'] }}</span>
            </div>
        </div>

        {{-- Card Kedua: Informasi Cakupan & Kecamatan Terpilih --}}
        <div class="lg:col-span-6 2xl:col-span-7 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-2 mb-3">
                    <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Rincian Ketersediaan Wilayah</p>
                    <button type="button" onclick="openTotalStokModal({{ $item['komoditas_id'] }})" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1 cursor-pointer">
                        <span>Lihat 11 Kecamatan</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </button>
                </div>

                {{-- Progress Pelaporan Kecamatan --}}
                <div class="space-y-1.5 mb-4">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                        <span>Cakupan Laporan Kecamatan</span>
                        <span>{{ $item['persentase_pelaporan'] }}% ({{ $item['kecamatan_melaporkan'] }}/{{ $item['total_kecamatan'] }})</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $item['persentase_pelaporan'] }}%"></div>
                    </div>
                </div>

                {{-- Status Khusus Jika Ada Filter Kecamatan --}}
                @if(!empty($stokPanganRingkasan['selectedKecamatanId']))
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kecamatan Terpilih</span>
                            <p class="text-sm font-extrabold text-slate-800">{{ $stokPanganRingkasan['namaKecamatanTerpilih'] }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stok di Kecamatan</span>
                            <p class="text-sm font-black text-blue-600">
                                @if($stokPanganRingkasan['stokKecamatanTerpilih'] !== null)
                                    {{ number_format((float) $stokPanganRingkasan['stokKecamatanTerpilih'], 0, ',', '.') }} {{ $item['satuan'] }}
                                @else
                                    <span class="text-slate-400 font-medium">Belum Ada Data</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 pt-1">
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 text-center">
                            <span class="text-[9px] font-bold uppercase text-slate-400 block">Status Daerah</span>
                            <span class="text-xs font-black {{ $statusKey === 'aman' ? 'text-emerald-600' : ($statusKey === 'waspada' ? 'text-amber-600' : ($statusKey === 'kritis' ? 'text-rose-600' : 'text-slate-500')) }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 text-center">
                            <span class="text-[9px] font-bold uppercase text-slate-400 block">Kec. Melaporkan</span>
                            <span class="text-xs font-black text-slate-800">{{ $item['kecamatan_melaporkan'] }} Kec.</span>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 text-center col-span-2 sm:col-span-1">
                            <span class="text-[9px] font-bold uppercase text-slate-400 block">Belum Lapor</span>
                            <span class="text-xs font-black text-slate-500">{{ $item['total_kecamatan'] - $item['kecamatan_melaporkan'] }} Kec.</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full {{ $statusKey === 'aman' ? 'bg-emerald-500' : ($statusKey === 'waspada' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                    Data diakumulasi dari input kondisi terakhir seluruh kecamatan
                </span>
                <button type="button" onclick="openTotalStokModal({{ $item['komoditas_id'] }})" class="font-bold text-blue-600 hover:text-blue-800 transition">
                    Rincian Lengkap &rarr;
                </button>
            </div>
        </div>
    </div>
@endif
