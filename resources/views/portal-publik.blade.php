<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP+ Gorontalo Utara - Siaga Pangan Plus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sigap-bg min-h-screen antialiased">

    {{-- Header --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl overflow-hidden shadow-lg shrink-0">
                    <img src="{{ asset('images/logo_gorut.png') }}" alt="Logo Gorontalo Utara" class="w-full h-full object-contain">
                </div>
                <div>
                    <p class="font-bold text-slate-900">SIGAP+ GORONTALO UTARA</p>
                    <p class="text-xs text-slate-500 font-medium">Sistem Informasi Siaga Pangan Plus</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sigap-accent text-white text-sm font-semibold hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
                Masuk Sistem
            </a>
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-sigap-primary via-sigap-primary-light to-sigap-accent text-white">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur text-xs font-semibold text-amber-300 border border-white/10 mb-5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sistem Pemantauan Pangan Real-Time
                    </span>
                    <h1 class="text-4xl lg:text-5xl font-black leading-tight mb-5">
                        Siaga Pangan<br>
                        <span class="text-amber-400">Gorontalo Utara</span>
                    </h1>
                    <p class="text-slate-300 text-base leading-relaxed mb-8 max-w-lg">
                        Platform terintegrasi untuk memantau harga, stok pangan, LPG, dan BBM di 11 kecamatan.
                        Bersama membangun ketahanan pangan yang transparan dan akuntabel.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#beri-saran" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-sm transition-colors shadow-lg shadow-amber-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                            </svg>
                            Beri Saran / Laporan
                        </a>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-amber-400/20 blur-3xl rounded-full"></div>
                        <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 w-96">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-emerald-500/10 border border-emerald-400/20 rounded-2xl p-5 text-center">
                                    <div class="text-3xl font-black text-emerald-400">11</div>
                                    <div class="text-xs text-slate-300 mt-1">Kecamatan</div>
                                </div>
                                <div class="bg-amber-500/10 border border-amber-400/20 rounded-2xl p-5 text-center">
                                    <div class="text-3xl font-black text-amber-400">30+</div>
                                    <div class="text-xs text-slate-300 mt-1">Komoditas</div>
                                </div>
                                <div class="bg-sky-500/10 border border-sky-400/20 rounded-2xl p-5 text-center">
                                    <div class="text-3xl font-black text-sky-400">EWS</div>
                                    <div class="text-xs text-slate-300 mt-1">Early Warning</div>
                                </div>
                                <div class="bg-rose-500/10 border border-rose-400/20 rounded-2xl p-5 text-center">
                                    <div class="text-3xl font-black text-rose-400">GIS</div>
                                    <div class="text-xs text-slate-300 mt-1">Peta Digital</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Beri Saran Section --}}
    <section id="beri-saran" class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Info Side --}}
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                    </svg>
                    KANAL PARTISIPASI PUBLIK
                </span>
                <h2 class="text-3xl font-black text-slate-900 mb-4 leading-tight">
                    Beri Saran &<br>Laporkan Temuan
                </h2>
                <p class="text-slate-500 leading-relaxed mb-8">
                    Bantu kami memantau harga dan ketersediaan pangan, LPG, serta BBM di wilayah Anda.
                    Setiap laporan akan divalidasi oleh TPID Kabupaten Gorontalo Utara sebelum ditindaklanjuti.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 005.814-5.519l2.74-1.22m0 0l-5.94-2.28 2.28 5.941"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Lonjakan Harga</h4>
                            <p class="text-xs text-slate-500">Temukan harga di pasar yang jauh melebihi harga normal.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0C2.678 5.577 2.25 6.057 2.25 6.625v11.25"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Kelangkaan Stok</h4>
                            <p class="text-xs text-slate-500">Laporan kekosongan stok LPG 3kg atau BBM di wilayah tertentu.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Saran Kebijakan</h4>
                            <p class="text-xs text-slate-500">Usulan pelaksanaan Operasi Pasar atau Gerakan Pangan Murah.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Side --}}
            <div>
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-sigap-accent to-blue-700 p-6 text-white">
                        <h3 class="font-bold text-xl">Sampaikan Saran / Aduan</h3>
                        <p class="text-sm opacity-90 mt-1">Masukan Anda membantu kami menjaga stabilitas daerah.</p>
                    </div>

                    @if(session('success'))
                        <div class="mx-6 mt-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mx-6 mt-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('saran-publik.store') }}" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Laporan *</label>
                            <select name="kategori" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                                <option value="lonjakan_harga" @selected(old('kategori', 'lonjakan_harga') === 'lonjakan_harga')>Lonjakan Harga</option>
                                <option value="kelangkaan_stok" @selected(old('kategori') === 'kelangkaan_stok')>Kelangkaan Stok</option>
                                <option value="saran_kebijakan" @selected(old('kategori') === 'saran_kebijakan')>Saran Kebijakan</option>
                                <option value="pelanggaran_het" @selected(old('kategori') === 'pelanggaran_het')>Pelanggaran HET</option>
                                <option value="lainnya" @selected(old('kategori') === 'lainnya')>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat *</label>
                            <input type="text" name="judul_laporan" required placeholder="Contoh: Dusun,kecamatan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Laporan *</label>
                            <input type="text" name="judul_laporan" required placeholder="Contoh: Harga cabai naik tajam" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>

                        <div id="commodity-fields" class="grid grid-cols-2 gap-3 @if(!in_array(old('kategori', 'lonjakan_harga'), ['lonjakan_harga', 'kelangkaan_stok', 'pelanggaran_het'], true)) hidden @endif">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Komoditas *</label>
                                <input type="text" name="nama_komoditas_dilaporkan" value="{{ old('nama_komoditas_dilaporkan') }}" required placeholder="Contoh: Cabai Rawit" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga (Rp) *</label>
                                <input type="number" name="harga_dilaporkan" value="{{ old('harga_dilaporkan') }}" min="0" required placeholder="Contoh: 85000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Isi Laporan / Saran *</label>
                            <textarea name="isi_laporan" rows="4" required placeholder="Tuliskan kelangkaan barang, fluktuasi harga, atau saran untuk TPID..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama (opsional)</label>
                                <input type="text" name="nama_pelapor" placeholder="Nama Anda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kontak (Wajib)*</label>
                                <input type="text" name="kontak_pelapor" value="{{ old('kontak_pelapor') }}" required placeholder="No. HP / Email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm placeholder:text-slate-300 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                            </div>
                        </div>

                        <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                            <input type="checkbox" name="anonim" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Kirim sebagai anonim</span>
                        </label>

                        <button type="submit" class="w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-sm shadow-lg shadow-amber-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            Kirim Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-sigap-primary text-slate-400 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl overflow-hidden shadow-lg shrink-0">
                        <img src="{{ asset('images/logo_gorut.png') }}" alt="Logo Gorontalo Utara" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">SIGAP+ Gorontalo Utara</p>
                        <p class="text-xs">TPID Kabupaten Gorontalo Utara</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500">&copy; {{ date('Y') }} Sistem Informasi Siaga Pangan Plus. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.querySelector('select[name="kategori"]');
            const commodityFields = document.querySelector('#commodity-fields');
            const commodityInputs = commodityFields.querySelectorAll('input');
            const requiredCategories = ['lonjakan_harga', 'kelangkaan_stok', 'pelanggaran_het'];

            const updateCommodityFields = () => {
                const required = requiredCategories.includes(categorySelect.value);
                commodityFields.classList.toggle('hidden', !required);
                commodityInputs.forEach((input) => {
                    input.required = required;
                    input.disabled = !required;
                    if (!required) input.value = '';
                });
            };

            categorySelect.addEventListener('change', updateCommodityFields);
            updateCommodityFields();
        });
    </script>

</body>
</html>
