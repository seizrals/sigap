@extends('layouts.app')

@section('title', 'Early Warning System')
@section('page-title', 'Early Warning System (EWS)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Alert Cards --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Card Header EWS --}}
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        Pusat Peringatan Dini (EWS)
                    </h3>
                    <p class="text-sm text-slate-500">Sistem Analisis Deteksi Ancaman Pangan, LPG, & BBM Terintegrasi</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Deteksi Kritis --}}
            <div class="border-2 border-red-100 rounded-2xl p-5 bg-red-50/30 mb-4">
                <p class="text-xs font-bold uppercase tracking-wider text-red-600 mb-3">Notifikasi Deteksi Kritis</p>

                @forelse($alerts as $alert)
                <div class="relative bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs overflow-hidden {{ $loop->last ? '' : 'mb-4' }}">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $alert->tingkat_risiko == 'tinggi' ? 'bg-red-600' : ($alert->tingkat_risiko == 'sedang' ? 'bg-amber-500' : 'bg-emerald-500') }}"></div>
                    <div class="pl-2">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $alert->tingkat_risiko == 'tinggi' ? 'bg-red-100/80 text-red-600' : ($alert->tingkat_risiko == 'sedang' ? 'bg-amber-100/80 text-amber-600' : 'bg-emerald-100/80 text-emerald-600') }} flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 005.814-5.519l2.74-1.22m0 0l-5.94-2.28 2.28 5.941"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $alert->judul_alert }}</h3>
                                    <p class="text-xs font-semibold {{ $alert->tingkat_risiko == 'tinggi' ? 'text-red-600' : ($alert->tingkat_risiko == 'sedang' ? 'text-amber-600' : 'text-emerald-600') }} mt-0.5">Wilayah: {{ $alert->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara' }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-lg bg-slate-100 text-[11px] font-semibold text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($alert->waktu_terdeteksi)->diffForHumans() }}
                            </span>
                        </div>
                        <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3.5 mb-4 text-xs leading-relaxed text-slate-600">
                            <strong class="font-bold text-slate-800">Hasil Analisis Sistem :</strong>
                            {{ $alert->analisis_sistem }}
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0C2.678 5.577 2.25 6.057 2.25 6.625v11.25"/>
                                </svg>
                                <span>Terbitkan Rekomendasi Operasi Pasar</span>
                            </button>
                            <button class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                                Panggil Pemasok Lokal
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-400 text-sm">
                    Tidak ada peringatan kritis saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Widget --}}
    <div class="space-y-6">

        {{-- Widget Peta Kerawanan --}}
        <div class="relative bg-[#0F172A] rounded-2xl p-5 border border-slate-800 text-white shadow-xl overflow-hidden">
            <div class="absolute -right-4 -bottom-6 text-slate-800/40 pointer-events-none">
                <svg class="w-40 h-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                </svg>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/>
                    </svg>
                    <h3 class="text-xs font-black tracking-wider uppercase text-white">Peta Kerawanan</h3>
                </div>
                <div class="space-y-2 mb-4">
                    @forelse($kecamatanList as $kec)
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition-colors">
                        <span class="text-xs font-bold text-slate-100">{{ $kec->nama_kecamatan }}</span>
                        <span class="w-3 h-3 rounded-full shadow-sm
                            @if($kec->zona_risiko == 'merah') bg-red-500 shadow-red-500/50
                            @elseif($kec->zona_risiko == 'kuning') bg-amber-400 shadow-amber-400/50
                            @else bg-emerald-500 shadow-emerald-500/50 @endif"></span>
                    </div>
                    @empty
                    <div class="px-4 py-3 text-center text-slate-400 text-xs">Belum ada data kecamatan.</div>
                    @endforelse
                </div>
                <a href="{{ route('dashboard') }}" class="block w-full py-2.5 rounded-xl border border-blue-500/40 bg-blue-950/20 hover:bg-blue-900/40 text-blue-400 text-xs font-bold transition-all text-center">
                    Lihat Peta Lengkap
                </a>
            </div>
        </div>

        {{-- Widget Rekomendasi Strategis Otomatis --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <h3 class="text-sm font-bold text-slate-900">Rekomendasi Strategis</h3>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                Saran tindakan otomatis berdasarkan data warning per kecamatan.
            </p>

            <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
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

        {{-- Widget Input Laporan Manual --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 leading-snug mb-2">Input Laporan Manual Lapangan</h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-5">
                Formulir darurat untuk petugas jika sistem analitik belum menangkap anomali di lokasi terpencil.
            </p>
            <button x-data @click="$dispatch('open-alert-modal')" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-red-50 hover:bg-red-100 border border-red-200/80 text-red-600 text-xs font-bold transition-all active:scale-95">
                <svg class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Buat Peringatan Baru</span>
            </button>
        </div>
    </div>
</div>

{{-- Modal Buat Peringatan Baru --}}
<div x-data="{ open: false }"
     @open-alert-modal.window="open = true"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">

    <div @click.away="open = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 relative border border-slate-100">

        <button @click="open = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="flex items-center gap-2 mb-1">
            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Buat Peringatan Baru</h2>
                <p class="text-xs text-slate-500">Laporan manual untuk anomali yang belum terdeteksi sistem.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('ews.store') }}" class="mt-5 space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori *</label>
                    <select name="kategori" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="pangan">Pangan</option>
                        <option value="lpg">LPG</option>
                        <option value="bbm">BBM</option>
                        <option value="multikomoditas">Multikomoditas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tingkat Risiko *</label>
                    <select name="tingkat_risiko" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="rendah">Rendah</option>
                        <option value="sedang" selected>Sedang</option>
                        <option value="tinggi">Tinggi</option>
                        <option value="kritis">Kritis</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kecamatan *</label>
                <select name="kecamatan_id" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                    <option value="">Pilih Kecamatan...</option>
                    @foreach($kecamatanOptions as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Peringatan *</label>
                <input type="text" name="judul_alert" required placeholder="Contoh: Lonjakan Harga Cabai Rawit" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Analisis / Keterangan *</label>
                <textarea name="analisis_sistem" rows="4" required placeholder="Jelaskan temuan atau anomali yang terdeteksi di lapangan..." class="w-full bg-white border border-slate-200 rounded-xl p-3.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition resize-none"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-2xl flex items-center justify-center gap-2 text-xs transition-colors shadow-md shadow-red-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Simpan Peringatan
                </button>
                <button type="button" @click="open = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-5 rounded-2xl text-xs transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
