<section class="nv-home-hero">
    <div
        class="nv-hero-background"
        aria-hidden="true"
    ></div>

    <div class="container">
        <div class="nv-hero-layout">
            <div class="nv-hero-copy">
                <span class="nv-hero-kicker">
                    <span aria-hidden="true"></span>

                    {{ __('public.hero.eyebrow') }}
                </span>

                <h1>
                    {{ __('public.hero.title') }}
                </h1>

                <p class="nv-hero-lead">
                    {{ __('public.hero.text') }}
                </p>

                <div class="nv-hero-actions">
                    <a
                        href="#products"
                        class="
                            nv-button
                            nv-button-primary
                        "
                    >
                        {{
                            __(
                                'public.hero.primary_button'
                            )
                        }}

                        <span aria-hidden="true">
                            →
                        </span>
                    </a>

                    <a
                        href="#contact"
                        class="
                            nv-button
                            nv-button-outline
                        "
                    >
                        {{
                            __(
                                'public.hero.secondary_button'
                            )
                        }}
                    </a>
                </div>
            </div>

            <aside
                class="nv-hero-product"
                aria-label="{{ $hero['product_name'] }}"
            >
                <div class="nv-hero-product-top">
                    <span>
                        Natviewer
                    </span>

                    <strong>
                        {{ $hero['variant_label'] }}
                    </strong>
                </div>

                <div class="nv-hero-product-media">
                    @if ($hero['image_url'])
                        @if ($hero['detail_url'])
                            <a
                                href="{{ $hero['detail_url'] }}"
                                class="nv-hero-product-image-link"
                                aria-label="{{
                                    $locale === 'en'
                                        ? 'View details for ' . $hero['product_name']
                                        : 'Ver detalles de ' . $hero['product_name']
                                }}"
                            >
                                <img
                                    src="{{ $hero['image_url'] }}"
                                    alt="{{ $hero['image_alt'] }}"
                                    class="nv-hero-product-image"
                                    fetchpriority="high"
                                    decoding="async"
                                >
                            </a>
                        @else
                            <img
                                src="{{ $hero['image_url'] }}"
                                alt="{{ $hero['image_alt'] }}"
                                class="nv-hero-product-image"
                                fetchpriority="high"
                                decoding="async"
                            >
                        @endif
                    @else
                        <div
                            class="nv-hero-product-placeholder"
                            aria-hidden="true"
                        >
                            <span>
                                NATVIEWER
                            </span>

                            <strong>
                                {{ $hero['variant_label'] }}
                            </strong>
                        </div>
                    @endif
                </div>

                <div class="nv-hero-product-footer">
                    <div>
                        <span class="nv-hero-product-label">
                            Natviewer
                        </span>

                        <strong>
                            {{ $hero['product_name'] }}
                        </strong>

                        @if ($hero['short_description'])
                            <small>
                                {{ $hero['short_description'] }}
                            </small>
                        @endif
                    </div>

                    @if ($hero['detail_url'])
                        <a
                            href="{{ $hero['detail_url'] }}"
                            class="nv-hero-product-link"
                            aria-label="{{
                                $locale === 'en'
                                    ? 'View ' . $hero['product_name']
                                    : 'Ver ' . $hero['product_name']
                            }}"
                        >
                            <span aria-hidden="true">
                                ↗
                            </span>
                        </a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>