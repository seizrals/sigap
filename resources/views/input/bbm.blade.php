@extends('layouts.app')

@section('title', 'Input Data BBM')
@section('page-title', 'Input Data Distribusi BBM')

@section('content')
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-6 text-white flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">Form Input Ketersediaan BBM</h3>
                <p class="text-sm opacity-90">Pantau stok SPBU dan distribusi BBM untuk Nelayan & Pertanian.</p>
            </div>
        </div>

    <form class="bg-white p-6 rounded-2xl shadow-sm max-w-8xl mx-auto space-y-5 text-slate-700">
    
    <!-- Grid 2 Kolom untuk Input Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Nama SPBU / Penyalur -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama SPBU / Penyalur</label>
            <input type="text" placeholder="Cth: SPBU Kwandang 74.xxx" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
        </div>

        <!-- Jenis BBM -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis BBM</label>
            <select class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                <option value="pertalite" selected>Pertalite (JBKP)</option>
                <option value="biosolar">BioSolar (JBT)</option>
                <option value="pertamax">Pertamax</option>
            </select>
        </div>

        <!-- Stok Tersedia (Kiloliter) -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stok Tersedia (Kiloliter)</label>
            <input type="text" placeholder="Jumlah stok" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
        </div>

        <!-- Status Antrean / Stok -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Antrean / Stok</label>
            <select class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                <option value="aman" selected>🟢 Aman (Tidak ada antrean panjang)</option>
                <option value="antrean">🔴 Padat (Terdapat antrean)</option>
            </select>
        </div>

    </div>

    <!-- Container Distribusi Khusus (Sesuai Gambar) -->
    <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-4">
        <label class="block text-xs font-semibold text-slate-700 mb-3">Distribusi Khusus (Penyaluran Surat Rekomendasi)</label>
        <div class="flex flex-wrap items-center gap-6">
            <!-- Checkbox Nelayan -->
            <label class="flex items-center gap-2.5 text-xs text-slate-700 font-medium cursor-pointer">
                <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span>Terdapat Distribusi untuk Nelayan</span>
            </label>

            <!-- Checkbox Pertanian -->
            <label class="flex items-center gap-2.5 text-xs text-slate-700 font-medium cursor-pointer">
                <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span>Terdapat Distribusi untuk Pertanian</span>
            </label>
        </div>
    </div>

    <!-- Tombol Kirim Laporan BBM -->
    <div class="flex justify-end pt-2">
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-600/20 flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Kirim Laporan BBM
        </button>
    </div>

</form>
    </div>
@endsection
