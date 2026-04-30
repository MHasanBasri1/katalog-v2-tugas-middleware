@extends('admin.layouts.app')

@section('title', 'Log Aktivitas')
@section('header', 'Riwayat Aktivitas')

@section('content')
    <div class="space-y-6 pb-8 w-full">
        <!-- Header & Action Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Log Aktivitas Sistem</h2>
                    <p class="text-sm text-gray-500">Memantau seluruh interaksi user dan pengunjung dalam aplikasi.</p>
                </div>
                <div class="flex items-center gap-2" x-data="{ months: 3 }">
                    <form x-ref="clearForm" action="{{ route('admin.logs.clear') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <div class="relative">
                            <select name="months" x-model="months" class="appearance-none bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl pl-4 pr-10 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all cursor-pointer uppercase tracking-wider">
                                <option value="1">1 Bulan</option>
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">12 Bulan</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <i class="ti ti-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="$store.confirm.open({
                                title: 'Bersihkan Log',
                                message: `Hapus semua log yang sudah lebih dari ${months} bulan?`,
                                confirmText: 'Ya, Bersihkan',
                                onConfirm: () => $refs.clearForm.submit()
                            })"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-bold uppercase tracking-wider border border-rose-100 dark:border-rose-900/10 shadow-sm"
                        >
                            <i class="ti ti-trash text-sm"></i>
                            Bersihkan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('admin.logs.index') }}" method="GET" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[300px]" x-data="{ q: '{{ request('q') }}' }">
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Pencarian Log</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
                            <i class="ti ti-search text-xs"></i>
                        </div>
                        <input type="text" name="q" x-model="q" placeholder="Cari deskripsi aktivitas..." 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500 pr-10"
                            style="padding: 0.65rem 2.5rem 0.65rem 44px;">
                        
                        {{-- Clear Button --}}
                        <template x-if="q.length > 0">
                            <button type="button" @click="q = ''; $nextTick(() => $el.closest('form').submit())" class="absolute right-0 top-0 bottom-0 px-3 text-gray-400 hover:text-rose-500 transition-colors">
                                <i class="ti ti-circle-x text-sm"></i>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Filter Peran</label>
                    <select name="role" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium" style="padding: 0.65rem 1rem;">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'role']))
                        <a href="{{ route('admin.logs.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Waktu</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Pemeran</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Aktivitas</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->created_at->format('d M Y') }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ $log->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->role !== 'guest' && $log->role !== 'pengunjung')
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->username }}</span>
                                            <span class="text-[10px] font-black uppercase tracking-tighter text-blue-500">{{ $log->role }}</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pengunjung</span>
                                            <span class="text-[9px] font-medium text-gray-300 dark:text-gray-600">Guest Session</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $log->activity }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-blue-50 dark:bg-blue-900/20 text-blue-600 border border-blue-100 dark:border-blue-900/30">
                                        {{ $log->ip_address ?: 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <i class="ti ti-history text-4xl text-gray-200 dark:text-gray-800 mb-2"></i>
                                    <p class="text-gray-500 italic">Belum ada riwayat aktivitas ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
