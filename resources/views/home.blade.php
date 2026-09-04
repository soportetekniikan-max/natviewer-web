@extends('layouts.app')

@section('title', __('public.seo.home_title'))
@section('meta_description', __('public.seo.home_description'))

@php
    /*
    |--------------------------------------------------------------------------
    | Helpers de presentación
    |--------------------------------------------------------------------------
    |
    | Por ahora viven en esta vista para mantener esta fase simple.
    | Más adelante podremos moverlos a ViewModels / Presenters si crece
    | la lógica de presentación del catálogo.
    |
    */

    $localized = static function ($model, string $field) use ($locale) {
        if (! $model) {
            return null;
        }

        $localizedField = $field . '_' . $locale;
        $fallbackField = $field . '_es';

        return $model->{$localizedField}
            ?: $model->{$fallbackField}
            ?: null;
    };

    $variantShortLabel = static function ($variant) use ($localized) {
        $specifications = $variant->specifications ?? [];

        $magnification = $specifications['magnification'] ?? null;
        $objectiveDiameter = $specifications['objective_diameter'] ?? null;

        if ($magnification && $objectiveDiameter) {
            $magnification = preg_replace('/x$/i', '', trim($magnification));
            $objectiveDiameter = preg_replace('/\s*mm$/i', '', trim($objectiveDiameter));

            return $magnification . '×' . $objectiveDiameter;
        }

        return $localized($variant, 'name');
    };

    $formatPrice = static function ($variant) {
        if ($variant->price === null) {
            return null;
        }

        return $variant->currency . ' ' . number_format(
            (float) $variant->price,
            0,
            ',',
            '.'
        );
    };

    $formatStock = static function ($variant) use ($locale) {
        if (
            $variant->manage_stock
            && $variant->stock_quantity !== null
        ) {
            if ($variant->stock_quantity > 0) {
                return $locale === 'en'
                    ? $variant->stock_quantity . ' units available'
                    : $variant->stock_quantity . ' unidades disponibles';
            }

            return $locale === 'en'
                ? 'Out of stock'
                : 'Agotado';
        }

        return match ($variant->stock_status) {
            'in_stock' => $locale === 'en'
                ? 'Available'
                : 'Disponible',

            'out_of_stock' => $locale === 'en'
                ? 'Out of stock'
                : 'Agotado',

            'backorder' => $locale === 'en'
                ? 'Available on request'
                : 'Disponible bajo pedido',

            default => null,
        };
    };

    /*
    |--------------------------------------------------------------------------
    | Producto destacado
    |--------------------------------------------------------------------------
    */

    $featuredProduct = $products->first();

    $featuredVariants = $featuredProduct
        ? $featuredProduct->variants
        : collect();

    $firstHeroVariant = $featuredVariants->get(0);
    $secondHeroVariant = $featuredVariants->get(1);

    $heroGlass = $firstHeroVariant
        ? data_get($firstHeroVariant->specifications, 'glass')
        : null;

    $defaultCurrency = $contactSettings?->default_currency ?? 'COP';

    /*
    |--------------------------------------------------------------------------
    | Catálogo plano
    |--------------------------------------------------------------------------
    |
    | Transformamos producto -> variantes en una colección que permite
    | generar una tarjeta comercial por cada variante.
    |
    */

    $catalogVariants = $products->flatMap(function ($product) {
        return $product->variants->map(function ($variant) use ($product) {
            return [
                'product' => $product,
                'variant' => $variant,
            ];
        });
    });
@endphp

