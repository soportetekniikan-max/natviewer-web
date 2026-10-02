<section class="nv-home-hero">
    <div
        class="nv-hero-background"
        aria-hidden="true"
    ></div>

    <div
        class="nv-hero-decoration"
        aria-hidden="true"
    ></div>

    <div class="container">
        <div class="nv-hero-layout">
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
            </div>
        </div>
    </div>
</section>