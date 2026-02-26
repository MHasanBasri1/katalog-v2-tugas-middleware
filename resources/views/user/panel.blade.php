@extends('frontend.layouts.app')

@section('title', 'Profil Saya - VISTORA')
@section('meta_description', 'Profil pengguna VISTORA untuk mengelola data akun dan wishlist.')
@section('canonical', route('user.panel'))
@section('og_url', route('user.panel'))

@section('content')
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-primary">Profil Saya</p>
                <h1 class="mt-2 text-2xl sm:text-3xl font-black text-gray-900">Halo, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola nama, email, password, dan wishlist akun Anda.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition">
                    <i class="fas fa-sign-out-alt text-xs"></i>
                    Logout
                </button>
            </form>
        </div>

        @if(session('status'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('status') }}
            </div>
        @endif
        @if(session('status_password'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('status_password') }}
            </div>
        @endif
        @if(session('status_wishlist'))
            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700">
                {{ session('status_wishlist') }}
            </div>
        @endif

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-100 p-4 bg-gray-50">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nama</p>
                <p class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->name }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 p-4 bg-gray-50">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Email</p>
                <p class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <form method="POST" action="{{ route('user.profile.update') }}" class="rounded-2xl border border-gray-100 p-4 bg-white space-y-3">
                @csrf
                @method('PUT')
                <h2 class="text-base font-bold text-gray-900">Ubah Profil</h2>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name', 'profileUpdate')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email', 'profileUpdate')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-blue-700 transition">
                    Simpan Profil
                </button>
            </form>

            <form method="POST" action="{{ route('user.password.update') }}" class="rounded-2xl border border-gray-100 p-4 bg-white space-y-3">
                @csrf
                @method('PUT')
                <h2 class="text-base font-bold text-gray-900">Ubah Password</h2>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('current_password', 'passwordUpdate')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Password Baru</label>
                    <input type="password" name="password" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('password', 'passwordUpdate')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-blue-700 transition">
                    Simpan Password
                </button>
            </form>
        </div>

        <div class="mt-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold text-gray-900">Wishlist Saya</h2>
                <span class="text-xs font-semibold text-gray-500">{{ $wishlistProducts->count() }} produk</span>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                @forelse($wishlistProducts as $product)
                    <div class="rounded-2xl border border-gray-100 p-3 hover:border-blue-200 transition">
                        <div class="aspect-square w-full bg-gray-100 rounded-xl overflow-hidden">
                            <a href="{{ route('produk.detail', $product->slug) }}">
                                @if($product->primaryImage?->image)
                                    <img src="{{ $product->primaryImage->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-box text-2xl"></i>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-gray-800 leading-snug min-h-[2.5rem] break-words">
                            <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                        </p>
                        <p class="text-sm font-bold text-primary mt-1">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('user.wishlist.destroy', $product) }}" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-trash text-[10px]"></i>
                                Hapus Wishlist
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-gray-200 p-4 text-sm text-gray-500">
                        Belum ada produk di wishlist.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
