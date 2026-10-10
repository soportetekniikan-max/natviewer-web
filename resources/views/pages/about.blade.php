@extends('layouts.app')

@section('title')
    {{ __('about.seo.title') }}
@endsection

@section('meta_description')
    {{ __('about.seo.description') }}
@endsection

@section('canonical')
    {{ route(
        'about.' . app()->getLocale()
    ) }}
@endsection

@section('alternate_es')
    {{ route('about.es') }}
@endsection

@section('alternate_en')
    {{ route('about.en') }}
@endsection

@section('alternate_default')
    {{ route('about.en') }}
@endsection

@section('body_class')
    nv-about-page
@endsection

@section('content')
    <main class="nv-about">

        <section class="nv-about-hero">
            <div class="container">
                <div class="nv-about-hero-content">
                    <p class="nv-about-kicker">
                        {{ __('about.hero.kicker') }}
                    </p>

                    <h1>
                        {{ __('about.hero.title') }}
                    </h1>

                    <p class="nv-about-hero-lead">
                        {{ __('about.hero.lead') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="nv-about-section">
            <div class="container">
                <div class="nv-about-split">
                    <div class="nv-about-section-heading">
                        <p class="nv-about-kicker">
                            {{ __('about.story.kicker') }}
                        </p>

                        <h2>
                            {{ __('about.story.title') }}
                        </h2>
                    </div>

                    <div class="nv-about-copy">
                        @foreach (
                            __('about.story.paragraphs')
                            as $paragraph
                        )
                            <p>
                                {{ $paragraph }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="nv-about-section nv-about-section-soft">
            <div class="container">
                <div class="nv-about-feature">
                    <p class="nv-about-kicker">
                        {{ __('about.purpose.kicker') }}
                    </p>

                    <h2>
                        {{ __('about.purpose.title') }}
                    </h2>

                    <p>
                        {{ __('about.purpose.text') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="nv-about-section">
            <div class="container">
                <div class="nv-about-centered-heading">
                    <h2>
                        {{ __('about.principles.title') }}
                    </h2>
                </div>

                <div class="nv-about-principles">
                    @foreach (
                        __('about.principles.items')
                        as $item
                    )
                        <article class="nv-about-principle-card">
                            <span
                                class="nv-about-principle-number"
                                aria-hidden="true"
                            >
                                {{
                                    str_pad(
                                        (string) ($loop->iteration),
                                        2,
                                        '0',
                                        STR_PAD_LEFT
                                    )
                                }}
                            </span>

                            <h3>
                                {{ $item['title'] }}
                            </h3>

                            <p>
                                {{ $item['text'] }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="nv-about-section nv-about-section-dark">
            <div class="container">
                <div class="nv-about-split nv-about-split-dark">
                    <div class="nv-about-section-heading">
                        <p class="nv-about-kicker">
                            {{ __('about.experience.kicker') }}
                        </p>

                        <h2>
                            {{ __('about.experience.title') }}
                        </h2>
                    </div>

                    <div class="nv-about-copy">
                        @foreach (
                            __('about.experience.paragraphs')
                            as $paragraph
                        )
                            <p>
                                {{ $paragraph }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="nv-about-section">
            <div class="container">
                <div class="nv-about-catalog">
                    <div class="nv-about-catalog-content">
                        <p class="nv-about-kicker">
                            {{ __('about.catalog.kicker') }}
                        </p>

                        <h2>
                            {{ __('about.catalog.title') }}
                        </h2>

                        <p>
                            {{ __('about.catalog.text') }}
                        </p>

                        <a
                            href="{{
                                route(
                                    'home',
                                    [
                                        'locale' =>
                                            app()->getLocale(),
                                    ]
                                )
                            }}#products"
                            class="nv-button nv-button-primary"
                        >
                            {{ __('about.catalog.button') }}
                        </a>
                    </div>

                    <div
                        class="nv-about-catalog-mark"
                        aria-hidden="true"
                    >
                        <span>
                            8×42
                        </span>

                        <span>
                            10×42
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class="nv-about-section nv-about-section-soft">
            <div class="container">
                <div class="nv-about-feature">
                    <p class="nv-about-kicker">
                        {{ __('about.commitment.kicker') }}
                    </p>

                    <h2>
                        {{ __('about.commitment.title') }}
                    </h2>

                    <p>
                        {{ __('about.commitment.text') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="nv-about-cta">
            <div class="container">
                <div class="nv-about-cta-card">
                    <div>
                        <p class="nv-about-kicker">
                            {{ __('about.cta.kicker') }}
                        </p>

                        <h2>
                            {{ __('about.cta.title') }}
                        </h2>

                        <p>
                            {{ __('about.cta.text') }}
                        </p>
                    </div>

                    <div class="nv-about-cta-action">
                        <a
                            href="{{
                                route(
                                    'home',
                                    [
                                        'locale' =>
                                            app()->getLocale(),
                                    ]
                                )
                            }}#products"
                            class="nv-button nv-button-primary"
                        >
                            {{ __('about.cta.button') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection