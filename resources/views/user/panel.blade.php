@extends('frontend.layouts.app')

@section('title', 'Dashboard Member - Kataloque')
@section('meta_description', 'Dashboard member Kataloque untuk mengelola data akun, favorit, dan voucher.')
@section('canonical', route('user.panel'))
@section('og_url', route('user.panel'))

@section('content')
<section class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
    <div class="absolute inset-x-6 top-4 h-48 bg-gradient-to-r from-sky-100 via-blue-100 to-cyan-100 blur-3xl opacity-70 pointer-events-none"></div>

    @php
        $activeTab = request()->query('tab', session('active_tab', 'profil'));
    @endphp
    <div class="relative bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/70 p-6 sm:p-8 md:p-9" x-data="{ tab: '{{ $activeTab }}' }">
        <div class="grid gap-4 md:grid-cols-[1.6fr_1fr] md:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary">Dashboard Member</p>
                <h1 class="mt-2 text-2xl sm:text-3xl font-black text-gray-900">Halo, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola akun, favorit, dan voucher Anda dari satu tempat.</p>
            </div>
            <div class="rounded-2xl border border-blue-100 bg-blue-50/70 px-4 py-3">
                <p class="text-[11px] uppercase tracking-wider font-bold text-blue-700">Status Akun</p>
                <p class="mt-1 text-sm font-semibold text-gray-800">{{ auth()->user()->email }}</p>
                <p class="mt-1 text-xs text-gray-500">Terdaftar sebagai member Kataloque</p>
            </div>
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
        @if(session('status_favorite'))
            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700">
                {{ session('status_favorite') }}
            </div>
        @endif
        @if(session('status_avatar'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('status_avatar') }}
            </div>
        @endif

        <div class="mt-6 flex w-full max-w-sm rounded-xl border border-gray-200 bg-gray-50 p-1">
            <button type="button" @click="tab = 'profil'" :class="tab === 'profil' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-800'" class="flex-1 rounded-lg px-4 py-2 text-sm font-semibold transition">
                <i class="fas fa-user-circle mr-1.5 text-xs"></i>
                Profil
            </button>
            <button type="button" @click="tab = 'favorit'" :class="tab === 'favorit' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-800'" class="flex-1 rounded-lg px-4 py-2 text-sm font-semibold transition">
                <i class="fas fa-heart mr-1.5 text-xs"></i>
                Favorit
            </button>
            <button type="button" @click="tab = 'voucher'" :class="tab === 'voucher' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-800'" class="flex-1 rounded-lg px-4 py-2 text-sm font-semibold transition">
                <i class="fas fa-ticket-alt mr-1.5 text-xs"></i>
                Voucher
            </button>
        </div>

        <div x-show="tab === 'profil'" x-cloak class="mt-6 space-y-6">
            <div class="rounded-2xl border border-gray-100 p-4 sm:p-5 bg-white shadow-sm">
                <h2 class="text-base font-bold text-gray-900">Avatar Profil</h2>
                <p class="mt-1 text-xs text-gray-500">Upload avatar baru (JPG/PNG/WEBP, maks. 2MB) atau hapus untuk kembali ke avatar default Google.</p>

                <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar {{ auth()->user()->name }}" class="h-16 w-16 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="h-16 w-16 rounded-full border border-gray-200 bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-lg">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto space-y-2">
                        <form method="POST" action="{{ route('user.avatar.update') }}" enctype="multipart/form-data" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            @csrf
                            <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="w-full sm:w-auto rounded-xl border-gray-200 bg-gray-50 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-blue-700">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-blue-700 transition">
                                Simpan Avatar
                            </button>
                        </form>
                        @error('avatar', 'avatarUpdate')
                            <p class="text-xs text-rose-500">{{ $message }}</p>
                        @enderror

                        <form method="POST" action="{{ route('user.avatar.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                                Hapus Avatar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-gray-100 p-4 bg-gray-50">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nama</p>
                    <p class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 p-4 bg-gray-50">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Email</p>
                    <p class="mt-1 text-base font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <form method="POST" action="{{ route('user.profile.update') }}" class="rounded-2xl border border-gray-100 p-4 sm:p-5 bg-white space-y-4 shadow-sm">
                    @csrf
                    @method('PUT')
                    <h2 class="text-base font-bold text-gray-900">Ubah Profil</h2>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nama</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400" style="width: 36px;">
                                <i class="fas fa-user text-xs"></i>
                            </span>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none" style="padding: 0.625rem 0.75rem 0.625rem 36px;">
                        </div>
                        @error('name', 'profileUpdate')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400" style="width: 36px;">
                                <i class="fas fa-envelope text-xs"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none" style="padding: 0.625rem 0.75rem 0.625rem 36px;">
                        </div>
                        @error('email', 'profileUpdate')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-blue-700 transition">
                        Simpan Profil
                    </button>
                </form>

                <form method="POST" action="{{ route('user.password.update') }}" class="rounded-2xl border border-gray-100 p-4 sm:p-5 bg-white space-y-4 shadow-sm">
                    @csrf
                    @method('PUT')
                    <h2 class="text-base font-bold text-gray-900">Ubah Password</h2>
                    <div x-data="{ showCurrentPassword: false }">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Password Saat Ini</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400" style="width: 36px;">
                                <i class="fas fa-lock text-xs"></i>
                            </span>
                            <input :type="showCurrentPassword ? 'text' : 'password'" name="current_password" class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none" style="padding: 0.625rem 36px 0.625rem 36px;">
                            <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute inset-y-0 right-0 flex items-center justify-center text-gray-400 hover:text-blue-600 transition" style="width: 36px;" :aria-label="showCurrentPassword ? 'Sembunyikan password saat ini' : 'Tampilkan password saat ini'">
                                <i class="fas text-xs" :class="showCurrentPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('current_password', 'passwordUpdate')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div x-data="{ showNewPassword: false }">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Password Baru</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400" style="width: 36px;">
                                <i class="fas fa-key text-xs"></i>
                            </span>
                            <input :type="showNewPassword ? 'text' : 'password'" name="password" class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none" style="padding: 0.625rem 36px 0.625rem 36px;">
                            <button type="button" @click="showNewPassword = !showNewPassword" class="absolute inset-y-0 right-0 flex items-center justify-center text-gray-400 hover:text-blue-600 transition" style="width: 36px;" :aria-label="showNewPassword ? 'Sembunyikan password baru' : 'Tampilkan password baru'">
                                <i class="fas text-xs" :class="showNewPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password', 'passwordUpdate')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div x-data="{ showPasswordConfirmation: false }">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400" style="width: 36px;">
                                <i class="fas fa-check-double text-xs"></i>
                            </span>
                            <input :type="showPasswordConfirmation ? 'text' : 'password'" name="password_confirmation" class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm focus:border-blue-500 focus:ring-blue-500 outline-none" style="padding: 0.625rem 36px 0.625rem 36px;">
                            <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center justify-center text-gray-400 hover:text-blue-600 transition" style="width: 36px;" :aria-label="showPasswordConfirmation ? 'Sembunyikan konfirmasi password baru' : 'Tampilkan konfirmasi password baru'">
                                <i class="fas text-xs" :class="showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-blue-700 transition">
                        Simpan Password
                    </button>
                </form>
            </div>
        </div>

        <div x-show="tab === 'favorit'" x-cloak class="mt-6" wire:key="tab-favorit-wrapper">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold text-gray-900">Favorit Saya</h2>
                <span class="text-xs font-semibold text-gray-500">{{ $favoriteProducts->count() }} produk</span>
            </div>
            <div class="grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-4 gap-3">
                @forelse($favoriteProducts as $product)
                    <div class="rounded-2xl border border-gray-100 p-3 hover:border-blue-200 transition bg-white">
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
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-sm font-bold text-primary">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                        </div>
                        
                        <form action="{{ route('user.favorite.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-100 transition"
                            >
                                <i class="fas fa-trash text-[10px]"></i>
                                <span>Hapus Favorit</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-gray-200 p-8 text-center bg-gray-50">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100">
                            <i class="fas fa-heart text-gray-200 text-xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium text-sm">Belum ada produk di favorit.</p>
                        <a href="{{ route('katalog') }}" class="inline-block mt-4 text-xs font-bold text-primary hover:underline">Jelajahi Produk</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="tab === 'voucher'" x-cloak class="mt-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold text-gray-900">Voucher Saya</h2>
                <span class="text-xs font-semibold text-gray-500">{{ $vouchers->count() }} voucher tersedia</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($vouchers as $voucher)
                    <div class="group relative flex flex-col sm:flex-row sm:items-center rounded-2xl border border-gray-100 bg-white p-4 shadow-sm hover:shadow-md transition-all overflow-hidden gap-4">
                        <div class="absolute top-0 right-0 h-16 w-16 bg-blue-50 rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <div class="relative shrink-0 flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                                <i class="fas fa-ticket-alt text-xl sm:text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider">
                                        {{ $voucher->code }}
                                    </span>
                                    @if($voucher->end_date)
                                        <span class="text-[10px] font-bold text-gray-400">Exp: {{ $voucher->end_date->format('d M Y') }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-1 text-sm font-bold text-gray-900 truncate">{{ $voucher->name }}</h3>
                                <p class="text-[11px] text-gray-500 line-clamp-1 italic">{{ $voucher->description }}</p>
                            </div>
                        </div>

                        <div class="shrink-0 flex sm:flex-col items-center sm:items-end justify-between sm:justify-center pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100" x-data="{ isClaimed: {{ in_array($voucher->id, $claimedVoucherIds) ? 'true' : 'false' }} }">
                            <p class="text-sm sm:text-base font-black text-blue-600">
                                @if($voucher->type === 'percentage')
                                    {{ number_format($voucher->value, 0) }}% OFF
                                @else
                                    Rp {{ number_format($voucher->value/1000, 0) }}K OFF
                                @endif
                            </p>
                            <button 
                                @click="
                                    navigator.clipboard.writeText('{{ $voucher->code }}'); 
                                    if(!isClaimed) {
                                        isClaimed = true; // Set to true immediately to prevent double clicks
                                        fetch('{{ route('user.voucher.claim', $voucher->code) }}', { 
                                            method: 'POST', 
                                            headers: { 
                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 
                                                'Content-Type': 'application/json', 
                                                'Accept': 'application/json' 
                                            } 
                                        }).catch(e => {
                                            isClaimed = false; // Reset if fetch fails
                                            console.error(e);
                                        });
                                    }
                                    window.dispatchEvent(new CustomEvent('alert', { detail: { message: 'Kode voucher {{ $voucher->code }} berhasil disalin!', type: 'success' } }))
                                " 
                                :class="isClaimed ? 'text-emerald-500 hover:text-emerald-600' : 'text-gray-400 hover:text-blue-600'"
                                class="text-[10px] font-bold transition uppercase tracking-widest sm:mt-2"
                            >
                                <span x-text="isClaimed ? '✓ Sudah Disalin' : 'Salin Kode'"></span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-gray-200 p-8 text-center bg-gray-50">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100">
                            <i class="fas fa-ticket-alt text-gray-200 text-xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium text-sm">Belum ada voucher tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition">
                    <i class="fas fa-sign-out-alt text-xs"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
