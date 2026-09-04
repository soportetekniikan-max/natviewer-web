@extends('layouts.app')

@section('title', __('public.seo.home_title'))
@section('meta_description', __('public.seo.home_description'))

@php
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
                    <span class="nv-eyebrow">{{ __('public.hero.eyebrow') }}</span>

                    <h1>{{ __('public.hero.title') }}</h1>

                    <p class="nv-hero-lead">
                        {{ __('public.hero.text') }}
                    </p>

                    <div class="nv-hero-tags">
                        @if ($firstHeroVariant)
                            <span>{{ $variantShortLabel($firstHeroVariant) }}</span>
                        @endif

                        @if ($secondHeroVariant)
                            <span>{{ $variantShortLabel($secondHeroVariant) }}</span>
                        @endif

                        @if ($heroGlass)
                            <span>{{ $heroGlass }}</span>
                        @endif

                        <span>Outdoor</span>
                    </div>

                    <div class="nv-hero-actions">
                        <a href="#products" class="nv-button nv-button-primary">
                            {{ __('public.hero.primary_button') }}
                        </a>

                        <a href="#contact" class="nv-button nv-button-outline">
                            {{ __('public.hero.secondary_button') }}
                        </a>
                    </div>
                </div>

                <aside class="nv-hero-showcase">
                    <div class="nv-hero-showcase-header">
                        <span>{{ __('public.hero.card_label') }}</span>

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
                            <span>{{ __('public.hero.panel_kicker') }}</span>

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

                            <span>{{ __('public.hero.spec_1') }}</span>
                        </div>

                        <div>
                            <strong>
                                {{ $secondHeroVariant
                                    ? $variantShortLabel($secondHeroVariant)
                                    : '10×42' }}
                            </strong>

                            <span>{{ __('public.hero.spec_2') }}</span>
                        </div>

                        <div>
                            <strong>{{ $defaultCurrency }}</strong>
                            <span>{{ __('public.hero.spec_3') }}</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="nv-home-strip" id="specs">
        <div class="container">
            <div class="nv-strip-grid">
                <div>
                    <strong>{{ $heroGlass ?: 'UD' }}</strong>
                    <span>{{ __('public.strip.item_1') }}</span>
                </div>

                <div>
                    <strong>Coated</strong>
                    <span>{{ __('public.strip.item_2') }}</span>
                </div>

                <div>
                    <strong>3 m</strong>
                    <span>{{ __('public.strip.item_3') }}</span>
                </div>

                <div>
                    <strong>Outdoor</strong>
                    <span>{{ __('public.strip.item_4') }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="nv-home-section" id="products">
        <div class="container">
            @if (session('quote_success'))
                <div
                    class="alert nv-quote-alert nv-quote-alert-success"
                    role="alert"
                >
                    {{ session('quote_success') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="alert nv-quote-alert nv-quote-alert-error"
                    role="alert"
                >
                    {{ $locale === 'en'
                        ? 'Please review the quote form fields.'
                        : 'Revisa los campos del formulario de cotización.' }}
                </div>
            @endif

            <div class="nv-section-header nv-section-header-split">
                <div>
                    <span class="nv-eyebrow">
                        {{ __('public.products.eyebrow') }}
                    </span>

                    <h2>{{ __('public.products.title') }}</h2>
                </div>

                <p>{{ __('public.products.text') }}</p>
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
                                    <strong>{{ $variantName }}</strong>

                                    <p>{{ $productDescription }}</p>
                                </div>
                            </div>

                            <div class="nv-product-body">
                                <span class="nv-product-category">
                                    {{ $categoryName }}
                                </span>

                                <h3>{{ $productTitle }}</h3>

                                <p>{{ $productDescription }}</p>

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

                                <button
                                    type="button"
                                    class="nv-button nv-button-primary nv-quote-trigger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#quoteModal"
                                    data-quote-product="{{ $product->id }}"
                                    data-quote-variant="{{ $variant->id }}"
                                    data-quote-sku="{{ $variant->sku }}"
                                    data-quote-product-name="{{ $localized($product, 'name') }}"
                                    data-quote-variant-name="{{ $variantName }}"
                                >
                                    {{ __('public.products.quote_button') }}
                                </button>
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

                <h2>{{ __('public.benefits.title') }}</h2>

                <p>{{ __('public.benefits.text') }}</p>
            </div>

            <div class="nv-benefits-grid">
                <article class="nv-benefit-card">
                    <span>01</span>
                    <h3>{{ __('public.benefits.item_1_title') }}</h3>
                    <p>{{ __('public.benefits.item_1_text') }}</p>
                </article>

                <article class="nv-benefit-card">
                    <span>02</span>
                    <h3>{{ __('public.benefits.item_2_title') }}</h3>
                    <p>{{ __('public.benefits.item_2_text') }}</p>
                </article>

                <article class="nv-benefit-card">
                    <span>03</span>
                    <h3>{{ __('public.benefits.item_3_title') }}</h3>
                    <p>{{ __('public.benefits.item_3_text') }}</p>
                </article>

                <article class="nv-benefit-card">
                    <span>04</span>
                    <h3>{{ __('public.benefits.item_4_title') }}</h3>
                    <p>{{ __('public.benefits.item_4_text') }}</p>
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

                    <h2>{{ __('public.included.title') }}</h2>

                    <p>{{ __('public.included.text') }}</p>
                </div>

                <div class="nv-info-list">
                    <span>{{ __('public.included.item_1') }}</span>
                    <span>{{ __('public.included.item_2') }}</span>
                    <span>{{ __('public.included.item_3') }}</span>
                    <span>{{ __('public.included.item_4') }}</span>
                    <span>{{ __('public.included.item_5') }}</span>
                    <span>{{ __('public.included.item_6') }}</span>
                </div>
            </div>
        </div>
    </section>

    <div
        class="modal fade nv-quote-modal"
        id="quoteModal"
        tabindex="-1"
        aria-labelledby="quoteModalLabel"
        aria-hidden="true"
        data-has-errors="{{ $errors->any() ? 'true' : 'false' }}"
        data-old-variant="{{ old('product_variant_id') }}"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form
                    method="POST"
                    action="{{ route('quotes.store', ['locale' => $locale]) }}"
                    id="quoteForm"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="product_id"
                        id="quoteProductId"
                        value="{{ old('product_id') }}"
                    >

                    <input
                        type="hidden"
                        name="product_variant_id"
                        id="quoteVariantId"
                        value="{{ old('product_variant_id') }}"
                    >

                    <input
                        type="hidden"
                        name="utm_source"
                        value="{{ request()->query('utm_source') }}"
                    >

                    <input
                        type="hidden"
                        name="utm_medium"
                        value="{{ request()->query('utm_medium') }}"
                    >

                    <input
                        type="hidden"
                        name="utm_campaign"
                        value="{{ request()->query('utm_campaign') }}"
                    >

                    <input
                        type="hidden"
                        name="utm_term"
                        value="{{ request()->query('utm_term') }}"
                    >

                    <input
                        type="hidden"
                        name="utm_content"
                        value="{{ request()->query('utm_content') }}"
                    >

                    <div class="modal-header">
                        <div>
                            <span class="nv-eyebrow">
                                {{ $locale === 'en'
                                    ? 'Request a quote'
                                    : 'Solicitar cotización' }}
                            </span>

                            <h2
                                class="modal-title"
                                id="quoteModalLabel"
                            >
                                {{ $locale === 'en'
                                    ? 'Tell us how to contact you'
                                    : 'Cuéntanos cómo contactarte' }}
                            </h2>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="{{ $locale === 'en' ? 'Close' : 'Cerrar' }}"
                        ></button>
                    </div>

                    <div class="modal-body">
                        <div class="nv-quote-selection">
                            <span>
                                {{ $locale === 'en'
                                    ? 'Selected product'
                                    : 'Producto seleccionado' }}
                            </span>

                            <strong id="quoteSelectedProduct">
                                Natviewer Falco
                            </strong>

                            <strong id="quoteSelectedVariant"></strong>
                        </div>

                        <div class="nv-quote-fields">
                            <div class="nv-quote-field">
                                <label for="customerName">
                                    {{ $locale === 'en'
                                        ? 'Name *'
                                        : 'Nombre *' }}
                                </label>

                                <input
                                    type="text"
                                    id="customerName"
                                    name="customer_name"
                                    value="{{ old('customer_name') }}"
                                    maxlength="150"
                                    autocomplete="name"
                                    required
                                    class="@error('customer_name') is-invalid @enderror"
                                >

                                @error('customer_name')
                                    <p class="nv-quote-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="nv-quote-field">
                                <label for="customerPhone">
                                    {{ $locale === 'en'
                                        ? 'Phone *'
                                        : 'Teléfono *' }}
                                </label>

                                <input
                                    type="tel"
                                    id="customerPhone"
                                    name="customer_phone"
                                    value="{{ old('customer_phone') }}"
                                    maxlength="40"
                                    autocomplete="tel"
                                    required
                                    class="@error('customer_phone') is-invalid @enderror"
                                >

                                @error('customer_phone')
                                    <p class="nv-quote-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="nv-quote-field">
                                <label for="customerEmail">
                                    {{ $locale === 'en'
                                        ? 'Email'
                                        : 'Correo electrónico' }}
                                </label>

                                <input
                                    type="email"
                                    id="customerEmail"
                                    name="customer_email"
                                    value="{{ old('customer_email') }}"
                                    maxlength="255"
                                    autocomplete="email"
                                    class="@error('customer_email') is-invalid @enderror"
                                >

                                @error('customer_email')
                                    <p class="nv-quote-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="nv-quote-field">
                                <label for="quoteQuantity">
                                    {{ $locale === 'en'
                                        ? 'Quantity'
                                        : 'Cantidad' }}
                                </label>

                                <input
                                    type="number"
                                    id="quoteQuantity"
                                    name="quantity"
                                    min="1"
                                    max="99"
                                    value="{{ old('quantity', 1) }}"
                                    required
                                    class="@error('quantity') is-invalid @enderror"
                                >

                                @error('quantity')
                                    <p class="nv-quote-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="nv-quote-field nv-quote-field-full">
                                <label for="customerMessage">
                                    {{ $locale === 'en'
                                        ? 'Message'
                                        : 'Mensaje' }}
                                </label>

                                <textarea
                                    id="customerMessage"
                                    name="customer_message"
                                    maxlength="2000"
                                    placeholder="{{ $locale === 'en'
                                        ? 'Tell us anything else you would like to know.'
                                        : 'Cuéntanos qué más te gustaría saber.' }}"
                                >{{ old('customer_message') }}</textarea>

                                @error('customer_message')
                                    <p class="nv-quote-error">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <p class="nv-quote-note">
                            {{ $locale === 'en'
                                ? 'Submitting this form registers your quote request. If WhatsApp is available, you will then continue there.'
                                : 'Al enviar este formulario registraremos tu solicitud. Si WhatsApp está disponible, luego continuarás allí.' }}
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="nv-button nv-button-outline"
                            data-bs-dismiss="modal"
                        >
                            {{ $locale === 'en'
                                ? 'Cancel'
                                : 'Cancelar' }}
                        </button>

                        <button
                            type="submit"
                            class="nv-button nv-button-primary"
                            id="quoteSubmitButton"
                            data-loading-text="{{ $locale === 'en'
                                ? 'Sending...'
                                : 'Enviando...' }}"
                        >
                            {{ $locale === 'en'
                                ? 'Request quote'
                                : 'Solicitar cotización' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection