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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
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
    <style>
        html, body {
            margin: 0;
            padding: 0;
            max-width: 100%;
            overflow-x: hidden;
        }
        [x-cloak] { display: none !important; }
        .slide-transition { transition: transform 0.5s ease-in-out; }
        .promo-marquee-track {
            display: inline-block;
            min-width: max-content;
            animation: promoMarquee 20s linear infinite;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .header-icon-btn {
            width: 46px;
            height: 46px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #4b5563;
            transition: all 0.2s ease;
        }
        .header-icon-btn:hover {
            background: #e5e7eb;
            color: #2563eb;
        }
        .icon-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            background: #2563eb;
            border: 2px solid #ffffff;
        }
        .blink {
            animation: blinkPulse 1.1s ease-in-out infinite;
        }
        @keyframes blinkPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.25; }
        }
        @keyframes promoMarquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .nav-icon-circle {
            width: 26px;
            height: 26px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eef2f7;
            font-size: 13px;
        }
        .nav-icon-circle.is-active {
            background: #2563eb;
            color: #ffffff;
        }
        .mobile-only {
            display: inline-flex;
        }
        .desktop-only {
            display: none !important;
        }
        @media (min-width: 768px) {
            .mobile-only {
                display: none !important;
            }
            .desktop-only {
                display: inline-flex !important;
            }
        }
        @media (max-width: 767px) {
            .promo-marquee-track {
                animation-duration: 15s;
            }
        }
    </style>
    @livewireStyles
    @stack('meta')
    @stack('styles')
</head>
<body class="m-0 min-h-screen text-gray-800 font-['Plus_Jakarta_Sans'] antialiased overflow-x-hidden relative selection:bg-primary/10 selection:text-primary">
    <!-- Dynamic Glamorphism Background -->
    <div class="fixed inset-0 -z-20 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-[#fbfcfe]"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-gradient-to-br from-blue-400/20 to-indigo-500/20 blur-[130px] animate-pulse"></div>
        <div class="absolute top-[15%] -right-[5%] w-[45%] h-[45%] rounded-full bg-gradient-to-br from-purple-400/15 to-pink-500/15 blur-[120px]" style="animation: pulse 10s infinite"></div>
        <div class="absolute bottom-[5%] left-[10%] w-[40%] h-[40%] rounded-full bg-gradient-to-br from-cyan-400/20 to-blue-500/20 blur-[110px]" style="animation: bounce 20s infinite"></div>
        <div class="absolute top-[40%] left-[20%] w-[30%] h-[30%] rounded-full bg-gradient-to-tr from-indigo-400/10 to-blue-400/10 blur-[100px] animate-pulse"></div>

        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.02]"></div>
        
        <!-- White/Transparent Gradient Overlays for depth -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/20 via-transparent to-white/40"></div>
    </div>

    <livewire:public.header />

    <main class="relative z-10 pt-[96px] md:pt-[132px] pb-20 md:pb-0 @yield('main_class') max-w-full overflow-x-hidden">
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

    @livewireScripts
    @stack('scripts')
    <script>
        window.addEventListener('alert', event => {
            alert(event.detail.message);
        });
    </script>
</body>
</html>
