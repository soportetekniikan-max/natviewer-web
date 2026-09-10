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
                        @if (
                            $hero['first_variant_label']
                        )
                            <span>
                                {{
                                    $hero[
                                        'first_variant_label'
                                    ]
                                }}
                            </span>
                        @endif

                        @if (
                            $hero['second_variant_label']
                        )
                            <span>
                                {{
                                    $hero[
                                        'second_variant_label'
                                    ]
                                }}
                            </span>
                        @endif

                        @if ($hero['glass'])
                            <span>
                                {{ $hero['glass'] }}
                            </span>
                        @endif

                        <span>
                            Outdoor
                        </span>
                    </div>

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

                <aside class="nv-hero-showcase">
                    <div
                        class="
                            nv-hero-showcase-header
                        "
                    >
                        <span>
                            {{
                                __(
                                    'public.hero.card_label'
                                )
                            }}
                        </span>

                        <strong>
                            {{
                                $hero[
                                    'product_name'
                                ]
                            }}
                        </strong>
                    </div>

                    <div
                        class="
                            nv-hero-showcase-brand
                        "
                    >
                        <img
                            src="{{ asset(
                                'images/logo-natviewer-white.png'
                            ) }}"
                            alt="Natviewer"
                            class="
                                nv-hero-showcase-logo
                            "
                        >

                        <div>
                            <span>
                                {{
                                    __(
                                        'public.hero.panel_kicker'
                                    )
                                }}
                            </span>

                            <h2>
                                {{
                                    $hero[
                                        'short_description'
                                    ]
                                }}
                            </h2>
                        </div>
                    </div>

                    <div
                        class="
                            nv-hero-showcase-specs
                        "
                    >
                        <div>
                            <strong>
                                {{
                                    $hero[
                                        'first_variant_label'
                                    ]
                                }}
                            </strong>

                            <span>
                                {{
                                    __(
                                        'public.hero.spec_1'
                                    )
                                }}
                            </span>
                        </div>

                        <div>
                            <strong>
                                {{
                                    $hero[
                                        'second_variant_label'
                                    ]
                                }}
                            </strong>

                            <span>
                                {{
                                    __(
                                        'public.hero.spec_2'
                                    )
                                }}
                            </span>
                        </div>

                        <div>
                            <strong>
                                {{
                                    $hero[
                                        'default_currency'
                                    ]
                                }}
                            </strong>

                            <span>
                                {{
                                    __(
                                        'public.hero.spec_3'
                                    )
                                }}
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
                        {{
                            $hero['glass']
                            ?: 'UD'
                        }}
                    </strong>

                    <span>
                        {{
                            __(
                                'public.strip.item_1'
                            )
                        }}
                    </span>
                </div>

                <div>
                    <strong>
                        Coated
                    </strong>

                    <span>
                        {{
                            __(
                                'public.strip.item_2'
                            )
                        }}
                    </span>
                </div>

                <div>
                    <strong>
                        3 m
                    </strong>

                    <span>
                        {{
                            __(
                                'public.strip.item_3'
                            )
                        }}
                    </span>
                </div>

                <div>
                    <strong>
                        Outdoor
                    </strong>

                    <span>
                        {{
                            __(
                                'public.strip.item_4'
                            )
                        }}
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
            @if (
                session('quote_success')
            )
                <div
                    class="
                        alert
                        nv-quote-alert
                        nv-quote-alert-success
                    "
                    role="alert"
                >
                    {{
                        session(
                            'quote_success'
                        )
                    }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="
                        alert
                        nv-quote-alert
                        nv-quote-alert-error
                    "
                    role="alert"
                >
                    {{
                        $locale === 'en'
                            ? 'Please review the quote form fields.'
                            : 'Revisa los campos del formulario de cotización.'
                    }}
                </div>
            @endif

            <div
                class="
                    nv-section-header
                    nv-section-header-split
                "
            >
                <div>
                    <span class="nv-eyebrow">
                        {{
                            __(
                                'public.products.eyebrow'
                            )
                        }}
                    </span>

                    <h2>
                        {{
                            __(
                                'public.products.title'
                            )
                        }}
                    </h2>
                </div>

                <p>
                    {{
                        __(
                            'public.products.text'
                        )
                    }}
                </p>
            </div>

            <div class="row g-4">
                @forelse (
                    $catalogItems
                    as $item
                )
                    @include(
                        'home.partials.product-card',
                        [
                            'item' => $item,
                        ]
                    )
                @empty
                    <div class="col-12">
                        <div
                            class="
                                alert
                                alert-light
                                border
                            "
                        >
                            {{
                                $locale === 'en'
                                    ? 'No products are currently available.'
                                    : 'No hay productos disponibles actualmente.'
                            }}
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section
        class="
            nv-home-section
            nv-home-benefits
        "
        id="benefits"
    >
        <div class="container">
            <div class="nv-section-header">
                <span class="nv-eyebrow">
                    {{
                        __(
                            'public.benefits.eyebrow'
                        )
                    }}
                </span>

                <h2>
                    {{
                        __(
                            'public.benefits.title'
                        )
                    }}
                </h2>

                <p>
                    {{
                        __(
                            'public.benefits.text'
                        )
                    }}
                </p>
            </div>

            <div class="nv-benefits-grid">
                <article class="nv-benefit-card">
                    <span>01</span>

                    <h3>
                        {{
                            __(
                                'public.benefits.item_1_title'
                            )
                        }}
                    </h3>

                    <p>
                        {{
                            __(
                                'public.benefits.item_1_text'
                            )
                        }}
                    </p>
                </article>

                <article class="nv-benefit-card">
                    <span>02</span>

                    <h3>
                        {{
                            __(
                                'public.benefits.item_2_title'
                            )
                        }}
                    </h3>

                    <p>
                        {{
                            __(
                                'public.benefits.item_2_text'
                            )
                        }}
                    </p>
                </article>

                <article class="nv-benefit-card">
                    <span>03</span>

                    <h3>
                        {{
                            __(
                                'public.benefits.item_3_title'
                            )
                        }}
                    </h3>

                    <p>
                        {{
                            __(
                                'public.benefits.item_3_text'
                            )
                        }}
                    </p>
                </article>

                <article class="nv-benefit-card">
                    <span>04</span>

                    <h3>
                        {{
                            __(
                                'public.benefits.item_4_title'
                            )
                        }}
                    </h3>

                    <p>
                        {{
                            __(
                                'public.benefits.item_4_text'
                            )
                        }}
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section
        class="
            nv-home-section
            nv-info-section
        "
        id="contact"
    >
        <div class="container">
            <div class="nv-info-card">
                <div>
                    <span class="nv-eyebrow">
                        {{
                            __(
                                'public.included.eyebrow'
                            )
                        }}
                    </span>

                    <h2>
                        {{
                            __(
                                'public.included.title'
                            )
                        }}
                    </h2>

                    <p>
                        {{
                            __(
                                'public.included.text'
                            )
                        }}
                    </p>
                </div>

                <div class="nv-info-list">
                    <span>
                        {{
                            __(
                                'public.included.item_1'
                            )
                        }}
                    </span>

                    <span>
                        {{
                            __(
                                'public.included.item_2'
                            )
                        }}
                    </span>

                    <span>
                        {{
                            __(
                                'public.included.item_3'
                            )
                        }}
                    </span>

                    <span>
                        {{
                            __(
                                'public.included.item_4'
                            )
                        }}
                    </span>

                    <span>
                        {{
                            __(
                                'public.included.item_5'
                            )
                        }}
                    </span>

                    <span>
                        {{
                            __(
                                'public.included.item_6'
                            )
                        }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    @include(
        'quotes.partials.modal'
    )
@endsection