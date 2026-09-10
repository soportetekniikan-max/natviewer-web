@extends('layouts.app')

@section(
    'title',
    $seo['title']
)

@section(
    'meta_description',
    $seo['description']
)

@section(
    'canonical',
    $seo['canonical']
)

@section(
    'alternate_es',
    $seo['alternate_es']
)

@section(
    'alternate_en',
    $seo['alternate_en']
)

@section(
    'alternate_default',
    $seo['alternate_default']
)

@if ($seo['image'])
    @section(
        'seo_image',
        $seo['image']
    )
@endif

@section(
    'seo_image_alt',
    $seo['image_alt']
)

@section(
    'og_type',
    'product'
)

@push('head')
    @include(
        'products.partials.schema'
    )
@endpush

@section('content')
    <div
        class="nv-detail-page"
        data-product-detail
    >
        <div
            class="
                container
                nv-detail-shell
            "
        >
            @include(
                'products.partials.breadcrumb'
            )

            @if (session('quote_success'))
                <div
                    class="
                        alert
                        alert-success
                        rounded-4
                        mb-4
                    "
                    role="alert"
                >
                    {{ session('quote_success') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="
                        alert
                        alert-danger
                        rounded-4
                        mb-4
                    "
                    role="alert"
                >
                    {{ __('product.quote_error') }}
                </div>
            @endif

            <section class="nv-detail-hero">
                @include(
                    'products.partials.gallery'
                )

                @include(
                    'products.partials.summary'
                )
            </section>

            @include(
                'products.partials.description'
            )

            @include(
                'products.partials.specifications'
            )

            @include(
                'products.partials.quote-form'
            )
        </div>
    </div>
@endsection