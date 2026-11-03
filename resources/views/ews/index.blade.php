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
                    <p class="text-sm text-slate-500">Analisis otomatis & laporan lapangan terintegrasi</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Deteksi --}}
            <div class="border-2 border-slate-100 rounded-2xl p-5 bg-slate-50/30 mb-4">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Notifikasi Deteksi Anomali</p>
                    <span class="text-[10px] font-semibold bg-white border border-slate-200 text-slate-600 px-2.5 py-0.5 rounded-full shadow-sm">
                        {{ isset($alerts) ? count($alerts) : 0 }} Peringatan Aktif
                    </span>
                </div>

                <!-- Container dengan Fungsi Scroll -->
                <div class="max-h-[600px] overflow-y-auto pr-1 space-y-4 custom-scrollbar">
                    @forelse($alerts as $alert)
                        <div class="relative bg-white rounded-2xl border border-slate-200 p-5 shadow-xs transition hover:shadow-md">
                            {{-- Indikator Warna Kiri --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $alert->status_ketersediaan == 'kritis' ? 'bg-red-500' : ($alert->status_ketersediaan == 'waspada' ? 'bg-amber-500' : 'bg-emerald-500') }}"></div>
                            
                            <div class="pl-2">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-xl {{ $alert->status_ketersediaan == 'kritis' ? 'bg-red-50 text-red-600' : ($alert->status_ketersediaan == 'waspada' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }} flex items-center justify-center shrink-0 border border-current/10">
                                            @if($alert->is_manual)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 005.814-5.519l2.74-1.22m0 0l-5.94-2.28 2.28 5.941"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $alert->judul_alert }}</h3>
                                                @if(!$alert->is_manual)
                                                    <span class="text-[9px] font-black uppercase tracking-tighter bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded border border-blue-200">Auto-Detect</span>
                                                @else
                                                    <span class="text-[9px] font-black uppercase tracking-tighter bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded border border-slate-200">Manual</span>
                                                @endif
                                            </div>
                                            <p class="text-xs font-semibold text-slate-600">Wilayah: {{ $alert->wilayah }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $alert->kategori }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $alert->status_ketersediaan == 'kritis' ? 'bg-red-100 text-red-700' : ($alert->status_ketersediaan == 'waspada' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                                    Status {{ ucfirst($alert->status_ketersediaan) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-400 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($alert->waktu_terdeteksi)->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-xs leading-relaxed text-slate-700">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v17.792m0-17.792l-6.25 6.25m6.25-6.25l6.25 6.25M9.75 3.104c0-1.104.896-2 2-2s2 .896 2 2v17.792c0 1.104-.896 2-2 2s-2-.896-2-2"/></svg>
                                        <div>
                                            <strong class="font-bold text-slate-900 block mb-1">Analisis Sistem & Rekomendasi :</strong>
                                            {{ $alert->analisis_sistem }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-dashed border-slate-100">
                                    <div class="flex flex-wrap gap-2">
                                        @if($alert->foto_laporan_path)
                                            @foreach($alert->foto_laporan_path as $index => $fotoPath)
                                                <button type="button" x-data @click="$dispatch('open-alert-photo', { src: @js(asset('storage/' . $fotoPath)), title: @js('Lampiran foto ' . ($index + 1) . ' - ' . $alert->judul_alert) })" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-100 hover:bg-blue-100 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                                    Lihat Foto {{ $index + 1 }}
                                                </button>
                                            @endforeach
                                        @endif
                                    </div>

                                    @if(!$alert->is_manual || ($alert->is_manual && !isset($alert->status) || $alert->status != 'selesai'))
                                        <button type="button" x-data @click="$dispatch('open-resolve-modal', { id: @js($alert->id), title: @js($alert->judul_alert) })" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 text-white text-[11px] font-black hover:bg-emerald-700 transition shadow-sm active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.746 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                                            Selesai Ditindaklanjuti
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.746 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900 mb-1">Seluruh Wilayah Aman</h4>
                            <p class="text-xs text-slate-500">Sistem belum mendeteksi adanya anomali stok atau harga saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Widget --}}
    <div class="space-y-6">
        {{-- Widget Input Laporan Manual --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 leading-snug mb-2">Input Laporan Manual Lapangan</h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-5">
                Gunakan formulir ini untuk melaporkan temuan anomali harga atau stok yang belum tertangkap otomatis oleh sistem.
            </p>
            <button x-data @click="$dispatch('open-alert-modal')" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-md shadow-red-500/20 active:scale-95">
                <svg class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Buat Peringatan Baru</span>
            </button>
        </div>

        {{-- Widget Info --}}
        <div class="bg-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/20">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                </div>
                <h4 class="text-sm font-bold">Informasi Sistem</h4>
            </div>
            <p class="text-[11px] leading-relaxed text-blue-50/80">
                Pusat Peringatan Dini (EWS) mengintegrasikan data real-time dari:
            </p>
            <ul class="mt-3 space-y-2">
                <li class="flex items-center gap-2 text-[10px] font-bold bg-white/10 rounded-lg px-2 py-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    Pantauan Harga Pangan Strategis
                </li>
                <li class="flex items-center gap-2 text-[10px] font-bold bg-white/10 rounded-lg px-2 py-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    Stok LPG 3KG di Pangkalan & Agen
                </li>
                <li class="flex items-center gap-2 text-[10px] font-bold bg-white/10 rounded-lg px-2 py-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    Ketersediaan BBM di Seluruh SPBU
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Modal Preview Foto --}}
<div x-data="{ open: false, src: '', title: '', failed: false }"
     @open-alert-photo.window="src = $event.detail.src; title = $event.detail.title; failed = false; open = true"
     @keydown.escape.window="open = false"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4"
     role="dialog"
     aria-modal="true">
    <button type="button" @click="open = false" class="absolute inset-0 w-full h-full cursor-default"></button>
    <div class="relative z-10 w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <p class="text-sm font-bold text-slate-800 truncate" x-text="title"></p>
            <button type="button" @click="open = false" class="shrink-0 p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-slate-900 shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex items-center justify-center bg-slate-100 p-6 min-h-64 max-h-[80vh] overflow-auto">
            <img :src="src" :alt="title" x-show="!failed" x-on:error="failed = true" class="max-w-full max-h-full object-contain rounded-xl shadow-lg border-4 border-white">
            <p x-show="failed" class="py-16 text-sm font-bold text-red-600">Foto tidak dapat dimuat. Periksa folder storage.</p>
        </div>
    </div>
</div>

{{-- Modal Selesai Ditindaklanjuti --}}
<div x-data="{ open: false, id: null, title: '' }"
     @open-resolve-modal.window="id = $event.detail.id; title = $event.detail.title; open = true"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    
    <div @click.away="open = false" class="bg-white rounded-[32px] shadow-2xl max-w-lg w-full p-8 relative border border-slate-200">
        <button @click="open = false" class="absolute top-6 right-6 p-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 hover:text-slate-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.746 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Selesai Ditindaklanjuti</h2>
                <p class="text-sm text-slate-500 font-medium" x-text="title"></p>
            </div>
        </div>

        <form method="POST" :action="'/ews/' + id + '/resolve'" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Apa yang sudah dilakukan? *</label>
                <textarea name="tindak_lanjut" rows="4" required placeholder="Jelaskan penanganan yang telah dilakukan..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-sm font-medium placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition resize-none"></textarea>
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Bukti Foto Penanganan *</label>
                <input type="file" name="bukti_penanganan" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 border border-slate-200 rounded-2xl p-2 bg-slate-50 shadow-sm transition cursor-pointer">
                <p class="text-[10px] text-slate-400 font-medium italic">Format: JPG, PNG, WEBP. Ukuran maks 5MB.</p>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-6 rounded-[20px] text-xs transition-all shadow-lg shadow-emerald-500/30 active:scale-[0.98]">
                    SIMPAN PENANGANAN
                </button>
                <button type="button" @click="open = false" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-[20px] text-xs transition-all active:scale-[0.98]">
                    BATAL
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Buat Peringatan Baru --}}
<div x-data="{ open: @json($errors->any()) }"
     @open-alert-modal.window="open = true"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">

    <div @click.away="open = false" class="bg-white rounded-[32px] shadow-2xl max-w-xl w-full p-8 relative border border-slate-200 max-h-[90vh] overflow-y-auto custom-scrollbar">

        <button @click="open = false" class="absolute top-6 right-6 p-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 hover:text-slate-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center border border-red-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Buat Peringatan Baru</h2>
                <p class="text-sm text-slate-500 font-medium">Laporkan anomali yang ditemukan di lapangan.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('ews.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 text-xs font-bold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kategori *</label>
                    <select name="kategori" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="pangan" @selected(old('kategori', 'pangan') === 'pangan')>Pangan</option>
                        <option value="lpg" @selected(old('kategori') === 'lpg')>LPG</option>
                        <option value="bbm" @selected(old('kategori') === 'bbm')>BBM</option>
                        <option value="multikomoditas" @selected(old('kategori') === 'multikomoditas')>Multikomoditas</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status *</label>
                    <select name="status_ketersediaan" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="aman" @selected(old('status_ketersediaan') === 'aman')>Aman</option>
                        <option value="waspada" @selected(old('status_ketersediaan', 'waspada') === 'waspada')>Waspada</option>
                        <option value="kritis" @selected(old('status_ketersediaan') === 'kritis')>Kritis</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kecamatan Wilayah *</label>
                <select name="kecamatan_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                    <option value="">Pilih Kecamatan...</option>
                    @foreach($kecamatanOptions as $kec)
                        <option value="{{ $kec->id }}" @selected((string) old('kecamatan_id') === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Judul Peringatan *</label>
                <input type="text" name="judul_alert" value="{{ old('judul_alert') }}" required placeholder="Contoh: Kelangkaan LPG 3KG di Wilayah X" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Analisis & Keterangan Lapangan *</label>
                <textarea name="analisis_sistem" rows="4" required placeholder="Jelaskan detail anomali, perkiraan penyebab, dan rekomendasi tindakan awal..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-sm font-medium placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition resize-none">{{ old('analisis_sistem') }}</textarea>
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Lampiran Foto Bukti (Maks. 3)</label>
                <div class="relative">
                    <input type="file" name="fotos[]" accept="image/*" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 border border-slate-200 rounded-2xl p-2 bg-slate-50 shadow-sm transition cursor-pointer">
                </div>
                <p class="text-[10px] text-slate-400 font-medium">Format: JPG, PNG, WEBP. Ukuran maks 5MB per file.</p>
            </div>
        
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-black py-4 px-6 rounded-[20px] flex items-center justify-center gap-3 text-xs transition-all shadow-lg shadow-red-500/30 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    SIMPAN PERINGATAN
                </button>
                <button type="button" @click="open = false" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-[20px] text-xs transition-all active:scale-[0.98]">
                    BATAL
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
