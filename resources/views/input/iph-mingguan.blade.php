@extends('layouts.app')

@section('title', 'Spreadsheet IPH Mingguan')
@section('page-title', 'Data IPH Mingguan')

@section('content')
<div class="space-y-6">

    {{-- Excel Window Container --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
        
        {{-- Excel Title & Ribbon Bar --}}
        <div class="bg-[#107c41] px-6 py-4 text-white flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center font-black text-lg tracking-wider text-white border border-white/20">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.17 3.25Q21.5 3.25 21.76 3.5 22 3.74 22 4.1v15.8q0 .35-.24.6-.25.25-.59.25H7.83q-.35 0-.6-.25-.24-.25-.24-.6V17H2.83q-.35 0-.59-.24Q2 16.5 2 16.16V7.84q0-.35.24-.6.24-.24.59-.24H7V4.1q0-.36.24-.6.25-.25.59-.25zM7 15.5h13.5V4.75H7.83V7H12q.35 0 .6.24.24.25.24.6v8.32q0 .35-.24.6-.25.24-.6.24H7zm-3.5 0h3.5V8.5H3.5zm7.32-6.72L9.2 12.3l1.62 3.52H9.3l-1.05-2.52-.3 1v1.52H6.67V8.78h1.28v2.45l1.32-2.45z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold tracking-wider bg-white/20 px-2 py-0.5 rounded uppercase">Excel Grid</span>
                        <h3 class="font-extrabold text-base md:text-lg">IPH_Mingguan_Gorut.xlsx</h3>
                    </div>
                    <p class="text-xs text-emerald-100 opacity-90 mt-0.5">Tabel Indeks Perkembangan Harga (IPH) Mingguan BPS Kabupaten Gorontalo Utara</p>
                </div>
            </div>

            {{-- Action Buttons: Download Excel & Monitoring --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('input.iph.export-excel', ['tahun' => $tahun]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-[#107c41] hover:bg-emerald-50 rounded-xl text-xs font-black transition shadow-md">
                    <svg class="w-4 h-4 text-[#107c41]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Download Excel (.xlsx)
                </a>

                <a href="{{ route('dashboard', ['tab' => 'iph-mingguan']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-800/80 hover:bg-emerald-900 text-white rounded-xl text-xs font-bold transition border border-emerald-600/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    Lihat Grafik Monitoring
                </a>
            </div>
        </div>

        {{-- Excel Formula / Quick Input Bar ("Bisa di Input") --}}
        <div class="bg-slate-50 border-b border-slate-200 p-4">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center justify-center px-2.5 py-1 bg-emerald-100 text-emerald-800 font-mono font-bold text-xs rounded border border-emerald-200">
                    fx
                </span>
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Input Data Baru ke Spreadsheet</span>
                <span class="text-xs text-slate-400 font-normal">| Masukkan data baris lalu klik "Simpan ke Sheet"</span>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('input.iph.store') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3 items-end">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ $tahun ?? date('Y') }}" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs font-bold text-slate-800 outline-none focus:border-[#107c41] focus:ring-1 focus:ring-[#107c41]">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Bulan</label>
                    <select name="bulan" required class="w-full bg-white border border-slate-300 rounded-lg px-2.5 py-2 text-xs font-bold text-slate-800 outline-none focus:border-[#107c41] focus:ring-1 focus:ring-[#107c41]">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                            <option value="{{ $b }}" @selected(date('F') === $b || date('M') === substr($b, 0, 3))>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Minggu Ke</label>
                    <select name="minggu" required class="w-full bg-white border border-slate-300 rounded-lg px-2.5 py-2 text-xs font-bold text-slate-800 outline-none focus:border-[#107c41] focus:ring-1 focus:ring-[#107c41]">
                        <option value="I">Minggu I</option>
                        <option value="II" selected>Minggu II</option>
                        <option value="III">Minggu III</option>
                        <option value="IV">Minggu IV</option>
                        <option value="V">Minggu V</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nilai IPH (%) *</label>
                    <input type="number" step="0.01" name="iph" placeholder="Cth: 2.33 atau -0.55" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs font-bold text-slate-800 outline-none focus:border-[#107c41] focus:ring-1 focus:ring-[#107c41]">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Ranking Nasional</label>
                    <input type="number" name="ranking" placeholder="Cth: 14" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs font-bold text-slate-800 outline-none focus:border-[#107c41] focus:ring-1 focus:ring-[#107c41]">
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Komoditas Andil</label>
                    <input type="text" name="komoditas_andil" placeholder="Cth: Cabai Rawit (0.38), Bawang Merah (0.25)" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-[#107c41] focus:ring-1 focus:ring-[#107c41]">
                </div>

                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-[#107c41] hover:bg-[#0c6133] text-white rounded-lg text-xs font-black transition flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Simpan ke Sheet
                    </button>
                </div>
            </form>
        </div>

        {{-- Filter Sheet Tahun --}}
        <div class="px-6 py-3 bg-slate-100/60 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-3">
                <span class="font-extrabold text-slate-500 uppercase text-[10px] tracking-wider">Filter Lembar Kerja:</span>
                <form method="GET" action="{{ route('input.iph.index') }}" class="inline-flex items-center gap-2">
                    <select name="tahun" onchange="this.form.submit()" class="bg-white border border-slate-300 text-slate-800 text-xs font-bold rounded-md px-2.5 py-1 outline-none">
                        <option value="">Semua Tahun</option>
                        @foreach($daftarTahun as $th)
                            <option value="{{ $th }}" @selected((string) $tahun === (string) $th)>Tahun {{ $th }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="text-slate-500 text-[11px] font-medium">
                Total Data: <strong class="text-slate-800">{{ $records->count() }} baris</strong>
            </div>
        </div>

        {{-- Excel Grid Table --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    {{-- Row Index Letters (Excel Style) --}}
                    <tr class="bg-slate-200 text-slate-500 text-[10px] font-mono border-b border-slate-300">
                        <th class="w-12 px-2 py-1 text-center border-r border-slate-300 bg-slate-300/70">#</th>
                        <th class="px-3 py-1 border-r border-slate-300">A</th>
                        <th class="px-3 py-1 border-r border-slate-300">B</th>
                        <th class="px-3 py-1 border-r border-slate-300">C</th>
                        <th class="px-3 py-1 border-r border-slate-300">D</th>
                        <th class="px-3 py-1 border-r border-slate-300">E</th>
                        <th class="px-3 py-1 border-r border-slate-300">F</th>
                        <th class="px-3 py-1 border-r border-slate-300">G</th>
                        <th class="px-3 py-1 text-center">H</th>
                    </tr>
                    {{-- Column Headers --}}
                    <tr class="bg-slate-100 text-slate-700 font-extrabold uppercase tracking-wider text-[11px] border-b border-slate-300">
                        <th class="w-12 px-2 py-2.5 text-center border-r border-slate-300 bg-slate-200/60">No</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Tahun</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Bulan</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Minggu Ke</th>
                        <th class="px-3 py-2.5 border-r border-slate-300 text-right">Nilai IPH (%)</th>
                        <th class="px-3 py-2.5 border-r border-slate-300 text-center">Status</th>
                        <th class="px-3 py-2.5 border-r border-slate-300 text-center">Ranking</th>
                        <th class="px-3 py-2.5 border-r border-slate-300">Komoditas Andil Utama</th>
                        <th class="px-3 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium text-slate-800">
                    @forelse($records as $index => $row)
                        @php
                            [, $namaBulan] = App\Http\Controllers\InputDataController::parseBulan($row->bulan);
                            $val = (float) $row->iph_kabupaten;
                            $status = $val > 3.5 || $val < -2.5 ? 'Tinggi' : ($val >= 2.5 ? 'Waspada' : 'Terkendali');
                            $badgeColor = match ($status) {
                                'Tinggi' => 'bg-rose-100 text-rose-700 border-rose-200',
                                'Waspada' => 'bg-amber-100 text-amber-800 border-amber-200',
                                default => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            };
                        @endphp
                        <tr class="hover:bg-emerald-50/40 transition group">
                            {{-- Row Number --}}
                            <td class="w-12 px-2 py-3 text-center border-r border-slate-300 bg-slate-100 font-mono text-[11px] text-slate-500 font-bold group-hover:bg-emerald-100/50">
                                {{ $index + 1 }}
                            </td>
                            {{-- Tahun --}}
                            <td class="px-3 py-3 border-r border-slate-200 font-mono font-bold">{{ $row->tahun }}</td>
                            {{-- Bulan --}}
                            <td class="px-3 py-3 border-r border-slate-200 font-semibold">{{ $namaBulan }}</td>
                            {{-- Minggu --}}
                            <td class="px-3 py-3 border-r border-slate-200 font-bold text-slate-700">Minggu {{ $row->minggu_ke }}</td>
                            {{-- IPH Value --}}
                            <td class="px-3 py-3 border-r border-slate-200 text-right font-mono font-black text-sm {{ $val < 0 ? 'text-emerald-600' : ($val > 3.5 ? 'text-rose-600' : 'text-slate-800') }}">
                                {{ number_format($val, 2, ',', '.') }}%
                            </td>
                            {{-- Status --}}
                            <td class="px-3 py-3 border-r border-slate-200 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold uppercase border {{ $badgeColor }}">
                                    {{ $status }}
                                </span>
                            </td>
                            {{-- Ranking --}}
                            <td class="px-3 py-3 border-r border-slate-200 text-center font-mono font-bold">
                                {{ $row->ranking ? '#'.$row->ranking : '-' }}
                            </td>
                            {{-- Komoditas Andil --}}
                            <td class="px-3 py-3 border-r border-slate-200 text-slate-600 text-xs max-w-xs truncate" title="{{ $row->komoditas_andil ?? $row->komoditas_penyumbang_inflasi }}">
                                {{ $row->komoditas_andil ?? $row->komoditas_penyumbang_inflasi ?? '-' }}
                            </td>
                            {{-- Action --}}
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('input.iph.destroy', $row->id) }}" onsubmit="return confirm('Hapus baris data IPH ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-rose-50 transition" title="Hapus Baris">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"/></svg>
                                Belum ada data IPH Mingguan untuk tahun yang dipilih. Silakan input pada baris formulir di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Excel Sheet Bottom Status Bar --}}
        <div class="bg-slate-100 border-t border-slate-300 px-6 py-2.5 flex flex-wrap items-center justify-between text-[11px] font-mono text-slate-500">
            <div class="flex items-center gap-2">
                <span class="bg-white border border-slate-300 px-3 py-1 rounded-t font-bold text-[#107c41] flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    Sheet1 (IPH Mingguan)
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span>BARIS: <strong>{{ $records->count() }}</strong></span>
                @if($records->count() > 0)
                    <span>RATA-RATA: <strong>{{ number_format((float) $records->avg('iph_kabupaten'), 2, ',', '.') }}%</strong></span>
                    <span>TERAKHIR: <strong>{{ number_format((float) $records->first()->iph_kabupaten, 2, ',', '.') }}%</strong></span>
                @endif
                <span class="text-emerald-700 font-bold">READY</span>
            </div>
        </div>

    </div>
</div>
@endsection
