@extends('layouts.app')

@section('title', 'Input Data LPG')
@section('page-title', 'Input Data Distribusi LPG')

@section('content')
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-orange-400 to-orange-600 p-6 text-white flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">Form Input Ketersediaan LPG</h3>
                <p class="text-sm opacity-90">Laporkan stok, distribusi, dan harga LPG per pangkalan di wilayah Anda.</p>
            </div>
        </div>

    <form class="bg-white p-6 rounded-2xl shadow-sm max-w-8xl mx-auto space-y-5 text-slate-700">
    
    <!-- Grid 2 Kolom Baris Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Tanggal Input -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Input</label>
            <input type="date" value="2026-12-05" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-orange-500 shadow-sm transition">
        </div>

        <!-- Desa Lokasi Pangkalan -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Desa Lokasi Pangkalan</label>
            <input type="text" placeholder="Masukkan nama desa" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-orange-500 shadow-sm transition">
        </div>

        <!-- Nama Pangkalan -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Pangkalan</label>
            <input type="text" placeholder="Cth: Pangkalan Sinar Makmur" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-orange-500 shadow-sm transition">
        </div>

        <!-- Jenis LPG -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis LPG</label>
            <select class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-orange-500 shadow-sm transition">
                <option value="3kg" selected>LPG 3 Kg (Subsidi)</option>
                <option value="12kg">LPG 12 Kg</option>
                <option value="5.5kg">Bright Gas 5.5 Kg</option>
            </select>
        </div>

    </div>

    <!-- Container Oranye Lembayung (Stok Tersedia, Distribusi Masuk, Keluar) -->
    <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <!-- Stok Tersedia -->
        <div>
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-700 mb-1.5">STOK TERSEDIA</label>
            <div class="relative flex items-center">
                <input type="number" placeholder="0" class="w-full bg-white border border-amber-200 rounded-xl pl-3.5 pr-14 py-2 text-sm text-slate-800 outline-none focus:border-amber-500 transition">
                <span class="absolute right-3 text-xs text-slate-400 pointer-events-none">Tabung</span>
            </div>
        </div>

        <!-- Distribusi Masuk -->
        <div>
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-700 mb-1.5">DISTRIBUSI MASUK</label>
            <div class="relative flex items-center">
                <input type="number" placeholder="0" class="w-full bg-white border border-amber-200 rounded-xl pl-3.5 pr-14 py-2 text-sm text-slate-800 outline-none focus:border-amber-500 transition">
                <span class="absolute right-3 text-xs text-slate-400 pointer-events-none">Tabung</span>
            </div>
        </div>

        <!-- Keluar (Terjual) -->
        <div>
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-amber-700 mb-1.5">KELUAR (TERJUAL)</label>
            <div class="relative flex items-center">
                <input type="number" placeholder="0" class="w-full bg-white border border-amber-200 rounded-xl pl-3.5 pr-14 py-2 text-sm text-slate-800 outline-none focus:border-amber-500 transition">
                <span class="absolute right-3 text-xs text-slate-400 pointer-events-none">Tabung</span>
            </div>
        </div>

    </div>

    <!-- Grid 2 Kolom untuk Harga, Status, Upload -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Harga Pangkalan (Sesuai HET) -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Pangkalan (Sesuai HET)</label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-sm font-semibold text-slate-400">Rp</span>
                <input type="text" value="18000" class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-orange-500 shadow-sm transition">
            </div>
        </div>

        <!-- Harga Eceran (Pantauan Lapangan) -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Eceran (Pantauan Lapangan)</label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-sm font-semibold text-slate-400">Rp</span>
                <input type="text" placeholder="Harga di warung/pengecer" class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-orange-500 shadow-sm transition">
            </div>
        </div>

        <!-- Status Ketersediaan -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Ketersediaan</label>
            <select class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-orange-500 shadow-sm transition">
                <option value="aman" selected>🟢 Aman</option>
                <option value="terbatas">🟡 Terbatas</option>
                <option value="langka">🔴 Langka</option>
            </select>
        </div>

        <!-- Upload Bukti Foto -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Upload Bukti Foto</label>
            <div class="bg-white border border-slate-200 rounded-xl p-1.5 flex items-center gap-3 shadow-sm">
                <label class="cursor-pointer bg-orange-50 hover:bg-orange-100 text-orange-700 text-xs font-semibold px-4 py-2 rounded-lg transition">
                    Choose File
                    <input type="file" class="hidden">
                </label>
                <span class="text-xs text-slate-400">No file chosen</span>
            </div>
        </div>

    </div>

    <!-- Tombol Submit Data LPG -->
    <div class="flex justify-end pt-4 border-t border-slate-100">
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm shadow-md shadow-orange-500/20 flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 0115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            Submit Data LPG
        </button>
    </div>

</form>
        </div>
    </div>
@endsection
