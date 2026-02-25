<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Katalog Produk Modern')</title>
    <meta name="description" content="@yield('meta_description', 'VISTORA adalah katalog produk modern dengan pencarian cepat, kategori lengkap, dan detail produk terbaik.')">
    <meta name="robots" content="@yield('meta_robots', 'index,follow,max-image-preview:large')">
    <meta name="author" content="VISTORA">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="VISTORA">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', 'Katalog Produk Modern')))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', 'VISTORA adalah katalog produk modern dengan pencarian cepat, kategori lengkap, dan detail produk terbaik.')))">
    <meta property="og:url" content="@yield('og_url', trim($__env->yieldContent('canonical', url()->current())))">
    <meta property="og:image" content="@yield('og_image', 'https://picsum.photos/seed/vistora-og/1200/630')">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', trim($__env->yieldContent('title', 'Katalog Produk Modern')))">
    <meta name="twitter:description" content="@yield('twitter_description', trim($__env->yieldContent('meta_description', 'VISTORA adalah katalog produk modern dengan pencarian cepat, kategori lengkap, dan detail produk terbaik.')))">
    <meta name="twitter:image" content="@yield('twitter_image', trim($__env->yieldContent('og_image', 'https://picsum.photos/seed/vistora-og/1200/630')))">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "VISTORA",
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
        }
        [x-cloak] { display: none !important; }
        .slide-transition { transition: transform 0.5s ease-in-out; }
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
    </style>
    @livewireStyles
    @stack('meta')
    @stack('styles')
</head>
<body class="m-0 min-h-screen bg-gray-50 text-gray-800 font-sans antialiased">
    <livewire:public.header />

    <main class="pt-[116px] md:pt-[132px] @yield('main_class')">
        @yield('content')
    </main>

    <livewire:public.footer />

    @livewireScripts
    @stack('scripts')
</body>
</html>
