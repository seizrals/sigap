@extends('layouts.app')

@section('title', 'Input Pangan Strategis')
@section('page-title', 'Input Komoditas Pangan Strategis')

@section('content')
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-amber-400 to-amber-500 p-6 text-white flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.422 48.422 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">Form Input Komoditas Pangan Strategis</h3>
                <p class="text-sm opacity-90">Laporkan harga dan ketersediaan pangan per komoditas di pasar wilayah Anda.</p>
            </div>
        </div>
        
    <form class="bg-white p-6 rounded-2xl max-w-8xl mx-auto space-y-5 text-slate-700">
    
    <!-- Grid 2 Kolom untuk Form Field -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Tanggal Input -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Input</label>
            <input type="date" value="2026-12-05" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
        </div>

        <!-- Kecamatan (Otomatis) -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kecamatan (Otomatis)</label>
            <input type="text" value="Kwandang" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-500 font-medium outline-none cursor-not-allowed shadow-sm">
        </div>

        <!-- Nama Pasar / Lokasi Pantau -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Pasar / Lokasi Pantau</label>
            <input type="text" placeholder="Cth: Pasar Sentral Kwandang" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
        </div>

        <!-- Jenis Komoditas -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis Komoditas</label>
            <select class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                <option value="" disabled selected>Pilih Komoditas...</option>
                <option value="beras">Beras Premium</option>
                <option value="cabai">Cabai Rawit</option>
                <option value="bawang">Bawang Merah</option>
            </select>
        </div>

        <!-- Harga Hari Ini (Rp) -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Hari Ini (Rp)</label>
            <input type="number" placeholder="Cth: 15000" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
        </div>

        <!-- Status Ketersediaan -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Ketersediaan</label>
            <select class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                <option value="stabil" selected>🟢 Stabil / Aman</option>
                <option value="kritis">🔴 Kritis / Langka</option>
            </select>
        </div>

        <!-- Stok Tersedia (Estimasi) - Input + Satuan Input Group -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stok Tersedia (Estimasi)</label>
            <div class="flex rounded-xl shadow-sm overflow-hidden border border-slate-200 focus-within:border-blue-500 transition">
                <input type="text" placeholder="Jumlah" class="w-full bg-white px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none">
                <input type="text" placeholder="Satuan (Kg/Ton)" class="w-2/5 bg-slate-50 border-l border-slate-200 px-3 py-2.5 text-sm placeholder:text-slate-300 text-slate-600 outline-none text-center">
            </div>
        </div>

        <!-- Distribusi Masuk Hari Ini -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Distribusi Masuk Hari Ini</label>
            <input type="text" placeholder="Cth: 2 Ton dari Gorontalo" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
        </div>

    </div>

    <!-- Keterangan / Kendala Lapangan -->
    <div class="pt-2">
        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan / Kendala Lapangan</label>
        <textarea rows="3" placeholder="Tambahkan catatan jika ada lonjakan harga atau masalah distribusi..." class="w-full bg-white border border-slate-200 rounded-xl p-3.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition resize-none"></textarea>
    </div>

    <!-- Tombol Action (Batal & Simpan) -->
    <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-100">
        <button type="button" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition">
            Batal
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm shadow-md shadow-amber-500/20 flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Simpan Data Komoditas
        </button>
    </div>

</form>
@endsection
