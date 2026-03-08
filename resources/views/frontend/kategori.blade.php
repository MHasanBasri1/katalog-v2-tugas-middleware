@extends('frontend.layouts.app')

@section('title', 'Kategori Produk - Kataloque')
@section('meta_description', 'Lihat semua kategori produk Kataloque untuk menemukan produk sesuai kebutuhan Anda.')
@section('canonical', route('kategori'))
@section('og_url', route('kategori'))
@section('main_class', '')

@section('content')
    <livewire:public.categories-page />
@endsection
