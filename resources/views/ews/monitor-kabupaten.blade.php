@extends('layouts.app')

@section('title', 'Monitor Kabupaten')
@section('page-title', 'Monitor Kabupaten - Ringkasan TPID')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M15.75 3.75h-9a2.25 2.25 0 00-2.25 2.25v12A2.25 2.25 0 006.75 20.25h9a2.25 2.25 0 002.25-2.25v-12a2.25 2.25 0 00-2.25-2.25z"/></svg>
                        Ringkasan Indikator Pemicu Aktif
                    </h3>
                    <p class="text-sm text-slate-500">Kondisi spesifik yang memicu alert dan tindak lanjut TPID saat ini</p>
                </div>
                <span class="text-xs font-semibold text-slate-600">Total Pemicu Terdeteksi: <span class="text-red-600 font-bold">8 Indikator</span></span>
            </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Pemicu Harga (Kuning/Amber) -->
        <div class="bg-amber-50/60 border border-amber-200/80 rounded-xl p-4">
            <h4 class="text-xs font-bold text-amber-900 flex items-center gap-1.5 mb-3">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 005.814-5.519l2.74-1.22m0 0l-5.94-2.28 2.28 5.941"/>
                </svg>
                Pemicu Harga
            </h4>
            <ul class="space-y-2 text-xs">
                <!-- Item Aktif (Petir) -->
                <li class="flex items-start gap-1.5 text-amber-900 font-medium">
                    <span class="text-amber-600 font-bold">⚡</span>
                    <span>Kenaikan Harga Harian (>10%)</span>
                </li>
                <li class="flex items-start gap-1.5 text-amber-900 font-medium">
                    <span class="text-amber-600 font-bold">⚡</span>
                    <span>Kenaikan Mingguan Akumulatif</span>
                </li>
                <li class="flex items-start gap-1.5 text-amber-900 font-medium">
                    <span class="text-amber-600 font-bold">⚡</span>
                    <span>Harga Melewati Threshold HAP</span>
                </li>
                <!-- Item Non-Aktif (Lingkaran) -->
                <li class="flex items-start gap-1.5 text-amber-700/60">
                    <span class="text-amber-500">◯</span>
                    <span>Selisih Antarwilayah Tinggi</span>
                </li>
            </ul>
        </div>
                <!-- Card 2: Pemicu Pasokan (Merah) -->
        <div class="bg-red-50/60 border border-red-200/80 rounded-xl p-4">
            <h4 class="text-xs font-bold text-red-900 flex items-center gap-1.5 mb-3">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0C2.678 5.577 2.25 6.057 2.25 6.625v11.25"/>
                </svg>
                Pemicu Pasokan
            </h4>
            <ul class="space-y-2 text-xs">
                <li class="flex items-start gap-1.5 text-red-900 font-medium">
                    <span class="text-red-600 font-bold">⚡</span>
                    <span>Cuaca Ekstrem (Gelombang)</span>
                </li>
                <li class="flex items-start gap-1.5 text-red-900 font-medium">
                    <span class="text-red-600 font-bold">⚡</span>
                    <span>Gangguan Transportasi Laut</span>
                </li>
                <li class="flex items-start gap-1.5 text-red-900 font-medium">
                    <span class="text-red-600 font-bold">⚡</span>
                    <span>Keterlambatan Pengiriman</span>
                </li>
                <li class="flex items-start gap-1.5 text-red-900 font-medium">
                    <span class="text-red-600 font-bold">⚡</span>
                    <span>Biaya Angkut Meningkat</span>
                </li>
            </ul>
        </div>
                <!-- Card 3: Pemicu BBM (Biru) -->
        <div class="bg-blue-50/60 border border-blue-200/80 rounded-xl p-4">
            <h4 class="text-xs font-bold text-blue-900 flex items-center gap-1.5 mb-3">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Pemicu BBM
            </h4>
            <ul class="space-y-2 text-xs">
                <li class="flex items-start gap-1.5 text-blue-900 font-medium">
                    <span class="text-blue-600 font-bold">⚡</span>
                    <span>Stok SPBU Menipis</span>
                </li>
                <li class="flex items-start gap-1.5 text-blue-900 font-medium">
                    <span class="text-blue-600 font-bold">⚡</span>
                    <span>Antrean SPBU Meningkat</span>
                </li>
                <li class="flex items-start gap-1.5 text-blue-700/60">
                    <span class="text-blue-500">◯</span>
                    <span>Pengiriman Terlambat</span>
                </li>
                <li class="flex items-start gap-1.5 text-blue-700/60">
                    <span class="text-blue-500">◯</span>
                    <span>SPBU Kekosongan</span>
                </li>
            </ul>
        </div>
                <!-- Card 4: Pemicu LPG 3Kg (Oranye) -->
        <div class="bg-orange-50/60 border border-orange-200/80 rounded-xl p-4">
            <h4 class="text-xs font-bold text-orange-900 flex items-center gap-1.5 mb-3">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/>
                </svg>
                Pemicu LPG 3Kg
            </h4>
            <ul class="space-y-2 text-xs">
                <li class="flex items-start gap-1.5 text-orange-900 font-medium">
                    <span class="text-orange-600 font-bold">⚡</span>
                    <span>Harga Melebihi HET Pangkalan</span>
                </li>
                <li class="flex items-start gap-1.5 text-orange-900 font-medium">
                    <span class="text-orange-600 font-bold">⚡</span>
                    <span>Kelangkaan Pangkalan Sisi Luar</span>
                </li>
                <li class="flex items-start gap-1.5 text-orange-900 font-medium">
                    <span class="text-orange-600 font-bold">⚡</span>
                    <span>Permintaan Meningkat Tajam</span>
                </li>
                <li class="flex items-start gap-1.5 text-orange-700/60">
                    <span class="text-orange-500">◯</span>
                    <span>Distribusi Terlambat</span>
                </li>
            </ul>
        </div>
            </div>
        </div>

        <div x-data="{ 
    openModal: false, 
    selectedKecamatan: '', 
    statusText: '', 
    statusBg: '', 
    statusDot: '', 
    pemicuList: [] 
}">

