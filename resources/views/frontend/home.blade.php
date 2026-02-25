@extends('frontend.layouts.app')

@section('title', 'Beranda - VISTORA')
@section('meta_description', 'Beranda VISTORA: temukan produk terbaru, flash sale, kategori populer, dan promo terbaik setiap hari.')
@section('canonical', route('home'))
@section('og_url', route('home'))
@section('main_class', '')

@section('content')
    <livewire:public.home />
@endsection
