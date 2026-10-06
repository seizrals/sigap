@extends('layouts.app')

@section('title', 'Monitor TPID - SIGAP+')
@section('page-title', 'Monitor TPID Real-Time')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-[24px] p-6 text-white shadow-lg shadow-orange-500/20">
                <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Komoditas Defisit</p>
                <p class="text-5xl font-black mb-3">02</p>
                <p class="text-sm font-medium opacity-90">Item: Cabai Rawit, Bawang Merah</p>
            </div>

            <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-[24px] p-6 text-white shadow-lg shadow-red-500/20">
                <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Kecamatan Zona Merah</p>
                <p class="text-5xl font-black mb-3">01</p>
                <p class="text-sm font-medium opacity-90">Wilayah: KAB. Gorontalo Utara (LPG)</p>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-[24px] p-6 text-white shadow-lg shadow-blue-500/20">
                <p class="text-xs font-bold uppercase tracking-wider opacity-90 mb-2">Rekomendasi Distribusi</p>
                <p class="text-4xl font-black mb-3">Aktif</p>
                <p class="text-sm font-medium opacity-90">Saran: Operasi Pasar Kwadang</p>
            </div>
        </div>

        <!-- Tab Navigation -->
    <div class="bg-sm-600 rounded-xl p-4 text-white shadow-lg shadow-sm-600/20">
    <div class="flex items-center gap-8 border-b border-slate-100 pb-4 mb-6">
        <!-- Button Data IPH -->
        <button id="btn-iph" onclick="switchTab('iph')" class="tab-btn text-blue-600 font-bold border-b-2 border-blue-600 pb-4 -mb-4 text-sm transition-all">
            DATA IPH MINGGUAN
        </button>
        <!-- Button Pangan Strategis -->
        <button id="btn-pangan" onclick="switchTab('pangan')" class="tab-btn text-slate-400 font-semibold text-sm hover:text-slate-600 pb-4 -mb-4 border-b-2 border-transparent transition-all">
            PANGAN STRATEGIS
        </button>
        <button id="btn-lpg" onclick="switchTab('lpg')" class="tab-btn text-slate-400 font-semibold text-sm hover:text-slate-600 pb-4 -mb-4 border-b-2 border-transparent transition-all">LPG</button>
        <button id="btn-bbm" onclick="switchTab('bbm')" class="tab-btn text-slate-400 font-semibold text-sm hover:text-slate-600 pb-4 -mb-4 border-b-2 border-transparent transition-all">BBM</button>
    </div>

<!-- Include Library Chart.js CDN (jika belum dimasukkan di file layout utama) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Konten Tab DATA IPH MINGGUAN -->
<div id="content-iph" class="tab-content">
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        
        <!-- Sidebar Kiri: Filter Pencarian & Card Status -->
        <div class="md:col-span-4 space-y-4">
            
            <!-- Box Filter Pencarian -->
            <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-100 space-y-4">
                <p class="text-[11px] font-extrabold text-slate-400 tracking-wider uppercase">Filter Pencarian</p>
                
                <!-- Select Tahun -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Tahun</label>
                    <select class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <!-- Select Bulan -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Bulan</label>
                    <select class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl px-3 py-2 outline-none focus:border-blue-500 shadow-sm">
                        <option value="Mei" selected>Mei</option>
                        <option value="April">April</option>
                    </select>
                </div>
            </div>

            <!-- Card IPH Minggu II Mei -->
            <div class="bg-emerald-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20">
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-100 mb-1">IPH Minggu II Mei</p>
                <p class="text-4xl font-extrabold tracking-tight mb-3">2.33%</p>
                <span class="inline-block bg-emerald-500/50 backdrop-blur-sm text-white text-[11px] font-semibold px-3 py-1 rounded-lg border border-emerald-400/30">
                    Status: Terkendali
                </span>
            </div>

        </div>

        <!-- Area Kanan: Grafik Line Chart -->
        <div class="md:col-span-8 relative h-[320px] w-full pt-2">
            <canvas id="iphChart"></canvas>
        </div>

    </div>

