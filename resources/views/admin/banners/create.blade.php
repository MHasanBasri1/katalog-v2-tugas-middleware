@extends('admin.layouts.app')

@section('title', 'Tambah Banner')
@section('header', 'Tambah Banner')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.banner.index') }}" class="hover:text-blue-600 transition-colors">Banner</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Banner Baru</span>
    </nav>

    @include('admin.banners._form')
</div>
@endsection
