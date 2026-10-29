@extends('layouts.app')

@section('title', 'Monitor TPID - SIGAP+')
@section('page-title', 'Monitor TPID Real-Time')

@section('content')
<div class="space-y-6">

    {{-- Quick Status Cards --}}
    
    {{-- GIS Map Section + Rekomendasi Strategis --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
                        Peta GIS Kerawanan Wilayah
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Visualisasi zona risiko inflasi per kecamatan di Kabupaten Gorontalo Utara</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span><span class="font-semibold text-slate-600">Aman</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500"></span><span class="font-semibold text-slate-600">Waspada</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span><span class="font-semibold text-slate-600">Bahaya</span></div>
                </div>
            </div>
            <div id="gisMap" class="h-[400px] w-full"></div>
        </div>

        {{-- Widget Rekomendasi Strategis Otomatis --}}
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <h3 class="text-base font-bold text-slate-900">Rekomendasi Strategis</h3>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Rekomendasi pasar yang diterbitkan dan tindakan otomatis berdasarkan data peringatan.
                </p>
            </div>
            <div class="p-4 space-y-3 max-h-[400px] overflow-y-auto">
                @forelse($rekomendasiStrategis as $rek)
                <div class="border-l-4 pl-3 py-1 {{ $rek['zona'] == 'merah' ? 'border-red-500' : ($rek['zona'] == 'kuning' ? 'border-amber-400' : 'border-emerald-500') }}">
                    <div class="flex items-start gap-2.5">
                        <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center
                            @if($rek['warna'] == 'red') bg-red-100 text-red-600
                            @elseif($rek['warna'] == 'amber') bg-amber-100 text-amber-600
                            @else bg-emerald-100 text-emerald-600 @endif">
                            @if($rek['ikon'] == 'store')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.723l2.121-2.122a3 3 0 014.243 0L9 8.25m-4.5 0h.008v.008H4.5V8.25z"/></svg>
                            @elseif($rek['ikon'] == 'truck')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0C2.678 5.577 2.25 6.057 2.25 6.625v11.25"/></svg>
                            @elseif($rek['ikon'] == 'clipboard')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                            @elseif($rek['ikon'] == 'eye')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-900 leading-snug">{{ $rek['judul'] }}</p>
                            <p class="text-[11px] font-semibold {{ $rek['zona'] == 'merah' ? 'text-red-600' : ($rek['zona'] == 'kuning' ? 'text-amber-600' : 'text-emerald-600') }} mb-1">Kec. {{ $rek['kecamatan'] }}</p>
                            <p class="text-[11px] text-slate-500 leading-relaxed">{{ $rek['detail'] }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-400 text-xs py-4">
                    Tidak ada rekomendasi saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tab Navigation & Content --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 border-b border-slate-100 px-6 overflow-x-auto">
            <button id="btn-iph-mingguan" onclick="switchTab('iph-mingguan')" class="tab-btn whitespace-nowrap px-4 py-4 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-all">
                DATA IPH MINGGUAN
            </button>
            <button id="btn-iph-bulanan" onclick="switchTab('iph-bulanan')" class="tab-btn whitespace-nowrap px-4 py-4 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">
                DATA IPH BULANAN
            </button>
            <button id="btn-pangan" onclick="switchTab('pangan')" class="tab-btn whitespace-nowrap px-4 py-4 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">
                PANGAN STRATEGIS
            </button>
            <button id="btn-lpg" onclick="switchTab('lpg')" class="tab-btn whitespace-nowrap px-4 py-4 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">LPG 3 KG</button>
            <button id="btn-bbm" onclick="switchTab('bbm')" class="tab-btn whitespace-nowrap px-4 py-4 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">BBM SUBSIDI</button>
        </div>

        <div class="p-6">
            {{-- Tab IPH Mingguan--}}
            <div id="content-iph-mingguan" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <div class="lg:col-span-4 space-y-4">
                        <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/70 p-5 rounded-2xl border border-slate-100 space-y-4">
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
                        <div class="{{ $bgMingguan }} rounded-2xl p-5 text-white shadow-lg transition">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-100 mb-1">{{ $iphMingguanTerbaru?->periode_label ?? 'IPH Minggu II Mei' }}</p>
                            <p class="text-4xl font-extrabold tracking-tight mb-3">{{ number_format($valMingguan, 2, ',', '.') }}%</p>
                            <span class="inline-block {{ $badgeMingguan }} backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border">
                                Status: {{ $statusMingguan }}
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-8 relative h-[320px] w-full">
                        <canvas id="iphChartMingguan"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tab IPH Bulanan--}}
            <div id="content-iph-bulanan" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <div class="lg:col-span-4 space-y-4">
                        <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/70 p-5 rounded-2xl border border-slate-100 space-y-4">
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
                        <div class="{{ $bgBulanan }} rounded-2xl p-5 text-white shadow-lg transition">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-100 mb-1">{{ $iphBulananTerbaru ? ($iphBulananTerbaru->nama_bulan . ' ' . $iphBulananTerbaru->tahun) : 'Mei 2026' }}</p>
                            <p class="text-4xl font-extrabold tracking-tight mb-3">{{ number_format($valBulanan, 2, ',', '.') }}%</p>
                            <span class="inline-block {{ $badgeBulanan }} backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border">
                                Status: {{ $statusBulanan }}
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-8 relative h-[320px] w-full">
                        <canvas id="iphChartBulanan"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tab Pangan --}}
            <div id="content-pangan" class="tab-content hidden">
                <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-4 rounded-2xl mb-6 flex flex-wrap items-end gap-4 text-xs font-semibold text-slate-500">
                    <input type="hidden" name="tab" value="pangan">
                    <label class="flex flex-col gap-1.5">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Kecamatan</span>
                        <select name="pangan_kecamatan_id" class="min-w-44 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                            <option value="">Seluruh Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" @selected((string) ($filters['pangan_kecamatan_id'] ?? '') === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Komoditas</span>
                        <select name="pangan_komoditas_id" class="min-w-48 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                            <option value="">Semua Komoditas</option>
                            @foreach($komoditasPangan as $item)
                                <option value="{{ $item->id }}" @selected((string) ($filters['pangan_komoditas_id'] ?? '') === (string) $item->id)>{{ $item->nama_komoditas }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Periode Lalu</span>
                        <input type="date" name="periode_sebelumnya" value="{{ $periodeSebelumnya }}" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Periode Saat Ini</span>
                        <input type="date" name="periode_saat_ini" value="{{ $periodeSaatIni }}" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-amber-500 shadow-sm">
                    </label>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs transition-colors">Terapkan</button>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
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
                                <td class="py-4 pr-4 text-slate-700 font-bold">{{ $d['kecamatan'] }}</td>
                                <td class="py-4 pr-4">
                                    <p class="font-bold text-slate-800">{{ $d['komoditas'] }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $d['satuan'] }}</p>
                                </td>
                                <td class="py-4 px-4 text-slate-600">{{ $d['stok'] !== null ? number_format((float) $d['stok'], 0, ',', '.') : '-' }} {{ $d['satuan'] }}</td>
                                <td class="py-4 px-4 text-slate-600">{{ $d['harga_lalu'] !== null ? 'Rp '.number_format((float) $d['harga_lalu'], 0, ',', '.') : '-' }}</td>
                                <td class="py-4 px-4 font-bold text-slate-800">{{ $d['harga_saat_ini'] !== null ? 'Rp '.number_format((float) $d['harga_saat_ini'], 0, ',', '.') : '-' }}</td>
                                <td class="py-4 px-4 {{ ($d['perubahan_persen'] ?? 0) > 0 ? 'text-rose-500' : (($d['perubahan_persen'] ?? 0) < 0 ? 'text-emerald-500' : 'text-slate-400') }}">
                                    {{ $d['perubahan_persen'] === null ? '-' : (($d['perubahan_persen'] > 0 ? '+' : '').number_format((float) $d['perubahan_persen'], 1, ',', '.').'%') }}
                                </td>
                                <td class="py-4 pl-4 text-right">
                                    <span class="inline-block text-[11px] font-bold px-3 py-1 rounded-full uppercase {{ $d['status_ketersediaan'] === 'aman' ? 'bg-emerald-100 text-emerald-700' : ($d['status_ketersediaan'] === 'waspada' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-600') }}">{{ $d['status_ketersediaan'] ? ($d['status_ketersediaan'] === 'kosong' ? 'Kritis' : $d['status_ketersediaan']) : '-' }}</span>
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
                <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-4 rounded-2xl mb-6 flex flex-wrap items-end gap-4 text-xs font-semibold text-slate-500">
                    <input type="hidden" name="tab" value="lpg">
                    <label class="flex flex-col gap-1.5">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Kecamatan</span>
                        <select name="lpg_kecamatan_id" class="min-w-44 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-orange-500 shadow-sm">
                            <option value="">Semua Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" @selected((string) ($filters['lpg_kecamatan_id'] ?? request('lpg_kecamatan_id')) === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-1.5">
                        <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Tanggal</span>
                        <input type="date" name="lpg_tanggal" value="{{ $lpgTanggal ?? '' }}" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-orange-500 shadow-sm">
                    </label>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs transition-colors">Terapkan</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-[1250px] w-full text-left border-collapse">
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
                                <td class="py-4 px-3 text-right">{{ number_format($d->stok_tersedia_tabung, 0, ',', '.') }}</td>
                                <td class="py-4 px-3 text-right">{{ number_format($d->distribusi_masuk_tabung, 0, ',', '.') }}</td>
                                <td class="py-4 px-3 text-right text-rose-600">{{ number_format($d->distribusi_keluar_tabung, 0, ',', '.') }}</td>
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
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <form method="GET" action="{{ route('dashboard') }}" class="bg-slate-50/60 p-4 rounded-2xl flex flex-wrap items-end gap-4 text-xs font-semibold text-slate-500">
                        <input type="hidden" name="tab" value="bbm">
                        <label class="flex flex-col gap-1.5">
                            <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Jenis BBM</span>
                            <select name="bbm_jenis" class="min-w-48 bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                                <option value="">Semua Jenis BBM</option>
                                <option value="Pertalite (JBKP)" @selected(in_array($bbmJenis, ['Pertalite (JBKP)', 'Pertalite'], true))>Pertalite</option>
                                <option value="Bio Solar" @selected($bbmJenis === 'Bio Solar')>Bio Solar</option>
                            </select>
                        </label>
                        
                        <label class="flex flex-col gap-1.5">
                            <span class="uppercase font-bold text-[10px] tracking-wider text-slate-400">Pilih Tanggal</span>
                            <input type="date" name="bbm_tanggal" value="{{ $bbmTanggal ?? '' }}" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                        </label>

                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors">Terapkan</button>
                    </form>

                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">Pertalite</p>
                            <p class="text-xs font-black italic">Rp 10.000</p>
                        </div>
                        <div class="bg-orange-500 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">BioSolar</p>
                            <p class="text-xs font-black italic">Rp 6.800</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
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
                                    <label class="inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <input type="checkbox" disabled @checked($d->distribusi_nelayan) class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-600">
                                        {{ $d->distribusi_nelayan ? 'Ya' : 'Tidak' }}
                                    </label>
                                </td>
                                <td class="py-4 px-3">
                                    <label class="inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <input type="checkbox" disabled @checked($d->distribusi_pertanian) class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-600">
                                        {{ $d->distribusi_pertanian ? 'Ya' : 'Tidak' }}
                                    </label>
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
            const map = L.map('gisMap').setView([0.72, 123.05], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            const kecamatanData = @json($kecamatan ?? []);
            const marketRecommendations = @json($rekomendasiPeta ?? []);

            const colorMap = {
                'hijau': '#10b981',
                'kuning': '#f59e0b',
                'merah': '#ef4444'
            };

            if (kecamatanData.length > 0) {
                kecamatanData.forEach(function(kec) {
                    if (kec.latitude && kec.longitude) {
                        const color = colorMap[kec.zona_risiko] || '#94a3b8';
                        L.circleMarker([parseFloat(kec.latitude), parseFloat(kec.longitude)], {
                            radius: 12,
                            fillColor: color,
                            color: '#ffffff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.85
                        }).addTo(map)
                        .bindPopup('<strong>' + kec.nama_kecamatan + '</strong><br>Zona: ' + (kec.zona_risiko || 'hijau'));
                    }
                });
            } else {
                const fallback = [
                    { nama: 'Kwandang', lat: 0.7210, lng: 123.0510, zona: 'merah' },
                    { nama: 'Atinggola', lat: 0.7450, lng: 123.1200, zona: 'merah' },
                    { nama: 'Sumalata', lat: 0.6800, lng: 123.1800, zona: 'kuning' },
                    { nama: 'Gentuma Raya', lat: 0.6500, lng: 123.2200, zona: 'kuning' },
                    { nama: 'Anggrek', lat: 0.6200, lng: 123.1000, zona: 'hijau' }
                ];
                fallback.forEach(function(kec) {
                    const color = colorMap[kec.zona];
                    L.circleMarker([kec.lat, kec.lng], {
                        radius: 12,
                        fillColor: color,
                        color: '#ffffff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.85
                    }).addTo(map)
                    .bindPopup('<strong>' + kec.nama + '</strong><br>Zona: ' + kec.zona);
                });
            }

            marketRecommendations.forEach(function(rekomendasi) {
                const color = colorMap[rekomendasi.zona] || '#ef4444';
                const popup = document.createElement('div');
                const title = document.createElement('strong');
                const alertTitle = document.createElement('p');
                const location = document.createElement('p');
                const detail = document.createElement('p');

                title.textContent = rekomendasi.judul;
                alertTitle.textContent = 'Peringatan: ' + rekomendasi.alert;
                location.textContent = 'Kec. ' + rekomendasi.kecamatan + ' | Zona: ' + rekomendasi.zona;
                detail.textContent = rekomendasi.detail;
                popup.append(title, alertTitle, location, detail);

                L.circleMarker([rekomendasi.latitude, rekomendasi.longitude], {
                    radius: 9,
                    fillColor: color,
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.95
                }).addTo(map).bindPopup(popup);
            });
        }
    });
</script>
@endpush
