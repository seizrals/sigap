@extends('layouts.app')

@section('title', 'Validasi OPD')
@section('page-title', 'Validasi Data oleh OPD Teknis')

@section('content')
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-8 min-h-[500px] flex items-center justify-center text-slate-400">
        <div class="text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-violet-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-semibold mb-1">Daftar Data Menunggu Validasi OPD</p>
            <p class="text-xs">Akan diisi pada tahap selanjutnya</p>
        </div>
    </div>
@endsection
