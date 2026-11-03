<div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
    <table class="min-w-[1250px] w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                <th class="pb-4 pr-4">Tanggal</th>
                <th class="pb-4 pr-4">Kecamatan</th>
                <th class="pb-4 pr-4">SPBU</th>
                <th class="pb-4 pr-4">Jenis BBM</th>
                <th class="pb-4 px-3 text-right">Stok (KL)</th>
                <th class="pb-4 px-3">Nelayan</th>
                <th class="pb-4 px-3">Pertanian</th>
                <th class="pb-4 px-3">Antrean</th>
                <th class="pb-4 px-3">Ketersediaan</th>
                <th class="pb-4 px-3 text-right">Harga Eceran</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
            @forelse($dataBbm as $d)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->tanggal_input?->format('d/m/Y') }}</td>
                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->kecamatan?->nama_kecamatan ?? $d->spbu?->kecamatan?->nama_kecamatan ?? '-' }}</td>
                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->spbu?->nama_spbu ?? '-' }}</td>
                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->jenis_bbm }}</td>
                <td class="py-4 px-3 text-right">{{ $d->stok_tersedia_kl !== null ? number_format((float) $d->stok_tersedia_kl, 3, ',', '.') : '-' }}</td>
                <td class="py-4 px-3">
                    @if($d->distribusi_nelayan)
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200 whitespace-nowrap">
                                <input type="checkbox" disabled checked class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600">
                                Ya
                            </span>
                            <div class="text-[11px] text-slate-600 space-y-0.5 whitespace-nowrap bg-slate-50/70 p-1.5 rounded-lg border border-slate-200/60">
                                <div>Distribusi Masuk: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_masuk_nelayan_kl ?? $d->kuota_nelayan_harian_kl) !== null ? number_format((float) ($d->distribusi_masuk_nelayan_kl ?? $d->kuota_nelayan_harian_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                                <div>Distribusi Keluar: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_keluar_nelayan_kl ?? $d->realisasi_nelayan_kl) !== null ? number_format((float) ($d->distribusi_keluar_nelayan_kl ?? $d->realisasi_nelayan_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                            </div>
                        </div>
                    @else
                        <label class="inline-flex items-center gap-1.5 whitespace-nowrap text-slate-400 font-normal">
                            <input type="checkbox" disabled class="w-3.5 h-3.5 rounded border-slate-300 text-slate-400">
                            Tidak
                        </label>
                    @endif
                </td>
                <td class="py-4 px-3">
                    @if($d->distribusi_pertanian)
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 whitespace-nowrap">
                                <input type="checkbox" disabled checked class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-600">
                                Ya
                            </span>
                            <div class="text-[11px] text-slate-600 space-y-0.5 whitespace-nowrap bg-slate-50/70 p-1.5 rounded-lg border border-slate-200/60">
                                <div>Distribusi Masuk: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_masuk_pertanian_kl ?? $d->kuota_pertanian_harian_kl) !== null ? number_format((float) ($d->distribusi_masuk_pertanian_kl ?? $d->kuota_pertanian_harian_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                                <div>Distribusi Keluar: <span class="font-extrabold text-slate-800">{{ ($d->distribusi_keluar_pertanian_kl ?? $d->realisasi_pertanian_kl) !== null ? number_format((float) ($d->distribusi_keluar_pertanian_kl ?? $d->realisasi_pertanian_kl), 3, ',', '.') . ' KL' : '-' }}</span></div>
                            </div>
                        </div>
                    @else
                        <label class="inline-flex items-center gap-1.5 whitespace-nowrap text-slate-400 font-normal">
                            <input type="checkbox" disabled class="w-3.5 h-3.5 rounded border-slate-300 text-slate-400">
                            Tidak
                        </label>
                    @endif
                </td>
                <td class="py-4 px-3 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ in_array($d->status_antrean, ['panjang', 'sangat_panjang'], true) ? 'bg-rose-100 text-rose-700' : ($d->status_antrean === 'pendek' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ str_replace('_', ' ', $d->status_antrean) }}</span>
                </td>
                <td class="py-4 px-3 whitespace-nowrap">{{ ucfirst($d->status_ketersediaan) }}</td>
                <td class="py-4 px-3 text-right whitespace-nowrap">{{ $d->harga_jual_per_liter !== null ? 'Rp '.number_format((float) $d->harga_jual_per_liter, 0, ',', '.') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data untuk BBM dan tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
