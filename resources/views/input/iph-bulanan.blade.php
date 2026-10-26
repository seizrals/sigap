@extends('layouts.app')

@section('title', 'Input IPH Bulanan')
@section('page-title', 'Form Input IPH Bulanan (BPS)')

@section('content')
<div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
    <!-- Header Card dengan Tombol Cetak ke Google Sheet di Kanan -->
    <div class="bg-gradient-to-r from-blue-600 to-emerald-600 p-6 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">Form Input IPH Bulanan</h3>
                <p class="text-sm opacity-90">Upload Indeks Perkembangan Harga Bulanan oleh BPS Kabupaten Gorontalo Utara.</p>
            </div>
        </div>

        <!-- Tombol Cetak / Buka Google Sheet -->
        <div>
            <a href="https://docs.google.com/spreadsheets/d/1fu28DPHDExDi2zKRWkuHAsq-QodvV4WHTi1nHDeEU4U/edit?usp=sharing" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-emerald-700 hover:bg-slate-100 rounded-xl text-xs font-bold transition shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656"/>
                </svg>
                Cetak ke Google Sheet
            </a>
        </div>
    </div>

    <!-- Form Konten -->
    <div class="p-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('input.iph_bulanan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Baris 1: Tahun & Bulan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tahun -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tahun *</label>
                    <select name="tahun" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 px-4 bg-slate-50/50" required>
                        <option value="2026" {{ date('Y') == '2026' ? 'selected' : '' }}>2026</option>
                        <option value="2025" {{ date('Y') == '2025' ? 'selected' : '' }}>2025</option>
                    </select>
                </div>

                <!-- Bulan -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Bulan *</label>
                    <select name="bulan" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 px-4" required>
                        <option value="" disabled selected>Pilih Bulan...</option>
                        <option value="Jan">Jan</option>
                        <option value="Feb">Feb</option>
                        <option value="Mrt">Mrt</option>
                        <option value="Apr">Apr</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agst">Agst</option>
                        <option value="Sept">Sept</option>
                        <option value="Okt">Okt</option>
                        <option value="Nov">Nov</option>
                        <option value="Des">Des</option>
                    </select>
                </div>
            </div>

            <!-- Baris 2: IPH Bulanan (%) -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">IPH Bulanan (%) *</label>
                <div class="relative">
                    <input type="number" step="0.01" name="iph_bulanan" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3 px-4 pr-10" placeholder="Contoh: 1.39 atau -2.49" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 font-semibold">%</div>
                </div>
                <p class="text-xs text-slate-500">Masukkan nilai desimal (gunakan tanda minus `-` jika minus)</p>
            </div>

            <!-- Baris 3: Komoditas Andil Perubahan Harga -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Komoditas Andil Perubahan Harga *</label>
                <textarea name="komoditas_andil" rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm p-4" placeholder="Contoh: Cabai Merah(0.6506), Bawang Merah(0.2445)" required></textarea>
                <p class="text-xs text-slate-500">Sebutkan komoditas utama penyumbang andil IPH beserta persentasenya sesuai data BPS</p>
            </div>

            <!-- Catatan BPS -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600">
                <span class="font-bold text-slate-700">Catatan BPS:</span> Data IPH bulanan merupakan akumulasi atau angka resmi bulanan yang dirilis untuk memonitor inflasi daerah Kabupaten Gorontalo Utara.
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Data IPH Bulanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
