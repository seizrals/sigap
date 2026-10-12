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
                <!-- Card Alert Lonjakan Harga -->
            <div class="relative bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs overflow-hidden">
                <!-- Garis Indikator Merah di Sisi Kiri -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-600"></div>

                <div class="pl-2">
                 <!-- Header Alert: Icon, Judul, Wilayah, & Badge Waktu -->
        <div class="flex items-start justify-between gap-4 mb-4">
            <div class="flex items-start gap-3">
                <!-- Icon Lonjakan Merah -->
                <div class="w-10 h-10 rounded-xl bg-red-100/80 text-red-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 005.814-5.519l2.74-1.22m0 0l-5.94-2.28 2.28 5.941"/>
                    </svg>
                </div>
                <!-- Judul & Wilayah -->
                <div>
                    <h3 class="text-base font-bold text-slate-900 leading-snug">
                        Lonjakan Harga Cabai Rawit Merah (+18.5%)
                    </h3>
                    <p class="text-xs font-semibold text-red-600 mt-0.5">
                        Wilayah: Kec. Kwandang & Kec. Atinggola
                    </p>
                </div>
            </div>

            <!-- Badge Waktu -->
            <span class="px-3 py-1 rounded-lg bg-slate-100 text-[11px] font-semibold text-slate-500 whitespace-nowrap">
                2 Jam Lalu
            </span>
        </div>

        <!-- Box Hasil Analisis Sistem -->
        <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3.5 mb-4 text-xs leading-relaxed text-slate-600">
            <strong class="font-bold text-slate-800">Hasil Analisis Sistem :</strong> 
            Terjadi penurunan pasokan cabai lokal akibat curah hujan tinggi di sentra produksi. Diproyeksikan stok eceran hanya bertahan hingga 3 hari kedepan jika tidak ada pasokan substitusi.
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Tombol Utama (Biru) -->
            <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0C2.678 5.577 2.25 6.057 2.25 6.625v11.25"/>
                </svg>
                <span>Terbitkan Rekomendasi Operasi Pasar</span>
            </button>

            <!-- Tombol Sekunder (Abu-abu) -->
            <button class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                Panggil Pemasok Lokal
            </button>
        </div>
    </div>
</div>
</div>
</div>

            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 min-h-[50px] flex items-center justify-center text-slate-400">
                <!-- Card Alert Penumpukan Pembelian LPG 3Kg -->
        <div class="relative bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs overflow-hidden">
        <!-- Garis Indikator Kuning/Amber di Sisi Kiri -->
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>

    <div class="pl-2">
        <!-- Header Alert: Icon, Judul, Wilayah, & Badge Waktu -->
        <div class="flex items-start justify-between gap-4 mb-4">
            <div class="flex items-start gap-3">
                <!-- Icon Segitiga Peringatan (Amber) -->
                <div class="w-10 h-10 rounded-xl bg-amber-100/80 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <!-- Judul & Wilayah -->
                <div>
                    <h3 class="text-base font-bold text-slate-900 leading-snug">
                        Indikasi Penumpukan Pembelian LPG 3Kg
                    </h3>
                    <p class="text-xs font-semibold text-amber-600 mt-0.5">
                        Wilayah: Kec. Ponelo Kepulauan
                    </p>
                </div>
            </div>

            <!-- Badge Waktu -->
            <span class="px-3 py-1 rounded-lg bg-slate-100 text-[11px] font-semibold text-slate-500 whitespace-nowrap">
                5 Jam Lalu
            </span>
        </div>

        <!-- Box Hasil Analisis Sistem -->
        <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3.5 mb-4 text-xs leading-relaxed text-slate-600">
            <strong class="font-bold text-slate-800">Hasil Analisis Sistem :</strong> 
            Laporan sisa stok pangkalan menunjukkan tingkat keluar 210% melebihi rata-rata mingguan. Disarankan monitoring lapangan pencegahan panic buying.
        </div>

        <!-- Action Button -->
        <div class="flex items-center">
            <!-- Tombol Utama Sidak Pangkalan LPG (Amber/Oranye) -->
            <button class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-amber-950 text-xs font-bold transition-all shadow-sm active:scale-95">
                Sidak Pangkalan LPG
            </button>
        </div>
    </div>
</div>
            </div>
        </div>

        <div class="space-y-6">
            <<!-- Widget Peta Kerawanan (Dark Mode) -->
<div class="relative bg-[#0F172A] rounded-2xl p-5 border border-slate-800 text-white shadow-xl max-w-xs w-full overflow-hidden">
    <!-- Watermark Icon Pin di Latar Belakang Kanan Bawah -->
    <div class="absolute -right-4 -bottom-6 text-slate-800/40 pointer-events-none">
        <svg class="w-40 h-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
        </svg>
    </div>

    <div class="relative z-10">
        <!-- Header Widget -->
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/>
            </svg>
            <h3 class="text-xs font-black tracking-wider uppercase text-white">
                Peta Kerawanan
            </h3>
        </div>

        <!-- Daftar Kecamatan -->
        <div class="space-y-2 mb-4">
            <!-- 1. Kwandang (Merah) -->
            <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition-colors">
                <span class="text-xs font-bold text-slate-100">Kwandang</span>
                <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm shadow-red-500/50"></span>
            </div>

            <!-- 2. Atinggola (Merah) -->
            <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition-colors">
                <span class="text-xs font-bold text-slate-100">Atinggola</span>
                <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm shadow-red-500/50"></span>
            </div>

            <!-- 3. Sumalata (Kuning) -->
            <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition-colors">
                <span class="text-xs font-medium text-slate-300">Sumalata</span>
                <span class="w-3 h-3 rounded-full bg-amber-400 shadow-sm shadow-amber-400/50"></span>
            </div>

            <!-- 4. Gentuma Raya (Kuning) -->
            <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition-colors">
                <span class="text-xs font-medium text-slate-300">Gentuma Raya</span>
                <span class="w-3 h-3 rounded-full bg-amber-400 shadow-sm shadow-amber-400/50"></span>
            </div>

            <!-- 5. Anggrek (Hijau) -->
            <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition-colors">
                <span class="text-xs font-medium text-slate-300">Anggrek</span>
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
            </div>
        </div>

        <!-- Tombol Lihat Peta Lengkap -->
        <button class="w-full py-2.5 rounded-xl border border-blue-500/40 bg-blue-950/20 hover:bg-blue-900/40 text-blue-400 text-xs font-bold transition-all text-center">
            Lihat Peta Lengkap
        </button>
    </div>
</div>
            <!-- Widget Input Laporan Manual Lapangan -->
<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs max-w-xs w-full">
    <!-- Judul Widget -->
    <h3 class="text-sm font-bold text-slate-900 leading-snug mb-2">
        Input Laporan Manual Lapangan
    </h3>

    <!-- Deskripsi Widget -->
    <p class="text-xs text-slate-500 leading-relaxed mb-5">
        Formulir darurat untuk petugas jika sistem analitik belum menangkap anomali di lokasi terpencil.
    </p>

    <!-- Tombol Buat Peringatan Baru -->
    <button class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-red-50 hover:bg-red-100 border border-red-200/80 text-red-600 text-xs font-bold transition-all active:scale-95">
        <!-- Icon Plus Lingkaran -->
        <svg class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Buat Peringatan Baru</span>
    </button>
    </div>
</div>
@endsection