</div>

    <!-- Konten 2: PANGAN STRATEGIS (Awalnya Sembunyi / hidden) -->
    <div id="content-pangan" class="tab-content hidden">
        <!-- Section Filter -->
        <div class="bg-slate-50/60 p-4 rounded-2xl mb-6 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500">
            <div class="flex items-center gap-2">
                <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Pilih Kecamatan</span>
                <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none shadow-sm">
                    <option value="all" selected>Seluruh Kecamatan</option>
                </select>
            </div>
            <div class="flex items-center gap-2 ml-auto">
                <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Periode</span>
                <input type="date" value="2026-05-10" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none shadow-sm">
                <span class="text-slate-400 font-bold">vs</span>
                <input type="date" value="2026-05-11" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none shadow-sm">
            </div>
        </div>

        <!-- Tabel Pangan Strategis -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                        <th class="pb-4 pr-4">Nama Komoditas</th>
                        <th class="pb-4 px-4">Satuan</th>
                        <th class="pb-4 px-4">10 Mei</th>
                        <th class="pb-4 px-4">11 Mei</th>
                        <th class="pb-4 px-4">Perubahan</th>
                        <th class="pb-4 pl-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                    <tr>
                        <td class="py-4 pr-4 font-bold text-slate-800">Beras Premium</td>
                        <td class="py-4 px-4 text-slate-400">Kg</td>
                        <td class="py-4 px-4 font-bold">Rp 15.000</td>
                        <td class="py-4 px-4 font-bold">Rp 15.000</td>
                        <td class="py-4 px-4 text-slate-400">-</td>
                        <td class="py-4 pl-4 text-right">
                            <span class="bg-emerald-100 text-emerald-700 text-[11px] font-bold px-3 py-1 rounded-full">STABIL</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4 pr-4 font-bold text-slate-800">Cabai Rawit</td>
                        <td class="py-4 px-4 text-slate-400">Kg</td>
                        <td class="py-4 px-4 font-bold">Rp 75.000</td>
                        <td class="py-4 px-4 font-bold">Rp 85.000</td>
                        <td class="py-4 px-4 text-rose-500 font-bold">↑ Rp 10.000</td>
                        <td class="py-4 pl-4 text-right">
                            <span class="bg-rose-100 text-rose-600 text-[11px] font-bold px-3 py-1 rounded-full">HARGA TINGGI</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Konten Tab LPG -->
