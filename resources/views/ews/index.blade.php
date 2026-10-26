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
    <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-bold uppercase tracking-wider text-red-600">Notifikasi Deteksi Kritis</p>
        <span class="text-[10px] font-semibold bg-red-100 text-red-700 px-2.5 py-0.5 rounded-full">
            {{ isset($alerts) ? count($alerts) : 0 }} Peringatan
        </span>
    </div>

    <!-- Container dengan Fungsi Scroll (Maksimal tinggi sekitar 480px, bisa disesuaikan) -->
    <div class="max-h-[300px] overflow-y-auto pr-1 space-y-4 custom-scrollbar">
        @forelse($alerts as $alert)
        <div class="relative bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs overflow-hidden">
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
                    <strong class="font-bold text-slate-800">Analisis :</strong>
                    {{ $alert->analisis_sistem }}
                </div>
                
                <!-- Pemicu (Trigger) Berbentuk Teks Interaktif / Tombol ala Link di Bawah Analisis -->
                @if(isset($alert->fotos) && count($alert->fotos) > 0)
                <div class="mb-4">
                    <button @click="showPhotos = !showPhotos" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-all focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                        <span x-text="showPhotos ? 'Sembunyikan Lampiran Foto' : 'Lihat Lampiran Foto ({{ count($alert->fotos) }})'"></span>
                    </button>
                    <!-- Galeri Foto yang Muncul Ketika Diklik -->
                    <div x-show="showPhotos" x-transition class="flex flex-wrap gap-2.5 mt-2.5 pt-2 border-t border-slate-100">
                        @foreach($alert->fotos as $foto)
                            <a href="{{ asset('storage/' . $foto->path) }}" target="_blank" class="block w-16 h-16 rounded-xl overflow-hidden border border-slate-200 shadow-xs hover:opacity-90 transition">
                                <img src="{{ asset('storage/' . $foto->path) }}" class="w-full h-full object-cover">
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

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

    {{-- Kolom Kanan: Widget --}}
    <div class="space-y-6">

        {{-- Widget Peta Kerawanan --}}


        {{-- Widget Rekomendasi Strategis Otomatis --}}


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
<div x-data="{ 
        open: false,
        files: [],
        handleFileChange(event) {
            const selectedFiles = Array.from(event.target.files);
            const totalFiles = this.files.length + selectedFiles.length;
            
            if (totalFiles > 3) {
                alert('Maksimal hanya dapat mengunggah 3 foto!');
                event.target.value = '';
                return;
            }

            selectedFiles.forEach(file => {
                this.files.push({
                    file: file,
                    url: URL.createObjectURL(file)
                });
            });
            
            this.updateInputFiles();
            event.target.value = '';
        },
        removeFile(index) {
            this.files.splice(index, 1);
            this.updateInputFiles();
        },
        updateInputFiles() {
            const dt = new DataTransfer();
            this.files.forEach(item => dt.items.add(item.file));
            document.getElementById('fotos-input').files = dt.files;
        }
     }"
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

    <div @click.away="open = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 relative border border-slate-100 max-h-[90vh] overflow-y-auto">

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

        <form method="POST" action="{{ route('ews.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
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

            <!-- Input Foto & Container Preview dengan Tombol Hapus -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lampiran Foto (Maksimal 3 Foto)</label>
                <input type="file" id="fotos-input" name="fotos[]" accept="image/*" multiple @change="handleFileChange($event)" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl p-1.5 cursor-pointer shadow-sm transition">
                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, JPEG</p>

                <!-- Daftar Preview Foto yang Dipilih beserta Tombol Hapus -->
                <div class="flex flex-wrap gap-3 mt-3" x-show="files.length > 0">
                    <template x-for="(item, index) in files" :key="index">
                        <div class="relative w-20 h-20 rounded-xl overflow-hidden border-2 border-slate-200 shadow-md bg-slate-100">
                            <!-- Gambar Preview -->
                            <img :src="item.url" class="w-full h-full object-cover">
                            
                            <!-- Tombol Hapus (X) -->
                            <button type="button" @click="removeFile(index)" class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full p-1.5 shadow-lg flex items-center justify-center transition transform hover:scale-110" title="Hapus foto">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
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
