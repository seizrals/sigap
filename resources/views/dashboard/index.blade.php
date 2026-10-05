@extends('layouts.app')

@section('title', 'Monitor TPID - SIGAP+')
@section('page-title', 'Monitor TPID Real-Time')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-[24px] p-6 text-white shadow-lg shadow-orange-500/20">
                <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Komoditas Defisit</p>
                <p class="text-5xl font-black mb-3">02</p>
                <p class="text-sm font-medium opacity-90">Cabai Rawit, Bawang Merah</p>
            </div>

            <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-[24px] p-6 text-white shadow-lg shadow-red-500/20">
                <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Kecamatan Zona Merah</p>
                <p class="text-5xl font-black mb-3">01</p>
                <p class="text-sm font-medium opacity-90">Kabupaten: Gorontalo Utara (LPG)</p>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-[24px] p-6 text-white shadow-lg shadow-blue-500/20">
                <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Rekomendasi Distribusi</p>
                <p class="text-4xl font-black mb-3">Aktif</p>
                <p class="text-sm font-medium opacity-90">Status: Operasi Pasar Kwadang</p>
            </div>
        </div>

        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100">
                <nav class="flex px-6 gap-8 text-sm">
                    <button class="py-4 font-bold border-b-2 border-sigap-accent text-sigap-accent">DATA IPH MINGGUAN</button>
                    <button class="py-4 font-semibold text-slate-500 hover:text-slate-800 transition-colors">PANGAN STRATEGIS</button>
                    <button class="py-4 font-semibold text-slate-500 hover:text-slate-800 transition-colors">LPG</button>
                    <button class="py-4 font-semibold text-slate-500 hover:text-slate-800 transition-colors">BBM</button>
                </nav>
            </div>
            <div class="p-6 min-h-[400px] flex items-center justify-center text-slate-400">
                <p class="text-sm">Konten tab akan diisi pada tahap selanjutnya</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 min-h-[350px] flex items-center justify-center text-slate-400">
                <p class="text-sm">Peta Rawan Inflasi (GIS Digital) - akan diimplementasikan selanjutnya</p>
            </div>
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 min-h-[350px] flex items-center justify-center text-slate-400">
                <p class="text-sm">Rekomendasi Strategis Otomatis - akan diimplementasikan selanjutnya</p>
            </div>
        </div>
    </div>
@endsection
