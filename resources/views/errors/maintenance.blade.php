<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs Sedang Dalam Pemeliharaan - {{ $setting->shop_name ?? 'Kataloque' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col items-center justify-center p-6 sm:p-12">
    
    <div class="max-w-xl w-full">
        <!-- Brand Logo/Name -->
        <div class="mb-16 flex flex-col items-center text-center">
            @if($setting->shop_logo)
                <img src="{{ $setting->shop_logo }}" alt="{{ $setting->shop_name }}" class="h-12 w-auto mb-4 opacity-80">
            @else
                <span class="text-xl font-black uppercase tracking-widest text-gray-400">{{ $setting->shop_name ?? 'KATALOQUE' }}</span>
            @endif
        </div>

        <div class="space-y-8">
            <!-- Icon & Status -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-900 dark:bg-white flex items-center justify-center shrink-0">
                    <i class="ti ti-tool text-white dark:text-gray-900 text-2xl"></i>
                </div>
                <div class="h-px flex-1 bg-gray-200 dark:bg-gray-800"></div>
                <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    Maintenance Mode
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight">
                    Sedang Dalam <br class="hidden sm:block"> Pemeliharaan.
                </h1>
                <p class="text-lg text-gray-500 dark:text-gray-400 font-medium leading-relaxed max-w-md">
                    {{ $message }}
                </p>
            </div>

            <!-- Contact/Links -->
            <div class="pt-8 flex flex-wrap items-center gap-6 border-t border-gray-200 dark:border-gray-800">
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Hubungi Kami</p>
                    <div class="flex items-center gap-4">
                        @if($setting->whatsapp)
                            <a href="https://wa.me/{{ $setting->whatsapp }}" target="_blank" class="flex items-center gap-2 text-sm font-bold hover:text-green-500 transition-colors">
                                <i class="ti ti-brand-whatsapp text-lg"></i>
                                <span>WhatsApp</span>
                            </a>
                        @endif
                        @if($setting->email)
                            <a href="mailto:{{ $setting->email }}" class="flex items-center gap-2 text-sm font-bold hover:text-blue-500 transition-colors">
                                <i class="ti ti-mail text-lg"></i>
                                <span>Email</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="sm:ms-auto">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Status Sistem</p>
                    <p class="text-xs font-bold px-3 py-1 bg-gray-100 dark:bg-gray-900 rounded-full inline-block border border-gray-200 dark:border-gray-800">Update Sedang Berjalan</p>
                </div>
            </div>
        </div>

        <!-- Progress Footer (Static Minimalist) -->
        <div class="mt-20">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} {{ $setting->shop_name ?? 'Kataloque' }} &mdash; Limited Access
            </p>
        </div>
    </div>

</body>
</html>