<div id="content-lpg" class="tab-content">
    
    <!-- Filter Section (Kecamatan, Desa, Pilih Tanggal) -->
    <div class="bg-slate-50/60 p-4 rounded-2xl mb-6 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500">
        <!-- Filter Kecamatan -->
        <div class="flex items-center gap-2">
            <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Kecamatan</span>
            <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                <option value="all" selected>Semua Kecamatan</option>
                <option value="kwadang">Kwadang</option>
                <option value="atinggola">Atinggola</option>
            </select>
        </div>

        <!-- Filter Desa -->
        <div class="flex items-center gap-2">
            <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Desa</span>
            <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                <option value="all" selected>Semua Desa</option>
                <option value="molingkapoto">Desa Molingkapoto</option>
                <option value="kotajin">Desa Kota Jin</option>
            </select>
        </div>

        <!-- Pilih Tanggal -->
        <div class="flex items-center gap-2 ml-auto">
            <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Pilih Tanggal</span>
            <input type="date" value="2026-12-05" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
        </div>
    </div>

    <!-- Info HET di Kanan Atas Tabel -->
    <div class="flex justify-end items-center gap-6 mb-4 text-right">
        <div>
            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">HET LPG 3KG</p>
            <p class="text-sm font-extrabold text-orange-500">Rp 18.500</p>
        </div>
        <div>
            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">HET LPG 12KG</p>
            <p class="text-sm font-extrabold text-orange-500">Rp 215.000</p>
        </div>
    </div>

    <!-- Tabel Monitoring Pangkalan LPG -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                    <th class="pb-4 pr-4">Pangkalan / Wilayah</th>
                    <th class="pb-4 px-4 text-slate-400">Kebutuhan<br><span class="text-[10px] lowercase text-slate-300">(tbg)</span></th>
                    <th class="pb-4 px-4 text-emerald-600">Dist.<br>Masuk</th>
                    <th class="pb-4 px-4 text-rose-500">Dist.<br>Keluar</th>
                    <th class="pb-4 px-4 text-slate-400">Harga<br>Jual</th>
                    <th class="pb-4 pl-4 text-right">Status<br>Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                
                <!-- Baris 1: Pkl. Berkah Abadi -->
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="py-4 pr-4">
                        <p class="font-bold italic text-slate-800">Pkl. Berkah Abadi</p>
                        <p class="text-xs italic text-slate-400 font-normal">Desa Molingkapoto, Kwadang</p>
                    </td>
                    <td class="py-4 px-4 font-bold text-slate-800">850</td>
                    <td class="py-4 px-4 font-extrabold text-emerald-600">1.200</td>
                    <td class="py-4 px-4 font-bold text-rose-500">400</td>
                    <td class="py-4 px-4">
                        <p class="font-bold text-slate-800">Rp 18.500</p>
                        <p class="text-[10px] font-bold italic text-emerald-600">SESUAI HET</p>
                    </td>
                    <td class="py-4 pl-4 text-right">
                        <span class="inline-block bg-emerald-100 text-emerald-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase">
                            TERSEDIA
                        </span>
                    </td>
                </tr>

                <!-- Baris 2: Pkl. Utama Jaya -->
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="py-4 pr-4">
                        <p class="font-bold italic text-slate-800">Pkl. Utama Jaya</p>
                        <p class="text-xs italic text-slate-400 font-normal">Desa Kota Jin, Atinggola</p>
                    </td>
                    <td class="py-4 px-4 font-bold text-slate-800">400</td>
                    <td class="py-4 px-4 font-extrabold text-emerald-600">0</td>
                    <td class="py-4 px-4 font-bold text-rose-500">398</td>
                    <td class="py-4 px-4">
                        <p class="font-bold text-rose-600">Rp 25.000</p>
                        <p class="text-[10px] font-bold italic text-rose-500">DIATAS HET</p>
                    </td>
                    <td class="py-4 pl-4 text-right">
                        <span class="inline-block bg-rose-100 text-rose-600 text-[11px] font-bold px-3 py-1 rounded-full uppercase">
                            KRITIS
                        </span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

