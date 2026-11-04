@extends('layouts.app')

@section('title', 'Master Data - SIGAP+')
@section('page-title', 'Manajemen Master Data')

@section('content')
<div class="space-y-6">
    {{-- Tab Navigation --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 border-b border-slate-100 px-6 overflow-x-auto">
            <button 
                onclick="switchMasterTab('komoditas')" 
                id="btn-komoditas"
                class="master-tab-btn whitespace-nowrap px-6 py-4 text-sm border-b-2 transition-all text-blue-600 font-bold border-blue-600">
                DATA KOMODITAS
            </button>
            <button 
                onclick="switchMasterTab('spbu')" 
                id="btn-spbu"
                class="master-tab-btn whitespace-nowrap px-6 py-4 text-sm border-b-2 transition-all text-slate-400 font-semibold border-transparent hover:text-slate-600">
                DATA LOKASI SPBU
            </button>
        </div>

        <div class="p-6">
            {{-- Tab Komoditas --}}
            <div id="content-komoditas" class="master-tab-content space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Daftar Komoditas</h3>
                        <p class="text-sm text-slate-500">Kelola daftar komoditas pangan strategis yang dipantau.</p>
                    </div>
                    <button 
                        onclick="openKomoditasModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Komoditas
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="py-4 px-6">Nama Komoditas</th>
                                <th class="py-4 px-6">Satuan</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm font-semibold">
                            @foreach($komoditas as $k)
                            <tr class="hover:bg-slate-50/30 transition">
                                <td class="py-4 px-6">
                                    <span class="text-slate-900 font-bold">{{ $k->nama_komoditas }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-slate-600">{{ $k->satuan }}</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            onclick="openKomoditasModal({{ json_encode($k) }})"
                                            class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                            title="Edit Komoditas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                        </button>
                                        <button 
                                            type="button"
                                            onclick="openDeleteModal('{{ route('master-data.komoditas.destroy', $k) }}', 'komoditas {{ addslashes($k->nama_komoditas) }}')"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                            title="Hapus Komoditas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab SPBU --}}
            <div id="content-spbu" class="master-tab-content hidden space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Daftar SPBU</h3>
                        <p class="text-sm text-slate-500">Kelola daftar lokasi SPBU dan ketersediaan layanannya.</p>
                    </div>
                    <button 
                        onclick="openSpbuModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah SPBU
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <th class="py-4 px-6">Nama SPBU</th>
                                <th class="py-4 px-6">Kecamatan</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm font-semibold">
                            @foreach($spbu as $s)
                            <tr class="hover:bg-slate-50/30 transition">
                                <td class="py-4 px-6">
                                    <span class="text-slate-900 font-bold">{{ $s->nama_spbu }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-slate-900 font-bold">{{ $s->kecamatan?->nama_kecamatan }}</span>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-tight">{{ $s->desa?->nama_desa ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            onclick="openSpbuModal({{ json_encode($s) }})"
                                            class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                            title="Edit Data SPBU">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                        </button>
                                        <button 
                                            type="button"
                                            onclick="openDeleteModal('{{ route('master-data.spbu.destroy', $s) }}', 'SPBU {{ addslashes($s->nama_spbu) }}')"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                            title="Hapus Data SPBU">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Komoditas Modal --}}
    <div id="komoditasModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[32px] w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 id="komoditasModalTitle" class="text-xl font-bold text-slate-900">Tambah Komoditas</h3>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi informasi detail komoditas.</p>
                </div>
                <button onclick="closeKomoditasModal()" class="p-2 hover:bg-white rounded-full transition text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="komoditasForm" action="{{ route('master-data.komoditas.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div id="komoditasMethod"></div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Komoditas</label>
                        <input type="text" name="nama_komoditas" id="k_nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 transition shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                        <select name="kategori" id="k_kategori" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 transition shadow-sm">
                            <option value="pangan">Pangan Strategis</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Satuan</label>
                        <input type="text" name="satuan" id="k_satuan" required placeholder="Kg, Liter, Butir, Ikat" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 transition shadow-sm">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeKomoditasModal()" class="flex-1 px-6 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold transition">Batal</button>
                    <button type="submit" class="flex-2 px-10 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-lg shadow-blue-600/20">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SPBU Modal --}}
    <div id="spbuModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[32px] w-full max-w-2xl overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 id="spbuModalTitle" class="text-xl font-bold text-slate-900">Tambah Data SPBU</h3>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi informasi lokasi dan layanan SPBU.</p>
                </div>
                <button onclick="closeSpbuModal()" class="p-2 hover:bg-white rounded-full transition text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="spbuForm" action="{{ route('master-data.spbu.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div id="spbuMethod"></div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nama SPBU</label>
                        <input type="text" name="nama_spbu" id="s_nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 transition shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Kecamatan</label>
                        <select name="kecamatan_id" id="s_kecamatan" required onchange="filterDesaByKecamatan(this.value)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 transition shadow-sm">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Desa (Opsional)</label>
                        <select name="desa_id" id="s_desa" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500 transition shadow-sm">
                            <option value="">Pilih Desa</option>
                            @foreach($desa as $d)
                                <option value="{{ $d->id }}" data-kecamatan="{{ $d->kecamatan_id }}">{{ $d->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeSpbuModal()" class="flex-1 px-6 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold transition">Batal</button>
                    <button type="submit" class="flex-2 px-10 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-lg shadow-blue-600/20">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[28px] w-full max-w-md overflow-hidden shadow-2xl p-6 sm:p-7 space-y-5 animate-in fade-in duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Konfirmasi Hapus</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-sm text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus <span id="deleteItemName" class="font-bold text-slate-900"></span>?
            </p>

            <form id="deleteModalForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-5 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold transition shadow-lg shadow-rose-600/20">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchMasterTab(tabName) {
        document.querySelectorAll('.master-tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.querySelectorAll('.master-tab-btn').forEach(btn => {
            btn.classList.remove('text-blue-600', 'font-bold', 'border-blue-600');
            btn.classList.add('text-slate-400', 'font-semibold', 'border-transparent');
        });
        document.getElementById(`content-${tabName}`).classList.remove('hidden');
        const activeBtn = document.getElementById(`btn-${tabName}`);
        activeBtn.classList.add('text-blue-600', 'font-bold', 'border-blue-600');
        activeBtn.classList.remove('text-slate-400', 'font-semibold', 'border-transparent');
    }

    // Modal Hapus (Delete Confirmation)
    function openDeleteModal(actionUrl, itemName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteModalForm');
        const nameSpan = document.getElementById('deleteItemName');

        form.action = actionUrl;
        nameSpan.textContent = itemName;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Modal Komoditas
    function openKomoditasModal(data = null) {
        const modal = document.getElementById('komoditasModal');
        const form = document.getElementById('komoditasForm');
        const title = document.getElementById('komoditasModalTitle');
        const methodDiv = document.getElementById('komoditasMethod');

        if (data) {
            title.innerText = 'Edit Komoditas';
            form.action = `{{ url('master-data/komoditas') }}/${data.id}`;
            methodDiv.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('k_nama').value = data.nama_komoditas;
            document.getElementById('k_kategori').value = data.kategori || 'pangan';
            document.getElementById('k_satuan').value = data.satuan;
        } else {
            title.innerText = 'Tambah Komoditas';
            form.action = "{{ route('master-data.komoditas.store') }}";
            methodDiv.innerHTML = '';
            form.reset();
            document.getElementById('k_kategori').value = 'pangan';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeKomoditasModal() {
        const modal = document.getElementById('komoditasModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Modal SPBU
    function openSpbuModal(data = null) {
        const modal = document.getElementById('spbuModal');
        const form = document.getElementById('spbuForm');
        const title = document.getElementById('spbuModalTitle');
        const methodDiv = document.getElementById('spbuMethod');

        if (data) {
            title.innerText = 'Edit Data SPBU';
            form.action = `{{ url('master-data/spbu') }}/${data.id}`;
            methodDiv.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('s_kecamatan').value = data.kecamatan_id;
            filterDesaByKecamatan(data.kecamatan_id);
            document.getElementById('s_desa').value = data.desa_id;
            document.getElementById('s_nama').value = data.nama_spbu;
        } else {
            title.innerText = 'Tambah Data SPBU';
            form.action = "{{ route('master-data.spbu.store') }}";
            methodDiv.innerHTML = '';
            form.reset();
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSpbuModal() {
        const modal = document.getElementById('spbuModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function filterDesaByKecamatan(kecamatanId) {
        const desaSelect = document.getElementById('s_desa');
        const options = desaSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === "") {
                option.style.display = 'block';
            } else if (option.getAttribute('data-kecamatan') == kecamatanId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) switchMasterTab(tab);

        // Tutup modal jika klik backdrop di luar area box
        ['deleteModal', 'komoditasModal', 'spbuModal'].forEach(id => {
            const modalEl = document.getElementById(id);
            if (modalEl) {
                modalEl.addEventListener('click', (e) => {
                    if (e.target === modalEl) {
                        modalEl.classList.add('hidden');
                        modalEl.classList.remove('flex');
                    }
                });
            }
        });

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeKomoditasModal();
                closeSpbuModal();
            }
        });
    });
</script>
@endpush
