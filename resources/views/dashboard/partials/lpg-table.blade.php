<div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
    <table class="min-w-[1150px] w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                <th class="pb-4 pr-4">Tanggal</th>
                <th class="pb-4 pr-4">Kecamatan</th>
                <th class="pb-4 pr-4">Jenis LPG</th>
                <th class="pb-4 px-3 text-right">Stok</th>
                <th class="pb-4 px-3 text-right">Masuk</th>
                <th class="pb-4 px-3 text-right">Keluar</th>
                <th class="pb-4 px-3 text-right">Harga HET</th>
                <th class="pb-4 px-3 text-right">Harga Eceran</th>
                <th class="pb-4 px-3">Ketersediaan</th>
                <th class="pb-4 px-3">Distribusi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
            @forelse($dataLpg as $d)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->tanggal_input?->format('d/m/Y') }}</td>
                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->kecamatan?->nama_kecamatan ?? '-' }}</td>
                <td class="py-4 pr-4 whitespace-nowrap">{{ $d->jenis_lpg }}</td>
                <td class="py-4 px-3 text-right whitespace-nowrap">{{ number_format($d->stok_tersedia_tabung, 0, ',', '.') }}</td>
                <td class="py-4 px-3 text-right whitespace-nowrap">{{ number_format($d->distribusi_masuk_tabung, 0, ',', '.') }}</td>
                <td class="py-4 px-3 text-right text-rose-600 whitespace-nowrap">{{ number_format($d->distribusi_keluar_tabung, 0, ',', '.') }}</td>
                <td class="py-4 px-3 text-right whitespace-nowrap">{{ $d->harga_pangkalan !== null ? 'Rp '.number_format((float) $d->harga_pangkalan, 0, ',', '.') : '-' }}</td>
                <td class="py-4 px-3 text-right whitespace-nowrap">{{ $d->harga_eceran_lapangan !== null ? 'Rp '.number_format((float) $d->harga_eceran_lapangan, 0, ',', '.') : '-' }}</td>
                <td class="py-4 px-3 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $d->status_ketersediaan === 'aman' ? 'bg-emerald-100 text-emerald-700' : ($d->status_ketersediaan === 'waspada' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">{{ $d->status_ketersediaan }}</span>
                </td>
                <td class="py-4 px-3 whitespace-nowrap">{{ str_replace('_', ' ', $d->status_distribusi) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data LPG untuk filter ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