<!-- Konten Tab BBM -->
<div id="content-bbm" class="tab-content hidden">
    
    <!-- Filter Section & Badge Harga -->
    <div class="mb-6 space-y-4">
        <!-- Baris Filter: Kecamatan, Lokasi SPBU, Pilih Tanggal -->
        <div class="bg-slate-50/60 p-4 rounded-2xl flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500">
            <!-- Filter Kecamatan -->
            <div class="flex items-center gap-2">
                <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Kecamatan</span>
                <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                    <option value="all" selected>Semua Kecamatan</option>
                    <option value="kwandang">Kwadang</option>
                    <option value="anggrek">Anggrek</option>
                </select>
            </div>

            <!-- Filter Lokasi SPBU (Desa) -->
            <div class="flex items-center gap-2">
                <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Lokasi SPBU (Desa)</span>
                <select class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
                    <option value="all" selected>Semua Lokasi</option>
                    <option value="titidu">Desa Titidu</option>
                    <option value="ilangata">Desa Ilangata</option>
                </select>
            </div>

            <!-- Pilih Tanggal -->
            <div class="flex items-center gap-2">
                <span class="uppercase font-bold text-[11px] tracking-wider text-slate-400">Pilih Tanggal</span>
                <input type="date" value="2026-12-05" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-slate-800 font-bold text-xs outline-none focus:border-blue-500 shadow-sm">
            </div>
        </div>

        <!-- Badge Ringkasan Harga BBM di Kanan Atas -->
        <div class="flex justify-end items-center gap-3">
            <!-- Badge Pertalite -->
            <div class="bg-blue-600 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">Pertalite</p>
                <p class="text-xs font-black italic">Rp 10.000</p>
            </div>

            <!-- Badge BioSolar -->
            <div class="bg-blue-900 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">BioSolar</p>
                <p class="text-xs font-black italic">Rp 6.800</p>
            </div>

            <!-- Badge Pertamax -->
            <div class="bg-red-600 text-white px-4 py-2 rounded-xl text-center shadow-sm">
                <p class="text-[9px] font-extrabold tracking-wider uppercase opacity-90">Pertamax</p>
                <p class="text-xs font-black italic">Rp 13.200</p>
            </div>
        </div>
    </div>

    <!-- Tabel Monitoring Stok & Distribusi BBM -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                    <th class="pb-4 pr-4">Jenis BBM & Lokasi</th>
                    <th class="pb-4 px-4 text-slate-400">Kebutuhan (KL)</th>
                    <th class="pb-4 px-4 text-slate-400">Stok Saat Ini (KL)</th>
                    <th class="pb-4 px-4 text-slate-400">Harga Satuan</th>
                    <th class="pb-4 pl-4 text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-semibold">
                
                <!-- Baris 1: Pertalite (SPBU Kwandang) -->
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="py-4 pr-4">
                        <p class="font-bold text-slate-800">Pertalite (SPBU Kwandang)</p>
                        <p class="text-xs italic text-slate-400 font-normal">Desa Titidu, Kwandang</p>
                    </td>
                    <td class="py-4 px-4 font-bold text-slate-800">15.5</td>
                    <td class="py-4 px-4 font-bold text-slate-800">12.0</td>
                    <td class="py-4 px-4 font-extrabold italic text-blue-600">Rp 10.000</td>
                    <td class="py-4 pl-4 text-right">
                        <span class="inline-block bg-emerald-100 text-emerald-700 text-[11px] font-bold px-3 py-1 rounded-full uppercase">
                            LANCAR
                        </span>
                    </td>
                </tr>

                <!-- Baris 2: BioSolar (SPBU Anggrek) -->
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="py-4 pr-4">
                        <p class="font-bold text-slate-800">BioSolar (SPBU Anggrek)</p>
                        <p class="text-xs italic text-slate-400 font-normal">Desa Ilangata, Anggrek</p>
                    </td>
                    <td class="py-4 px-4 font-bold text-slate-800">8.0</td>
                    <td class="py-4 px-4 font-extrabold italic text-red-500">0.8</td>
                    <td class="py-4 px-4 font-extrabold italic text-blue-800">Rp 6.800</td>
                    <td class="py-4 pl-4 text-right">
                        <span class="inline-block bg-rose-100 text-rose-600 text-[11px] font-bold px-3 py-1 rounded-full uppercase">
                            ANTREAN
                        </span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

</div>

@endsection

@push ('scripts')
<!-- Script JavaScript untuk Pindah Tab -->
<script>
    function switchTab(tabName) {
        // 1. Sembunyikan semua konten tab
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // 2. Reset style semua tombol tab menjadi tidak aktif
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-blue-600', 'font-bold', 'border-blue-600');
            btn.classList.add('text-slate-400', 'font-semibold', 'border-transparent');
        });

        // 3. Tampilkan konten & aktifkan tombol secara otomatis berdasarkan nama tab
        document.getElementById(`content-${tabName}`).classList.remove('hidden');
        
        const activeBtn = document.getElementById(`btn-${tabName}`);
        activeBtn.classList.add('text-blue-600', 'font-bold', 'border-blue-600');
        activeBtn.classList.remove('text-slate-400', 'font-semibold', 'border-transparent');
    }
</script>

<!-- Script Chart.js untuk Menggambar Grafik -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('iphChart').getContext('2d');
        
        // Membuat Gradient Background Hijau Transparan di Bawah Garis
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['April Mng III', 'April Mng IV', 'Mei Mng I', 'Mei Mng II'],
                datasets: [{
                    data: [1.85, 2.10, 1.95, 2.33],
                    borderColor: '#10b981',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.45, // Efek lengkungan garis halus
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 11, weight: '500' } }
                    },
                    y: {
                        min: 1.80,
                        max: 2.35,
                        ticks: {
                            stepSize: 0.05,
                            color: '#64748b',
                            font: { size: 11 },
                            callback: function(value) {
                                return value.toFixed(2).replace('.', ',');
                            }
                        },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    });
</script>
@endpush
