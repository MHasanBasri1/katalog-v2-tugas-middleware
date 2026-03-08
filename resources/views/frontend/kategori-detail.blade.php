@extends('frontend.layouts.app')

@section('title', $seoTitle ?? 'Kategori Produk - Kataloque')
@section('meta_description', $seoDescription ?? 'Lihat produk berdasarkan kategori pilihan di Kataloque.')
@section('canonical', $canonical ?? route('kategori'))
@section('og_url', $canonical ?? route('kategori'))
@section('og_image', $ogImage ?? 'https://picsum.photos/seed/kataloque-kategori/1200/630')
@section('main_class', '')

@section('content')
    <livewire:public.category-detail-page :slug="$slug" />
@endsection
