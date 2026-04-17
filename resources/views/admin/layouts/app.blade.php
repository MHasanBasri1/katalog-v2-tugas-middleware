<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') - Kataloque</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark');
        })();
    </script>
    <!-- Tailwind CSS is handled by Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @livewireStyles
    @stack('styles')
</head>
<body class="m-0 h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 font-['Plus_Jakarta_Sans'] antialiased overflow-x-hidden">
    @if($setting->is_maintenance)
        <div class="fixed top-0 inset-x-0 z-[250] bg-rose-600 text-white text-[10px] font-black uppercase tracking-[0.2em] py-1.5 px-4 text-center">
            <i class="ti ti-tool me-2"></i> Mode Pemeliharaan Sedang Aktif — Pengunjung Tidak Bisa Mengakses Katalog
        </div>
    @endif
    <div class="min-h-screen flex max-w-full overflow-x-hidden" x-data>
        <div
            x-show="$store.sidebar.isMobileOpen"
            x-cloak
            @click="$store.sidebar.toggleMobileOpen()"
            class="fixed inset-0 z-[190] bg-black/50 xl:hidden"
        ></div>
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300"
            :class="{
                'xl:ml-72': $store.sidebar.isExpanded,
                'xl:ml-20': !$store.sidebar.isExpanded
            }">
            @include('admin.partials.topbar')
            <main class="flex-1 p-4 md:p-8 pt-24 md:pt-28 max-w-full overflow-x-hidden">
                @yield('content')
            </main>
            @include('admin.partials.footer')
        </div>
    </div>
    @livewireScripts
    
    <!-- Global Notification Toast -->
    <div x-data="{ 
            show: false, 
            type: 'success', 
            message: '',
            timeout: null,
            init() {
                window.addEventListener('notify', event => {
                    const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
                    this.message = data.message;
                    this.type = data.type || 'success';
                    this.show = true;
                    if(this.timeout) clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => { this.show = false }, 3000);
                });
            }
        }"
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 md:translate-y-0 md:translate-x-4"
        x-transition:enter-end="opacity-100 translate-y-0 md:translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-6 right-6 z-[300] max-w-sm w-full"
    >
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-2xl rounded-2xl p-4 flex items-center gap-4">
            <template x-if="type === 'success'">
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ti ti-check text-emerald-600 text-lg"></i>
                </div>
            </template>
            <template x-if="type === 'error'">
                <div class="w-10 h-10 bg-rose-50 dark:bg-rose-900/20 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ti ti-x text-rose-600 text-lg"></i>
                </div>
            </template>
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="message"></p>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="ti ti-x text-sm"></i>
            </button>
        </div>
    </div>
    
    @include('admin.partials.confirmation-modal')

    @stack('scripts')
</body>
</html>
