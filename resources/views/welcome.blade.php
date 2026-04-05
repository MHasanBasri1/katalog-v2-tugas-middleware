<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-sans">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 border rounded hover:bg-gray-100 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 hover:underline">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 border rounded hover:bg-gray-100 transition">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="w-full max-w-4xl bg-white dark:bg-[#161615] rounded-xl shadow-sm p-8 lg:p-12 flex flex-col items-center text-center">
            <h1 class="text-3xl font-bold mb-4">Welcome to {{ config('app.name') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">Your modern product catalog. Discover amazing items and categories.</p>
            
            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition">Go to Storefront</a>
            </div>
        </main>
    </body>
</html>
