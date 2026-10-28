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
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ in_array($alert->tingkat_risiko, ['tinggi', 'kritis'], true) ? 'bg-red-600' : ($alert->tingkat_risiko == 'sedang' ? 'bg-amber-500' : 'bg-emerald-500') }}"></div>
            <div class="pl-2">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl {{ in_array($alert->tingkat_risiko, ['tinggi', 'kritis'], true) ? 'bg-red-100/80 text-red-600' : ($alert->tingkat_risiko == 'sedang' ? 'bg-amber-100/80 text-amber-600' : 'bg-emerald-100/80 text-emerald-600') }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 005.814-5.519l2.74-1.22m0 0l-5.94-2.28 2.28 5.941"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $alert->judul_alert }}</h3>
                            <p class="text-xs font-semibold {{ in_array($alert->tingkat_risiko, ['tinggi', 'kritis'], true) ? 'text-red-600' : ($alert->tingkat_risiko == 'sedang' ? 'text-amber-600' : 'text-emerald-600') }} mt-0.5">Wilayah: {{ $alert->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara' }}</p>
                            <p class="text-[11px] text-slate-500 mt-1">{{ ucfirst($alert->kategori) }} · Risiko {{ ucfirst($alert->tingkat_risiko) }}</p>
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
                @if($alert->latitude !== null && $alert->longitude !== null)
                    <p class="text-[11px] text-slate-500 mb-4">Titik lokasi: {{ number_format((float) $alert->latitude, 5, '.', '') }}, {{ number_format((float) $alert->longitude, 5, '.', '') }}</p>
                @endif
                
                @if($alert->foto_laporan_path)
                <div class="mb-4">
                    <p class="text-xs font-semibold text-slate-600 mb-2">Lampiran foto</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-2">
                        @foreach($alert->foto_laporan_path as $index => $fotoPath)
                            <button type="button" x-data @click="$dispatch('open-alert-photo', { src: @js(asset('storage/' . $fotoPath)), title: @js('Lampiran foto ' . ($index + 1) . ' - ' . $alert->judul_alert) })" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                Lihat foto {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex flex-wrap items-center gap-2.5">
                    @if(!$alert->rekomendasi->contains('tipe_rekomendasi', 'operasi_pasar'))
                        <form method="POST" action="{{ route('ews.rekomendasi.store', $alert) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-md shadow-blue-500/20 transition-colors">
                                <span>Rekomendasi Strategis</span>
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-semibold">
                            Rekomendasi sudah diterbitkan
                        </span>
                    @endif
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
     aria-modal="true"
     :aria-label="title">
    <button type="button" @click="open = false" class="absolute inset-0 w-full h-full cursor-default" aria-label="Tutup foto"></button>
    <div class="relative z-10 w-full max-w-5xl max-h-[90vh] bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-5 py-3 border-b border-slate-200">
            <p class="text-sm font-semibold text-slate-800 truncate" x-text="title"></p>
            <button type="button" @click="open = false" class="shrink-0 p-2 text-slate-500 hover:text-slate-900" aria-label="Tutup foto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="flex items-center justify-center bg-slate-100 p-3 min-h-48 max-h-[calc(90vh-4rem)] overflow-auto">
            <img :src="src" :alt="title" x-show="!failed" x-on:error="failed = true" class="max-w-full max-h-[calc(90vh-6rem)] object-contain">
            <p x-show="failed" class="py-16 text-sm text-red-700" role="alert">Foto tidak dapat dimuat. Periksa file lampiran dan storage publik.</p>
        </div>
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

        <form id="ews-alert-form" data-open="{{ $errors->any() ? 'true' : 'false' }}" method="POST" action="{{ route('ews.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori *</label>
                    <select name="kategori" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="pangan" @selected(old('kategori', 'pangan') === 'pangan')>Pangan</option>
                        <option value="lpg" @selected(old('kategori') === 'lpg')>LPG</option>
                        <option value="bbm" @selected(old('kategori') === 'bbm')>BBM</option>
                        <option value="multikomoditas" @selected(old('kategori') === 'multikomoditas')>Multikomoditas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tingkat Risiko *</label>
                    <select name="tingkat_risiko" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                        <option value="rendah" @selected(old('tingkat_risiko') === 'rendah')>Rendah</option>
                        <option value="sedang" @selected(old('tingkat_risiko', 'sedang') === 'sedang')>Sedang</option>
                        <option value="tinggi" @selected(old('tingkat_risiko') === 'tinggi')>Tinggi</option>
                        <option value="kritis" @selected(old('tingkat_risiko') === 'kritis')>Kritis</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kecamatan *</label>
                <select name="kecamatan_id" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500 shadow-sm transition">
                    <option value="">Pilih Kecamatan...</option>
                    @foreach($kecamatanOptions as $kec)
                        <option value="{{ $kec->id }}" @selected((string) old('kecamatan_id') === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Titik Lokasi di Provinsi Gorontalo *</label>
                <div id="ews-location-map" class="h-[260px] w-full rounded-xl border border-slate-200 overflow-hidden"></div>
                <label for="ews-coordinate-input" class="block text-xs font-semibold text-slate-600 mt-3 mb-1.5">Tempel Koordinat Google Maps</label>
                <input type="text" id="ews-coordinate-input" value="{{ old('latitude') && old('longitude') ? old('latitude') . ', ' . old('longitude') : '' }}" placeholder="Contoh: 0.7200000, 123.0500000 atau URL Google Maps" autocomplete="off" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
                <input type="hidden" name="latitude" id="ews-latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="ews-longitude" value="{{ old('longitude') }}">
                <p id="ews-location-message" class="text-[11px] text-slate-500 mt-1.5" aria-live="polite">Klik peta atau tempel koordinat desimal dari Google Maps. URL pendek tanpa koordinat tidak dapat dibaca.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Peringatan *</label>
                <input type="text" name="judul_alert" value="{{ old('judul_alert') }}" required placeholder="Contoh: Lonjakan Harga Cabai Rawit" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Analisis / Keterangan *</label>
                <textarea name="analisis_sistem" rows="4" required placeholder="Jelaskan temuan atau anomali yang terdeteksi di lapangan..." class="w-full bg-white border border-slate-200 rounded-xl p-3.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 shadow-sm transition resize-none">{{ old('analisis_sistem') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lampiran Foto (Maksimal 3 Foto)</label>
                <input type="file" id="fotos-input" name="fotos[]" accept="image/jpeg,image/png,image/webp" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl p-1.5 cursor-pointer shadow-sm transition">
                <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, atau WEBP, maksimal 3 foto, 5 MB per file.</p>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('ews-alert-form');
        const mapElement = document.getElementById('ews-location-map');
        const coordinateInput = document.getElementById('ews-coordinate-input');
        const latitudeInput = document.getElementById('ews-latitude');
        const longitudeInput = document.getElementById('ews-longitude');
        const locationMessage = document.getElementById('ews-location-message');
        let map;
        let marker;
        let boundary;

        const pointInRing = (longitude, latitude, ring) => {
            let isInside = false;
            for (let index = 0, previous = ring.length - 1; index < ring.length; previous = index++) {
                const [currentLongitude, currentLatitude] = ring[index];
                const [previousLongitude, previousLatitude] = ring[previous];
                const crossesLatitude = (currentLatitude > latitude) !== (previousLatitude > latitude);
                const crossingLongitude = (previousLongitude - currentLongitude)
                    * (latitude - currentLatitude)
                    / ((previousLatitude - currentLatitude) || 0.0000000001)
                    + currentLongitude;

                if (crossesLatitude && longitude < crossingLongitude) isInside = !isInside;
            }

            return isInside;
        };

        const isInsideBoundary = (longitude, latitude, geometry) => geometry.coordinates.some((polygon) => {
            if (!pointInRing(longitude, latitude, polygon[0])) return false;
            return !polygon.slice(1).some((hole) => pointInRing(longitude, latitude, hole));
        });

        const parseCoordinates = (value) => {
            const latitudeFirst = value.match(/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/i)
                || value.match(/(?:@|[?&]q=)?\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/i);
            const longitudeFirst = value.match(/!2d(-?\d+(?:\.\d+)?)!3d(-?\d+(?:\.\d+)?)/i);

            if (latitudeFirst) return [Number(latitudeFirst[1]), Number(latitudeFirst[2])];
            if (longitudeFirst) return [Number(longitudeFirst[2]), Number(longitudeFirst[1])];

            return null;
        };

        const setSelectedLocation = (latitude, longitude, boundary) => {
            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)
                || latitude < -90 || latitude > 90 || longitude < -180 || longitude > 180
                || !isInsideBoundary(longitude, latitude, boundary.geometry)) {
                latitudeInput.value = '';
                longitudeInput.value = '';
                if (marker) map.removeLayer(marker);
                locationMessage.textContent = 'Koordinat tidak valid atau berada di luar Provinsi Gorontalo.';
                locationMessage.classList.add('text-red-600');
                return false;
            }

            latitudeInput.value = latitude.toFixed(7);
            longitudeInput.value = longitude.toFixed(7);
            coordinateInput.value = `${latitude.toFixed(7)}, ${longitude.toFixed(7)}`;
            locationMessage.textContent = `Lokasi dipilih: ${latitude.toFixed(5)}, ${longitude.toFixed(5)}`;
            locationMessage.classList.remove('text-red-600');
            if (marker) map.removeLayer(marker);
            marker = L.marker([latitude, longitude]).addTo(map);
            map.setView([latitude, longitude], Math.max(map.getZoom(), 12));

            return true;
        };

        const initializeMap = async () => {
            if (!map) {
                const response = await fetch('{{ asset('data/gorontalo-boundary.geojson') }}');
                boundary = await response.json();
                map = L.map(mapElement).setView([0.72, 123.05], 9);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 18
                }).addTo(map);
                const boundaryLayer = L.geoJSON(boundary, {
                    style: { color: '#2563eb', weight: 2, fillColor: '#0ea5e9', fillOpacity: 0.08 }
                }).addTo(map);
                map.fitBounds(boundaryLayer.getBounds());
                map.setMaxBounds(boundaryLayer.getBounds().pad(0.15));
                map.setMaxBoundsViscosity(1);

                map.on('click', (event) => {
                    const { lat, lng } = event.latlng;
                    setSelectedLocation(lat, lng, boundary);
                });

                if (latitudeInput.value && longitudeInput.value
                    && isInsideBoundary(Number(longitudeInput.value), Number(latitudeInput.value), boundary.geometry)) {
                    setSelectedLocation(Number(latitudeInput.value), Number(longitudeInput.value), boundary);
                }
            }

            window.setTimeout(() => map.invalidateSize(), 100);
        };

        window.addEventListener('open-alert-modal', initializeMap);
        if (form.dataset.open === 'true') initializeMap();

        coordinateInput.addEventListener('input', async () => {
            const coordinates = parseCoordinates(coordinateInput.value.trim());
            if (!coordinates) {
                latitudeInput.value = '';
                longitudeInput.value = '';
                if (marker && map) map.removeLayer(marker);
                locationMessage.textContent = 'Tempel pasangan latitude, longitude atau URL Google Maps yang memuat koordinat.';
                locationMessage.classList.remove('text-red-600');
                return;
            }

            if (!map) await initializeMap();
            setSelectedLocation(coordinates[0], coordinates[1], boundary);
        });

        form.addEventListener('submit', (event) => {
            if (!latitudeInput.value || !longitudeInput.value) {
                event.preventDefault();
                locationMessage.textContent = 'Pilih titik lokasi di peta sebelum menyimpan peringatan.';
                locationMessage.classList.add('text-red-600');
            }
        });
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
