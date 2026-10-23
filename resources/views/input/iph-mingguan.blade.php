@extends('layouts.app')

@section('title', 'Input IPH Mingguan')
@section('page-title', 'Input Data IPH Mingguan (BPS)')

@section('content')
<div class="space-y-6">

    {{-- Top Bar Header & Action Button --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-6 text-white flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Form Input IPH Mingguan</h3>
                    <p class="text-xs opacity-90 mt-0.5">Upload Indeks Perkembangan Harga mingguan oleh BPS Kabupaten Gorontalo Utara.</p>
                </div>
            </div>

            {{-- Tombol Cetak Laporan / Sync ke Google Sheets --}}
            <div>
                <a href="{{ route('input.iph.export-spreadsheet') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656"/>
                </svg>
            Cetak ke Google Sheet
                </a>
            </div>
        </div>

        {{-- Alert Flash Message --}}
        @if(session('success'))
            <div class="m-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Body --}}
        <form method="POST" action="{{ url('/input/iph-mingguan') }}" class="p-8 space-y-6">
            @csrf

            {{-- Baris 1: Wilayah & Waktu --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kabupaten / Kota</label>
                    <input type="text" name="kabupaten" value="Kab. Gorontalo Utara" readonly class="w-full bg-slate-100 border border-slate-200 text-slate-600 text-sm font-bold rounded-xl px-4 py-3 outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Bulan *</label>
                    <select name="bulan" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                        <option value="">Pilih Bulan...</option>
                        @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Juni', 'Juli', 'Agust', 'Sept', 'Okt', 'Nov', 'Des'] as $b)
                            <option value="{{ $b }}" {{ date('M') == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Minggu Ke- *</label>
                    <select name="minggu" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white transition">
                        <option value="1">Minggu 1</option>
                        <option value="2">Minggu 2</option>
                        <option value="3">Minggu 3</option>
                        <option value="4" selected>Minggu 4</option>
                        <option value="5">Minggu 5</option>
                    </select>
                </div>
            </div>

            {{-- Baris 2: Parameter Utama (IPH & Ranking) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                <div>
                    <label class="block text-xs font-bold text-blue-900 uppercase mb-2">Nilai IPH (%) *</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="iph" placeholder="Contoh: -0.55 atau 2.33" required class="w-full bg-white border border-blue-200 text-slate-900 text-base font-black rounded-xl px-4 py-3 outline-none focus:border-blue-500 shadow-sm">
                        <span class="absolute right-4 top-3.5 text-xs font-bold text-slate-400">%</span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1.5 block">Masukkan nilai desimal (gunakan tanda minus `-` jika minus)</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-blue-900 uppercase mb-2">Ranking Nasional *</label>
                    <input type="number" name="ranking" placeholder="Contoh: 14" required class="w-full bg-white border border-blue-200 text-slate-900 text-base font-black rounded-xl px-4 py-3 outline-none focus:border-blue-500 shadow-sm">
                    <span class="text-[11px] text-slate-400 mt-1.5 block">Urutan posisi peringkat inflasi/IPH nasional</span>
                </div>
            </div>

            {{-- Baris 3: Komoditas Andil Perubahan Harga --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Komoditas Andil Perubahan Harga *</label>
                <textarea name="komoditas_andil" rows="3" required placeholder="Contoh: Cabai Merah(-0,2954), Cabai Rawit(-0,2243), Beras(-0,0553)" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition resize-none"></textarea>
                <span class="text-[11px] text-slate-400 mt-1 block">Sebutkan 3 komoditas utama penyumbang andil IPH beserta persentasenya sesuai data BPS</span>
            </div>

            {{-- Catatan Informasi --}}
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-500 text-xs leading-relaxed">
                <span class="font-bold text-slate-700">Catatan BPS:</span> IPH minggu ke-n adalah rerata dari minggu 1 sd minggu ke-n, sehingga IPH minggu terakhir menjadi IPH bulanan (MtM).
            </div>

            {{-- Tombol Submit --}}
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/20 transition-all active:scale-[0.98] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                    Simpan Data IPH Mingguan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
