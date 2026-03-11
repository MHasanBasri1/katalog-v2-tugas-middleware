@extends('frontend.layouts.app')

@section('title', 'Katalog Produk - Kataloque')
@section('meta_description', 'Eksplorasi seluruh katalog produk di Kataloque. Cari dan temukan berbagai produk pilihan dengan mudah dan cepat.')
@section('canonical', route('katalog'))
@section('og_url', route('katalog'))
@section('main_class', '')

@section('content')
    <livewire:public.products-page />
@endsection
