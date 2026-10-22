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

    @if(session('success'))
        <div class="mx-6 mt-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mx-6 mt-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('input.pangan.store') }}" class="bg-white p-6 rounded-2xl max-w-8xl mx-auto space-y-5 text-slate-700">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Input</label>
                <input type="date" name="tanggal_input" value="{{ old('tanggal_input', date('Y-m-d')) }}" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-amber-500 shadow-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kecamatan</label>
                <select name="kecamatan_id" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-amber-500 shadow-sm transition">
                    <option value="">Pilih Kecamatan...</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis Komoditas</label>
                <select name="komoditas_id" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-amber-500 shadow-sm transition">
                    <option value="">Pilih Komoditas...</option>
                    <option value="" selected>Bawang Merah(KG)</option>
                    <option value="" selected>Bawang Putih(KG)</option>
                    <option value="" selected>Beras Medium(KG)</option>
                    <option value="" selected>Berah Premium(KG)</option>
                    <option value="" selected>Cabai Merah Keriting(KG)</option>
                    <option value="" selected>Cabai Rawit Merah(KG)</option>
                    <option value="" selected>Daging Ayam(KG)</option>
                    <option value="" selected>Daging Sapi(KG)</option>
                    <option value="" selected>Garam Halus(KG)</option>
                    <option value="" selected>Gula Pasir(KG)</option>
                     <option value="" selected>Ikan(KG)</option>
                    <option value="" selected>Minyak Goreng Sawit Curah(LITER)</option>
                    <option value="" selected>Minyak Goreng Kemasan(LITER)</option>
                    <option value="" selected>Telur Ayam(KG)</option>                        
                    <option value="" selected>Tepung Terigu(KG)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Hari Ini (Rp)</label>
                <input type="number" name="harga_hari_ini" value="{{ old('harga_hari_ini') }}" placeholder="Cth: 15000" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-amber-500 shadow-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Hari Sebelumnya (Rp)</label>
                <input type="number" name="harga_hari_sebelumnya" value="{{ old('harga_hari_sebelumnya') }}" placeholder="Cth: 14000" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-amber-500 shadow-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Ketersediaan</label>
                <select name="status_ketersediaan" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-amber-500 shadow-sm transition">
                    <option value="aman" {{ old('status_ketersediaan', 'aman') == 'aman' ? 'selected' : '' }}>Aman</option>
                    <option value="waspada" {{ old('status_ketersediaan') == 'waspada' ? 'selected' : '' }}>Waspada</option>
                    <option value="kurang" {{ old('status_ketersediaan') == 'kurang' ? 'selected' : '' }}>Kurang</option>
                    <option value="kosong" {{ old('status_ketersediaan') == 'kosong' ? 'selected' : '' }}>Kritis</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Stok Lokal Tersedia (Estimasi)</label>
                <div class="flex rounded-xl shadow-sm overflow-hidden border border-slate-200 focus-within:border-amber-500 transition">
                    <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia') }}" placeholder="Jumlah" class="w-full bg-white px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none">
                    <input type="text" name="satuan_stok" value="{{ old('satuan_stok', 'Kg') }}" placeholder="Satuan" class="w-2/5 bg-slate-50 border-l border-slate-200 px-3 py-2.5 text-sm placeholder:text-slate-300 text-slate-600 outline-none text-center">
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Distribusi Dari luar </label>
                <input type="text" name="sumber_distribusi_masuk" value="{{ old('sumber_distribusi_masuk') }}" placeholder="Cth: 2 Ton dari Gorontalo" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-amber-500 shadow-sm transition">
            </div>
        </div>
        <div class="pt-2">
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan / Kendala Lapangan</label>
            <textarea name="keterangan_lapangan" rows="3" placeholder="Tambahkan catatan jika ada lonjakan harga atau masalah distribusi..." class="w-full bg-white border border-slate-200 rounded-xl p-3.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-amber-500 shadow-sm transition resize-none">{{ old('keterangan_lapangan') }}</textarea>
        </div>
        <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-100">
            <button type="button" onclick="window.history.back()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition">Batal</button>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm shadow-md shadow-amber-500/20 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Data Komoditas
            </button>
        </div>
    </form>
</div>
@endsection
