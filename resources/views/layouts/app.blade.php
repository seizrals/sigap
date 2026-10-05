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
    <div class="flex min-h-screen">
        @include('layouts.partials.sidebar')
        <div class="flex flex-col flex-1 min-w-0">
            @include('layouts.partials.navbar')
            <main class="flex-1 p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('modals')
    @stack('scripts')
</body>
</html>