@section('content')
    <section class="nv-home-hero">
        <div class="container">
            <div class="nv-hero-grid">
                <div class="nv-hero-copy">
                    <span class="nv-eyebrow">
                        {{ __('public.hero.eyebrow') }}
                    </span>

                    <h1>
                        {{ __('public.hero.title') }}
                    </h1>

                    <p class="nv-hero-lead">
                        {{ __('public.hero.text') }}
                    </p>

                    <div class="nv-hero-tags">
                        @if ($firstHeroVariant)
                            <span>
                                {{ $variantShortLabel($firstHeroVariant) }}
                            </span>
                        @endif

                        @if ($secondHeroVariant)
                            <span>
                                {{ $variantShortLabel($secondHeroVariant) }}
                            </span>
                        @endif

                        @if ($heroGlass)
                            <span>
                                {{ $heroGlass }}
                            </span>
                        @endif

                        <span>Outdoor</span>
                    </div>

                    <div class="nv-hero-actions">
                        <a
                            href="#products"
                            class="nv-button nv-button-primary"
                        >
                            {{ __('public.hero.primary_button') }}
                        </a>

                        <a
                            href="#contact"
                            class="nv-button nv-button-outline"
                        >
                            {{ __('public.hero.secondary_button') }}
                        </a>
                    </div>
                </div>

                <aside class="nv-hero-showcase">
                    <div class="nv-hero-showcase-header">
                        <span>
                            {{ __('public.hero.card_label') }}
                        </span>

                        <strong>
                            {{ $featuredProduct
                                ? $localized($featuredProduct, 'name')
                                : 'Falco UD' }}
                        </strong>
                    </div>

                    <div class="nv-hero-showcase-brand">
                        <img
                            src="{{ asset('images/logo-natviewer-white.png') }}"
                            alt="Natviewer"
                            class="nv-hero-showcase-logo"
                        >

                        <div>
                            <span>
                                {{ __('public.hero.panel_kicker') }}
                            </span>

                            <h2>
                                {{ $featuredProduct
                                    ? $localized($featuredProduct, 'short_description')
                                    : __('public.hero.text') }}
                            </h2>
                        </div>
                    </div>

                    <div class="nv-hero-showcase-specs">
                        <div>
                            <strong>
                                {{ $firstHeroVariant
                                    ? $variantShortLabel($firstHeroVariant)
                                    : '8×42' }}
                            </strong>

                            <span>
                                {{ __('public.hero.spec_1') }}
                            </span>
                        </div>

                        <div>
                            <strong>
                                {{ $secondHeroVariant
                                    ? $variantShortLabel($secondHeroVariant)
                                    : '10×42' }}
                            </strong>

                            <span>
                                {{ __('public.hero.spec_2') }}
                            </span>
                        </div>

                        <div>
                            <strong>
                                {{ $defaultCurrency }}
                            </strong>

                            <span>
                                {{ __('public.hero.spec_3') }}
                            </span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section
        class="nv-home-strip"
        id="specs"
    >
        <div class="container">
            <div class="nv-strip-grid">
                <div>
                    <strong>
                        {{ $heroGlass ?: 'UD' }}
                    </strong>

                    <span>
                        {{ __('public.strip.item_1') }}
                    </span>
                </div>

                <div>
                    <strong>Coated</strong>

                    <span>
                        {{ __('public.strip.item_2') }}
                    </span>
                </div>

                <div>
                    <strong>3 m</strong>

                    <span>
                        {{ __('public.strip.item_3') }}
                    </span>
                </div>

                <div>
                    <strong>Outdoor</strong>

                    <span>
                        {{ __('public.strip.item_4') }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section
        class="nv-home-section"
        id="products"
    >
        <div class="container">
            <div class="nv-section-header nv-section-header-split">
                <div>
                    <span class="nv-eyebrow">
                        {{ __('public.products.eyebrow') }}
                    </span>

                    <h2>
                        {{ __('public.products.title') }}
                    </h2>
                </div>

                <p>
                    {{ __('public.products.text') }}
                </p>
            </div>

            <div class="row g-4">
                @forelse ($catalogVariants as $catalogItem)
                    @php
                        $product = $catalogItem['product'];
                        $variant = $catalogItem['variant'];

                        $price = $formatPrice($variant);
                        $stock = $formatStock($variant);

                        $variantName = $localized($variant, 'name');

                        $productTitle = trim(
                            ($product->brand?->name ?? 'Natviewer')
                            . ' '
                            . $variantName
                        );

                        $categoryName = $localized(
                            $product->category,
                            'name'
                        );

                        $productDescription = $localized(
                            $product,
                            'short_description'
                        );

                        $mediaClass = $loop->index % 2 === 0
                            ? 'nv-product-media-green'
                            : 'nv-product-media-dark';
                    @endphp

                    <div class="col-lg-6">
                        <article
                            class="nv-product-card"
                            data-product-id="{{ $product->id }}"
                            data-variant-id="{{ $variant->id }}"
                            data-sku="{{ $variant->sku }}"
                        >
                            <div class="nv-product-media {{ $mediaClass }}">
                                <div class="nv-product-media-header">
                                    <span>
                                        {{ $variantShortLabel($variant) }}
                                    </span>

                                    <span>
                                        {{ $localized($product, 'name') }}
                                    </span>
                                </div>

                                <div class="nv-product-media-content">
                                    <strong>
                                        {{ $variantName }}
                                    </strong>

                                    <p>
                                        {{ $productDescription }}
                                    </p>
                                </div>
                            </div>

                            <div class="nv-product-body">
                                <span class="nv-product-category">
                                    {{ $categoryName }}
                                </span>

                                <h3>
                                    {{ $productTitle }}
                                </h3>

                                <p>
                                    {{ $productDescription }}
                                </p>

                                <div class="nv-product-data">
                                    <div>
                                        <strong>
                                            {{ $price ?: __('public.products.price_pending') }}
                                        </strong>

                                        <span>
                                            {{ __('public.products.price_note') }}
                                        </span>
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $stock ?: __('public.products.stock_pending') }}
                                        </strong>

                                        <span>
                                            {{ __('public.products.stock_note') }}
                                        </span>
                                    </div>
                                </div>

                                <a
                                    href="#contact"
                                    class="nv-button nv-button-primary"
                                    data-quote-product="{{ $product->id }}"
                                    data-quote-variant="{{ $variant->id }}"
                                    data-quote-sku="{{ $variant->sku }}"
                                >
                                    {{ __('public.products.quote_button') }}
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border">
                            {{ $locale === 'en'
                                ? 'No products are currently available.'
                                : 'No hay productos disponibles actualmente.' }}
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section
        class="nv-home-section nv-home-benefits"
        id="benefits"
    >
        <div class="container">
            <div class="nv-section-header">
                <span class="nv-eyebrow">
                    {{ __('public.benefits.eyebrow') }}
                </span>

                <h2>
                    {{ __('public.benefits.title') }}
                </h2>

                <p>
                    {{ __('public.benefits.text') }}
                </p>
            </div>

            <div class="nv-benefits-grid">
                <article class="nv-benefit-card">
                    <span>01</span>

                    <h3>
                        {{ __('public.benefits.item_1_title') }}
                    </h3>

                    <p>
                        {{ __('public.benefits.item_1_text') }}
                    </p>
                </article>

                <article class="nv-benefit-card">
                    <span>02</span>

                    <h3>
                        {{ __('public.benefits.item_2_title') }}
                    </h3>

                    <p>
                        {{ __('public.benefits.item_2_text') }}
                    </p>
                </article>

                <article class="nv-benefit-card">
                    <span>03</span>

                    <h3>
                        {{ __('public.benefits.item_3_title') }}
                    </h3>

                    <p>
                        {{ __('public.benefits.item_3_text') }}
                    </p>
                </article>

                <article class="nv-benefit-card">
                    <span>04</span>

                    <h3>
                        {{ __('public.benefits.item_4_title') }}
                    </h3>

                    <p>
                        {{ __('public.benefits.item_4_text') }}
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section
        class="nv-home-section nv-info-section"
        id="contact"
    >
        <div class="container">
            <div class="nv-info-card">
                <div>
                    <span class="nv-eyebrow">
                        {{ __('public.included.eyebrow') }}
                    </span>

                    <h2>
                        {{ __('public.included.title') }}
                    </h2>

                    <p>
                        {{ __('public.included.text') }}
                    </p>
                </div>

                <div class="nv-info-list">
                    <span>
                        {{ __('public.included.item_1') }}
                    </span>

                    <span>
                        {{ __('public.included.item_2') }}
                    </span>

                    <span>
                        {{ __('public.included.item_3') }}
                    </span>

                    <span>
                        {{ __('public.included.item_4') }}
                    </span>

                    <span>
                        {{ __('public.included.item_5') }}
                    </span>

                    <span>
                        {{ __('public.included.item_6') }}
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection