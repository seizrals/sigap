@extends('layouts.app')

@section('title', 'Input Data Pangan Strategis')
@section('page-title', 'Input Data Pangan Strategis')

@section('content')
<div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 p-4 sm:p-6 text-white flex items-center gap-3 sm:gap-4">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-16.5-.52c-1.01.143-2.01.317-3 .52m16.5 0a48.667 48.667 0 01-3 10.5m-10.5-10.5a48.667 48.667 0 00-3 10.5m16.5 0a48.667 48.667 0 01-10.5 0"/>
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-base sm:text-lg">Form Input Pangan Strategis</h3>
            <p class="text-xs sm:text-sm opacity-90 mt-0.5">Input harga dan ketersediaan komoditas pangan per kecamatan secara langsung.</p>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="mx-4 sm:mx-6 mt-4 sm:mt-6 p-3.5 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <span>{{ session('success') }}</span>
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

    <!-- Form Input Pangan Langsung (Bukan Popup) -->
    <form id="pangan-form" method="POST" action="{{ route('input.pangan.store') }}" class="p-4 sm:p-6 space-y-5 text-slate-700">
        @csrf

        <!-- Parameter Utama: Tanggal Input & Kecamatan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <label for="pangan-tanggal-input" class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Tanggal Input *
                </label>
                <input type="date" id="pangan-tanggal-input" name="tanggal_input" value="{{ old('tanggal_input', old('periode_saat_ini', date('Y-m-d'))) }}" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-amber-500 shadow-xs transition">
            </div>

            <div>
                <label for="pangan-global-kecamatan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Kecamatan *
                </label>
                <select id="pangan-global-kecamatan" name="kecamatan_id" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-amber-500 shadow-xs transition">
                    <option value="">Pilih Kecamatan...</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}" {{ (string) old('kecamatan_id') === (string) $kec->id ? 'selected' : '' }}>
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Filter Pencarian Komoditas Cepat -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
            <div class="text-xs text-slate-500 font-medium">
                Silakan isi komoditas yang dilaporkan. Baris yang tidak diisi akan otomatis dilewati.
            </div>
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input type="text" id="filter-komoditas" placeholder="Cari nama komoditas..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-700 outline-none focus:bg-white focus:border-amber-500 shadow-2xs transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                </div>
            </div>
        </div>

        <!-- Tabel Komoditas Pangan -->
        <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-2xs">
            <table class="min-w-[900px] w-full border-collapse text-left text-xs">
                <thead class="bg-amber-50 text-slate-700">
                    <tr class="border-b border-amber-200/80">
                        <th class="px-3.5 py-3 font-bold w-48 sm:w-56">Nama Komoditas</th>
                        <th class="px-3 py-3 font-bold w-40 sm:w-44">Harga Sebelumnya</th>
                        <th class="px-3 py-3 font-bold w-36">Harga Hari Ini *</th>
                        <th class="px-3 py-3 font-bold w-44">Stok Tersedia</th>
                        <th class="px-3 py-3 font-bold w-44">Distribusi Dari Luar</th>
                        <th class="px-3 py-3 font-bold w-36">Status Ketersediaan *</th>
                    </tr>
                </thead>
                <tbody id="komoditas-tbody" class="divide-y divide-slate-100 text-slate-700">
                    @forelse($komoditas as $item)
                        <tr data-entry-row data-komoditas-id="{{ $item->id }}" data-nama-komoditas="{{ strtolower($item->nama_komoditas) }}" class="odd:bg-white even:bg-slate-50/60 hover:bg-amber-50/40 transition-colors">
                            <!-- Komoditas -->
                            <td class="px-3.5 py-3 font-semibold text-slate-800 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-800">{{ $item->nama_komoditas }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">Satuan: {{ $item->satuan }}</div>
                                <input type="hidden" name="entries[{{ $item->id }}][komoditas_id]" value="{{ $item->id }}">
                                <input type="hidden" name="entries[{{ $item->id }}][kecamatan_id]" class="row-kecamatan-id" value="{{ old('entries.'.$item->id.'.kecamatan_id', old('kecamatan_id')) }}">
                            </td>

                            <!-- Harga Sebelumnya (Otomatis dengan Tanggal Opacity) -->
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 text-xs" data-prev-price="{{ $item->id }}">
                                        Rp {{ number_format((float) old('entries.'.$item->id.'.harga_hari_sebelumnya', 0), 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 opacity-60 font-medium" data-prev-date="{{ $item->id }}">
                                        Belum ada data
                                    </span>
                                </div>
                                <input type="hidden" name="entries[{{ $item->id }}][harga_hari_sebelumnya]" data-prev-input="{{ $item->id }}" value="{{ old('entries.'.$item->id.'.harga_hari_sebelumnya', 0) }}">
                            </td>

                            <!-- Harga Hari Ini -->
                            <td class="px-3 py-3">
                                <input data-entry-value type="number" min="0" step="0.01" name="entries[{{ $item->id }}][harga_hari_ini]" value="{{ old('entries.'.$item->id.'.harga_hari_ini') }}" placeholder="Rp" class="w-32 bg-white border border-slate-200 rounded-lg px-2.5 py-2 text-xs outline-none focus:border-amber-500 shadow-2xs">
                            </td>

                            <!-- Stok Tersedia -->
                            <td class="px-3 py-3">
                                <div class="flex">
                                    <input data-entry-value type="number" min="0" step="0.01" name="entries[{{ $item->id }}][stok_tersedia]" value="{{ old('entries.'.$item->id.'.stok_tersedia') }}" placeholder="Jumlah" class="w-24 bg-white border border-slate-200 rounded-l-lg px-2.5 py-2 text-xs outline-none focus:border-amber-500">
                                    <input type="text" name="entries[{{ $item->id }}][satuan_stok]" value="{{ old('entries.'.$item->id.'.satuan_stok', $item->satuan) }}" maxlength="30" class="w-16 bg-slate-50 border border-l-0 border-slate-200 rounded-r-lg px-2 py-2 text-xs text-center outline-none focus:border-amber-500 text-slate-600 font-medium">
                                </div>
                            </td>

                            <!-- Distribusi Dari Luar -->
                            <td class="px-3 py-3">
                                <input data-entry-value type="text" name="entries[{{ $item->id }}][sumber_distribusi_masuk]" value="{{ old('entries.'.$item->id.'.sumber_distribusi_masuk') }}" maxlength="255" placeholder="Asal pasokan / luar daerah" class="w-40 bg-white border border-slate-200 rounded-lg px-2.5 py-2 text-xs outline-none focus:border-amber-500 shadow-2xs">
                            </td>

                            <!-- Status Ketersediaan -->
                            <td class="px-3 py-3">
                                <select data-entry-value name="entries[{{ $item->id }}][status_ketersediaan]" class="w-32 bg-white border border-slate-200 rounded-lg px-2 py-2 text-xs outline-none focus:border-amber-500 shadow-2xs">
                                    <option value="">Pilih...</option>
                                    <option value="aman" @selected(old('entries.'.$item->id.'.status_ketersediaan', 'aman') === 'aman')>Aman</option>
                                    <option value="waspada" @selected(old('entries.'.$item->id.'.status_ketersediaan') === 'waspada')>Waspada</option>
                                    <option value="kritis" @selected(old('entries.'.$item->id.'.status_ketersediaan') === 'kritis')>Kritis</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada komoditas pangan aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row justify-end items-center gap-3 pt-4 border-t border-slate-100">
            <button type="reset" id="pangan-reset-btn" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition text-center cursor-pointer">
                Reset Form
            </button>
            <button type="submit" id="pangan-submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-md shadow-amber-500/20 transition text-center cursor-pointer">
                Simpan Data Pangan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('pangan-form');
        const tanggalInput = document.getElementById('pangan-tanggal-input');
        const kecamatanSelect = document.getElementById('pangan-global-kecamatan');
        const filterInput = document.getElementById('filter-komoditas');
        const rows = [...form.querySelectorAll('[data-entry-row]')];
        const previousPricesUrl = '{{ route("input.pangan.previous-prices") }}';

        // 1. Sinkronisasi Kecamatan ke seluruh baris
        const syncKecamatanToRows = () => {
            const selectedKecamatanId = kecamatanSelect.value;
            form.querySelectorAll('.row-kecamatan-id').forEach(input => {
                input.value = selectedKecamatanId;
            });
        };

        // 2. Fetch harga sebelumnya dan tanggalnya secara otomatis
        const fetchPreviousPrices = async () => {
            const kecamatanId = kecamatanSelect.value;
            const tanggal = tanggalInput.value;

            if (!kecamatanId || !tanggal) {
                // Reset tampilan harga sebelumnya
                rows.forEach(row => {
                    const komoditasId = row.dataset.komoditasId;
                    const priceLabel = row.querySelector(`[data-prev-price="${komoditasId}"]`);
                    const dateLabel = row.querySelector(`[data-prev-date="${komoditasId}"]`);
                    const hiddenInput = row.querySelector(`[data-prev-input="${komoditasId}"]`);

                    if (priceLabel) priceLabel.textContent = 'Rp 0';
                    if (dateLabel) {
                        dateLabel.textContent = 'Pilih kecamatan';
                        dateLabel.className = 'text-[11px] text-slate-400 opacity-60 font-medium';
                    }
                    if (hiddenInput) hiddenInput.value = '0';
                });
                return;
            }

            try {
                const response = await fetch(`${previousPricesUrl}?kecamatan_id=${encodeURIComponent(kecamatanId)}&tanggal_input=${encodeURIComponent(tanggal)}`);
                if (!response.ok) return;

                const data = await response.json();

                rows.forEach(row => {
                    const komoditasId = row.dataset.komoditasId;
                    const priceLabel = row.querySelector(`[data-prev-price="${komoditasId}"]`);
                    const dateLabel = row.querySelector(`[data-prev-date="${komoditasId}"]`);
                    const hiddenInput = row.querySelector(`[data-prev-input="${komoditasId}"]`);

                    if (data[komoditasId]) {
                        const price = parseFloat(data[komoditasId].harga || 0);
                        const formattedPrice = new Intl.NumberFormat('id-ID').format(price);
                        if (priceLabel) priceLabel.textContent = `Rp ${formattedPrice}`;
                        if (dateLabel) {
                            dateLabel.textContent = `(${data[komoditasId].tanggal})`;
                            dateLabel.className = 'text-[11px] text-slate-500 opacity-60 font-medium';
                        }
                        if (hiddenInput) hiddenInput.value = price;
                    } else {
                        // Belum ada data sebelumnya
                        if (priceLabel) priceLabel.textContent = 'Rp 0';
                        if (dateLabel) {
                            dateLabel.textContent = 'Belum ada data';
                            dateLabel.className = 'text-[11px] text-slate-400 opacity-60 font-medium';
                        }
                        if (hiddenInput) hiddenInput.value = '0';
                    }
                });
            } catch (err) {
                console.error('Gagal mengambil riwayat harga:', err);
            }
        };

        kecamatanSelect.addEventListener('change', () => {
            syncKecamatanToRows();
            fetchPreviousPrices();
        });

        tanggalInput.addEventListener('change', () => {
            fetchPreviousPrices();
        });

        // Jalankan saat pertama kali halaman dimuat jika kecamatan sudah terpilih
        syncKecamatanToRows();
        if (kecamatanSelect.value) {
            fetchPreviousPrices();
        }

        // 3. Filter Komoditas Cepat
        if (filterInput) {
            filterInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                rows.forEach(row => {
                    const name = row.dataset.namaKomoditas || '';
                    if (name.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // 4. Sebelum submit: Hanya submit baris yang diisi harga hari ini
        form.addEventListener('submit', (e) => {
            if (!kecamatanSelect.value) {
                alert('Silakan pilih kecamatan terlebih dahulu.');
                kecamatanSelect.focus();
                e.preventDefault();
                return;
            }

            syncKecamatanToRows();

            let filledCount = 0;
            rows.forEach((row) => {
                const hargaInput = row.querySelector('input[name*="[harga_hari_ini]"]');
                const hasValue = hargaInput && hargaInput.value.trim() !== '';

                if (hasValue) {
                    filledCount++;
                    row.querySelectorAll('input, select').forEach((control) => {
                        control.disabled = false;
                    });
                } else {
                    row.querySelectorAll('input, select').forEach((control) => {
                        control.disabled = true;
                    });
                }
            });

            if (filledCount === 0) {
                alert('Silakan isi minimal satu komoditas sebelum menyimpan.');
                rows.forEach(row => {
                    row.querySelectorAll('input, select').forEach(c => c.disabled = false);
                });
                e.preventDefault();
            }
        });

        // Pastikan semua input aktif kembali jika user kembali ke halaman (bfcache)
        window.addEventListener('pageshow', () => {
            rows.forEach(row => {
                row.querySelectorAll('input, select').forEach(c => c.disabled = false);
            });
        });

        // 5. Reset form: Kembalikan semua disabled state
        document.getElementById('pangan-reset-btn')?.addEventListener('click', () => {
            setTimeout(() => {
                rows.forEach(row => {
                    row.style.display = '';
                    row.querySelectorAll('input, select').forEach(c => c.disabled = false);
                });
                if (filterInput) filterInput.value = '';
                fetchPreviousPrices();
            }, 50);
        });
    });
</script>
@endpush
