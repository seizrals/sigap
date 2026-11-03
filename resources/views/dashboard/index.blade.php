@extends('layouts.app')

@section('title', 'Monitor TPID - SIGAP+')
@section('page-title', 'Monitor TPID Real-Time')

@section('content')
<div class="space-y-6">

    {{-- Quick Status Cards --}}
    
    {{-- GIS Map Section + Rekomendasi Strategis --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 2xl:gap-8 items-stretch">
        <div class="lg:col-span-8 2xl:col-span-8 bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden flex flex-col relative z-0 isolate h-full">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-6 border-b border-slate-100 gap-3 shrink-0">
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
            <div class="p-4 sm:p-5 flex-1 flex flex-col min-h-0">
                <div id="gisMap" class="w-full h-full min-h-[460px] sm:min-h-[500px] lg:min-h-[560px] rounded-2xl border border-slate-200/80 shadow-xs relative z-0 isolate overflow-hidden"></div>
            </div>
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
                        <select id="map_filter_type" name="map_filter_type" onchange="applyFilter(this.form)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                            <option value="pangan" @selected($mapFilterType === 'pangan')>Pangan Strategis</option>
                            <option value="lpg" @selected($mapFilterType === 'lpg')>LPG 3 KG</option>
                            <option value="bbm" @selected($mapFilterType === 'bbm')>BBM Subsidi</option>
                        </select>
                    </div>

                    <div id="map-komoditas-wrapper" class="{{ $mapFilterType === 'pangan' ? '' : 'hidden' }}">
                        <label for="map_komoditas_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Komoditas Pangan</label>
                        <select id="map_komoditas_id" name="map_komoditas_id" onchange="applyFilter(this.form)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                            <option value="">Semua Komoditas</option>
                            @foreach($komoditasPangan as $item)
                                <option value="{{ $item->id }}" @selected((string) $mapKomoditasId === (string) $item->id)>{{ $item->nama_komoditas }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 space-y-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Filter Aktif</p>
                        <p id="map-active-type" class="text-sm font-bold text-slate-800 mt-1">
                            {{ $mapFilterType === 'pangan' ? 'Pangan Strategis' : ($mapFilterType === 'lpg' ? 'LPG 3 KG' : 'BBM Subsidi') }}
                        </p>
                        <p id="map-active-komoditas" class="text-xs text-slate-500 mt-1 {{ $mapFilterType === 'pangan' ? '' : 'hidden' }}">
                            Komoditas: {{ $mapFilterKomoditas ? $mapFilterKomoditas->nama_komoditas : 'Semua komoditas strategis' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white border border-slate-200 px-3 py-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Titik Aktif</p>
                            <p id="map-active-count" class="text-2xl font-extrabold text-slate-900 mt-1">{{ $activePointsCount }}</p>
                        </div>
                        <div class="rounded-xl bg-white border border-slate-200 px-3 py-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sumber Data</p>
                            <p id="map-active-source" class="text-sm font-bold text-slate-900 mt-2">{{ $mapFilterType === 'pangan' ? 'Data Pangan' : ($mapFilterType === 'lpg' ? 'Data LPG' : 'Data BBM') }}</p>
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
                        <form id="iph-mingguan-form" method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-100 space-y-4">
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
                                    <select name="iph_mingguan_tahun" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                                        @foreach($daftarTahunMingguan as $thn)
                                            <option value="{{ $thn }}" @selected((string) ($filterTahunMingguan ?? date('Y')) === (string) $thn)>{{ $thn }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Dari Bulan</label>
                                        <select name="iph_mingguan_dari_bulan" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-emerald-500 shadow-sm cursor-pointer">
                                            <option value="">Semua Bulan</option>
                                            @foreach([
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ] as $num => $nama)
                                                <option value="{{ $num }}" @selected((string) ($filterDariBulanMingguan ?? '') === (string) $num)>{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ke Bulan</label>
                                        <select name="iph_mingguan_sampai_bulan" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-emerald-500 shadow-sm cursor-pointer">
                                            <option value="">Semua Bulan</option>
                                            @foreach([
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ] as $num => $nama)
                                                <option value="{{ $num }}" @selected((string) ($filterSampaiBulanMingguan ?? '') === (string) $num)>{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Dari Minggu</label>
                                        <select name="iph_mingguan_dari_minggu" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                                            <option value="I" @selected(($filterDariMinggu ?? 'I') === 'I' || ($filterDariMinggu ?? '') === '1')>Minggu I</option>
                                            <option value="II" @selected(($filterDariMinggu ?? '') === 'II' || ($filterDariMinggu ?? '') === '2')>Minggu II</option>
                                            <option value="III" @selected(($filterDariMinggu ?? '') === 'III' || ($filterDariMinggu ?? '') === '3')>Minggu III</option>
                                            <option value="IV" @selected(($filterDariMinggu ?? '') === 'IV' || ($filterDariMinggu ?? '') === '4')>Minggu IV</option>
                                            <option value="V" @selected(($filterDariMinggu ?? '') === 'V' || ($filterDariMinggu ?? '') === '5')>Minggu V</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ke Minggu</label>
                                        <select name="iph_mingguan_sampai_minggu" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                                            <option value="I" @selected(($filterSampaiMinggu ?? '') === 'I' || ($filterSampaiMinggu ?? '') === '1')>Minggu I</option>
                                            <option value="II" @selected(($filterSampaiMinggu ?? '') === 'II' || ($filterSampaiMinggu ?? '') === '2')>Minggu II</option>
                                            <option value="III" @selected(($filterSampaiMinggu ?? '') === 'III' || ($filterSampaiMinggu ?? '') === '3')>Minggu III</option>
                                            <option value="IV" @selected(($filterSampaiMinggu ?? 'IV') === 'IV' || ($filterSampaiMinggu ?? '') === '4' || empty($filterSampaiMinggu))>Minggu IV</option>
                                            <option value="V" @selected(($filterSampaiMinggu ?? '') === 'V' || ($filterSampaiMinggu ?? '') === '5')>Minggu V</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @php
                            $valMingguan = $iphMingguanTerbaru ? (float) $iphMingguanTerbaru->iph_kabupaten : 2.33;
                            $statusMingguan = $valMingguan > 3.5 || $valMingguan < -2.5 ? 'Tinggi' : ($valMingguan >= 2.5 ? 'Waspada' : 'Terkendali');
                            $bgMingguan = $statusMingguan === 'Tinggi' ? 'bg-rose-600 shadow-rose-600/20' : ($statusMingguan === 'Waspada' ? 'bg-amber-600 shadow-amber-600/20' : 'bg-emerald-600 shadow-emerald-600/20');
                            $badgeMingguan = $statusMingguan === 'Tinggi' ? 'bg-rose-500/50 border-rose-400/30' : ($statusMingguan === 'Waspada' ? 'bg-amber-500/50 border-amber-400/30' : 'bg-emerald-500/50 border-emerald-400/30');
                        @endphp
                        <div id="iph-mingguan-card" class="{{ $bgMingguan }} rounded-2xl p-4 sm:p-5 text-white shadow-lg transition">
                            <p id="iph-mingguan-periode" class="text-[10px] font-bold uppercase tracking-wider text-emerald-100 mb-1">{{ $iphMingguanTerbaru?->periode_label ?? 'IPH Minggu II Mei' }}</p>
                            <p id="iph-mingguan-val" class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">{{ number_format($valMingguan, 2, ',', '.') }}%</p>
                            <span id="iph-mingguan-status" class="inline-block {{ $badgeMingguan }} backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border">
                                Status: {{ $statusMingguan }}
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-7 2xl:col-span-8 space-y-2.5">
                        <div class="bg-slate-50/80 p-2.5 sm:p-3 rounded-xl border border-slate-200/80 flex flex-wrap items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-800 text-xs sm:text-sm">Grafik Fluktuasi IPH Mingguan</span>
                                <div id="iph-mingguan-badge-wrapper">
                                    @if(!empty($filterDariBulanMingguan))
                                        @php
                                            [, $labelDari] = \App\Http\Controllers\InputDataController::parseBulan($filterDariBulanMingguan);
                                            $labelSampai = !empty($filterSampaiBulanMingguan) ? \App\Http\Controllers\InputDataController::parseBulan($filterSampaiBulanMingguan)[1] : null;
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            {{ $labelDari }}{{ $labelSampai && $labelSampai !== $labelDari ? ' - ' . $labelSampai : '' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="inline-flex items-center gap-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Filter Bulan:</label>
                                    <select id="chart_filter_bulan" onchange="syncAndApplyMingguanBulan(this.value)" class="bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg px-2.5 py-1 outline-none focus:border-emerald-500 shadow-xs cursor-pointer">
                                        <option value="">Semua Bulan</option>
                                        @foreach([
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ] as $num => $nama)
                                            <option value="{{ $num }}" @selected((string) ($filterDariBulanMingguan ?? '') === (string) $num)>{{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100 hidden sm:inline-block">💡 Klik titik untuk rincian</span>
                            </div>
                        </div>
                        <div class="relative h-[260px] sm:h-[320px] lg:h-[360px] 2xl:h-[480px] w-full min-w-0" id="iphMingguanChartWrapper">
                            <canvas id="iphChartMingguan"></canvas>
                            {{-- Popup Deskripsi seperti di Peta saat titik diklik --}}
                            <div id="iphMingguanPopup" class="absolute z-30 hidden bg-white rounded-xl shadow-xl border border-slate-200 p-3.5 w-[280px] sm:w-[320px] max-w-[92vw] text-left pointer-events-auto">
                                <button type="button" class="absolute top-2 right-2.5 text-slate-400 hover:text-slate-700 text-lg font-bold leading-none w-6 h-6 flex items-center justify-center rounded-lg hover:bg-slate-100 transition" onclick="closePopup('iphMingguanPopup')" title="Tutup">&times;</button>
                                <div id="iphMingguanPopupContent"></div>
                                <div id="iphMingguanPopupTip" class="absolute w-2.5 h-2.5 bg-white border border-slate-200 rotate-45 pointer-events-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab IPH Bulanan--}}
            <div id="content-iph-bulanan" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-start">
                    <div class="lg:col-span-5 2xl:col-span-4 space-y-4">
                        <form id="iph-bulanan-form" method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-100 space-y-4">
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
                                    <select name="iph_bulanan_tahun" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                                        @foreach($daftarTahunBulanan as $thn)
                                            <option value="{{ $thn }}" @selected((string) ($filterTahunBulanan ?? date('Y')) === (string) $thn)>{{ $thn }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Dari Bulan</label>
                                        <select name="iph_bulanan_dari_bulan" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
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
                                        <select name="iph_bulanan_sampai_bulan" onchange="applyFilter(this.form)" class="w-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2 outline-none focus:border-blue-500 shadow-sm cursor-pointer">
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
                            </div>
                        </form>
                        @php
                            $valBulanan = $iphBulananTerbaru ? (float) $iphBulananTerbaru->iph_bulanan : 1.85;
                            $statusBulanan = $valBulanan > 3.5 || $valBulanan < -2.5 ? 'Tinggi' : ($valBulanan >= 2.5 ? 'Waspada' : 'Terkendali');
                            $bgBulanan = $statusBulanan === 'Tinggi' ? 'bg-rose-600 shadow-rose-600/20' : ($statusBulanan === 'Waspada' ? 'bg-amber-600 shadow-amber-600/20' : 'bg-blue-600 shadow-blue-600/20');
                            $badgeBulanan = $statusBulanan === 'Tinggi' ? 'bg-rose-500/50 border-rose-400/30' : ($statusBulanan === 'Waspada' ? 'bg-amber-500/50 border-amber-400/30' : 'bg-blue-500/50 border-blue-400/30');
                        @endphp
                        <div id="iph-bulanan-card" class="{{ $bgBulanan }} rounded-2xl p-4 sm:p-5 text-white shadow-lg transition">
                            <p id="iph-bulanan-periode" class="text-[10px] font-bold uppercase tracking-wider text-blue-100 mb-1">{{ $iphBulananTerbaru ? ($iphBulananTerbaru->nama_bulan . ' ' . $iphBulananTerbaru->tahun) : 'Mei 2026' }}</p>
                            <p id="iph-bulanan-val" class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">{{ number_format($valBulanan, 2, ',', '.') }}%</p>
                            <span id="iph-bulanan-status" class="inline-block {{ $badgeBulanan }} backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border">
                                Status: {{ $statusBulanan }}
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-7 2xl:col-span-8 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span class="font-medium">Grafik Tren IPH Bulanan</span>
                            <span class="text-[11px] text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">💡 Klik titik grafik untuk melihat rincian</span>
                        </div>
                        <div class="relative h-[260px] sm:h-[320px] lg:h-[360px] 2xl:h-[480px] w-full min-w-0" id="iphBulananChartWrapper">
                            <canvas id="iphChartBulanan"></canvas>
                            {{-- Popup Deskripsi seperti di Peta saat titik diklik --}}
                            <div id="iphBulananPopup" class="absolute z-30 hidden bg-white rounded-xl shadow-xl border border-slate-200 p-3.5 w-[280px] sm:w-[320px] max-w-[92vw] text-left pointer-events-auto">
                                <button type="button" class="absolute top-2 right-2.5 text-slate-400 hover:text-slate-700 text-lg font-bold leading-none w-6 h-6 flex items-center justify-center rounded-lg hover:bg-slate-100 transition" onclick="closePopup('iphBulananPopup')" title="Tutup">&times;</button>
                                <div id="iphBulananPopupContent"></div>
                                <div id="iphBulananPopupTip" class="absolute w-2.5 h-2.5 bg-white border border-slate-200 rotate-45 pointer-events-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Pangan --}}
            <div id="content-pangan" class="tab-content hidden">
                <form id="pangan-filter-form" method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-3.5 sm:p-4 rounded-2xl mb-5 flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3 sm:gap-4 text-xs font-semibold text-slate-500">
                    <input type="hidden" name="tab" value="pangan">
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Kecamatan</span>
                        <select name="pangan_kecamatan_id" onchange="applyFilter(this.form)" class="w-full sm:min-w-44 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm cursor-pointer">
                            <option value="">Seluruh Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" @selected((string) ($filters['pangan_kecamatan_id'] ?? '') === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Komoditas</span>
                        <select name="pangan_komoditas_id" onchange="applyFilter(this.form)" class="w-full sm:min-w-48 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm cursor-pointer">
                            <option value="">Semua Komoditas</option>
                            @foreach($komoditasPangan as $item)
                                <option value="{{ $item->id }}" @selected((string) ($filters['pangan_komoditas_id'] ?? '') === (string) $item->id)>{{ $item->nama_komoditas }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Periode Lalu</span>
                        <input type="date" name="periode_sebelumnya" value="{{ $periodeSebelumnya }}" onchange="applyFilter(this.form)" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm cursor-pointer">
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Periode Saat Ini</span>
                        <input type="date" name="periode_saat_ini" value="{{ $periodeSaatIni }}" onchange="applyFilter(this.form)" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm cursor-pointer">
                    </label>
                </form>
                <div id="pangan-table-wrapper">
                    @include('dashboard.partials.pangan-table')
                </div>
            </div>

            {{-- Tab LPG --}}
            <div id="content-lpg" class="tab-content hidden">
                <form id="lpg-filter-form" method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-3.5 sm:p-4 rounded-2xl mb-5 flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3 sm:gap-4 text-xs font-semibold text-slate-500">
                    <input type="hidden" name="tab" value="lpg">
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Kecamatan</span>
                        <select name="lpg_kecamatan_id" onchange="applyFilter(this.form)" class="w-full sm:min-w-44 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-orange-500 shadow-sm cursor-pointer">
                            <option value="">Semua Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" @selected((string) ($filters['lpg_kecamatan_id'] ?? request('lpg_kecamatan_id')) === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Tanggal</span>
                        <input type="date" name="lpg_tanggal" value="{{ $lpgTanggal ?? '' }}" onchange="applyFilter(this.form)" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-orange-500 shadow-sm cursor-pointer">
                    </label>
                </form>

                <div id="lpg-table-wrapper">
                    @include('dashboard.partials.lpg-table')
                </div>
            </div>

            {{-- Tab BBM --}}
            <div id="content-bbm" class="tab-content hidden">
                <div class="mb-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <form id="bbm-filter-form" method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-3.5 sm:p-4 rounded-2xl flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3 sm:gap-4 text-xs font-semibold text-slate-500 w-full lg:w-auto">
                        <input type="hidden" name="tab" value="bbm">
                        <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                            <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Jenis BBM</span>
                            <select name="bbm_jenis" onchange="applyFilter(this.form)" class="w-full sm:min-w-48 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                                <option value="">Semua Jenis BBM</option>
                                <option value="Pertalite (JBKP)" @selected(in_array($bbmJenis, ['Pertalite (JBKP)', 'Pertalite'], true))>Pertalite</option>
                                <option value="Bio Solar" @selected($bbmJenis === 'Bio Solar')>Bio Solar</option>
                            </select>
                        </label>
                        
                        <label class="flex flex-col gap-1.5 w-full sm:w-auto">
                            <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Tanggal</span>
                            <input type="date" name="bbm_tanggal" value="{{ $bbmTanggal ?? '' }}" onchange="applyFilter(this.form)" class="w-full sm:w-auto bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm cursor-pointer">
                        </label>
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
                <div id="bbm-table-wrapper">
                    @include('dashboard.partials.bbm-table')
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
    let currentActiveTab = new URLSearchParams(window.location.search).get('tab') || 'iph-mingguan';
    let leafletMap = null;
    let markersLayer = null;
    let chartMingguanInstance = null;
    let chartBulananInstance = null;
    let chartMingguanDetails = @json($chartMingguanDetails ?? []);
    let chartBulananDetails = @json($chartBulananDetails ?? []);

    function switchTab(tabName) {
        currentActiveTab = tabName;
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-blue-600', 'font-bold', 'border-blue-600');
            btn.classList.add('text-slate-400', 'font-semibold', 'border-transparent');
        });
        const content = document.getElementById(`content-${tabName}`);
        if (content) content.classList.remove('hidden');
        const activeBtn = document.getElementById(`btn-${tabName}`);
        if (activeBtn) {
            activeBtn.classList.add('text-blue-600', 'font-bold', 'border-blue-600');
            activeBtn.classList.remove('text-slate-400', 'font-semibold', 'border-transparent');
        }

        // Update URL tanpa reload
        const params = new URLSearchParams(window.location.search);
        params.set('tab', tabName);
        window.history.replaceState(null, '', `${window.location.pathname}?${params.toString()}`);

        // Tutup popup grafik saat berganti tab
        if (typeof closePopup === 'function') {
            closePopup('iphMingguanPopup');
            closePopup('iphBulananPopup');
        }
    }

    window.closePopup = function(popupId) {
        const el = document.getElementById(popupId);
        if (el) el.classList.add('hidden');
    };

    window.syncAndApplyMingguanBulan = function(val) {
        const leftDariBulan = document.querySelector('#iph-mingguan-form [name="iph_mingguan_dari_bulan"]');
        if (leftDariBulan) {
            leftDariBulan.value = val;
        }
        const form = document.getElementById('iph-mingguan-form');
        if (form) {
            applyFilter(form);
        }
    };

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

    window.applyFilter = function(formElement) {
        if (!formElement) return;

        if (formElement.id === 'map-filter-form') {
            const typeEl = document.getElementById('map_filter_type');
            const wrapEl = document.getElementById('map-komoditas-wrapper');
            if (typeEl && wrapEl) {
                wrapEl.classList.toggle('hidden', typeEl.value !== 'pangan');
            }
        }

        const formData = new FormData(formElement);
        const params = new URLSearchParams(window.location.search);

        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                params.set(key, value);
            } else {
                params.delete(key);
            }
        }

        if (currentActiveTab) {
            params.set('tab', currentActiveTab);
        }

        const url = `${window.location.pathname}?${params.toString()}`;
        window.history.replaceState(null, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.status !== 'success') return;

            // 1. Update Map Markers
            if (data.mapPoints && markersLayer && leafletMap) {
                markersLayer.clearLayers();
                const bounds = [];
                data.mapPoints.forEach(function(point) {
                    if (!point.latitude || !point.longitude) return;

                    const statusKey = (point.status_ketersediaan || '').toLowerCase();
                    const color = statusColorMap[statusKey] || '#94a3b8';

                    L.circleMarker([parseFloat(point.latitude), parseFloat(point.longitude)], {
                        radius: 12,
                        fillColor: color,
                        color: '#ffffff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    }).addTo(markersLayer).bindPopup(popupHtml(point));

                    bounds.push([parseFloat(point.latitude), parseFloat(point.longitude)]);
                });

                if (bounds.length > 0) {
                    leafletMap.fitBounds(bounds, { padding: [45, 45] });
                }

                // Update sidebar info
                const activeTypeEl = document.getElementById('map-active-type');
                if (activeTypeEl) {
                    activeTypeEl.textContent = data.mapFilterType === 'pangan' ? 'Pangan Strategis' : (data.mapFilterType === 'lpg' ? 'LPG 3 KG' : 'BBM Subsidi');
                }
                const activeKomoditasEl = document.getElementById('map-active-komoditas');
                if (activeKomoditasEl) {
                    if (data.mapFilterType === 'pangan') {
                        activeKomoditasEl.classList.remove('hidden');
                        activeKomoditasEl.textContent = 'Komoditas: ' + (data.mapFilterKomoditas || 'Semua komoditas strategis');
                    } else {
                        activeKomoditasEl.classList.add('hidden');
                    }
                }
                const activeCountEl = document.getElementById('map-active-count');
                if (activeCountEl) {
                    activeCountEl.textContent = data.activePointsCount;
                }
                const activeSourceEl = document.getElementById('map-active-source');
                if (activeSourceEl) {
                    activeSourceEl.textContent = data.mapFilterType === 'pangan' ? 'Data Pangan' : (data.mapFilterType === 'lpg' ? 'Data LPG' : 'Data BBM');
                }
            }

            // 2. Update IPH Mingguan Chart & Card
            if (data.chartMingguan && chartMingguanInstance) {
                chartMingguanInstance.data.labels = data.chartMingguan.labels;
                chartMingguanInstance.data.datasets[0].data = data.chartMingguan.values;
                chartMingguanDetails = data.chartMingguan.details;
                chartMingguanInstance.update();

                const mingguanValEl = document.getElementById('iph-mingguan-val');
                if (mingguanValEl) mingguanValEl.textContent = data.chartMingguan.valFormatted;
                const mingguanPeriodeEl = document.getElementById('iph-mingguan-periode');
                if (mingguanPeriodeEl) mingguanPeriodeEl.textContent = data.chartMingguan.periode;
                const mingguanStatusEl = document.getElementById('iph-mingguan-status');
                if (mingguanStatusEl) {
                    mingguanStatusEl.textContent = 'Status: ' + data.chartMingguan.status;
                    const s = data.chartMingguan.status;
                    const cardEl = document.getElementById('iph-mingguan-card');
                    if (cardEl) {
                        cardEl.className = (s === 'Tinggi' ? 'bg-rose-600 shadow-rose-600/20' : (s === 'Waspada' ? 'bg-amber-600 shadow-amber-600/20' : 'bg-emerald-600 shadow-emerald-600/20')) + ' rounded-2xl p-4 sm:p-5 text-white shadow-lg transition';
                    }
                    mingguanStatusEl.className = 'inline-block ' + (s === 'Tinggi' ? 'bg-rose-500/50 border-rose-400/30' : (s === 'Waspada' ? 'bg-amber-500/50 border-amber-400/30' : 'bg-emerald-500/50 border-emerald-400/30')) + ' backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border';
                }

                // Sync filter bulan select above chart if filtered from left form
                const chartFilterBulan = document.getElementById('chart_filter_bulan');
                const leftDariBulan = document.querySelector('#iph-mingguan-form [name="iph_mingguan_dari_bulan"]');
                if (chartFilterBulan && leftDariBulan && chartFilterBulan !== document.activeElement) {
                    chartFilterBulan.value = leftDariBulan.value;
                }
                const badgeContainer = document.getElementById('iph-mingguan-badge-wrapper');
                if (badgeContainer) {
                    if (data.chartMingguan.labelDari) {
                        const txt = data.chartMingguan.labelDari + (data.chartMingguan.labelSampai && data.chartMingguan.labelSampai !== data.chartMingguan.labelDari ? ' - ' + data.chartMingguan.labelSampai : '');
                        badgeContainer.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">${txt}</span>`;
                    } else {
                        badgeContainer.innerHTML = '';
                    }
                }
            }

            // 3. Update IPH Bulanan Chart & Card
            if (data.chartBulanan && chartBulananInstance) {
                chartBulananInstance.data.labels = data.chartBulanan.labels;
                chartBulananInstance.data.datasets[0].data = data.chartBulanan.values;
                chartBulananDetails = data.chartBulanan.details;
                chartBulananInstance.update();

                const bulananValEl = document.getElementById('iph-bulanan-val');
                if (bulananValEl) bulananValEl.textContent = data.chartBulanan.valFormatted;
                const bulananPeriodeEl = document.getElementById('iph-bulanan-periode');
                if (bulananPeriodeEl) bulananPeriodeEl.textContent = data.chartBulanan.periode;
                const bulananStatusEl = document.getElementById('iph-bulanan-status');
                if (bulananStatusEl) {
                    bulananStatusEl.textContent = 'Status: ' + data.chartBulanan.status;
                    const s = data.chartBulanan.status;
                    const cardEl = document.getElementById('iph-bulanan-card');
                    if (cardEl) {
                        cardEl.className = (s === 'Tinggi' ? 'bg-rose-600 shadow-rose-600/20' : (s === 'Waspada' ? 'bg-amber-600 shadow-amber-600/20' : 'bg-blue-600 shadow-blue-600/20')) + ' rounded-2xl p-4 sm:p-5 text-white shadow-lg transition';
                    }
                    bulananStatusEl.className = 'inline-block ' + (s === 'Tinggi' ? 'bg-rose-500/50 border-rose-400/30' : (s === 'Waspada' ? 'bg-amber-500/50 border-amber-400/30' : 'bg-blue-500/50 border-blue-400/30')) + ' backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border';
                }
            }

            // 4. Update Tables
            const panganTableWrapper = document.getElementById('pangan-table-wrapper');
            if (panganTableWrapper && data.htmlPanganTable) {
                panganTableWrapper.innerHTML = data.htmlPanganTable;
            }
            const lpgTableWrapper = document.getElementById('lpg-table-wrapper');
            if (lpgTableWrapper && data.htmlLpgTable) {
                lpgTableWrapper.innerHTML = data.htmlLpgTable;
            }
            const bbmTableWrapper = document.getElementById('bbm-table-wrapper');
            if (bbmTableWrapper && data.htmlBbmTable) {
                bbmTableWrapper.innerHTML = data.htmlBbmTable;
            }
        })
        .catch(err => {
            console.error('Filter AJAX error:', err);
        });
    };

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

        function showChartPopup(popupId, contentId, tipId, wrapperId, detailsList, index, pointX, pointY, categoryTitle) {
            const detail = detailsList[index];
            if (!detail) return;

            const popupEl = document.getElementById(popupId);
            const contentEl = document.getElementById(contentId);
            const tipEl = document.getElementById(tipId);
            const wrapperEl = document.getElementById(wrapperId);
            if (!popupEl || !contentEl || !wrapperEl) return;

            const val = Number(detail.iph);
            const valFormatted = (!isNaN(val) ? val.toFixed(2).replace('.', ',') : detail.iph) + '%';
            const status = val > 3.5 || val < -2.5 ? 'Tinggi' : (val >= 2.5 ? 'Waspada' : 'Terkendali');
            const statusColor = status === 'Tinggi' ? '#ef4444' : (status === 'Waspada' ? '#f59e0b' : '#10b981');

            const items = detail.komoditas_list || [];
            let itemsHtml = '';
            if (items.length === 0) {
                itemsHtml = `
                    <div style="padding:6px 0;border-top:1px solid #e2e8f0;font-size:12px;color:#94a3b8;font-style:italic;">
                        Belum ada rincian komoditas andil.
                    </div>
                `;
            } else {
                itemsHtml = items.map((item) => {
                    let andilVal = item.andil || '-';
                    let andilColor = '#475569';
                    if (item.raw_val !== null && item.raw_val !== undefined) {
                        const num = Number(item.raw_val);
                        if (!isNaN(num)) {
                            andilColor = num > 0 ? '#ef4444' : (num < 0 ? '#10b981' : '#475569');
                            andilVal = (num > 0 ? '+' : '') + num.toFixed(2).replace('.', ',') + '%';
                        }
                    }
                    return `
                        <div style="padding:6px 0;border-top:1px solid #e2e8f0;">
                            <div style="font-weight:700;color:#0f172a;font-size:12px;">${item.nama}</div>
                            <ul style="margin:4px 0 0 16px;padding:0;color:#475569;font-size:11px;">
                                <li style="margin-top:1px;">Andil: <strong style="color:${andilColor};">${andilVal}</strong></li>
                            </ul>
                        </div>
                    `;
                }).join('');
            }

            const rankHtml = (detail.ranking !== null && detail.ranking !== undefined && detail.ranking !== '')
                ? `<div style="font-size:12px;color:#334155;margin-top:2px;"><strong>Ranking:</strong> #${detail.ranking}</div>`
                : '';

            contentEl.innerHTML = `
                <div style="min-width:210px;max-width:280px;padding-right:12px;">
                    <div style="font-weight:800;color:#0f172a;font-size:13px;line-height:1.2;">${detail.periode || '-'}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:2px;">${categoryTitle}</div>
                    <div style="font-size:12px;color:#334155;margin-top:5px;"><strong>Status:</strong> <span style="font-weight:700;color:${statusColor};">${status}</span></div>
                    <div style="font-size:12px;color:#334155;margin-top:2px;"><strong>Nilai IPH:</strong> <strong>${valFormatted}</strong></div>
                    ${rankHtml}
                    <div style="margin-top:4px;">
                        ${itemsHtml}
                    </div>
                </div>
            `;

            popupEl.classList.remove('hidden');

            const wrapperWidth = wrapperEl.clientWidth;
            const wrapperHeight = wrapperEl.clientHeight;
            const popupWidth = popupEl.offsetWidth || 260;
            const popupHeight = popupEl.offsetHeight || 180;

            let left = pointX - (popupWidth / 2);
            if (left < 10) left = 10;
            if (left + popupWidth > wrapperWidth - 10) {
                left = wrapperWidth - popupWidth - 10;
            }

            let top = pointY - popupHeight - 12;
            let isAbove = true;
            if (top < 10) {
                top = pointY + 12;
                isAbove = false;
            }
            if (top + popupHeight > wrapperHeight - 10) {
                top = Math.max(10, wrapperHeight - popupHeight - 10);
            }

            if (tipEl) {
                const tipX = Math.max(12, Math.min(popupWidth - 20, pointX - left - 5));
                tipEl.style.left = `${tipX}px`;
                if (isAbove) {
                    tipEl.style.top = '';
                    tipEl.style.bottom = '-5px';
                    tipEl.style.borderTop = 'none';
                    tipEl.style.borderLeft = 'none';
                    tipEl.style.borderBottom = '1px solid #cbd5e1';
                    tipEl.style.borderRight = '1px solid #cbd5e1';
                } else {
                    tipEl.style.bottom = '';
                    tipEl.style.top = '-5px';
                    tipEl.style.borderBottom = 'none';
                    tipEl.style.borderRight = 'none';
                    tipEl.style.borderTop = '1px solid #cbd5e1';
                    tipEl.style.borderLeft = '1px solid #cbd5e1';
                }
            }

            popupEl.style.left = `${left}px`;
            popupEl.style.top = `${top}px`;
        }

        // --- 1. GRAFIK IPH MINGGUAN ---
        const ctxMingguan = document.getElementById('iphChartMingguan');
        if (ctxMingguan) {
            const ctx2d = ctxMingguan.getContext('2d');
            const gradient = ctx2d.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

            chartMingguanInstance = new Chart(ctx2d, {
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
                    onClick: (evt, activeEls) => {
                        if (activeEls && activeEls.length > 0) {
                            const dataIndex = activeEls[0].index;
                            const el = activeEls[0].element;
                            showChartPopup('iphMingguanPopup', 'iphMingguanPopupContent', 'iphMingguanPopupTip', 'iphMingguanChartWrapper', chartMingguanDetails, dataIndex, el.x, el.y, 'IPH Mingguan');
                        }
                    },
                    onHover: (event, chartElement) => {
                        if (event && event.native && event.native.target) {
                            event.native.target.style.cursor = (chartElement && chartElement.length > 0) ? 'pointer' : 'default';
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'IPH: ' + context.parsed.y.toFixed(2).replace('.', ',') + '% (Klik untuk rincian)';
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

            chartBulananInstance = new Chart(ctxBulanan2d, {
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
                    onClick: (evt, activeEls) => {
                        if (activeEls && activeEls.length > 0) {
                            const dataIndex = activeEls[0].index;
                            const el = activeEls[0].element;
                            showChartPopup('iphBulananPopup', 'iphBulananPopupContent', 'iphBulananPopupTip', 'iphBulananChartWrapper', chartBulananDetails, dataIndex, el.x, el.y, 'IPH Bulanan');
                        }
                    },
                    onHover: (event, chartElement) => {
                        if (event && event.native && event.native.target) {
                            event.native.target.style.cursor = (chartElement && chartElement.length > 0) ? 'pointer' : 'default';
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'IPH Bulanan: ' + context.parsed.y.toFixed(2).replace('.', ',') + '% (Klik untuk rincian)';
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
            leafletMap = L.map('gisMap').setView([0.88, 122.75], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(leafletMap);

            markersLayer = L.layerGroup().addTo(leafletMap);

            const mapPoints = @json($mapPoints ?? []);
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
                }).addTo(markersLayer).bindPopup(popupHtml(point));

                bounds.push([parseFloat(point.latitude), parseFloat(point.longitude)]);
            });

            if (bounds.length > 0) {
                leafletMap.fitBounds(bounds, { padding: [45, 45] });
            }

            const refreshMapSize = () => {
                leafletMap.invalidateSize();
                if (bounds.length > 0) {
                    leafletMap.fitBounds(bounds, { padding: [45, 45] });
                }
            };

            setTimeout(refreshMapSize, 150);
            setTimeout(refreshMapSize, 400);

            window.addEventListener('resize', refreshMapSize);
        }
    });
</script>
@endpush
