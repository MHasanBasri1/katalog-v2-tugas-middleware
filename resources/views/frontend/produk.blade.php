@extends('frontend.layouts.app')

@section('title', 'Produk - VISTORA')
@section('meta_description', 'Jelajahi semua produk VISTORA dengan filter kategori dan pencarian cepat untuk menemukan produk terbaik.')
@section('canonical', route('katalog'))
@section('og_url', route('katalog'))
@section('main_class', '')

@section('content')
    <livewire:public.products-page />
@endsection
