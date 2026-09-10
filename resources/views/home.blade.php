@extends('layouts.app')

@section(
    'title',
    __('public.seo.home_title')
)

@section(
    'meta_description',
    __('public.seo.home_description')
)

@section('content')
    @include('home.partials.hero')

    @include('home.partials.feature-strip')

    @include('home.partials.products')

    @include('home.partials.benefits')

    @include('home.partials.contact')

    @include('quotes.partials.modal')
@endsection