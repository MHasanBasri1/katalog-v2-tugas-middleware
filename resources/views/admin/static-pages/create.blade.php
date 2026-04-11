@extends('admin.layouts.app')

@section('title', 'Tambah Halaman')
@section('header', 'Tambah Halaman')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">
        <a href="{{ route('admin.halaman-statis.index') }}" class="hover:text-blue-600 transition-colors">Halaman Statis</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Halaman Baru</span>
    </nav>

    @include('admin.static-pages._form')
</div>
@endsection
