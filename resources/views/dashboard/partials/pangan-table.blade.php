<div class="overflow-x-auto -mx-3.5 sm:mx-0 px-3.5 sm:px-0">
    <table class="min-w-[950px] w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                <th class="pb-4 pr-4">Kecamatan</th>
                <th class="pb-4 pr-4">Nama Komoditas</th>
                <th class="pb-4 px-4">Stok</th>
                <th class="pb-4 px-4">Periode Lalu<br><span class="text-[10px] font-medium normal-case">{{ \Carbon\Carbon::parse($periodeSebelumnya)->format('d/m/Y') }}</span></th>
                <th class="pb-4 px-4">Periode Saat Ini<br><span class="text-[10px] font-medium normal-case">{{ \Carbon\Carbon::parse($periodeSaatIni)->format('d/m/Y') }}</span></th>
                <th class="pb-4 px-4">Perubahan</th>
                <th class="pb-4 pl-4 text-right">Ketersediaan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm font-semibold">
            @forelse($dataPangan as $d)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="py-4 pr-4 text-slate-700 font-bold whitespace-nowrap">{{ $d['kecamatan'] }}</td>
                <td class="py-4 pr-4 whitespace-nowrap">
                    <p class="font-bold text-slate-800">{{ $d['komoditas'] }}</p>
                    <p class="text-[10px] text-slate-400">{{ $d['satuan'] }}</p>
                </td>
                <td class="py-4 px-4 text-slate-600 whitespace-nowrap">{{ $d['stok'] !== null ? number_format((float) $d['stok'], 0, ',', '.') : '-' }} {{ $d['satuan'] }}</td>
                <td class="py-4 px-4 text-slate-600 whitespace-nowrap">{{ $d['harga_lalu'] !== null ? 'Rp '.number_format((float) $d['harga_lalu'], 0, ',', '.') : '-' }}</td>
                <td class="py-4 px-4 font-bold text-slate-800 whitespace-nowrap">{{ $d['harga_saat_ini'] !== null ? 'Rp '.number_format((float) $d['harga_saat_ini'], 0, ',', '.') : '-' }}</td>
                <td class="py-4 px-4 whitespace-nowrap {{ ($d['perubahan_persen'] ?? 0) > 0 ? 'text-rose-500' : (($d['perubahan_persen'] ?? 0) < 0 ? 'text-emerald-500' : 'text-slate-400') }}">
                    {{ $d['perubahan_persen'] === null ? '-' : (($d['perubahan_persen'] > 0 ? '+' : '').number_format((float) $d['perubahan_persen'], 1, ',', '.').'%') }}
                </td>
                <td class="py-4 pl-4 text-right whitespace-nowrap">
                    <span class="inline-block text-[11px] font-bold px-3 py-1 rounded-full uppercase {{ $d['status_ketersediaan'] === 'aman' ? 'bg-emerald-100 text-emerald-700' : ($d['status_ketersediaan'] === 'waspada' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-600') }}">{{ $d['status_ketersediaan'] ? ucfirst($d['status_ketersediaan']) : '-' }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center text-slate-400 text-sm font-normal">Belum ada data untuk filter dan periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

