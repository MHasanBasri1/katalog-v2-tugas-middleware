<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin Kataloque')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            max-width: 100%;
            overflow-x: hidden;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="m-0 min-h-screen flex items-center justify-center text-gray-800 font-['Plus_Jakarta_Sans'] antialiased overflow-x-hidden relative selection:bg-primary/10 selection:text-primary">
    <!-- Glassmorphism Background Elements -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-[#f8fafc]"></div>
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-gradient-to-br from-blue-200/40 to-indigo-200/40 blur-[120px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[10%] w-[35%] h-[35%] rounded-full bg-gradient-to-br from-purple-100/40 to-pink-100/40 blur-[100px]"></div>
        <div class="absolute bottom-[10%] left-[5%] w-[30%] h-[30%] rounded-full bg-gradient-to-br from-cyan-100/40 to-blue-100/40 blur-[110px] animate-bounce" style="animation-duration: 15s"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.015]"></div>
    </div>

    <main class="relative z-10 w-full max-w-full">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
