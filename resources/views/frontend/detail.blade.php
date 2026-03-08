@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Detail Produk - Kataloque')
@section('meta_description', $seoDescription ?? 'Lihat detail produk lengkap, harga, dan link marketplace resmi di Kataloque.')
@section('canonical', $canonical ?? route('katalog'))
@section('og_url', $canonical ?? route('katalog'))
@section('og_image', $ogImage ?? 'https://picsum.photos/seed/kataloque-produk/1200/630')
@section('og_type', 'product')
@section('main_class', '')

@section('content')
    <livewire:public.product-detail :slug="$slug" />
@endsection
