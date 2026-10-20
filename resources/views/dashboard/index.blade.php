@extends('layouts.app')

@section('title', 'Monitor TPID - SIGAP+')
@section('page-title', 'Monitor TPID Real-Time')

@section('content')
<div class="space-y-6">

    {{-- Quick Status Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-[24px] p-6 text-white shadow-lg shadow-orange-500/20">
            <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Komoditas Defisit</p>
            <p class="text-5xl font-black mb-3">{{ $komoditasDefisit ?? '02' }}</p>
            <p class="text-sm font-medium opacity-90">Item: Cabai Rawit, Bawang Merah</p>
        </div>

        <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-[24px] p-6 text-white shadow-lg shadow-red-500/20">
            <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Kecamatan Zona Merah</p>
            <p class="text-5xl font-black mb-3">{{ $kecamatanZonaMerah ?? '01' }}</p>
            <p class="text-sm font-medium opacity-90">Wilayah: KAB. Gorontalo Utara (LPG)</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-[24px] p-6 text-white shadow-lg shadow-blue-500/20">
            <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Rekomendasi Distribusi</p>
            <p class="text-4xl font-black mb-3">Aktif</p>
            <p class="text-sm font-medium opacity-90">Saran: Operasi Pasar Kwadang</p>
        </div>
    </div>

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
                    Saran tindakan otomatis berdasarkan data warning per kecamatan.
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
                        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-100 space-y-4">
                            <p class="text-[11px] font-extrabold text-slate-400 tracking-wider uppercase">Filter Pencarian</p>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Tahun</label>
                                <select class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                                    <option value="2026" selected>2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Minggu</label>
                                <select class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                                    <option value="Minggu I" selected>Minggu I</option>
                                    <option value="Minggu II">Minggu II</option>
                                    <option value="Minggu III">Minggu III</option>
                                    <option value="Minggu IV">Minggu IV</option>
                                </select>
                            </div>
                        </div>
                        <div class="bg-emerald-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-100 mb-1">{{ $iphTerbaru?->periode_label ?? 'IPH Minggu II Mei' }}</p>
                            <p class="text-4xl font-extrabold tracking-tight mb-3">{{ $iphTerbaru?->iph_kabupaten ?? '2.33' }}%</p>
                            <span class="inline-block bg-emerald-500/50 backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border border-emerald-400/30">
                                Status: Terkendali
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
                        <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-100 space-y-4">
                            <p class="text-[11px] font-extrabold text-slate-400 tracking-wider uppercase">Filter Pencarian</p>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Tahun</label>
                                <select class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                                    <option value="2026" selected>2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Bulan</label>
                                <select class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                                    <option value="Januari" selected>Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="bg-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-100 mb-1">{{ $iphTerbaru?->periode_label ?? 'IPH Minggu II Mei' }}</p>
                            <p class="text-4xl font-extrabold tracking-tight mb-3">{{ $iphTerbaru?->iph_kabupaten ?? '2.33' }}%</p>
                            <span class="inline-block bg-blue-500/50 backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border border-emerald-400/30">
                                Status: Terkendali
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
                <div class="bg-slate-50/60 p-4 rounded-2xl mb-6 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Pilih Kecamatan</span>
                        <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none shadow-sm">
                            <option value="all" selected>Seluruh Kecamatan</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Periode</span>
                        <input type="date" value="2026-05-10" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none shadow-sm">
                        <span class="text-slate-400 font-bold">vs</span>
                        <input type="date" value="2026-05-11" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none shadow-sm">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="pb-4 pr-4">Nama Komoditas</th>
                                <th class="pb-4 px-4">Satuan</th>
                                <th class="pb-4 px-4">10 Mei</th>
                                <th class="pb-4 px-4">11 Mei</th>
                                <th class="pb-4 px-4">Perubahan</th>
                                <th class="pb-4 pl-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                            @forelse($dataPangan as $d)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 pr-4">
                                    <p class="font-bold text-slate-800">{{ $d->komoditas?->nama_komoditas ?? 'Komoditas' }}</p>
                                    <p class="text-xs text-slate-400 font-normal">{{ $d->kecamatan?->nama_kecamatan ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-4 text-slate-400">{{ $d->komoditas?->satuan ?? 'Kg' }}</td>
                                <td class="py-4 px-4 font-bold text-slate-600">Rp {{ number_format($d->harga_hari_sebelumnya, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 font-bold text-slate-800">Rp {{ number_format($d->harga_hari_ini, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 {{ $d->perubahan_harga_persen > 0 ? 'text-rose-500' : ($d->perubahan_harga_persen < 0 ? 'text-emerald-500' : 'text-slate-400') }}">
                                    {{ $d->perubahan_harga_persen > 0 ? '&uarr;' : ($d->perubahan_harga_persen < 0 ? '&darr;' : '-') }} {{ number_format($d->perubahan_harga_persen, 1) }}%
                                </td>
                                <td class="py-4 pl-4 text-right">
                                    <span class="inline-block
                                        @if($d->status_harga == 'stabil') bg-emerald-100 text-emerald-700
                                        @elseif($d->status_harga == 'naik_wajar') bg-amber-100 text-amber-700
                                        @else bg-rose-100 text-rose-600 @endif
                                        text-[11px] font-bold px-3 py-1 rounded-full uppercase">{{ $d->status_harga }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data pangan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab LPG --}}
            <div id="content-lpg" class="tab-content hidden">
                <div class="bg-slate-50/60 p-4 rounded-2xl mb-6 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Kecamatan</span>
                        <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                            <option value="all" selected>Semua Kecamatan</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Desa</span>
                        <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                            <option value="all" selected>Semua Desa</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 ml-auto">
                        <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Pilih Tanggal</span>
                        <input type="date" value="2026-12-05" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                    </div>
                </div>

                <div class="flex justify-end items-center gap-6 mb-4 text-right">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">HET LPG 3KG</p>
                        <p class="text-sm font-extrabold text-orange-500">Rp 18.500</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">HET LPG 12KG</p>
                        <p class="text-sm font-extrabold text-orange-500">Rp 215.000</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="pb-4 pr-4">Pangkalan / Wilayah</th>
                                <th class="pb-4 px-4 text-slate-400">Kebutuhan<br><span class="text-[10px] lowercase text-slate-300">(tbg)</span></th>
                                <th class="pb-4 px-4 text-emerald-600">Dist.<br>Masuk</th>
                                <th class="pb-4 px-4 text-rose-500">Dist.<br>Keluar</th>
                                <th class="pb-4 px-4 text-slate-400">Harga<br>Jual</th>
                                <th class="pb-4 pl-4 text-right">Status<br>Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                            @forelse($dataLpg as $d)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 pr-4">
                                    <p class="font-bold italic text-slate-800">{{ $d->pangkalanLpg?->nama_pangkalan ?? 'Pangkalan' }}</p>
                                    <p class="text-xs italic text-slate-400 font-normal">{{ $d->pangkalanLpg?->kecamatan?->nama_kecamatan ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-800">{{ $d->kebutuhan_tabung ?? '-' }}</td>
                                <td class="py-4 px-4 font-extrabold text-emerald-600">{{ $d->distribusi_masuk_tabung }}</td>
                                <td class="py-4 px-4 font-bold text-rose-500">{{ $d->distribusi_keluar_tabung }}</td>
                                <td class="py-4 px-4">
                                    <p class="font-bold {{ $d->harga_eceran_lapangan > $d->harga_pangkalan ? 'text-rose-600' : 'text-slate-800' }}">Rp {{ number_format($d->harga_pangkalan, 0, ',', '.') }}</p>
                                    <p class="text-[10px] font-bold italic {{ $d->harga_eceran_lapangan > $d->harga_pangkalan ? 'text-rose-500' : 'text-emerald-600' }}">
                                        {{ $d->harga_eceran_lapangan > $d->harga_pangkalan ? 'DIATAS HET' : 'SESUAI HET' }}
                                    </p>
                                </td>
                                <td class="py-4 pl-4 text-right">
                                    <span class="inline-block
                                        @if($d->status_ketersediaan == 'aman') bg-emerald-100 text-emerald-700
                                        @elseif($d->status_ketersediaan == 'waspada') bg-amber-100 text-amber-700
                                        @else bg-rose-100 text-rose-600 @endif
                                        text-[11px] font-bold px-3 py-1 rounded-full uppercase">{{ $d->status_ketersediaan }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data LPG.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab BBM --}}
            <div id="content-bbm" class="tab-content hidden">
                <div class="mb-6 space-y-4">
                    <div class="bg-slate-50/60 p-4 rounded-2xl flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500">
                        <div class="flex items-center gap-2">
                            <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Kecamatan</span>
                            <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                                <option value="all" selected>Semua Kecamatan</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Lokasi SPBU (Desa)</span>
                            <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                                <option value="all" selected>Semua Lokasi</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Pilih Tanggal</span>
                            <input type="date" value="2026-12-05" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-3">
                        <div class="bg-blue-600 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">Pertalite</p>
                            <p class="text-xs font-black italic">Rp 10.000</p>
                        </div>
                        <div class="bg-blue-900 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">BioSolar</p>
                            <p class="text-xs font-black italic">Rp 6.800</p>
                        </div>
                        <div class="bg-red-600 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">Pertamax</p>
                            <p class="text-xs font-black italic">Rp 16.650</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="pb-4 pr-4">Jenis BBM & Lokasi</th>
                                <th class="pb-4 px-4 text-slate-400">Kebutuhan (KL)</th>
                                <th class="pb-4 px-4 text-slate-400">Stok Saat Ini (KL)</th>
                                <th class="pb-4 px-4 text-slate-400">Harga Satuan</th>
                                <th class="pb-4 pl-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                            @forelse($dataBbm as $d)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 pr-4">
                                    <p class="font-bold text-slate-800">{{ $d->jenis_bbm }} ({{ $d->spbu?->nama_spbu ?? 'SPBU' }})</p>
                                    <p class="text-xs italic text-slate-400 font-normal">{{ $d->spbu?->kecamatan?->nama_kecamatan ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-800">{{ $d->stok_tersedia_kl }} KL</td>
                                <td class="py-4 px-4 font-bold text-slate-800">{{ $d->stok_tersedia_kl }} KL</td>
                                <td class="py-4 px-4 font-extrabold italic text-blue-600">Rp {{ number_format($d->harga_jual_per_liter, 0, ',', '.') }}</td>
                                <td class="py-4 pl-4 text-right">
                                    <span class="inline-block
                                        @if($d->status_antrean == 'tidak_ada') bg-emerald-100 text-emerald-700
                                        @elseif($d->status_antrean == 'pendek') bg-amber-100 text-amber-700
                                        @else bg-rose-100 text-rose-600 @endif
                                        text-[11px] font-bold px-3 py-1 rounded-full uppercase">{{ str_replace('_', ' ', $d->status_antrean) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data BBM.</td>
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
        // --- 1. GRAFIK IPH MINGGUAN ---
        const ctxMingguan = document.getElementById('iphChartMingguan');
        if (ctxMingguan) {
            const ctx2d = ctxMingguan.getContext('2d');
            const gradient = ctx2d.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

            new Chart(ctx2d, {
                type: 'line',
                data: {
                    labels: ['Minggu III', 'Minggu IV', 'Minggu I', 'Minggu II'],
                    datasets: [{
                        data: [1.85, 2.10, 1.95, 2.33],
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { size: 11, weight: '500' } }
                        },
                        y: {
                            min: 1.80,
                            max: 2.35,
                            ticks: {
                                stepSize: 0.05,
                                color: '#64748b',
                                font: { size: 11 },
                                callback: function(value) { return value.toFixed(2).replace('.', ','); }
                            },
                            grid: { color: '#f1f5f9' }
                        }
                    }
                }
            });
        }

        // --- 2. GRAFIK IPH BULANAN (TAMBAHAN) ---
        const ctxBulanan = document.getElementById('iphChartBulanan');
        if (ctxBulanan) {
            const ctxBulanan2d = ctxBulanan.getContext('2d');
            const gradientBulanan = ctxBulanan2d.createLinearGradient(0, 0, 0, 300);
            gradientBulanan.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
            gradientBulanan.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

            new Chart(ctxBulanan2d, {
                type: 'line',
                data: {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
                    datasets: [{
                        data: [1.20, 1.45, 1.80, 2.10, 1.85, 1.60],
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { size: 11, weight: '500' } }
                        },
                        y: {
                            min: 1.00,
                            max: 2.50,
                            ticks: {
                                stepSize: 0.25,
                                color: '#64748b',
                                font: { size: 11 },
                                callback: function(value) { return value.toFixed(2).replace('.', ','); }
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
        }
    });
</script>
@endpush
