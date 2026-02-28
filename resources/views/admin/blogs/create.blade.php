@extends('admin.layouts.app')

@section('title', 'Tambah Blog')
@section('header', 'Tambah Blog')

@section('content')
    <div class="max-w-4xl">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 md:p-6">
            @include('admin.blogs._form')
        </div>
    </div>
@endsection
