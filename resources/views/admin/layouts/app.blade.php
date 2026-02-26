<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - VISTORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark');
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @livewireStyles
</head>
<body class="h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 font-['Inter'] antialiased">
    <div class="min-h-screen flex" x-data>
        <div
            x-show="$store.sidebar.isMobileOpen"
            x-cloak
            @click="$store.sidebar.toggleMobileOpen()"
            class="fixed inset-0 z-40 bg-black/50 xl:hidden"
        ></div>
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col transition-all duration-300"
            :class="{
                'xl:ml-72': $store.sidebar.isExpanded,
                'xl:ml-20': !$store.sidebar.isExpanded
            }">
            @include('admin.partials.topbar')
            <main class="flex-1 p-4 md:p-8">
                @yield('content')
            </main>
            @include('admin.partials.footer')
        </div>
    </div>
    @livewireScripts
</body>
</html>
