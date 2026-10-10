@extends('layouts.app')

@section('title')
    {{ __('faq.seo.title') }}
@endsection

@section('meta_description')
    {{ __('faq.seo.description') }}
@endsection

@section('canonical')
    {{ route(
        'faq.' . app()->getLocale()
    ) }}
@endsection

@section('alternate_es')
    {{ route('faq.es') }}
@endsection

@section('alternate_en')
    {{ route('faq.en') }}
@endsection

@section('alternate_default')
    {{ route('faq.en') }}
@endsection

@section('body_class')
    nv-faq-page
@endsection

@section('content')
    <main class="nv-faq">

        <section class="nv-faq-hero">
            <div class="container">
                <div class="nv-faq-hero-content">
                    <p class="nv-faq-kicker">
                        {{ __('faq.hero.kicker') }}
                    </p>

                    <h1>
                        {{ __('faq.hero.title') }}
                    </h1>

                    <p class="nv-faq-hero-lead">
                        {{ __('faq.hero.lead') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="nv-faq-intro">
            <div class="container">
                <div class="nv-faq-intro-content">
                    <h2>
                        {{ __('faq.intro.title') }}
                    </h2>

                    <p>
                        {{ __('faq.intro.text') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="nv-faq-content">
            <div class="container">
                <div class="nv-faq-groups">
                    @foreach (
                        __('faq.groups')
                        as $groupIndex => $group
                    )
                        <section
                            class="nv-faq-group"
                            aria-labelledby="faq-group-{{
                                $groupIndex + 1
                            }}"
                        >
                            <header class="nv-faq-group-header">
                                <p class="nv-faq-kicker">
                                    {{ $group['kicker'] }}
                                </p>

                                <h2
                                    id="faq-group-{{
                                        $groupIndex + 1
                                    }}"
                                >
                                    {{ $group['title'] }}
                                </h2>
                            </header>

                            <div class="nv-faq-list">
                                @foreach (
                                    $group['items']
                                    as $itemIndex => $item
                                )
                                    <details
                                        class="nv-faq-item"
                                        @if (
                                            $groupIndex === 0
                                            && $itemIndex === 0
                                        )
                                            open
                                        @endif
                                    >
                                        <summary>
                                            <span
                                                class="
                                                    nv-faq-question-number
                                                "
                                                aria-hidden="true"
                                            >
                                                {{
                                                    str_pad(
                                                        (string) (
                                                            $itemIndex + 1
                                                        ),
                                                        2,
                                                        '0',
                                                        STR_PAD_LEFT
                                                    )
                                                }}
                                            </span>

                                            <span
                                                class="
                                                    nv-faq-question-text
                                                "
                                            >
                                                {{ $item['question'] }}
                                            </span>

                                            <span
                                                class="nv-faq-icon"
                                                aria-hidden="true"
                                            ></span>
                                        </summary>

                                        <div class="nv-faq-answer">
                                            <p>
                                                {{ $item['answer'] }}
                                            </p>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="nv-faq-cta">
            <div class="container">
                <div class="nv-faq-cta-card">
                    <div class="nv-faq-cta-content">
                        <p class="nv-faq-kicker">
                            {{ __('faq.cta.kicker') }}
                        </p>

                        <h2>
                            {{ __('faq.cta.title') }}
                        </h2>

                        <p>
                            {{ __('faq.cta.text') }}
                        </p>
                    </div>

                    <div class="nv-faq-cta-action">
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
                            {{ __('faq.cta.button') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection