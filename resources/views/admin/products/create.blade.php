@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('header', 'Tambah Produk')

@section('content')
    <div class="max-w-5xl">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            @include('admin.products._form', ['produk' => null])
        </div>
    </div>
@endsection
