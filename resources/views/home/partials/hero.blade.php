<section class="nv-home-hero">
    <div
        class="nv-hero-background"
        aria-hidden="true"
    ></div>

    <div class="container">
        <div class="nv-hero-grid">
            <div class="nv-hero-copy">
                <span class="nv-hero-kicker">
                    <span></span>

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

                <div class="nv-hero-trust">
                    @if (
                        $hero[
                            'first_variant_label'
                        ]
                    )
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
                    @endif

                    @if (
                        $hero[
                            'second_variant_label'
                        ]
                    )
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
                    @endif

                    <div>
                        <strong>
                            {{
                                $hero['glass']
                                ?: 'UD'
                            }}
                        </strong>

                        <span>
                            Premium optics
                        </span>
                    </div>
                </div>
            </div>

            <aside class="nv-hero-product">
                <div class="nv-hero-product-top">
                    <span>
                        {{
                            __(
                                'public.hero.card_label'
                            )
                        }}
                    </span>

                    <span class="nv-hero-status">
                        <i></i>
                        Outdoor
                    </span>
                </div>

                <div class="nv-hero-product-content">
                    <span class="nv-hero-product-kicker">
                        {{
                            __(
                                'public.hero.panel_kicker'
                            )
                        }}
                    </span>

                    <h2>
                        {{
                            $hero[
                                'product_name'
                            ]
                        }}
                    </h2>

                    <p>
                        {{
                            $hero[
                                'short_description'
                            ]
                        }}
                    </p>
                </div>

                <div class="nv-hero-product-footer">
                    <div>
                        <span>
                            {{
                                __(
                                    'public.hero.spec_3'
                                )
                            }}
                        </span>

                        <strong>
                            {{
                                $hero[
                                    'default_currency'
                                ]
                            }}
                        </strong>
                    </div>

                    <a
                        href="#products"
                        aria-label="{{
                            __(
                                'public.hero.primary_button'
                            )
                        }}"
                    >
                        →
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>