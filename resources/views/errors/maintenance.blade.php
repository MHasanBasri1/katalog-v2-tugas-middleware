<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Dalam Perbaikan - {{ $setting->shop_name ?? 'Kataloque' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f9fafb;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-6">

    <div class="w-full max-w-[480px] text-center">
        <!-- Logo/Brand -->
        <div class="mb-10">
            @if($setting->shop_logo)
                <img src="{{ $setting->shop_logo }}" alt="{{ $setting->shop_name }}" class="h-10 mx-auto object-contain">
            @else
                <span
                    class="text-xl font-black uppercase tracking-tighter text-gray-900">{{ $setting->shop_name ?? 'KATALOQUE' }}</span>
            @endif
        </div>

        <!-- Main Card (Style Beranda) -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8 sm:p-12">
            <div class="flex items-center justify-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center border border-blue-100">
                    <i class="ti ti-tool text-blue-600 text-3xl"></i>
                </div>
            </div>

            <div class="space-y-4">
                <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-gray-900">
                    Sistem Sedang <br> Diperbarui
                </h1>

                <div class="w-8 h-1 bg-blue-600 mx-auto rounded-full"></div>

                <p class="text-sm text-gray-500 font-medium leading-relaxed">
                    {{ $message }}
                </p>
            </div>

            <!-- Action/Support -->
            <div class="mt-10 pt-8 border-t border-gray-100 space-y-4 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Hubungi CS Kami</p>
                <div class="flex items-center justify-center gap-6">
                    @if($setting->whatsapp)
                        <a href="https://wa.me/{{ $setting->whatsapp }}" target="_blank"
                            class="text-gray-900 font-extrabold text-xs hover:text-blue-600 transition-colors uppercase tracking-tight">
                            WhatsApp
                        </a>
                    @endif
                    @if($setting->email)
                        <a href="mailto:{{ $setting->email }}"
                            class="text-gray-900 font-extrabold text-xs hover:text-blue-600 transition-colors uppercase tracking-tight">
                            Email
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- System Status Footer -->
        <div class="mt-10">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">
                &copy; {{ date('Y') }} {{ $setting->shop_name ?? 'Kataloque' }}
            </p>

        </div>
    </div>

</body>

</html>