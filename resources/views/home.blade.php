@extends('layouts.app')

@section(
    'title',
    __('public.seo.home_title')
)

@section(
    'meta_description',
    __('public.seo.home_description')
)

@section(
    'body_class',
    'nv-home-body'
)

@section('content')
    <div class="nv-home-page">
        @include('home.partials.hero')

        @include('home.partials.feature-strip')

        @include('home.partials.products')

        @include('home.partials.benefits')

        @include('home.partials.contact')
    </div>

    @include('quotes.partials.modal')
@endsection