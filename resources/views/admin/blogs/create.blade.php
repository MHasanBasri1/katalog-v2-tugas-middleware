@extends('admin.layouts.app')

@section('title', 'Tambah Artikel')
@section('header', 'Tambah Artikel')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">
        <a href="{{ route('admin.blog.index') }}" class="hover:text-blue-600 transition-colors">Blog</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Artikel Baru</span>
    </nav>

    @include('admin.blogs._form')
</div>
@endsection
