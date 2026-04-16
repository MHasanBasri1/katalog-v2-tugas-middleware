@extends('admin.layouts.app')

@section('title', 'Tambah Voucher')
@section('header', 'Tambah Voucher')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.voucher.index') }}" class="hover:text-blue-600 transition-colors">Voucher</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Voucher Baru</span>
    </nav>

    @include('admin.vouchers._form')
</div>
@endsection
