@extends('frontend.layouts.app')

@section('title', 'Verifikasi Email - VISTORA')
@section('meta_description', 'Verifikasi email akun VISTORA Anda untuk mengaktifkan fitur pengguna.')
@section('canonical', route('verification.notice'))
@section('og_url', route('verification.notice'))

@section('content')
<section class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="absolute inset-x-6 top-4 h-44 bg-gradient-to-r from-emerald-100 via-blue-100 to-cyan-100 blur-3xl opacity-70 pointer-events-none"></div>

    <div class="relative bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/60 p-6 sm:p-8 md:p-10">
        <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1 text-xs font-bold">
            <i class="fas fa-envelope-open-text text-[10px]"></i>
            Verifikasi Akun
        </div>

        <h1 class="mt-4 text-2xl sm:text-3xl font-black text-gray-900">Cek Email Anda</h1>
        <p class="mt-3 text-sm sm:text-base text-gray-600 leading-relaxed max-w-2xl">
            Kami sudah mengirim link verifikasi ke email akun Anda. Klik link tersebut untuk mengaktifkan fitur akun user seperti profil dan wishlist.
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                Link verifikasi baru berhasil dikirim. Silakan cek inbox/spam email Anda.
            </div>
        @endif

        @if (session('status') && session('status') !== 'verification-link-sent')
            <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    <i class="fas fa-paper-plane text-xs"></i>
                    Kirim Ulang Link Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-right-from-bracket text-xs"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
