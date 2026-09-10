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