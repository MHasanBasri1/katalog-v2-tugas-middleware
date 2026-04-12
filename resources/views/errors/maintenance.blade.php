<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Sedang Diperbarui - {{ $setting->shop_name ?? 'Kataloque' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex items-center justify-center p-6 overflow-hidden relative">
    <!-- Abstract Background -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-rose-400/20 rounded-full blur-[120px]"></div>

    <div class="max-w-xl w-full relative z-10 text-center">
        <!-- Brand Icon -->
        <div class="mb-12 flex justify-center">
            <div class="w-24 h-24 rounded-[2.5rem] bg-white shadow-2xl flex items-center justify-center animate-float group transition-all duration-500 hover:rotate-6">
                <i class="ti ti-tool text-4xl text-blue-600 group-hover:scale-125 transition-transform"></i>
            </div>
        </div>

        <div class="glass p-10 md:p-14 rounded-[3rem] shadow-2xl shadow-blue-500/5 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-rose-600"></div>
            
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                Sebentar ya, Kami Sedang Bersolek ✨
            </h1>
            
            <div class="space-y-6">
                <p class="text-base md:text-lg text-slate-600 font-medium leading-relaxed">
                    {{ $message }}
                </p>

                <div class="pt-8 border-t border-slate-200/50">
                    <div class="flex items-center justify-center gap-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">
                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                        Status: Sistem Update In Progress
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-12 space-y-4">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Hubungi kami melalui</p>
            <div class="flex items-center justify-center gap-4">
                @if($setting->whatsapp)
                <a href="https://wa.me/{{ $setting->whatsapp }}" target="_blank" class="w-12 h-12 rounded-2xl bg-white shadow-xl flex items-center justify-center text-slate-400 hover:text-emerald-500 hover:scale-110 transition-all duration-300">
                    <i class="ti ti-brand-whatsapp text-xl"></i>
                </a>
                @endif
                @if($setting->email)
                <a href="mailto:{{ $setting->email }}" class="w-12 h-12 rounded-2xl bg-white shadow-xl flex items-center justify-center text-slate-400 hover:text-rose-500 hover:scale-110 transition-all duration-300">
                    <i class="ti ti-mail text-xl"></i>
                </a>
                @endif
            </div>
            <p class="mt-8 text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                &copy; {{ date('Y') }} {{ $setting->shop_name ?? 'Kataloque' }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
