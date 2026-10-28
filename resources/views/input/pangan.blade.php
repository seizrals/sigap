@extends('layouts.app')

@section('title', 'Pangan Strategis')
@section('page-title', 'Pangan Strategis')

@section('content')
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-amber-400 to-amber-500 p-6 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold">Data Pangan Strategis</h2>
            <p class="text-sm opacity-90 mt-1">Harga dan ketersediaan pangan per kecamatan.</p>
        </div>
        <button type="button" id="open-pangan-modal" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white text-amber-700 font-bold text-sm shadow-sm hover:bg-amber-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Permintaan
        </button>
    </div>

    @if(session('success'))
        <div class="mx-5 mt-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium" role="status">{{ session('success') }}</div>
    @endif

    <div class="p-5">
        <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table class="min-w-[1250px] w-full border-collapse text-left text-xs">
                <thead class="bg-amber-50 text-slate-600">
                    <tr class="border-b border-amber-200">
                        <th class="px-3 py-3 font-bold">Periode Sebelumnya</th>
                        <th class="px-3 py-3 font-bold">Periode Saat Ini</th>
                        <th class="px-3 py-3 font-bold">Kecamatan</th>
                        <th class="px-3 py-3 font-bold">Komoditas</th>
                        <th class="px-3 py-3 font-bold text-right">Harga Hari Ini</th>
                        <th class="px-3 py-3 font-bold text-right">Harga Sebelumnya</th>
                        <th class="px-3 py-3 font-bold text-right">Perubahan</th>
                        <th class="px-3 py-3 font-bold">Ketersediaan</th>
                        <th class="px-3 py-3 font-bold text-right">Stok</th>
                        <th class="px-3 py-3 font-bold">Distribusi</th>
                        <th class="px-3 py-3 font-bold">Verifikasi</th>
                        <th class="px-3 py-3 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($dataPangan as $record)
                        <tr class="odd:bg-white even:bg-slate-50/60 hover:bg-amber-50/70 transition-colors">
                            <td class="px-3 py-3 whitespace-nowrap">{{ $record->tanggal_input_sebelumnya?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $record->tanggal_input?->format('d/m/Y') }}</td>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $record->kecamatan?->nama_kecamatan ?? '-' }}</td>
                            <td class="px-3 py-3 font-semibold whitespace-nowrap">{{ $record->komoditas?->nama_komoditas ?? '-' }}</td>
                            <td class="px-3 py-3 text-right whitespace-nowrap">Rp {{ number_format((float) $record->harga_hari_ini, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right whitespace-nowrap">Rp {{ number_format((float) $record->harga_hari_sebelumnya, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right whitespace-nowrap {{ $record->perubahan_harga_persen > 0 ? 'text-rose-600' : ($record->perubahan_harga_persen < 0 ? 'text-emerald-600' : 'text-slate-500') }}">{{ $record->perubahan_harga_persen > 0 ? '+' : '' }}{{ number_format((float) $record->perubahan_harga_persen, 2, ',', '.') }}%</td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 rounded-md font-semibold {{ $record->status_ketersediaan === 'aman' ? 'bg-emerald-100 text-emerald-700' : ($record->status_ketersediaan === 'waspada' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">{{ $record->status_ketersediaan === 'kosong' ? 'Kritis' : ucfirst($record->status_ketersediaan) }}</span>
                            </td>
                            <td class="px-3 py-3 text-right whitespace-nowrap">{{ $record->stok_tersedia !== null ? number_format((float) $record->stok_tersedia, 0, ',', '.') : '-' }} {{ $record->satuan_stok }}</td>
                            <td class="px-3 py-3 max-w-48 truncate" title="{{ $record->sumber_distribusi_masuk }}">{{ $record->sumber_distribusi_masuk ?: '-' }}</td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 rounded-md font-semibold {{ $record->status_verifikasi === 'disetujui' ? 'bg-emerald-100 text-emerald-700' : ($record->status_verifikasi === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($record->status_verifikasi) }}</span>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" data-edit-pangan
                                    data-record-id="{{ $record->id }}" data-periode-sebelumnya="{{ $record->tanggal_input_sebelumnya?->format('Y-m-d') }}"
                                    data-periode-saat-ini="{{ $record->tanggal_input?->format('Y-m-d') }}"
                                    data-kecamatan="{{ $record->kecamatan_id }}" data-komoditas="{{ $record->komoditas_id }}"
                                    data-harga="{{ $record->harga_hari_ini }}" data-harga-sebelumnya="{{ $record->harga_hari_sebelumnya }}"
                                    data-status="{{ $record->status_ketersediaan }}" data-stok="{{ $record->stok_tersedia }}"
                                    data-satuan="{{ $record->satuan_stok }}" data-distribusi="{{ $record->sumber_distribusi_masuk }}"
                                    aria-label="Edit data {{ $record->komoditas?->nama_komoditas ?? 'pangan' }}" title="Edit data"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-700 hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.67a4.5 4.5 0 01-1.897 1.13L6 18.5l.7-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487zM19.5 7.5l-3-3M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="px-4 py-12 text-center text-sm text-slate-400">Belum ada data pangan strategis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dataPangan->hasPages())
            <div class="mt-4">{{ $dataPangan->links() }}</div>
        @endif
    </div>
</div>

<div id="pangan-modal" data-open="{{ $errors->any() ? 'true' : 'false' }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 backdrop-blur-sm p-4" role="dialog" aria-modal="true" aria-labelledby="pangan-modal-title">
    <div class="w-full max-w-3xl max-h-[92vh] overflow-y-auto bg-white rounded-2xl shadow-2xl border border-slate-200">
        <div class="bg-gradient-to-r from-amber-400 to-amber-500 px-6 py-5 text-white flex items-start justify-between gap-4">
            <div><h2 id="pangan-modal-title" class="text-lg font-bold">Tambah Permintaan</h2><p class="text-sm opacity-90 mt-1">Input harga dan ketersediaan pangan strategis.</p></div>
            <button type="button" data-close-pangan-modal class="p-1 text-white/90 hover:text-white" aria-label="Tutup formulir">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="pangan-form" method="POST" action="{{ route('input.pangan.store') }}" data-update-url="{{ route('input.pangan.update', ['dataPangan' => '__ID__']) }}" class="p-6 space-y-4">
            @csrf
            @if(old('_method') === 'PUT') <input type="hidden" name="_method" value="PUT"> @endif
            <input type="hidden" name="record_id" id="pangan-record-id" value="{{ old('record_id') }}">
            <input type="hidden" name="record_commodity_id" id="pangan-record-commodity-id" value="{{ old('record_commodity_id') }}">
            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm" role="alert">
                    <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <p class="text-xs text-slate-500">Isi satu atau beberapa baris komoditas. Baris yang kosong akan dilewati.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label for="pangan-periode-sebelumnya" class="block text-xs font-semibold text-slate-600">
                    Periode Sebelumnya *
                    <input id="pangan-periode-sebelumnya" type="date" name="periode_sebelumnya" value="{{ old('periode_sebelumnya', now()->subDay()->toDateString()) }}" required class="mt-1.5 w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-normal text-slate-800 outline-none focus:border-amber-500 shadow-sm transition">
                </label>
                <label for="pangan-periode-saat-ini" class="block text-xs font-semibold text-slate-600">
                    Periode Saat Ini *
                    <input id="pangan-periode-saat-ini" type="date" name="periode_saat_ini" value="{{ old('periode_saat_ini', now()->toDateString()) }}" required class="mt-1.5 w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-normal text-slate-800 outline-none focus:border-amber-500 shadow-sm transition">
                </label>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="min-w-[1400px] w-full border-collapse text-left text-xs">
                    <thead class="bg-amber-50 text-slate-600">
                        <tr class="border-b border-amber-200">
                            <th class="px-3 py-3 font-bold">Nama Komoditas</th>
                            <th class="px-3 py-3 font-bold">Harga Sebelumnya</th>
                            <th class="px-3 py-3 font-bold">Harga Hari Ini</th>
                            <th class="px-3 py-3 font-bold">Stok Lokal Tersedia</th>
                            <th class="px-3 py-3 font-bold">Distribusi Dari Luar</th>
                            <th class="px-3 py-3 font-bold">Status Ketersediaan</th>
                            <th class="px-3 py-3 font-bold">Kecamatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($komoditas as $item)
                            <tr data-entry-row data-komoditas-id="{{ $item->id }}" class="odd:bg-white even:bg-slate-50/60">
                                <td class="px-3 py-2.5 font-semibold text-slate-800 whitespace-nowrap">
                                    {{ $item->nama_komoditas }} <span class="text-slate-400 font-normal">({{ $item->satuan }})</span>
                                    <input type="hidden" name="entries[{{ $item->id }}][komoditas_id]" value="{{ $item->id }}">
                                </td>
                                <td class="px-2 py-2"><input data-entry-control data-entry-value type="number" min="0" step="0.01" name="entries[{{ $item->id }}][harga_hari_sebelumnya]" value="{{ old('entries.'.$item->id.'.harga_hari_sebelumnya') }}" placeholder="Rp" class="w-32 bg-white border border-slate-200 rounded-lg px-2.5 py-2 text-xs outline-none focus:border-amber-500"></td>
                                <td class="px-2 py-2"><input data-entry-control data-entry-value type="number" min="0" step="0.01" name="entries[{{ $item->id }}][harga_hari_ini]" value="{{ old('entries.'.$item->id.'.harga_hari_ini') }}" placeholder="Rp" class="w-32 bg-white border border-slate-200 rounded-lg px-2.5 py-2 text-xs outline-none focus:border-amber-500"></td>
                                <td class="px-2 py-2">
                                    <div class="flex">
                                        <input data-entry-control data-entry-value type="number" min="0" step="0.01" name="entries[{{ $item->id }}][stok_tersedia]" value="{{ old('entries.'.$item->id.'.stok_tersedia') }}" placeholder="Jumlah" class="w-24 bg-white border border-slate-200 rounded-l-lg px-2.5 py-2 text-xs outline-none focus:border-amber-500">
                                        <input data-entry-control type="text" name="entries[{{ $item->id }}][satuan_stok]" value="{{ old('entries.'.$item->id.'.satuan_stok', $item->satuan) }}" maxlength="30" aria-label="Satuan stok {{ $item->nama_komoditas }}" class="w-16 bg-slate-50 border border-l-0 border-slate-200 rounded-r-lg px-2 py-2 text-xs text-center outline-none focus:border-amber-500">
                                    </div>
                                </td>
                                <td class="px-2 py-2"><input data-entry-control data-entry-value type="text" name="entries[{{ $item->id }}][sumber_distribusi_masuk]" value="{{ old('entries.'.$item->id.'.sumber_distribusi_masuk') }}" maxlength="255" placeholder="Asal distribusi" class="w-40 bg-white border border-slate-200 rounded-lg px-2.5 py-2 text-xs outline-none focus:border-amber-500"></td>
                                <td class="px-2 py-2">
                                    <select data-entry-control data-entry-value name="entries[{{ $item->id }}][status_ketersediaan]" class="w-32 bg-white border border-slate-200 rounded-lg px-2 py-2 text-xs outline-none focus:border-amber-500">
                                        <option value="">Pilih status</option>
                                        <option value="aman" @selected(old('entries.'.$item->id.'.status_ketersediaan') === 'aman')>Aman</option>
                                        <option value="waspada" @selected(old('entries.'.$item->id.'.status_ketersediaan') === 'waspada')>Waspada</option>
                                        <option value="kurang" @selected(old('entries.'.$item->id.'.status_ketersediaan') === 'kurang')>Kurang</option>
                                        <option value="kosong" @selected(old('entries.'.$item->id.'.status_ketersediaan') === 'kosong')>Kritis</option>
                                    </select>
                                </td>
                                <td class="px-2 py-2">
                                    <select data-entry-control data-entry-value name="entries[{{ $item->id }}][kecamatan_id]" class="w-36 bg-white border border-slate-200 rounded-lg px-2 py-2 text-xs outline-none focus:border-amber-500">
                                        <option value="">Pilih kecamatan</option>
                                        @foreach($kecamatan as $kec)
                                            <option value="{{ $kec->id }}" @selected((string) old('entries.'.$item->id.'.kecamatan_id') === (string) $kec->id)>{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada komoditas strategis aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-close-pangan-modal class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition">Batal</button>
                <button id="pangan-submit" type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm shadow-md shadow-amber-500/20 transition">Simpan Data Komoditas</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('pangan-modal');
        const form = document.getElementById('pangan-form');
        const recordIdInput = document.getElementById('pangan-record-id');
        const recordCommodityIdInput = document.getElementById('pangan-record-commodity-id');
        const previousPeriodInput = document.getElementById('pangan-periode-sebelumnya');
        const currentPeriodInput = document.getElementById('pangan-periode-saat-ini');
        const modalTitle = document.getElementById('pangan-modal-title');
        const submitButton = document.getElementById('pangan-submit');
        const storeUrl = form.action;
        const rows = [...form.querySelectorAll('[data-entry-row]')];

        const openModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        };
        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        };

        const setEditRow = (commodityId) => {
            rows.forEach((row) => {
                const isSelected = row.dataset.komoditasId === String(commodityId);
                row.hidden = !isSelected;
                row.querySelectorAll('input, select').forEach((control) => {
                    control.disabled = !isSelected;
                });
            });
        };

        document.getElementById('open-pangan-modal').addEventListener('click', () => {
            form.querySelector('input[name="_method"]')?.remove();
            form.reset();
            form.action = storeUrl;
            recordIdInput.value = '';
            recordCommodityIdInput.value = '';
            rows.forEach((row) => {
                row.hidden = false;
                row.querySelectorAll('input, select').forEach((control) => {
                    control.disabled = false;
                    if (control.name.endsWith('[tanggal_input]')) {
                        control.value = '{{ date('Y-m-d') }}';
                    } else if (control.name.endsWith('[satuan_stok]')) {
                        return;
                    } else if (control.type !== 'hidden') {
                        control.value = '';
                    }
                });
            });
            previousPeriodInput.value = '{{ now()->subDay()->toDateString() }}';
            currentPeriodInput.value = '{{ now()->toDateString() }}';
            modalTitle.textContent = 'Tambah Permintaan';
            submitButton.textContent = 'Simpan Data Komoditas';
            openModal();
        });

        document.querySelectorAll('[data-edit-pangan]').forEach((button) => {
            button.addEventListener('click', () => {
                form.action = form.dataset.updateUrl.replace('__ID__', button.dataset.recordId);
                setEditRow(button.dataset.komoditas);
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.append(methodInput);
                }
                methodInput.value = 'PUT';
                recordIdInput.value = button.dataset.recordId;
                recordCommodityIdInput.value = button.dataset.komoditas;
                previousPeriodInput.value = button.dataset.periodeSebelumnya || button.dataset.periodeSaatIni;
                currentPeriodInput.value = button.dataset.periodeSaatIni;
                const row = rows.find((item) => item.dataset.komoditasId === button.dataset.komoditas);
                row.querySelector('[name$="[kecamatan_id]"]').value = button.dataset.kecamatan;
                row.querySelector('[name$="[harga_hari_ini]"]').value = button.dataset.harga;
                row.querySelector('[name$="[harga_hari_sebelumnya]"]').value = button.dataset.hargaSebelumnya;
                row.querySelector('[name$="[status_ketersediaan]"]').value = button.dataset.status;
                row.querySelector('[name$="[stok_tersedia]"]').value = button.dataset.stok;
                row.querySelector('[name$="[satuan_stok]"]').value = button.dataset.satuan || 'Kg';
                row.querySelector('[name$="[sumber_distribusi_masuk]"]').value = button.dataset.distribusi;
                modalTitle.textContent = 'Edit Permintaan Pangan';
                submitButton.textContent = 'Simpan Perubahan';
                openModal();
            });
        });

        document.querySelectorAll('[data-close-pangan-modal]').forEach((button) => button.addEventListener('click', closeModal));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeModal();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });

        if (modal.dataset.open === 'true') {
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput?.value === 'PUT' && recordIdInput.value) {
                form.action = form.dataset.updateUrl.replace('__ID__', recordIdInput.value);
                setEditRow(recordCommodityIdInput.value);
                modalTitle.textContent = 'Edit Permintaan Pangan';
                submitButton.textContent = 'Simpan Perubahan';
            }
            openModal();
        }

        form.addEventListener('submit', () => {
            if (form.querySelector('input[name="_method"]')?.value === 'PUT') return;

            rows.forEach((row) => {
                const hasInput = [...row.querySelectorAll('[data-entry-value]')]
                    .some((control) => control.value.trim() !== '');
                if (!hasInput) {
                    row.querySelectorAll('input, select').forEach((control) => {
                        control.disabled = true;
                    });
                }
            });
        });
    });
</script>
@endpush
