<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- SEO Optimization --}}
    <title>@yield('title', ($setting->seo_settings['seo_title'] ?? $setting->shop_name ?? 'Kataloque') . ' - Katalog Produk Modern')</title>
    <meta name="description" content="@yield('meta_description', $setting->shop_description ?? 'Kataloque adalah katalog produk modern dengan pencarian cepat.')">
    <meta name="keywords" content="@yield('meta_keywords', $setting->seo_settings['seo_keywords'] ?? 'katalog, belanja, ecommerce')">
    <meta name="robots" content="@yield('meta_robots', $setting->seo_settings['robots'] ?? 'index, follow')">
    <meta name="author" content="@yield('meta_author', $setting->seo_settings['author'] ?? $setting->shop_name ?? 'Kataloque')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="theme-color" content="#2563eb">

    {{-- Search Console Verifications --}}
    @if($setting && isset($setting->seo_settings['google_verification'])) <meta name="google-site-verification" content="{{ $setting->seo_settings['google_verification'] }}"> @endif
    @if($setting && isset($setting->seo_settings['bing_verification'])) <meta name="msvalidate.01" content="{{ $setting->seo_settings['bing_verification'] }}"> @endif
    @if($setting && isset($setting->seo_settings['yandex_verification'])) <meta name="yandex-verification" content="{{ $setting->seo_settings['yandex_verification'] }}"> @endif

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $setting->shop_name ?? 'Kataloque' }}">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', ($setting->seo_settings['seo_title'] ?? $setting->shop_name ?? 'Kataloque'))))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', $setting->shop_description ?? 'Kataloque adalah katalog produk modern.')))">
    <meta property="og:url" content="@yield('og_url', trim($__env->yieldContent('canonical', url()->current())))">
    <meta property="og:image" content="@yield('og_image', ($setting->seo_settings['og_image'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200&h=630&auto=format&fit=crop'))">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter --}}
    <meta name="twitter:card" content="@yield('twitter_card', $setting->seo_settings['twitter_card'] ?? 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', trim($__env->yieldContent('title', ($setting->seo_settings['seo_title'] ?? $setting->shop_name ?? 'Kataloque'))))">
    <meta name="twitter:description" content="@yield('twitter_description', trim($__env->yieldContent('meta_description', $setting->shop_description ?? 'Kataloque adalah katalog produk modern.')))">
    <meta name="twitter:image" content="@yield('twitter_image', trim($__env->yieldContent('og_image', ($setting->seo_settings['og_image'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200&h=630&auto=format&fit=crop'))))">
    
    <link rel="icon" type="image/x-icon" href="{{ $setting->favicon ?? asset('favicon.ico') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <!-- Swiper 11.2.10 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    {{-- High-performance font loading --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "Organization",
                "@@id": "{{ url('/') }}/#organization",
                "name": "{{ $setting->shop_name ?? 'Kataloque' }}",
                "url": "{{ url('/') }}",
                "logo": {
                    "@@type": "ImageObject",
                    "url": "{{ str_starts_with($setting->shop_logo ?? '', 'http') ? $setting->shop_logo : url($setting->shop_logo ?? 'logo.png') }}"
                },
                "contactPoint": {
                    "@@type": "ContactPoint",
                    "telephone": "{{ $setting->phone ?? $setting->whatsapp ?? '' }}",
                    "contactType": "customer service",
                    "areaServed": "ID",
                    "availableLanguage": "Indonesian"
                },
                "sameAs": [
                    @if($setting->social_media)
                        @foreach($setting->social_media as $index => $social)
                            "{{ $social['username'] }}"{{ $index < count($setting->social_media) - 1 ? ',' : '' }}
                        @endforeach
                    @endif
                ]
            },
            {
                "@@type": "WebSite",
                "@@id": "{{ url('/') }}/#website",
                "url": "{{ url('/') }}",
                "name": "{{ $setting->shop_name ?? 'Kataloque' }}",
                "publisher": { "@@id": "{{ url('/') }}/#organization" },
                "potentialAction": {
                    "@@type": "SearchAction",
                    "target": "{{ route('katalog') }}?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            }
        ]
    }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <style>
        @font-face {
            font-family: 'Font Awesome 6 Free';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2") format("woff2"),
                 url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.ttf") format("truetype");
        }
        @font-face {
            font-family: 'Font Awesome 6 Brands';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2") format("woff2"),
                 url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.ttf") format("truetype");
        }
        .mobile-only { display: inline-flex; }
        .desktop-only { display: none !important; }
        @media (min-width: 768px) {
            .mobile-only { display: none !important; }
            .desktop-only { display: inline-flex !important; }
        }
        [x-cloak] { display: none !important; }
        /* Performance: CSS containment for product cards */
        .product-card {
            
        }
        /* Performance: reduce compositing layers on mobile nav */
        .mobile-nav-blur {
            -webkit-backdrop-filter: saturate(180%) blur(10px);
            backdrop-filter: saturate(180%) blur(10px);
        }
        /* Performance: GPU-accelerated image hover */
        .img-hover-scale {
            will-change: transform;
            transform: translateZ(0);
        }
    </style>
    @livewireStyles
    @stack('meta')
    @stack('styles')

    {{-- System Marketing & Tracking --}}
    @if($setting && isset($setting->system_settings['google_analytics_id']))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->system_settings['google_analytics_id'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $setting->system_settings['google_analytics_id'] }}');
        </script>
    @endif

    @if($setting && isset($setting->system_settings['facebook_pixel_id']))
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $setting->system_settings['facebook_pixel_id'] }}');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $setting->system_settings['facebook_pixel_id'] }}&ev=PageView&noscript=1"/></noscript>
    @endif
