<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - VISTORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark');
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        'primary-light': '#eff6ff',
                        'primary-dark': '#1e40af'
                    }
                }
            }
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @livewireStyles
</head>
<body class="m-0 h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 font-['Plus_Jakarta_Sans'] antialiased overflow-x-hidden">
    <div class="min-h-screen flex max-w-full overflow-x-hidden" x-data>
        <div
            x-show="$store.sidebar.isMobileOpen"
            x-cloak
            @click="$store.sidebar.toggleMobileOpen()"
            class="fixed inset-0 z-40 bg-black/50 xl:hidden"
        ></div>
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300"
            :class="{
                'xl:ml-72': $store.sidebar.isExpanded,
                'xl:ml-20': !$store.sidebar.isExpanded
            }">
            @include('admin.partials.topbar')
            <main class="flex-1 p-4 md:p-8 max-w-full overflow-x-hidden">
                @yield('content')
            </main>
            @include('admin.partials.footer')
        </div>
    </div>
    @livewireScripts
</body>
</html>
