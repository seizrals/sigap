@extends('layouts.app')

@section('title', 'Validasi OPD')
@section('page-title', 'Validasi Data OPD')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Statistik Validasi --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Data Pangan Pending</p>
            <p class="text-3xl font-black text-slate-900">{{ $dataPanganPending->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Data LPG Pending</p>
            <p class="text-3xl font-black text-slate-900">{{ $dataLpgPending->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Data BBM Pending</p>
            <p class="text-3xl font-black text-slate-900">{{ $dataBbmPending->count() }}</p>
        </div>
    </div>

    {{-- Tabel Validasi Pangan --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-amber-400 to-amber-500 p-4 text-white">
            <h3 class="font-bold">Validasi Data Pangan Strategis</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Komoditas</th>
                        <th class="px-5 py-3">Kecamatan</th>
                        <th class="px-5 py-3">Harga Hari Ini</th>
                        <th class="px-5 py-3">Perubahan</th>
                        <th class="px-5 py-3">Tanggal Input</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dataPanganPending as $d)
                    <tr>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $d->komoditas?->nama_komoditas ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ $d->kecamatan?->nama_kecamatan ?? '-' }}</td>
                        <td class="px-5 py-3.5 font-semibold">Rp {{ number_format($d->harga_hari_ini, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 {{ $d->perubahan_harga_persen > 0 ? 'text-red-600 font-semibold' : 'text-emerald-600 font-semibold' }}">
                            {{ number_format($d->perubahan_harga_persen, 1) }}%
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 text-xs">{{ $d->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('validasi.update', $d->id) }}" class="inline-flex gap-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="pangan">
                                <input type="hidden" name="status" value="disetujui">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('validasi.update', $d->id) }}" class="inline-flex gap-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="pangan">
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada data pangan yang menunggu validasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Validasi LPG --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-orange-400 to-orange-600 p-4 text-white">
            <h3 class="font-bold">Validasi Data LPG</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Kecamatan</th>
                        <th class="px-5 py-3">Jenis LPG</th>
                        <th class="px-5 py-3">Stok Tersedia</th>
                        <th class="px-5 py-3">Harga HET</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dataLpgPending as $d)
                    <tr>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $d->kecamatan?->nama_kecamatan ?? $d->pangkalanLpg?->kecamatan?->nama_kecamatan ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ $d->jenis_lpg ?? 'LPG 3 Kg (Subsidi)' }}</td>
                        <td class="px-5 py-3.5 font-semibold">{{ $d->stok_tersedia_tabung }} tabung</td>
                        <td class="px-5 py-3.5 font-semibold">Rp {{ number_format($d->harga_pangkalan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase
                                @if($d->status_ketersediaan == 'aman') bg-emerald-100 text-emerald-700
                                @elseif($d->status_ketersediaan == 'waspada') bg-amber-100 text-amber-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ $d->status_ketersediaan }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('validasi.update', $d->id) }}" class="inline-flex gap-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="lpg">
                                <input type="hidden" name="status" value="disetujui">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('validasi.update', $d->id) }}" class="inline-flex gap-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="lpg">
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada data LPG yang menunggu validasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Validasi BBM --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-4 text-white">
            <h3 class="font-bold">Validasi Data BBM</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Jenis BBM</th>
                        <th class="px-5 py-3">SPBU</th>
                        <th class="px-5 py-3">Stok (KL)</th>
                        <th class="px-5 py-3">Harga Eceran</th>
                        <th class="px-5 py-3">Antrean</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dataBbmPending as $d)
                    <tr>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $d->jenis_bbm }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ $d->spbu?->nama_spbu ?? '-' }}</td>
                        <td class="px-5 py-3.5 font-semibold">{{ $d->stok_tersedia_kl }}</td>
                        <td class="px-5 py-3.5 font-semibold">Rp {{ number_format($d->harga_jual_per_liter, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase
                                @if($d->status_antrean == 'tidak_ada') bg-emerald-100 text-emerald-700
                                @elseif($d->status_antrean == 'pendek') bg-amber-100 text-amber-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ str_replace('_', ' ', $d->status_antrean) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('validasi.update', $d->id) }}" class="inline-flex gap-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="bbm">
                                <input type="hidden" name="status" value="disetujui">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('validasi.update', $d->id) }}" class="inline-flex gap-1">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="bbm">
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada data BBM yang menunggu validasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
