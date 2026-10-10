@extends('layouts.app')

@section('title')
    {{ __('privacy.seo.title') }}
@endsection

@section('meta_description')
    {{ __('privacy.seo.description') }}
@endsection

@section('canonical')
    {{ route(
        'privacy-policy.' . app()->getLocale()
    ) }}
@endsection

@section('alternate_es')
    {{ route('privacy-policy.es') }}
@endsection

@section('alternate_en')
    {{ route('privacy-policy.en') }}
@endsection

@section('alternate_default')
    {{ route('privacy-policy.es') }}
@endsection

@section('body_class')
    nv-privacy-page
@endsection

@section('content')
    <section class="nv-privacy">
        <div class="container">
            <article class="nv-privacy-document">

                <header class="nv-privacy-header">
                    <p class="nv-privacy-kicker">
                        {{ __('privacy.kicker') }}
                    </p>

                    <h1>
                        {{ __('privacy.title') }}
                    </h1>

                    <div class="nv-privacy-meta">
                        <p>
                            <strong>
                                {{ __('privacy.effective_date.label') }}
                            </strong>

                            {{ __('privacy.effective_date.value') }}
                        </p>

                        <p>
                            <strong>
                                {{ __('privacy.last_updated.label') }}
                            </strong>

                            {{ __('privacy.last_updated.value') }}
                        </p>
                    </div>

                    <div class="nv-privacy-intro">
                        @foreach (
                            __('privacy.intro')
                            as $paragraph
                        )
                            <p>
                                {{ $paragraph }}
                            </p>
                        @endforeach
                    </div>
                </header>

                <nav
                    class="nv-privacy-toc"
                    aria-label="{{
                        __('privacy.contents_aria_label')
                    }}"
                >
                    <p>
                        <strong>
                            {{ __('privacy.contents_label') }}
                        </strong>
                    </p>

                    <ol>
                        @foreach (
                            __('privacy.sections')
                            as $index => $section
                        )
                            <li>
                                <a
                                    href="#privacy-{{
                                        $index + 1
                                    }}"
                                >
                                    {{ $section['toc'] }}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </nav>

                <div class="nv-privacy-content">
                    @foreach (
                        __('privacy.sections')
                        as $index => $section
                    )
                        <section
                            id="privacy-{{ $index + 1 }}"
                            class="nv-privacy-section"
                        >
                            <h2>
                                {{ $section['title'] }}
                            </h2>

                            @foreach (
                                $section['blocks']
                                as $block
                            )
                                @if (
                                    $block['type']
                                    === 'heading'
                                )
                                    <h3>
                                        {{ $block['text'] }}
                                    </h3>

                                @elseif (
                                    $block['type']
                                    === 'paragraph'
                                )
                                    <p>
                                        {{ $block['text'] }}
                                    </p>

                                @elseif (
                                    $block['type']
                                    === 'list'
                                )
                                    <ul>
                                        @foreach (
                                            $block['items']
                                            as $item
                                        )
                                            <li>
                                                {{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        </section>
                    @endforeach
                </div>
            </article>
        </div>
    </section>
@endsection