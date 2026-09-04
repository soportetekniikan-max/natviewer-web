@extends('layouts.app')

@section('title', __('public.seo.home_title'))
@section('meta_description', __('public.seo.home_description'))

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
                        <span>8×42</span>
                        <span>10×42</span>
                        <span>BAK-7</span>
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
                        <strong>Falco UD</strong>
                    </div>

                    <div class="nv-hero-showcase-brand">
                        <img src="{{ asset('images/logo-natviewer-white.png') }}" alt="Natviewer" class="nv-hero-showcase-logo">

                        <div>
                            <span>{{ __('public.hero.panel_kicker') }}</span>
                            <h2>Field optics for nature observation.</h2>
                        </div>
                    </div>

                    <div class="nv-hero-showcase-specs">
                        <div>
                            <strong>8×42</strong>
                            <span>{{ __('public.hero.spec_1') }}</span>
                        </div>

                        <div>
                            <strong>10×42</strong>
                            <span>{{ __('public.hero.spec_2') }}</span>
                        </div>

                        <div>
                            <strong>COP</strong>
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
                    <strong>BAK-7</strong>
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
            <div class="nv-section-header nv-section-header-split">
                <div>
                    <span class="nv-eyebrow">{{ __('public.products.eyebrow') }}</span>
                    <h2>{{ __('public.products.title') }}</h2>
                </div>

                <p>{{ __('public.products.text') }}</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <article class="nv-product-card">
                        <div class="nv-product-media nv-product-media-green">
                            <div class="nv-product-media-header">
                                <span>8×42</span>
                                <span>Falco UD</span>
                            </div>

                            <div class="nv-product-media-content">
                                <strong>{{ __('public.products.card_8_title') }}</strong>
                                <p>{{ __('public.products.card_8_text') }}</p>
                            </div>
                        </div>

                        <div class="nv-product-body">
                            <span class="nv-product-category">{{ __('public.products.category') }}</span>

                            <h3>Natviewer Falco 8×42 UD</h3>

                            <p>{{ __('public.products.falco_8_text') }}</p>

                            <div class="nv-product-data">
                                <div>
                                    <strong>{{ __('public.products.price_pending') }}</strong>
                                    <span>{{ __('public.products.price_note') }}</span>
                                </div>

                                <div>
                                    <strong>{{ __('public.products.stock_pending') }}</strong>
                                    <span>{{ __('public.products.stock_note') }}</span>
                                </div>
                            </div>

                            <a href="#contact" class="nv-button nv-button-primary">
                                {{ __('public.products.quote_button') }}
                            </a>
                        </div>
                    </article>
                </div>

                <div class="col-lg-6">
                    <article class="nv-product-card">
                        <div class="nv-product-media nv-product-media-dark">
                            <div class="nv-product-media-header">
                                <span>10×42</span>
                                <span>Falco UD</span>
                            </div>

                            <div class="nv-product-media-content">
                                <strong>{{ __('public.products.card_10_title') }}</strong>
                                <p>{{ __('public.products.card_10_text') }}</p>
                            </div>
                        </div>

                        <div class="nv-product-body">
                            <span class="nv-product-category">{{ __('public.products.category') }}</span>

                            <h3>Natviewer Falco 10×42 UD</h3>

                            <p>{{ __('public.products.falco_10_text') }}</p>

                            <div class="nv-product-data">
                                <div>
                                    <strong>{{ __('public.products.price_pending') }}</strong>
                                    <span>{{ __('public.products.price_note') }}</span>
                                </div>

                                <div>
                                    <strong>{{ __('public.products.stock_pending') }}</strong>
                                    <span>{{ __('public.products.stock_note') }}</span>
                                </div>
                            </div>

                            <a href="#contact" class="nv-button nv-button-primary">
                                {{ __('public.products.quote_button') }}
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="nv-home-section nv-home-benefits" id="benefits">
        <div class="container">
            <div class="nv-section-header">
                <span class="nv-eyebrow">{{ __('public.benefits.eyebrow') }}</span>
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

    <section class="nv-home-section nv-info-section">
        <div class="container">
            <div class="nv-info-card">
                <div>
                    <span class="nv-eyebrow">{{ __('public.included.eyebrow') }}</span>

                    <h2>{{ __('public.included.title') }}</h2>

                    <p>
                        {{ __('public.included.text') }}
                    </p>
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
@endsection