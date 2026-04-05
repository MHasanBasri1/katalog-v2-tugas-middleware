<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Katalog Produk Modern')</title>
    <meta name="description" content="@yield('meta_description', 'Kataloque adalah katalog produk modern dengan pencarian cepat, kategori lengkap, dan detail produk terbaik.')">
    <meta name="robots" content="@yield('meta_robots', 'index,follow,max-image-preview:large')">
    <meta name="author" content="Kataloque">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Kataloque">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', 'Katalog Produk Modern')))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', 'Kataloque adalah katalog produk modern dengan pencarian cepat, kategori lengkap, dan detail produk terbaik.')))">
    <meta property="og:url" content="@yield('og_url', trim($__env->yieldContent('canonical', url()->current())))">
    <meta property="og:image" content="@yield('og_image', 'https://picsum.photos/seed/kataloque-og/1200/630')">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', trim($__env->yieldContent('title', 'Katalog Produk Modern')))">
    <meta name="twitter:description" content="@yield('twitter_description', trim($__env->yieldContent('meta_description', 'Kataloque adalah katalog produk modern dengan pencarian cepat, kategori lengkap, dan detail produk terbaik.')))">
    <meta name="twitter:image" content="@yield('twitter_image', trim($__env->yieldContent('og_image', 'https://picsum.photos/seed/kataloque-og/1200/630')))">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "WebSite",
            "name": "Kataloque",
            "url": "{{ url('/') }}",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ route('katalog') }}?q={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .mobile-only { display: inline-flex; }
        .desktop-only { display: none !important; }
        @media (min-width: 768px) {
            .mobile-only { display: none !important; }
            .desktop-only { display: inline-flex !important; }
        }
    </style>
    @livewireStyles
    @stack('meta')
    @stack('styles')
</head>
<body class="m-0 min-h-screen text-gray-800 font-['Plus_Jakarta_Sans'] antialiased overflow-x-hidden relative selection:bg-primary/10 selection:text-primary">
    <!-- Clean Retail Background -->
    <div class="fixed inset-0 -z-20 bg-gray-50/50 pointer-events-none"></div>

    <livewire:public.header />

    <main class="relative z-10 pt-[90px] md:pt-[108px] pb-20 md:pb-0 @yield('main_class') max-w-full overflow-x-hidden">
        @yield('content')
    </main>

    <livewire:public.footer />

    <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-[90] bg-white/80 backdrop-blur-xl border-t border-gray-100 px-4 py-2 pb-safe shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
        <div class="flex items-center justify-around gap-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-400' }}">
                <i class="fas fa-home text-lg"></i>
                <span class="text-[10px] font-bold">Beranda</span>
            </a>
            <a href="{{ route('katalog') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('katalog') ? 'text-primary' : 'text-gray-400' }}">
                <i class="fas fa-th-large text-lg"></i>
                <span class="text-[10px] font-bold">Katalog</span>
            </a>
            <a href="{{ auth()->check() ? route('user.panel', ['tab' => 'favorit']) : route('user.login') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->query('tab') === 'favorit' || request()->is('user/favorites*') ? 'text-primary' : 'text-gray-400' }}">
                <i class="fas fa-heart text-lg"></i>
                <span class="text-[10px] font-bold">Favorit</span>
            </a>
            <a href="{{ auth()->check() ? (auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('user.panel')) : route('user.login') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('user/profile*') || request()->is('login*') || request()->is('register*') ? 'text-primary' : 'text-gray-400' }}">
                @auth
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" class="w-5 h-5 rounded-full object-cover">
                    @else
                        <i class="fas fa-user-circle text-lg"></i>
                    @endif
                @else
                    <i class="fas fa-user-circle text-lg"></i>
                @endauth
                <span class="text-[10px] font-bold">Profil</span>
            </a>
        </div>
    </div>

    <!-- Custom Toast Notification -->
    <div 
        x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            timeout: null,
            init() {
                this.$nextTick(() => {
                    // Handle Livewire 3 events
                    if (typeof window.Livewire !== 'undefined') {
                        window.Livewire.on('alert', (event) => {
                            const data = Array.isArray(event) ? event[0] : event;
                            this.showAlert(data);
                        });
                    }
                    
                    // Handle standard JS CustomEvents
                    window.addEventListener('alert', (event) => {
                        const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
                        this.showAlert(data);
                    });
                });
            },
            showAlert(data) {
                this.message = data.message;
                this.type = data.type || 'success';
                this.show = true;
                
                if(this.timeout) clearTimeout(this.timeout);
                this.timeout = setTimeout(() => { this.show = false }, 3000);
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed top-24 left-1/2 -translate-x-1/2 z-[200] w-[90%] max-w-sm pointer-events-none"
        x-cloak
    >
        <div 
            :class="{
                'bg-emerald-500 shadow-emerald-500/20': type === 'success',
                'bg-rose-500 shadow-rose-500/20': type === 'error',
                'bg-blue-500 shadow-blue-500/20': type === 'info',
                'bg-amber-500 shadow-amber-500/20': type === 'warning'
            }"
            class="p-4 rounded-2xl shadow-2xl flex items-center gap-3 pointer-events-auto border border-white/20 backdrop-blur-md"
        >
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <template x-if="type === 'success'"><i class="fas fa-check-circle text-white"></i></template>
                <template x-if="type === 'error'"><i class="fas fa-times-circle text-white"></i></template>
                <template x-if="type === 'info'"><i class="fas fa-info-circle text-white"></i></template>
                <template x-if="type === 'warning'"><i class="fas fa-exclamation-triangle text-white"></i></template>
            </div>
            <p class="text-white text-xs font-bold leading-tight" x-text="message"></p>
            <button @click="show = false" class="ml-auto text-white/60 hover:text-white transition-colors">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
