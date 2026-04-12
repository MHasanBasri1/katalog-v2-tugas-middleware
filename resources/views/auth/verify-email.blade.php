@extends('frontend.layouts.app')

@section('title', 'Verifikasi Email - Kataloque')
@section('meta_description', 'Verifikasi email akun Kataloque Anda untuk mengaktifkan fitur pengguna.')
@section('canonical', route('verification.notice'))
@section('og_url', route('verification.notice'))

@section('content')
<section class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="relative max-w-lg mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-10 relative z-10">
            <div class="inline-flex items-center gap-2 rounded-full bg-primary-light text-primary border border-primary/20 px-3 py-1 text-xs font-bold">
                <i class="fas fa-envelope-open-text text-[10px]"></i>
                Verifikasi Akun
            </div>

            <h1 class="mt-4 text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Cek Email Anda</h1>
            <p class="mt-3 text-sm text-gray-600 font-medium leading-relaxed">
                Kami sudah mengirim link verifikasi ke email akun Anda. Klik link tersebut untuk mengaktifkan fitur akun user seperti profil dan favorit.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    Link verifikasi baru berhasil dikirim. Silakan cek inbox/spam email Anda.
                </div>
            @endif

            @if (session('status') && session('status') !== 'verification-link-sent')
                <div class="mt-5 rounded-xl border border-primary/20 bg-primary-light px-4 py-3 text-sm font-medium text-primary">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mt-6 flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-primary-dark transition shadow-lg shadow-primary/20">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Kirim Ulang Link Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50 transition">
                        <i class="fas fa-right-from-bracket text-xs"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