</head>
<body class="m-0 min-h-screen text-gray-800 font-['Plus_Jakarta_Sans'] antialiased overflow-x-hidden relative selection:bg-primary/10 selection:text-primary">
    {{-- Announcement Bar Integration --}}
    @if($setting && ($setting->system_settings['announcement_enabled'] ?? false))
        <div class="relative bg-gradient-to-r from-amber-500 to-amber-600 text-white p-2.5 text-center overflow-hidden z-[200]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-center gap-3">
                <span class="inline-flex items-center gap-2 group cursor-default">
                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                    <span class="text-[11px] font-black uppercase tracking-widest">{!! $setting->system_settings['announcement_text'] !!}</span>
                </span>
                @if(!empty($setting->system_settings['announcement_url']))
                    <a href="{{ $setting->system_settings['announcement_url'] }}" class="inline-flex items-center gap-1.5 bg-white/20 hover:bg-white/30 backdrop-blur-md px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-300 hover:scale-105 active:scale-95 border border-white/20">
                        Cek Sekarang <i class="fas fa-arrow-right text-[8px]"></i>
                    </a>
                @endif
            </div>
            <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-black/5 rounded-full blur-2xl"></div>
        </div>
    @endif

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:z-[9999] focus:p-4 focus:bg-white focus:text-primary" aria-label="Skip to content">Skip to content</a>
    <!-- Clean Retail Background -->
    <div class="fixed inset-0 -z-20 bg-gray-50/50 pointer-events-none"></div>

    @if(auth()->check() && auth()->user()->hasRole('admin'))
        <div class="fixed top-0 left-0 right-0 z-[200] bg-gray-900 text-white text-[11px] font-bold py-1.5 px-4 backdrop-blur-md border-b border-white/10 flex justify-between items-center h-[32px]">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 px-2 py-0.5 bg-rose-500 rounded text-[9px] uppercase tracking-wider shadow-lg shadow-rose-500/20">
                    <i class="fas fa-user-shield"></i>
                    Admin Mode
                </div>
                <div class="hidden md:flex items-center gap-4 text-gray-400">
                    <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                    <span>Halaman: {{ Str::limit(View::getSection('title', 'Beranda'), 30) }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors flex items-center gap-1.5 px-3 py-1 rounded-md hover:bg-white/5">
                    <i class="fas fa-tachometer-alt"></i> Kembalike Panel Admin
                </a>
                <span class="text-gray-700">|</span>
                <span class="text-gray-400">Hi, {{ auth()->user()->name }}</span>
            </div>
        </div>
        <style>
            header { top: 32px !important; }
            .fixed.top-0.left-0.right-0.z-\[100\] { transform: translateY(32px) !important; }
            .isTopBarHidden { transform: translateY(-1px) !important; }
            main { padding-top: calc(95px + 32px) !important; }
            @media (min-width: 768px) {
                main { padding-top: calc(147px + 32px) !important; }
            }
        </style>
    @endif

    <livewire:public.header />

    <main id="main-content" class="relative z-10 pt-[95px] md:pt-[147px] pb-20 md:pb-0 @yield('main_class') max-w-full overflow-x-hidden">
        @yield('content')
    </main>

    <livewire:public.footer />

    <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-[90] bg-white/95 mobile-nav-blur border-t border-gray-100 px-4 py-2 pb-safe shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
        <div class="flex items-center justify-around gap-2" role="navigation" aria-label="Menu Navigasi Mobile">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-500' }}" aria-label="Halaman Beranda">
                <i class="fas fa-home text-lg" aria-hidden="true"></i>
                <span class="text-[10px] font-bold">Beranda</span>
            </a>
            <a href="{{ route('katalog') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->routeIs('katalog') ? 'text-primary' : 'text-gray-500' }}" aria-label="Lihat Katalog Produk">
                <i class="fas fa-th-large text-lg" aria-hidden="true"></i>
                <span class="text-[10px] font-bold">Katalog</span>
            </a>
            <a href="{{ auth()->check() ? route('user.panel', ['tab' => 'favorit']) : route('user.login') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->query('tab') === 'favorit' || request()->is('user/favorites*') ? 'text-primary' : 'text-gray-500' }}" aria-label="Produk Favorit Saya">
                <i class="fas fa-heart text-lg" aria-hidden="true"></i>
                <span class="text-[10px] font-bold">Favorit</span>
            </a>
            <a href="{{ auth()->check() ? (auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('user.panel')) : route('user.login') }}" class="flex flex-col items-center gap-1 p-2 {{ request()->is('profil-saya*') || request()->routeIs('user.panel') ? 'text-primary' : 'text-gray-500' }}" aria-label="Profil Akun Saya">
                @if(auth()->check())
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar Pengguna" class="w-6 h-6 rounded-full object-cover border border-gray-200" loading="lazy">
                    @else
                        <i class="fas fa-user-circle text-lg" aria-hidden="true"></i>
                    @endif
                @else
                    <i class="fas fa-user-circle text-lg" aria-hidden="true"></i>
                @endif
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

    @php
        $whatsapp_setting = \Illuminate\Support\Facades\Cache::remember('public.whatsapp_setting', 600, function() {
            return \App\Models\Setting::query()->select('whatsapp')->first()?->whatsapp;
        });
    @endphp

    @if($whatsapp_setting)
    <!-- Floating Agents Care -->
    <div 
        x-data="{ show: false }" 
        x-init="setTimeout(() => show = true, 1000)" 
        x-show="show" 
        x-transition:enter="transition ease-out duration-700" 
        x-transition:enter-start="opacity-0 translate-y-10 scale-90" 
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="fixed bottom-[85px] right-6 z-[100] md:bottom-10 group"
    >
        <a 
            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp_setting) }}" 
            target="_blank" 
            rel="noopener noreferrer"
            class="relative flex items-center gap-3 bg-white/80 backdrop-blur-xl border border-white/40 p-2 pr-6 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] hover:shadow-[0_20px_60px_rgba(37,211,102,0.25)] transition-all duration-500 group hover:-translate-y-2"
        >
            <div class="relative">
                <div class="absolute inset-0 bg-[#25D366] rounded-xl animate-ping opacity-20 group-hover:opacity-40"></div>
                <div class="relative flex items-center justify-center w-12 h-12 bg-gradient-to-br from-[#25D366] to-[#128C7E] text-white rounded-xl shadow-lg shadow-[#25D366]/30 group-hover:rotate-[360deg] transition-transform duration-700">
                    <i class="fab fa-whatsapp text-2xl"></i>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.1em] leading-none mb-1">Kataloque Care</span>
                <span class="text-sm font-extrabold text-gray-800 leading-none">Chat Sekarang</span>
            </div>
            
            <!-- Hover Glow Effect -->
            <div class="absolute -inset-px bg-gradient-to-r from-[#25D366]/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity -z-10"></div>
        </a>
    </div>
    @endif

    @livewireScripts
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trending Keywords Swiper
            new Swiper('.trending-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 8,
                freeMode: true,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 16
            });
        });

        // Re-init for Livewire navigation if needed
        document.addEventListener('livewire:navigated', function() {
            new Swiper('.trending-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 8,
                freeMode: true,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 16
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
