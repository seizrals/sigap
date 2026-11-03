@extends('layouts.app')

@section('title', 'Input Data BBM')
@section('page-title', 'Input Data Distribusi BBM')

@section('content')
<div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-4 sm:p-6 text-white flex items-center gap-3 sm:gap-4">
        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-base sm:text-lg">Form Input Ketersediaan BBM</h3>
            <p class="text-xs sm:text-sm opacity-90 mt-0.5">Pantau stok SPBU dan distribusi BBM untuk Nelayan & Pertanian.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mx-4 sm:mx-6 mt-4 sm:mt-6 p-3.5 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mx-4 sm:mx-6 mt-4 sm:mt-6 p-3.5 sm:p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('input.bbm.store') }}" class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm max-w-8xl mx-auto space-y-4 sm:space-y-5 text-slate-700">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Input</label>
                <input type="date" name="tanggal_input" value="{{ old('tanggal_input', date('Y-m-d')) }}" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kecamatan</label>
                <select id="bbm-kecamatan" name="kecamatan_id" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    <option value="">Pilih Kecamatan...</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama SPBU / Penyalur</label>
                <select id="bbm-spbu" name="spbu_id" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    <option value="">Pilih SPBU...</option>
                    @foreach($spbu as $s)
                        <option value="{{ $s->id }}" data-kecamatan-id="{{ $s->kecamatan_id }}" {{ old('spbu_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_spbu }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis BBM Subsidi</label>
                <select name="jenis_bbm" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    <option value="Pertalite (JBKP)" {{ old('jenis_bbm', 'Pertalite (JBKP)') == 'Pertalite (JBKP)' ? 'selected' : '' }}>Pertalite (JBKP)</option>
                    <option value="Bio Solar" {{ old('jenis_bbm') == 'Bio Solar' ? 'selected' : '' }}>Bio Solar (JBT)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kuota Tersedia (Kiloliter)</label>
                <input type="number" step="0.001" name="stok_tersedia_kl" value="{{ old('stok_tersedia_kl') }}" placeholder="Jumlah stok" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga Jual Eceran per Liter</label>
                <input type="number" name="harga_jual_per_liter" value="{{ old('harga_jual_per_liter') }}" placeholder="Cth: 10000" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Antrean</label>
                <select name="status_antrean" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    <option value="tidak_ada" {{ old('status_antrean', 'tidak_ada') == 'tidak_ada' ? 'selected' : '' }}>Tidak Ada Antrean</option>
                    <option value="pendek" {{ old('status_antrean') == 'pendek' ? 'selected' : '' }}>Antrean Pendek</option>
                    <option value="panjang" {{ old('status_antrean') == 'panjang' ? 'selected' : '' }}>Antrean Panjang</option>
                    <option value="sangat_panjang" {{ old('status_antrean') == 'sangat_panjang' ? 'selected' : '' }}>Antrean Sangat Panjang</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Ketersediaan</label>
                <select name="status_ketersediaan" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    <option value="aman" {{ old('status_ketersediaan', 'aman') == 'aman' ? 'selected' : '' }}>Aman</option>
                    <option value="waspada" {{ old('status_ketersediaan') == 'waspada' ? 'selected' : '' }}>Waspada</option>
                    <option value="menipis" {{ old('status_ketersediaan') == 'menipis' ? 'selected' : '' }}>Menipis</option>
                    <option value="kosong" {{ old('status_ketersediaan') == 'kosong' ? 'selected' : '' }}>Kritis</option>
                </select>
            </div>
        </div>

        <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700">Distribusi Khusus (Penyaluran Surat Rekomendasi)</label>
                <p class="text-[11px] text-slate-500 mt-0.5">Centang sektor untuk memunculkan kolom input Distribusi Masuk dan Distribusi Keluar.</p>
            </div>

            {{-- Opsi Distribusi Nelayan --}}
            <div class="border border-slate-200 bg-white rounded-xl p-4 space-y-3">
                <label class="flex items-center gap-2.5 text-xs text-slate-800 font-bold cursor-pointer select-none">
                    <input type="checkbox" id="check-distribusi-nelayan" name="distribusi_nelayan" value="1" {{ old('distribusi_nelayan') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Terdapat Distribusi untuk Nelayan</span>
                </label>
                <div id="nelayan-fields" class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 {{ old('distribusi_nelayan') ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Distribusi Masuk Nelayan (KL)</label>
                        <input type="number" step="0.001" min="0" id="input-masuk-nelayan" name="distribusi_masuk_nelayan_kl" value="{{ old('distribusi_masuk_nelayan_kl', old('kuota_nelayan_harian_kl')) }}" placeholder="Jumlah masuk (KL)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Distribusi Keluar Nelayan (KL)</label>
                        <input type="number" step="0.001" min="0" id="input-keluar-nelayan" name="distribusi_keluar_nelayan_kl" value="{{ old('distribusi_keluar_nelayan_kl', old('realisasi_nelayan_kl')) }}" placeholder="Jumlah keluar / penyaluran (KL)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    </div>
                </div>
            </div>

            {{-- Opsi Distribusi Pertanian --}}
            <div class="border border-slate-200 bg-white rounded-xl p-4 space-y-3">
                <label class="flex items-center gap-2.5 text-xs text-slate-800 font-bold cursor-pointer select-none">
                    <input type="checkbox" id="check-distribusi-pertanian" name="distribusi_pertanian" value="1" {{ old('distribusi_pertanian') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>Terdapat Distribusi untuk Pertanian</span>
                </label>
                <div id="pertanian-fields" class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 {{ old('distribusi_pertanian') ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Distribusi Masuk Pertanian (KL)</label>
                        <input type="number" step="0.001" min="0" id="input-masuk-pertanian" name="distribusi_masuk_pertanian_kl" value="{{ old('distribusi_masuk_pertanian_kl', old('kuota_pertanian_harian_kl')) }}" placeholder="Jumlah masuk (KL)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Distribusi Keluar Pertanian (KL)</label>
                        <input type="number" step="0.001" min="0" id="input-keluar-pertanian" name="distribusi_keluar_pertanian_kl" value="{{ old('distribusi_keluar_pertanian_kl', old('realisasi_pertanian_kl')) }}" placeholder="Jumlah keluar / penyaluran (KL)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-600 shadow-sm transition">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-600/20 flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                Kirim Laporan BBM
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const districtSelect = document.getElementById('bbm-kecamatan');
        const spbuSelect = document.getElementById('bbm-spbu');

        const filterSpbu = () => {
            const districtId = districtSelect.value;
            [...spbuSelect.options].forEach((option, index) => {
                if (index === 0) return;
                option.hidden = districtId !== '' && option.dataset.kecamatanId !== districtId;
                option.disabled = option.hidden;
            });
            if (spbuSelect.selectedOptions[0]?.disabled) spbuSelect.value = '';
        };

        districtSelect.addEventListener('change', filterSpbu);
        filterSpbu();

        // Toggle Kolom Distribusi Masuk & Keluar Nelayan
        const checkNelayan = document.getElementById('check-distribusi-nelayan');
        const nelayanFields = document.getElementById('nelayan-fields');
        const toggleNelayan = () => {
            if (checkNelayan.checked) {
                nelayanFields.classList.remove('hidden');
            } else {
                nelayanFields.classList.add('hidden');
                document.getElementById('input-masuk-nelayan').value = '';
                document.getElementById('input-keluar-nelayan').value = '';
            }
        };
        checkNelayan.addEventListener('change', toggleNelayan);

        // Toggle Kolom Distribusi Masuk & Keluar Pertanian
        const checkPertanian = document.getElementById('check-distribusi-pertanian');
        const pertanianFields = document.getElementById('pertanian-fields');
        const togglePertanian = () => {
            if (checkPertanian.checked) {
                pertanianFields.classList.remove('hidden');
            } else {
                pertanianFields.classList.add('hidden');
                document.getElementById('input-masuk-pertanian').value = '';
                document.getElementById('input-keluar-pertanian').value = '';
            }
        };
        checkPertanian.addEventListener('change', togglePertanian);
    });
</script>
@endpush
