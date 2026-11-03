@extends('layouts.app')

@section('title', 'Monitor TPID - SIGAP+')
@section('page-title', 'Monitor TPID Real-Time')

@section('content')
<div class="space-y-6">

    {{-- Quick Status Cards --}}
    
    {{-- GIS Map Section + Rekomendasi Strategis --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 2xl:gap-8">
        <div class="lg:col-span-8 2xl:col-span-8 bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden flex flex-col relative z-0 isolate">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-6 border-b border-slate-100 gap-3">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
                        Peta GIS Kerawanan Wilayah
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Visualisasi zona risiko inflasi per kecamatan di Kabupaten Gorontalo Utara</p>
                    <p class="text-sm text-slate-500 mt-1">Titik peta ditampilkan dari data {{ $mapFilterType === 'pangan' ? 'pangan strategis' : ($mapFilterType === 'lpg' ? 'LPG 3 KG' : 'BBM subsidi') }} per kecamatan.</p>
                </div>
                <div class="flex items-center gap-3 text-xs shrink-0">
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span><span class="font-semibold text-slate-600">Aman</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500"></span><span class="font-semibold text-slate-600">Waspada</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span><span class="font-semibold text-slate-600">Bahaya</span></div>
                </div>
            </div>
            <div id="gisMap" class="h-[220px] sm:h-[280px] lg:h-[350px] 2xl:h-[480px] w-full relative z-0 isolate"></div>
        </div>

        <div class="lg:col-span-4 2xl:col-span-4 bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5 text-fuchsia-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M3.75 6h.75m-.75 6h.75m-.75 6h.75m6-6h9.75m-9.75 6h9.75M6 5.625a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 6a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 6a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                    <h3 class="text-base font-bold text-slate-900">Filter Tampilan Peta</h3>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pilih jenis data yang ingin ditampilkan pada peta. Klik titik kecamatan untuk melihat detail hasil filter.
                </p>
            </div>
            <div class="p-4 space-y-4">
                <form method="GET" action="{{ route('dashboard') }}" class="space-y-4" id="map-filter-form">
                    @if(request('tab'))
                        <input type="hidden" name="tab" value="{{ request('tab') }}">
                    @endif
                    <div>
                        <label for="map_filter_type" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Data Peta</label>
                        <select id="map_filter_type" name="map_filter_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-sm">
                            <option value="pangan" @selected($mapFilterType === 'pangan')>Pangan Strategis</option>
                            <option value="lpg" @selected($mapFilterType === 'lpg')>LPG 3 KG</option>
                            <option value="bbm" @selected($mapFilterType === 'bbm')>BBM Subsidi</option>
                        </select>
                    </div>

                    <div id="map-komoditas-wrapper" class="{{ $mapFilterType === 'pangan' ? '' : 'hidden' }}">
                        <label for="map_komoditas_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Komoditas Pangan</label>
                        <select id="map_komoditas_id" name="map_komoditas_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-sm">
                            <option value="">Semua Komoditas</option>
                            @foreach($komoditasPangan as $item)
                                <option value="{{ $item->id }}" @selected((string) $mapKomoditasId === (string) $item->id)>{{ $item->nama_komoditas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-sm">
                        Terapkan Filter Peta
                    </button>
                </form>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 space-y-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Filter Aktif</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">
                            {{ $mapFilterType === 'pangan' ? 'Pangan Strategis' : ($mapFilterType === 'lpg' ? 'LPG 3 KG' : 'BBM Subsidi') }}
                        </p>
                        @if($mapFilterType === 'pangan' && $mapFilterKomoditas)
                            <p class="text-xs text-slate-500 mt-1">Komoditas: {{ $mapFilterKomoditas->nama_komoditas }}</p>
                        @elseif($mapFilterType === 'pangan')
                            <p class="text-xs text-slate-500 mt-1">Komoditas: Semua komoditas strategis</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white border border-slate-200 px-3 py-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Titik Aktif</p>
                            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $activePointsCount }}</p>
                        </div>
                        <div class="rounded-xl bg-white border border-slate-200 px-3 py-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sumber Data</p>
                            <p class="text-sm font-bold text-slate-900 mt-2">{{ $mapFilterType === 'pangan' ? 'Data Pangan' : ($mapFilterType === 'lpg' ? 'Data LPG' : 'Data BBM') }}</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white border border-slate-200 px-3 py-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Petunjuk</p>
                        <ul class="space-y-1 text-xs text-slate-600">
                            <li>Klik marker kecamatan untuk melihat detail.</li>
                            <li>Warna marker mengikuti status ketersediaan data terbaru.</li>
                            <li>Data ditarik langsung dari tabel sesuai filter aktif.</li>
                        </ul>
                    </div>
                </div>

                @if($mapPoints->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-xs text-slate-400">
                        Belum ada titik peta yang bisa ditampilkan untuk filter ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tab Navigation & Content --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-1 sm:gap-2 border-b border-slate-100 px-3 sm:px-6 overflow-x-auto scrollbar-none">
            <button id="btn-iph-mingguan" onclick="switchTab('iph-mingguan')" class="tab-btn whitespace-nowrap px-3 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-all">
                DATA IPH MINGGUAN
            </button>
            <button id="btn-iph-bulanan" onclick="switchTab('iph-bulanan')" class="tab-btn whitespace-nowrap px-3 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">
                DATA IPH BULANAN
            </button>
            <button id="btn-pangan" onclick="switchTab('pangan')" class="tab-btn whitespace-nowrap px-3 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">
                PANGAN STRATEGIS
            </button>
            <button id="btn-lpg" onclick="switchTab('lpg')" class="tab-btn whitespace-nowrap px-3 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">LPG 3 KG</button>
            <button id="btn-bbm" onclick="switchTab('bbm')" class="tab-btn whitespace-nowrap px-3 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">BBM SUBSIDI</button>
        </div>

        <div class="p-3.5 sm:p-6">
            {{-- Tab IPH Mingguan--}}
            <div id="content-iph-mingguan" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-start">
                    <div class="lg:col-span-5 2xl:col-span-4 space-y-4">
                        <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-100 space-y-4">
                            <input type="hidden" name="tab" value="iph-mingguan">
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-extrabold text-slate-400 tracking-wider uppercase">Filter Rentang IPH Mingguan</p>
                                <a href="{{ route('input.iph.index') }}" class="text-[11px] text-emerald-600 font-bold hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    Input Excel
                                </a>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Tahun</label>
                                    <select name="iph_mingguan_tahun" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                                        @foreach($daftarTahunMingguan as $thn)
                                            <option value="{{ $thn }}" @selected((string) ($filterTahunMingguan ?? date('Y')) === (string) $thn)>{{ $thn }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Dari Minggu</label>
                                        <select name="iph_mingguan_dari_minggu" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm">
                                            <option value="I" @selected(($filterDariMinggu ?? 'I') === 'I' || ($filterDariMinggu ?? '') === '1')>Minggu I</option>
                                            <option value="II" @selected(($filterDariMinggu ?? '') === 'II' || ($filterDariMinggu ?? '') === '2')>Minggu II</option>
                                            <option value="III" @selected(($filterDariMinggu ?? '') === 'III' || ($filterDariMinggu ?? '') === '3')>Minggu III</option>
                                            <option value="IV" @selected(($filterDariMinggu ?? '') === 'IV' || ($filterDariMinggu ?? '') === '4')>Minggu IV</option>
                                            <option value="V" @selected(($filterDariMinggu ?? '') === 'V' || ($filterDariMinggu ?? '') === '5')>Minggu V</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ke Minggu</label>
                                        <select name="iph_mingguan_sampai_minggu" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm">
                                            <option value="I" @selected(($filterSampaiMinggu ?? '') === 'I' || ($filterSampaiMinggu ?? '') === '1')>Minggu I</option>
                                            <option value="II" @selected(($filterSampaiMinggu ?? '') === 'II' || ($filterSampaiMinggu ?? '') === '2')>Minggu II</option>
                                            <option value="III" @selected(($filterSampaiMinggu ?? '') === 'III' || ($filterSampaiMinggu ?? '') === '3')>Minggu III</option>
                                            <option value="IV" @selected(($filterSampaiMinggu ?? 'IV') === 'IV' || ($filterSampaiMinggu ?? '') === '4' || empty($filterSampaiMinggu))>Minggu IV</option>
                                            <option value="V" @selected(($filterSampaiMinggu ?? '') === 'V' || ($filterSampaiMinggu ?? '') === '5')>Minggu V</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    Terapkan Filter Minggu
                                </button>
                            </div>
                        </form>
                        @php
                            $valMingguan = $iphMingguanTerbaru ? (float) $iphMingguanTerbaru->iph_kabupaten : 2.33;
                            $statusMingguan = $valMingguan > 3.5 || $valMingguan < -2.5 ? 'Tinggi' : ($valMingguan >= 2.5 ? 'Waspada' : 'Terkendali');
                            $bgMingguan = $statusMingguan === 'Tinggi' ? 'bg-rose-600 shadow-rose-600/20' : ($statusMingguan === 'Waspada' ? 'bg-amber-600 shadow-amber-600/20' : 'bg-emerald-600 shadow-emerald-600/20');
                            $badgeMingguan = $statusMingguan === 'Tinggi' ? 'bg-rose-500/50 border-rose-400/30' : ($statusMingguan === 'Waspada' ? 'bg-amber-500/50 border-amber-400/30' : 'bg-emerald-500/50 border-emerald-400/30');
                        @endphp
                        <div class="{{ $bgMingguan }} rounded-2xl p-4 sm:p-5 text-white shadow-lg transition">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-100 mb-1">{{ $iphMingguanTerbaru?->periode_label ?? 'IPH Minggu II Mei' }}</p>
                            <p class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">{{ number_format($valMingguan, 2, ',', '.') }}%</p>
                            <span class="inline-block {{ $badgeMingguan }} backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border">
                                Status: {{ $statusMingguan }}
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-7 2xl:col-span-8 relative h-[260px] sm:h-[320px] lg:h-[360px] 2xl:h-[480px] w-full min-w-0">
                        <canvas id="iphChartMingguan"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tab IPH Bulanan--}}
            <div id="content-iph-bulanan" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-start">
                    <div class="lg:col-span-5 2xl:col-span-4 space-y-4">
                        <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-100 space-y-4">
                            <input type="hidden" name="tab" value="iph-bulanan">
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-extrabold text-slate-400 tracking-wider uppercase">Filter Rentang IPH Bulanan</p>
                                <a href="{{ route('input.iph_bulanan') }}" class="text-[11px] text-blue-600 font-bold hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    Input Excel
                                </a>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Tahun</label>
                                    <select name="iph_bulanan_tahun" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                                        @foreach($daftarTahunBulanan as $thn)
                                            <option value="{{ $thn }}" @selected((string) ($filterTahunBulanan ?? date('Y')) === (string) $thn)>{{ $thn }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Dari Bulan</label>
                                        <select name="iph_bulanan_dari_bulan" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm">
                                            @foreach([
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ] as $num => $nama)
                                                <option value="{{ $num }}" @selected((string) ($filterDariBulan ?? '1') === (string) $num)>{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ke Bulan</label>
                                        <select name="iph_bulanan_sampai_bulan" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm">
                                            @foreach([
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ] as $num => $nama)
                                                <option value="{{ $num }}" @selected((string) ($filterSampaiBulan ?? '12') === (string) $num)>{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    Terapkan Filter Bulan
                                </button>
                            </div>
                        </form>
                        @php
                            $valBulanan = $iphBulananTerbaru ? (float) $iphBulananTerbaru->iph_bulanan : 1.85;
                            $statusBulanan = $valBulanan > 3.5 || $valBulanan < -2.5 ? 'Tinggi' : ($valBulanan >= 2.5 ? 'Waspada' : 'Terkendali');
                            $bgBulanan = $statusBulanan === 'Tinggi' ? 'bg-rose-600 shadow-rose-600/20' : ($statusBulanan === 'Waspada' ? 'bg-amber-600 shadow-amber-600/20' : 'bg-blue-600 shadow-blue-600/20');
                            $badgeBulanan = $statusBulanan === 'Tinggi' ? 'bg-rose-500/50 border-rose-400/30' : ($statusBulanan === 'Waspada' ? 'bg-amber-500/50 border-amber-400/30' : 'bg-blue-500/50 border-blue-400/30');
                        @endphp
                        <div class="{{ $bgBulanan }} rounded-2xl p-4 sm:p-5 text-white shadow-lg transition">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-100 mb-1">{{ $iphBulananTerbaru ? ($iphBulananTerbaru->nama_bulan . ' ' . $iphBulananTerbaru->tahun) : 'Mei 2026' }}</p>
                            <p class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">{{ number_format($valBulanan, 2, ',', '.') }}%</p>
                            <span class="inline-block {{ $badgeBulanan }} backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border">
                                Status: {{ $statusBulanan }}
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-7 2xl:col-span-8 relative h-[260px] sm:h-[320px] lg:h-[360px] 2xl:h-[480px] w-full min-w-0">
                        <canvas id="iphChartBulanan"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tab Pangan --}}
            <div id="content-pangan" class="tab-content hidden">
                <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-3.5 sm:p-4 rounded-2xl mb-5 flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3 sm:gap-4 text-xs font-semibold text-slate-500">
                    <input type="hidden" name="tab" value="pangan">
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Kecamatan</span>
                        <select name="pangan_kecamatan_id" class="w-full sm:min-w-44 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                            <option value="">Seluruh Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" @selected((string) ($filters['pangan_kecamatan_id'] ?? '') === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Komoditas</span>
                        <select name="pangan_komoditas_id" class="w-full sm:min-w-48 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                            <option value="">Semua Komoditas</option>
                            @foreach($komoditasPangan as $item)
                                <option value="{{ $item->id }}" @selected((string) ($filters['pangan_komoditas_id'] ?? '') === (string) $item->id)>{{ $item->nama_komoditas }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Periode Lalu</span>
                        <input type="date" name="periode_sebelumnya" value="{{ $periodeSebelumnya }}" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Periode Saat Ini</span>
                        <input type="date" name="periode_saat_ini" value="{{ $periodeSaatIni }}" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                    </label>
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs transition-colors text-center">Terapkan</button>
                </form>
                <div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
                    <table class="min-w-[950px] w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="pb-4 pr-4">Kecamatan</th>
                                <th class="pb-4 pr-4">Nama Komoditas</th>
                                <th class="pb-4 px-4">Stok</th>
                                <th class="pb-4 px-4">Periode Lalu<br><span class="text-[10px] font-medium normal-case">{{ \Carbon\Carbon::parse($periodeSebelumnya)->format('d/m/Y') }}</span></th>
                                <th class="pb-4 px-4">Periode Saat Ini<br><span class="text-[10px] font-medium normal-case">{{ \Carbon\Carbon::parse($periodeSaatIni)->format('d/m/Y') }}</span></th>
                                <th class="pb-4 px-4">Perubahan</th>
                                <th class="pb-4 pl-4 text-right">Ketersediaan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                            @forelse($dataPangan as $d)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 pr-4 text-slate-700 font-bold whitespace-nowrap">{{ $d['kecamatan'] }}</td>
                                <td class="py-4 pr-4 whitespace-nowrap">
                                    <p class="font-bold text-slate-800">{{ $d['komoditas'] }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $d['satuan'] }}</p>
                                </td>
                                <td class="py-4 px-4 text-slate-600 whitespace-nowrap">{{ $d['stok'] !== null ? number_format((float) $d['stok'], 0, ',', '.') : '-' }} {{ $d['satuan'] }}</td>
                                <td class="py-4 px-4 text-slate-600 whitespace-nowrap">{{ $d['harga_lalu'] !== null ? 'Rp '.number_format((float) $d['harga_lalu'], 0, ',', '.') : '-' }}</td>
                                <td class="py-4 px-4 font-bold text-slate-800 whitespace-nowrap">{{ $d['harga_saat_ini'] !== null ? 'Rp '.number_format((float) $d['harga_saat_ini'], 0, ',', '.') : '-' }}</td>
                                <td class="py-4 px-4 whitespace-nowrap {{ ($d['perubahan_persen'] ?? 0) > 0 ? 'text-rose-500' : (($d['perubahan_persen'] ?? 0) < 0 ? 'text-emerald-500' : 'text-slate-400') }}">
                                    {{ $d['perubahan_persen'] === null ? '-' : (($d['perubahan_persen'] > 0 ? '+' : '').number_format((float) $d['perubahan_persen'], 1, ',', '.').'%') }}
                                </td>
                                <td class="py-4 pl-4 text-right whitespace-nowrap">
                                    <span class="inline-block text-[11px] font-bold px-3 py-1 rounded-full uppercase {{ $d['status_ketersediaan'] === 'aman' ? 'bg-emerald-100 text-emerald-700' : ($d['status_ketersediaan'] === 'waspada' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-600') }}">{{ $d['status_ketersediaan'] ? ucfirst($d['status_ketersediaan']) : '-' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data untuk filter dan periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab LPG --}}
            <div id="content-lpg" class="tab-content hidden">
                <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-3.5 sm:p-4 rounded-2xl mb-5 flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3 sm:gap-4 text-xs font-semibold text-slate-500">
                    <input type="hidden" name="tab" value="lpg">
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Kecamatan</span>
                        <select name="lpg_kecamatan_id" class="w-full sm:min-w-44 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-orange-500 shadow-sm">
                            <option value="">Semua Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" @selected((string) ($filters['lpg_kecamatan_id'] ?? request('lpg_kecamatan_id')) === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Tanggal</span>
                        <input type="date" name="lpg_tanggal" value="{{ $lpgTanggal ?? '' }}" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-orange-500 shadow-sm">
                    </label>
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs transition-colors text-center">Terapkan</button>
                </form>

                <div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
                    <table class="min-w-[1150px] w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="pb-4 pr-4">Tanggal</th>
                                <th class="pb-4 pr-4">Kecamatan</th>
                                <th class="pb-4 pr-4">Jenis LPG</th>
                                <th class="pb-4 px-3 text-right">Stok</th>
                                <th class="pb-4 px-3 text-right">Masuk</th>
                                <th class="pb-4 px-3 text-right">Keluar</th>
                                <th class="pb-4 px-3 text-right">Harga HET</th>
                                <th class="pb-4 px-3 text-right">Harga Eceran</th>
                                <th class="pb-4 px-3">Ketersediaan</th>
                                <th class="pb-4 px-3">Distribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                            @forelse($dataLpg as $d)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->tanggal_input?->format('d/m/Y') }}</td>
                                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->kecamatan?->nama_kecamatan ?? '-' }}</td>
                                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->jenis_lpg }}</td>
                                <td class="py-4 px-3 text-right whitespace-nowrap">{{ number_format($d->stok_tersedia_tabung, 0, ',', '.') }}</td>
                                <td class="py-4 px-3 text-right whitespace-nowrap">{{ number_format($d->distribusi_masuk_tabung, 0, ',', '.') }}</td>
                                <td class="py-4 px-3 text-right text-rose-600 whitespace-nowrap">{{ number_format($d->distribusi_keluar_tabung, 0, ',', '.') }}</td>
                                <td class="py-4 px-3 text-right whitespace-nowrap">{{ $d->harga_pangkalan !== null ? 'Rp '.number_format((float) $d->harga_pangkalan, 0, ',', '.') : '-' }}</td>
                                <td class="py-4 px-3 text-right whitespace-nowrap">{{ $d->harga_eceran_lapangan !== null ? 'Rp '.number_format((float) $d->harga_eceran_lapangan, 0, ',', '.') : '-' }}</td>
                                <td class="py-4 px-3 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $d->status_ketersediaan === 'aman' ? 'bg-emerald-100 text-emerald-700' : ($d->status_ketersediaan === 'waspada' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">{{ $d->status_ketersediaan }}</span>
                                </td>
                                <td class="py-4 px-3 whitespace-nowrap">{{ str_replace('_', ' ', $d->status_distribusi) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data LPG untuk filter ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab BBM --}}
            <div id="content-bbm" class="tab-content hidden">
                <div class="mb-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-3.5 sm:p-4 rounded-2xl flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3 sm:gap-4 text-xs font-semibold text-slate-500 w-full lg:w-auto">
                        <input type="hidden" name="tab" value="bbm">
                        <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                            <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Jenis BBM</span>
                            <select name="bbm_jenis" class="w-full sm:min-w-48 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                                <option value="">Semua Jenis BBM</option>
                                <option value="Pertalite (JBKP)" @selected(in_array($bbmJenis, ['Pertalite (JBKP)', 'Pertalite'], true))>Pertalite</option>
                                <option value="Bio Solar" @selected($bbmJenis === 'Bio Solar')>Bio Solar</option>
                            </select>
                        </label>
                        
                        <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                            <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Tanggal</span>
                            <input type="date" name="bbm_tanggal" value="{{ $bbmTanggal ?? '' }}" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                        </label>

                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors text-center">Terapkan</button>
                    </form>

                    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                        <div class="flex-1 sm:flex-initial bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">Pertalite</p>
                            <p class="text-xs font-black italic">Rp 10.000</p>
                        </div>
                        <div class="flex-1 sm:flex-initial bg-orange-500 text-white px-3 sm:px-4 py-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">BioSolar</p>
                            <p class="text-xs font-black italic">Rp 6.800</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
                    <table class="min-w-[1250px] w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="pb-4 pr-4">Tanggal</th>
                                <th class="pb-4 pr-4">Kecamatan</th>
                                <th class="pb-4 pr-4">SPBU</th>
                                <th class="pb-4 pr-4">Jenis BBM</th>
                                <th class="pb-4 px-3 text-right">Stok (KL)</th>
                                <th class="pb-4 px-3">Nelayan</th>
                                <th class="pb-4 px-3">Pertanian</th>
                                <th class="pb-4 px-3">Antrean</th>
                                <th class="pb-4 px-3">Ketersediaan</th>
                                <th class="pb-4 px-3 text-right">Harga Eceran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                            @forelse($dataBbm as $d)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->tanggal_input?->format('d/m/Y') }}</td>
                                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->kecamatan?->nama_kecamatan ?? $d->spbu?->kecamatan?->nama_kecamatan ?? '-' }}</td>
                                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->spbu?->nama_spbu ?? '-' }}</td>
                                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->jenis_bbm }}</td>
                                <td class="py-4 px-3 text-right">{{ $d->stok_tersedia_kl !== null ? number_format((float) $d->stok_tersedia_kl, 3, ',', '.') : '-' }}</td>
                                <td class="py-4 px-3">
                                    @if($d->distribusi_nelayan)
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200 whitespace-nowrap">
                                                <input type="checkbox" disabled checked class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600">
                                                Ya
                                            </span>
                                            <div class="text-[11px] text-slate-600 space-y-0.5 whitespace-nowrap bg-slate-50/70 p-1.5 rounded-lg border border-slate-200/60">
                                                <div>Distribusi Masuk: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_masuk_nelayan_kl ?? $d->kuota_nelayan_harian_kl) !== null ? number_format((float) ($d->distribusi_masuk_nelayan_kl ?? $d->kuota_nelayan_harian_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                                                <div>Distribusi Keluar: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_keluar_nelayan_kl ?? $d->realisasi_nelayan_kl) !== null ? number_format((float) ($d->distribusi_keluar_nelayan_kl ?? $d->realisasi_nelayan_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                                            </div>
                                        </div>
                                    @else
                                        <label class="inline-flex items-center gap-1.5 whitespace-nowrap text-slate-400 font-normal">
                                            <input type="checkbox" disabled class="w-3.5 h-3.5 rounded border-slate-300 text-slate-400">
                                            Tidak
                                        </label>
                                    @endif
                                </td>
                                <td class="py-4 px-3">
                                    @if($d->distribusi_pertanian)
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 whitespace-nowrap">
                                                <input type="checkbox" disabled checked class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-600">
                                                Ya
                                            </span>
                                            <div class="text-[11px] text-slate-600 space-y-0.5 whitespace-nowrap bg-slate-50/70 p-1.5 rounded-lg border border-slate-200/60">
                                                <div>Distribusi Masuk: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_masuk_pertanian_kl ?? $d->kuota_pertanian_harian_kl) !== null ? number_format((float) ($d->distribusi_masuk_pertanian_kl ?? $d->kuota_pertanian_harian_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                                                <div>Distribusi Keluar: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_keluar_pertanian_kl ?? $d->realisasi_pertanian_kl) !== null ? number_format((float) ($d->distribusi_keluar_pertanian_kl ?? $d->realisasi_pertanian_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                                            </div>
                                        </div>
                                    @else
                                        <label class="inline-flex items-center gap-1.5 whitespace-nowrap text-slate-400 font-normal">
                                            <input type="checkbox" disabled class="w-3.5 h-3.5 rounded border-slate-300 text-slate-400">
                                            Tidak
                                        </label>
                                    @endif
                                </td>
                                <td class="py-4 px-3 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ in_array($d->status_antrean, ['panjang', 'sangat_panjang'], true) ? 'bg-rose-100 text-rose-700' : ($d->status_antrean === 'pendek' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ str_replace('_', ' ', $d->status_antrean) }}</span>
                                </td>
                                <td class="py-4 px-3 whitespace-nowrap">{{ ucfirst($d->status_ketersediaan) }}</td>
                                <td class="py-4 px-3 text-right whitespace-nowrap">{{ $d->harga_jual_per_liter !== null ? 'Rp '.number_format((float) $d->harga_jual_per_liter, 0, ',', '.') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data untuk BBM dan tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-blue-600', 'font-bold', 'border-blue-600');
            btn.classList.add('text-slate-400', 'font-semibold', 'border-transparent');
        });
        document.getElementById(`content-${tabName}`).classList.remove('hidden');
        const activeBtn = document.getElementById(`btn-${tabName}`);
        activeBtn.classList.add('text-blue-600', 'font-bold', 'border-blue-600');
        activeBtn.classList.remove('text-slate-400', 'font-semibold', 'border-transparent');
    }

    document.addEventListener("DOMContentLoaded", function () {
        const requestedTab = new URLSearchParams(window.location.search).get('tab');
        if (requestedTab && document.getElementById(`content-${requestedTab}`)) {
            switchTab(requestedTab);
        }

        const mapFilterType = document.getElementById('map_filter_type');
        const mapKomoditasWrapper = document.getElementById('map-komoditas-wrapper');

        if (mapFilterType && mapKomoditasWrapper) {
            const toggleKomoditasFilter = () => {
                mapKomoditasWrapper.classList.toggle('hidden', mapFilterType.value !== 'pangan');
            };

            toggleKomoditasFilter();
            mapFilterType.addEventListener('change', toggleKomoditasFilter);
        }

        // --- 1. GRAFIK IPH MINGGUAN ---
        const ctxMingguan = document.getElementById('iphChartMingguan');
        if (ctxMingguan) {
            const ctx2d = ctxMingguan.getContext('2d');
            const gradient = ctx2d.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

            new Chart(ctx2d, {
                type: 'line',
                data: {
                    labels: @json($chartMingguanLabels),
                    datasets: [{
                        data: @json($chartMingguanValues),
                        borderColor: '#10b981',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.45,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'IPH: ' + context.parsed.y.toFixed(2).replace('.', ',') + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { size: 11, weight: '500' } }
                        },
                        y: {
                            ticks: {
                                color: '#64748b',
                                font: { size: 11 },
                                callback: function(value) { return value.toFixed(2).replace('.', ',') + '%'; }
                            },
                            grid: { color: '#f1f5f9' }
                        }
                    }
                }
            });
        }

        // --- 2. GRAFIK IPH BULANAN ---
        const ctxBulanan = document.getElementById('iphChartBulanan');
        if (ctxBulanan) {
            const ctxBulanan2d = ctxBulanan.getContext('2d');
            const gradientBulanan = ctxBulanan2d.createLinearGradient(0, 0, 0, 300);
            gradientBulanan.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
            gradientBulanan.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

            new Chart(ctxBulanan2d, {
                type: 'line',
                data: {
                    labels: @json($chartBulananLabels),
                    datasets: [{
                        data: @json($chartBulananValues),
                        borderColor: '#2563eb',
                        borderWidth: 3,
                        backgroundColor: gradientBulanan,
                        fill: true,
                        tension: 0.45,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'IPH Bulanan: ' + context.parsed.y.toFixed(2).replace('.', ',') + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { size: 11, weight: '500' } }
                        },
                        y: {
                            ticks: {
                                color: '#64748b',
                                font: { size: 11 },
                                callback: function(value) { return value.toFixed(2).replace('.', ',') + '%'; }
                            },
                            grid: { color: '#f1f5f9' }
                        }
                    }
                }
            });
        }

        // --- 3. INISIALISASI LEAFLET GIS ---
        const gisMapEl = document.getElementById('gisMap');
        if (gisMapEl) {
            const map = L.map('gisMap').setView([0.88, 122.75], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            const mapPoints = @json($mapPoints ?? []);
            const statusColorMap = {
                tidak_ada: '#cbd5e1', // Slate 300
                aman: '#10b981',
                waspada: '#f59e0b',
                kritis: '#ef4444'
            };
            const statusLabelMap = {
                tidak_ada: 'Belum Ada Data',
                aman: 'Aman',
                waspada: 'Waspada',
                kritis: 'Kritis'
            };

            const popupHtml = (point) => {
                const rows = (point.detail_items || []).map((item) => {
                    const lines = (item.baris || []).map((line) => `<li style="margin-top:2px;">${line}</li>`).join('');
                    return `
                        <div style="padding:8px 0;border-top:1px solid #e2e8f0;">
                            <div style="font-weight:700;color:#0f172a;">${item.judul}</div>
                            <ul style="margin:6px 0 0 18px;padding:0;color:#475569;font-size:12px;">
                                ${lines}
                            </ul>
                        </div>
                    `;
                }).join('');

                const status = statusLabelMap[point.status_ketersediaan] || point.status_ketersediaan || '-';

                return `
                    <div style="min-width:240px;max-width:320px;">
                        <div style="font-weight:800;color:#0f172a;font-size:14px;">${point.kecamatan}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">${point.kategori}</div>
                        <div style="font-size:12px;color:#334155;margin-top:6px;"><strong>Status:</strong> ${status}</div>
                        ${rows}
                    </div>
                `;
            };

            const bounds = [];
            mapPoints.forEach(function(point) {
                if (!point.latitude || !point.longitude) {
                    return;
                }

                const statusKey = (point.status_ketersediaan || '').toLowerCase();
                const color = statusColorMap[statusKey] || '#94a3b8';

                L.circleMarker([parseFloat(point.latitude), parseFloat(point.longitude)], {
                    radius: 12,
                    fillColor: color,
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.9
                }).addTo(map).bindPopup(popupHtml(point));

                bounds.push([parseFloat(point.latitude), parseFloat(point.longitude)]);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [25, 25] });
            }
        }
    });
</script>
@endpush
