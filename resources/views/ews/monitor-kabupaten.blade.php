@extends('layouts.app')

@section('title', 'Monitor Kabupaten')
@section('page-title', 'Monitor Kabupaten - Ringkasan TPID')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M15.75 3.75h-9a2.25 2.25 0 00-2.25 2.25v12A2.25 2.25 0 006.75 20.25h9a2.25 2.25 0 002.25-2.25v-12a2.25 2.25 0 00-2.25-2.25z"/></svg>
                        Ringkasan Indikator Pemicu 4D
                    </h3>
                    <p class="text-sm text-slate-500">Kondia spesifik yang memicu alert dan tindak lanjut TPID saat ini</p>
                </div>
                <span class="text-xs font-semibold text-slate-600">Total Pemicu Terdeteksi: <span class="text-red-600 font-bold">0 Indikator</span></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 min-h-[200px]">
                <div class="rounded-2xl p-5 bg-amber-50 border border-amber-100 flex items-center justify-center text-slate-400 text-xs text-center">Pemicu Harga</div>
                <div class="rounded-2xl p-5 bg-red-50 border border-red-100 flex items-center justify-center text-slate-400 text-xs text-center">Pemicu Pangan</div>
                <div class="rounded-2xl p-5 bg-blue-50 border border-blue-100 flex items-center justify-center text-slate-400 text-xs text-center">Pemicu BBM</div>
                <div class="rounded-2xl p-5 bg-orange-50 border border-orange-100 flex items-center justify-center text-slate-400 text-xs text-center">Pemicu LPG</div>
            </div>
        </div>

        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Tabel Pantauan Status Wilayah (11 Kecamatan)</h3>
                    <p class="text-sm text-slate-500">11 Batasan status ambang batas untuk melihat indikator pemicu per kecamatan</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> AMAN
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> WASPADA
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-700">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> BAHAYA
                    </span>
                </div>
            </div>
            <div class="min-h-[300px] flex items-center justify-center text-slate-400 text-sm">
                Tabel status kecamatan - akan diisi pada tahap selanjutnya
            </div>
        </div>
    </div>
@endsection
