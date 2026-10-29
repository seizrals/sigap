<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIGAP+ Gorontalo Utara')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-sigap-bg text-slate-800 antialiased">
    <div class="flex h-screen overflow-hidden relative">
        @include('layouts.partials.sidebar')
        
        {{-- Backdrop untuk mobile saat sidebar terbuka --}}
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity cursor-pointer"></div>

        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            @include('layouts.partials.navbar')
            <main class="flex-1 p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('modals')
    @stack('scripts')

    {{-- Script Toggle Navbar / Sidebar --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const STORAGE_KEY = 'sigap_sidebar_collapsed';
            const sidebar = document.getElementById('app-sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            const hideBtn = document.getElementById('sidebar-hide-btn');
            const backdrop = document.getElementById('sidebar-backdrop');

            if (!sidebar) return;

            // Cek state tersimpan
            const isDesktop = window.innerWidth >= 1024;
            const savedState = localStorage.getItem(STORAGE_KEY);
            if (isDesktop && savedState === 'true') {
                sidebar.classList.add('sidebar-collapsed');
            } else if (!isDesktop) {
                // Di layar mobile/tablet, default tertutup
                sidebar.classList.add('sidebar-collapsed');
            }

            function toggleSidebar() {
                const isCurrentlyCollapsed = sidebar.classList.contains('sidebar-collapsed');
                if (isCurrentlyCollapsed) {
                    // Munculkan sidebar
                    sidebar.classList.remove('sidebar-collapsed');
                    if (backdrop && window.innerWidth < 1024) {
                        backdrop.classList.remove('hidden');
                    }
                    if (window.innerWidth >= 1024) {
                        localStorage.setItem(STORAGE_KEY, 'false');
                    }
                } else {
                    // Hilangkan / sembunyikan sidebar
                    sidebar.classList.add('sidebar-collapsed');
                    if (backdrop) {
                        backdrop.classList.add('hidden');
                    }
                    if (window.innerWidth >= 1024) {
                        localStorage.setItem(STORAGE_KEY, 'true');
                    }
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            }

            if (hideBtn) {
                hideBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    sidebar.classList.add('sidebar-collapsed');
                    backdrop.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>
