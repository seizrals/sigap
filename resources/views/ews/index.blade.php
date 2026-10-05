@extends('layouts.app')

@section('title', 'Early Warning System')
@section('page-title', 'Early Warning System (EWS)')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                            Pusat Peringatan Dini (EWS)
                        </h3>
                        <p class="text-sm text-slate-500">Sistem Analisis Deteksi Ancaman Pangan, LPG, & BBM Terintegrasi</p>
                    </div>
                </div>

                <div class="border-2 border-red-100 rounded-2xl p-5 bg-red-50/30 mb-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-red-600 mb-3">Notifikasi Deteksi Kritis</p>
                    <div class="min-h-[300px] flex items-center justify-center text-slate-400 text-sm">
                        Daftar notifikasi EWS - akan diisi pada tahap selanjutnya
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 min-h-[350px] flex items-center justify-center text-slate-400">
                <p class="text-sm">Ringkasan Indikator Pemicu 4D - akan diisi pada tahap selanjutnya</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-sigap-primary text-slate-100 rounded-[24px] shadow-sm p-6 min-h-[300px] flex items-center justify-center text-slate-400">
                <p class="text-sm">Peta Kerawanan - akan diisi pada tahap selanjutnya</p>
            </div>

            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 min-h-[250px] flex items-center justify-center text-slate-400">
                <p class="text-sm">Input Laporan Manual Lapangan - akan diisi pada tahap selanjutnya</p>
            </div>
        </div>
    </div>
@endsection
