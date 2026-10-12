@extends('layouts.app')

@section('title', 'Saran Publik')
@section('page-title', 'Saran & Laporan Publik')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Form Saran --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden sticky top-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-5 text-white">
                <h3 class="font-bold text-lg">Laporkan Temuan</h3>
                <p class="text-sm opacity-90">Bantu kami memantau harga & ketersediaan di wilayah Anda.</p>
            </div>

            @if(session('success'))
                <div class="mx-5 mt-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('saran-publik.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori Laporan</label>
                    <select name="kategori" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="harga_pangan">Harga Pangan</option>
                        <option value="ketersediaan_pangan">Ketersediaan Pangan</option>
                        <option value="harga_lpg">Harga LPG</option>
                        <option value="ketersediaan_lpg">Ketersediaan LPG</option>
                        <option value="harga_bbm">Harga BBM</option>
                        <option value="ketersediaan_bbm">Ketersediaan BBM</option>
                        <option value="saran_umum">Saran Umum</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kecamatan</label>
                    <select name="kecamatan_id" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="">Pilih Kecamatan...</option>
                        @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Laporan</label>
                    <input type="text" name="judul_laporan" required placeholder="Contoh: Harga cabai naik tajam" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Komoditas (jika ada)</label>
                    <input type="text" name="nama_komoditas_dilaporkan" placeholder="Contoh: Cabai Rawit" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga yang Dilaporkan (Rp) - opsional</label>
                    <input type="number" name="harga_dilaporkan" placeholder="Contoh: 85000" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Isi Laporan</label>
                    <textarea name="isi_laporan" rows="4" required placeholder="Jelaskan temuan Anda..." class="w-full bg-white border border-slate-200 rounded-xl p-3.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama (opsional)</label>
                        <input type="text" name="nama_pelapor" placeholder="Nama Anda" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kontak (opsional)</label>
                        <input type="text" name="kontak_pelapor" placeholder="No. HP" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                    </div>
                </div>
                <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" name="anonim" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Kirim sebagai anonim</span>
                </label>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-600/20 transition">
                    Kirim Laporan
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Saran --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Daftar Laporan Publik</h2>
            <span class="text-xs font-semibold text-slate-500">{{ $saranList->count() }} laporan</span>
        </div>

        @forelse($saranList as $saran)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div>
                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-700 mb-2">
                        {{ str_replace('_', ' ', $saran->kategori) }}
                    </span>
                    <h4 class="font-bold text-slate-900">{{ $saran->judul_laporan }}</h4>
                </div>
                <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $saran->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed mb-3">{{ $saran->isi_laporan }}</p>
            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    {{ $saran->kecamatan?->nama_kecamatan ?? 'Tidak diketahui' }}
                </span>
                @if($saran->nama_komoditas_dilaporkan)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                        {{ $saran->nama_komoditas_dilaporkan }}
                    </span>
                @endif
                @if($saran->harga_dilaporkan)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Rp {{ number_format($saran->harga_dilaporkan, 0, ',', '.') }}
                    </span>
                @endif
                <span class="ml-auto px-2.5 py-1 rounded-md text-[10px] font-bold uppercase
                    @if($saran->status == 'baru') bg-slate-100 text-slate-600
                    @elseif($saran->status == 'diproses') bg-amber-100 text-amber-700
                    @else bg-emerald-100 text-emerald-700 @endif">
                    {{ $saran->status }}
                </span>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center text-slate-400">
            <p class="text-sm">Belum ada laporan publik.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