<div class="space-y-6">
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        Tabel Pantauan Status Wilayah (11 Kecamatan)
                    </h3>
                    <p class="text-sm text-slate-500">klik baris atau tombol detail untuk melihat rincian indikator pemicu per kecamatan</p>

                <div class="">
                    <span class="inline-block w-2 h-2 rounded-full bg-status-waspada animate-pulse"></span>
                    <span class="text-[11px] font-bold text-status-waspada">WASPADA</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-status-bahaya animate-pulse"></span>
                    <span class="text-[11px] font-bold text-status-bahaya">BAHAYA</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-status-aman animate-pulse"></span>
                    <span class="text-[11px] font-bold text-status-aman">AMAN</span>
                </div>
                </div>
            </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-4 px-6">Kecamatan</th>
                        <th class="py-4 px-6">Status Komoditas</th>
                        <th class="py-4 px-6">Indikator Pemicu Terdeteksi</th>
                        <th class="py-4 px-6">Terakhir Diperbarui</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    
                    <!-- 1. Kwandang -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2">
                            <span class="text-slate-400">🏛️</span> Kwandang
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100/80 text-amber-800 font-semibold border border-amber-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Waspada
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-wrap gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200/80 text-[11px]">
                                    📈 Kenaikan Harga Harian
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200/80 text-[11px]">
                                    🪵 Harga LPG > HET
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-500">Hari ini, 13:15 WITA</td>
                        <td class="py-4 px-6 text-right">
                            <button @click="
                                openModal = true; 
                                selectedKecamatan = 'Kwandang';
                                statusText = 'Waspada';
                                statusBg = 'bg-amber-100 text-amber-800';
                                statusDot = 'bg-amber-500';
                                pemicuList = [
                                    '📈 Kenaikan Harga Harian (>10%)',
                                    '🪵 Harga LPG Melebihi HET Pangkalan'
                                ];" 
                                class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                                Detail Pemicu
                            </button>
                        </td>
                    </tr>

                    <!-- 2. Atinggola -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2">
                            <span class="text-slate-400">📍</span> Atinggola
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100/80 text-red-800 font-semibold border border-red-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span> Bahaya
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-wrap gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-800 border border-red-200/80 text-[11px]">
                                    🚚 Keterlambatan Pengiriman
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-800 border border-red-200/80 text-[11px]">
                                    📈 Harga > Threshold
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200/80 text-[11px]">
                                    ⛽ Antrean SPBU
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-500">Hari ini, 12:40 WITA</td>
                        <td class="py-4 px-6 text-right">
                            <button @click="
                                openModal = true; 
                                selectedKecamatan = 'Atinggola';
                                statusText = 'Bahaya';
                                statusBg = 'bg-red-100 text-red-800';
                                statusDot = 'bg-red-600';
                                pemicuList = [
                                    '🚚 Keterlambatan Pengiriman Pasokan dari Gorontalo',
                                    '📈 Harga Bawang Merah Melewati Threshold HAP',
                                    '⛽ Antrean Kendaraan di SPBU Atinggola'
                                ];" 
                                class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                                Detail Pemicu
                            </button>
                        </td>
                    </tr>

                    <!-- 3. Ponelo Kepulauan -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2">
                            <span class="text-slate-400">🏝️</span> Ponelo Kepulauan
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100/80 text-red-800 font-semibold border border-red-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span> Bahaya
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-wrap gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-800 border border-red-200/80 text-[11px]">
                                    🌊 Cuaca Ekstrem / Laut
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200/80 text-[11px]">
                                    🪵 Kelangkaan Pangkalan
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200/80 text-[11px]">
                                    🚚 Biaya Angkut Naik
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-500">Hari ini, 11:20 WITA</td>
                        <td class="py-4 px-6 text-right">
                            <button @click="
                                openModal = true; 
                                selectedKecamatan = 'Ponelo Kepulauan';
                                statusText = 'Bahaya';
                                statusBg = 'bg-red-100 text-red-800';
                                statusDot = 'bg-red-600';
                                pemicuList = [
                                    '🌊 Cuaca Ekstrem / Gelombang Tinggi Laut',
                                    '🪵 Kelangkaan LPG Pangkalan Sisi Luar',
                                    '🚚 Biaya Angkut Transportasi Meningkat'
                                ];" 
                                class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                                Detail Pemicu
                            </button>
                        </td>
                    </tr>

                    <!-- 4. Sumalata -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2">
                            <span class="text-slate-400">📍</span> Sumalata
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100/80 text-amber-800 font-semibold border border-amber-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Waspada
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-wrap gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200/80 text-[11px]">
                                    📈 Kenaikan Mingguan
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200/80 text-[11px]">
                                    ⛽ Stok BBM Menipis
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-500">Hari ini, 10:05 WITA</td>
                        <td class="py-4 px-6 text-right">
                            <button @click="
                                openModal = true; 
                                selectedKecamatan = 'Sumalata';
                                statusText = 'Waspada';
                                statusBg = 'bg-amber-100 text-amber-800';
                                statusDot = 'bg-amber-500';
                                pemicuList = [
                                    '📈 Kenaikan Harga Mingguan Akumulatif',
                                    '⛽ Stok SPBU Menipis'
                                ];" 
                                class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                                Detail Pemicu
                            </button>
                        </td>
                    </tr>

                    <!-- 5. Anggrek (Aman / Tidak ada pemicu) -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-bold text-slate-900 flex items-center gap-2">
                            <span class="text-slate-400">⚓</span> Anggrek
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100/80 text-emerald-800 font-semibold border border-emerald-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Aman
                            </span>
                        </td>
                        <td class="py-4 px-6 text-slate-400 italic text-[11px]">
                            Tidak ada pemicu kritis terdeteksi
                        </td>
                        <td class="py-4 px-6 text-slate-500">Hari ini, 09:40 WITA</td>
                        <td class="py-4 px-6 text-right">
                            <button @click="
                                openModal = true; 
                                selectedKecamatan = 'Anggrek';
                                statusText = 'Aman';
                                statusBg = 'bg-emerald-100 text-emerald-800';
                                statusDot = 'bg-emerald-500';
                                pemicuList = [];" 
                                class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                                Detail Pemicu
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- MODAL POP-UP DINAMIS -->
    <div x-show="openModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        
        <div @click.away="openModal = false" 
             class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 relative border border-slate-100">
            
            <!-- Tombol Close (X) -->
            <button @click="openModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Badge Header -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100/70 text-amber-900 font-bold text-[11px] tracking-wide mb-3">
                <span>📍</span> RINCIAN INDIKATOR WILAYAH
            </div>

            <!-- Title & Subtitle -->
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                Kecamatan <span x-text="selectedKecamatan"></span>
            </h2>
            <p class="text-xs text-slate-500 mt-1 mb-5">
                Status komoditas dan daftar indikator pemicu yang terdeteksi.
            </p>

            <!-- Status Box Dinamis -->
            <div class="bg-slate-50/80 rounded-2xl p-3.5 flex items-center justify-between border border-slate-100 mb-4">
                <span class="text-xs font-semibold text-slate-700">Status Komoditas Pangan</span>
                <span :class="statusBg" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-bold text-xs">
                    <span :class="statusDot" class="w-2.5 h-2.5 rounded-full"></span>
                    <span x-text="statusText"></span>
                </span>
            </div>

            <!-- Card Pemicu Dinamis -->
            <div class="bg-amber-50/50 border border-amber-200/80 rounded-2xl p-4 mb-6">
                <h4 class="text-xs font-bold text-amber-900 flex items-center gap-1.5 mb-3">
                    <span class="text-amber-600">⚠️</span> INDIKATOR PEMICU AKTIF WILAYAH INI:
                </h4>
                
                <div class="space-y-2.5 text-xs text-amber-950 font-semibold">
                    <template x-if="pemicuList.length > 0">
                        <div>
                            <template x-for="item in pemicuList" :key="item">
                                <div class="bg-white/80 border border-amber-200/60 rounded-xl p-3 flex items-center gap-2.5 shadow-sm mb-2">
                                    <span class="text-amber-600">⚠️</span>
                                    <span x-text="item"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                    
                    <template x-if="pemicuList.length === 0">
                        <div class="bg-white/80 border border-emerald-200/60 rounded-xl p-3 text-emerald-700 italic text-center">
                            Tidak ada indikator pemicu aktif. Kondisi wilayah stabil.
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center gap-3">
                <button class="flex-1 bg-amber-500 hover:bg-amber-600 text-amber-950 font-bold py-3 px-4 rounded-2xl flex items-center justify-center gap-2 text-xs transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                    </svg>
                    Input Data & Pemicu Baru
                </button>
                <button @click="openModal = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-5 rounded-2xl text-xs transition-colors">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</div>
@endsection
