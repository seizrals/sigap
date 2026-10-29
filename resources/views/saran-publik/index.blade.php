@extends('layouts.app')

@section('title', 'Saran Publik - SIGAP+')
@section('page-title', 'Saran & Laporan Publik')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter & Kontrol Pencarian --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-800">Daftar Saran & Laporan Masuk</h3>
                <p class="text-xs text-slate-500">Aduan dan masukan masyarakat yang dikirimkan melalui Portal Publik.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-xs font-bold text-blue-700">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    Total: {{ $kategoriCounts['semua'] }} Laporan
                </span>
            </div>
        </div>

        {{-- Filter Pills Kategori Laporan --}}
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2.5">Filter Kategori</p>
            <div class="flex flex-wrap gap-2 text-xs font-bold">
                <a href="{{ route('saran-publik.index') }}" 
                   class="px-3.5 py-1.5 rounded-xl transition border {{ empty($filterKategori) ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200' }}">
                    Semua ({{ $kategoriCounts['semua'] }})
                </a>
                <a href="{{ route('saran-publik.index', array_filter(['kategori' => 'lonjakan_harga', 'kecamatan_id' => $filterKecamatan, 'q' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition border {{ $filterKategori === 'lonjakan_harga' ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100 border-rose-200' }}">
                    Lonjakan Harga ({{ $kategoriCounts['lonjakan_harga'] }})
                </a>
                <a href="{{ route('saran-publik.index', array_filter(['kategori' => 'kelangkaan_stok', 'kecamatan_id' => $filterKecamatan, 'q' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition border {{ $filterKategori === 'kelangkaan_stok' ? 'bg-amber-600 text-white border-amber-600 shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100 border-amber-200' }}">
                    Kelangkaan Stok ({{ $kategoriCounts['kelangkaan_stok'] }})
                </a>
                <a href="{{ route('saran-publik.index', array_filter(['kategori' => 'pelanggaran_het', 'kecamatan_id' => $filterKecamatan, 'q' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition border {{ $filterKategori === 'pelanggaran_het' ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : 'bg-purple-50 text-purple-700 hover:bg-purple-100 border-purple-200' }}">
                    Pelanggaran HET ({{ $kategoriCounts['pelanggaran_het'] }})
                </a>
                <a href="{{ route('saran-publik.index', array_filter(['kategori' => 'saran_kebijakan', 'kecamatan_id' => $filterKecamatan, 'q' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition border {{ $filterKategori === 'saran_kebijakan' ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border-emerald-200' }}">
                    Saran Kebijakan ({{ $kategoriCounts['saran_kebijakan'] }})
                </a>
                <a href="{{ route('saran-publik.index', array_filter(['kategori' => 'lainnya', 'kecamatan_id' => $filterKecamatan, 'q' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition border {{ $filterKategori === 'lainnya' ? 'bg-slate-700 text-white border-slate-700 shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border-slate-200' }}">
                    Lainnya ({{ $kategoriCounts['lainnya'] }})
                </a>
            </div>
        </div>

        {{-- Form Filter Pencarian & Dropdown --}}
        <form method="GET" action="{{ route('saran-publik.index') }}" class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-12 gap-2 text-xs">
            <div class="sm:col-span-5">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul laporan, komoditas, nama pelapor, kontak, kode laporan..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 placeholder:text-slate-400 outline-none focus:border-blue-500 shadow-2xs font-medium">
            </div>
            <div class="sm:col-span-3">
                <select name="kecamatan_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 font-semibold outline-none focus:border-blue-500 shadow-2xs">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}" @selected((string) $filterKecamatan === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <select name="kategori" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 font-semibold outline-none focus:border-blue-500 shadow-2xs">
                    <option value="">Semua Kategori</option>
                    <option value="lonjakan_harga" @selected($filterKategori === 'lonjakan_harga')>Lonjakan Harga</option>
                    <option value="kelangkaan_stok" @selected($filterKategori === 'kelangkaan_stok')>Kelangkaan Stok</option>
                    <option value="pelanggaran_het" @selected($filterKategori === 'pelanggaran_het')>Pelanggaran HET</option>
                    <option value="saran_kebijakan" @selected($filterKategori === 'saran_kebijakan')>Saran Kebijakan</option>
                    <option value="lainnya" @selected($filterKategori === 'lainnya')>Lainnya</option>
                </select>
            </div>
            <div class="sm:col-span-2 flex items-center gap-1.5">
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-2xs transition">
                    Cari
                </button>
                @if($filterKategori || $filterKecamatan || $search)
                    <a href="{{ route('saran-publik.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition text-center shrink-0" title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Feed Daftar Laporan --}}
    <div class="space-y-3.5">
        @forelse($saranList as $saran)
            @php
                $badgeStyle = match ($saran->kategori) {
                    'lonjakan_harga' => 'bg-rose-100 text-rose-800 border-rose-200',
                    'kelangkaan_stok' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'pelanggaran_het' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'saran_kebijakan' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                };
                $kategoriLabel = match ($saran->kategori) {
                    'lonjakan_harga' => 'Lonjakan Harga',
                    'kelangkaan_stok' => 'Kelangkaan Stok',
                    'pelanggaran_het' => 'Pelanggaran HET',
                    'saran_kebijakan' => 'Saran Kebijakan',
                    default => 'Lainnya',
                };
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 hover:border-slate-300 transition space-y-3">
                <div class="flex flex-wrap items-start justify-between gap-2 border-b border-slate-100 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider border {{ $badgeStyle }}">
                            {{ $kategoriLabel }}
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-400">
                            {{ $saran->kode_laporan }}
                        </span>
                    </div>
                    <span class="text-[11px] font-medium text-slate-400">
                        {{ $saran->created_at->isoFormat('D MMM YYYY, HH:mm') }} WITA ({{ $saran->created_at->diffForHumans() }})
                    </span>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 text-base leading-snug mb-1">{{ $saran->judul_laporan }}</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $saran->isi_laporan }}</p>
                </div>

                {{-- Metadata Chips --}}
                <div class="flex flex-wrap items-center gap-2 pt-1 text-xs">
                    @if($saran->kecamatan || $saran->lokasi_spesifik)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200/80 text-slate-700 font-semibold">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                            <span>{{ $saran->lokasi_spesifik ?: $saran->kecamatan?->nama_kecamatan }}</span>
                        </div>
                    @endif

                    @if($saran->nama_komoditas_dilaporkan)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-100 text-blue-800 font-semibold">
                            <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                            </svg>
                            <span>{{ $saran->nama_komoditas_dilaporkan }}</span>
                        </div>
                    @endif

                    @if($saran->harga_dilaporkan)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-800 font-bold">
                            <span>Rp {{ number_format($saran->harga_dilaporkan, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Bar Informasi Pelapor & Kontak (Sangat Jelas & Menonjol) --}}
                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs bg-slate-50/70 -mx-5 -mb-5 px-5 py-3 rounded-b-2xl">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 font-medium">Pelapor:</span>
                        <span class="font-bold text-slate-800">{{ $saran->nama_pelapor ?: 'Warga' }}</span>
                        @if($saran->anonim)
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 font-bold text-[10px] border border-amber-200">Anonim di Portal</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-slate-400 font-bold">Kontak Pelapor:</span>
                        @if($saran->kontak_pelapor)
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $saran->kontak_pelapor);
                                $isPhone = strlen($cleanPhone) >= 9;
                                $waUrl = $isPhone ? ('https://wa.me/' . (str_starts_with($cleanPhone, '0') ? ('62' . substr($cleanPhone, 1)) : $cleanPhone)) : null;
                            @endphp
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ $isPhone ? ('tel:' . $saran->kontak_pelapor) : ('mailto:' . $saran->kontak_pelapor) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 font-extrabold text-xs transition shadow-2xs"
                                   title="Hubungi Pelapor">
                                    <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                    </svg>
                                    <span>{{ $saran->kontak_pelapor }}</span>
                                </a>
                                @if($waUrl)
                                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold text-xs transition shadow-2xs"
                                       title="Chat WhatsApp Pelapor">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm5.79 14.12c-.24.68-1.39 1.25-1.92 1.33-.5.08-1.14.12-3.32-.78-2.6-1.07-4.27-3.73-4.4-3.9-.13-.17-1.05-1.4-1.05-2.66 0-1.27.66-1.89.89-2.15.24-.26.52-.33.69-.33.17 0 .35 0 .5.01.16.01.38-.06.59.45.22.53.75 1.83.82 1.97.07.14.11.31.02.49-.09.18-.14.29-.28.45-.14.16-.29.35-.42.47-.14.14-.29.29-.12.57.17.29.74 1.23 1.6 1.99 1.1 1 2.03 1.31 2.32 1.45.29.14.46.12.63-.07.17-.19.74-.86.94-1.15.2-.29.4-.24.67-.14.28.09 1.74.82 2.04.97.3.15.5.22.57.34.07.12.07.71-.17 1.39z"/>
                                        </svg>
                                        <span>WhatsApp</span>
                                    </a>
                                @endif
                            </div>
                        @else
                            <span class="text-slate-400 italic">Tidak tersedia</span>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center text-slate-400 space-y-2">
                <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <p class="text-sm font-semibold text-slate-600">Belum ada saran / laporan untuk kriteria pencarian ini.</p>
                <p class="text-xs text-slate-400">Aduan masyarakat yang dikirimkan melalui portal publik akan otomatis muncul di sini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($saranList->hasPages())
        <div class="pt-2">
            {{ $saranList->links() }}
        </div>
    @endif

</div>
@endsection
