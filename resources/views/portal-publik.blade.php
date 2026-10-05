<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Publik - SIGAP+ Gorontalo Utara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sigap-bg min-h-screen antialiased">
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-sigap-accent to-sigap-secondary flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-1.348c-1.263 0-2.421-.96-2.655-2.236z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-slate-900">SIGAP+ GORONTALO UTARA</p>
                    <p class="text-xs text-slate-500 font-medium">Portal Publik Informasi Harga & Stok</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sigap-secondary text-white text-sm font-semibold hover:bg-amber-600 transition-colors">
                Masuk Sistem
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-16 min-h-[500px] flex items-center justify-center text-slate-400">
        <div class="text-center">
            <p class="text-sm font-semibold mb-1">Portal Publik SIGAP+</p>
            <p class="text-xs">Akan diisi pada tahap selanjutnya (informasi publik, Beri Saran floating button)</p>
        </div>
    </main>
</body>
</html>
