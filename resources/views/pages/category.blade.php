@extends('layouts.app')

@section('title', $categoryName . ' | Natviewer')
@section('meta_description', $metaDescription)
@section('canonical', $canonicalUrl)
@section('alternate_es', route('categories.show.es', ['category' => $category->slug]))
@section('alternate_en', route('categories.show.en', ['category' => $category->slug]))
@section('alternate_default', route('categories.show.en', ['category' => $category->slug]))

@push('head')
    <script type="application/ld+json">{!! $schemaJson !!}</script>
@endpush

@section('content')
    <div class="nv-home-page nv-category-page">
        <div class="container">
            <nav class="nv-detail-breadcrumb" aria-label="{{ __('product.breadcrumb_home') }}">
                <a href="{{ route('home', ['locale' => $locale]) }}">{{ __('product.breadcrumb_home') }}</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ $categoryName }}</span>
            </nav>

            <header class="nv-category-header">
                <span class="nv-eyebrow">{{ __('catalog.category_label') }}</span>
                <h1>{{ $categoryName }}</h1>
                <p>{{ $categoryDescription }}</p>
                <h2 class="nv-category-count">{{ __('catalog.category_count', ['count' => $productCount]) }}</h2>
            </header>

            <div class="nv-product-showcase-list" id="products">
                @foreach ($catalogItems as $item)
                    @include('home.partials.product-card', ['item' => $item, 'position' => $loop->index, 'prioritizeImage' => $loop->first])
                @endforeach
            </div>
        </div>
    </div>

    @include('quotes.partials.modal')
@endsection
