<header class="nv-site-header">
    <nav class="nv-navbar container">
        <a
            href="{{ $navigationUrls['home'] }}"
            class="nv-brand"
            aria-label="Natviewer"
        >
            <img
                src="{{ asset(
                    'images/logo-natviewer-white.png'
                ) }}"
                alt="Natviewer"
                class="nv-brand-logo"
            >
        </a>

        <div
            class="nv-nav-links"
            aria-label="Natviewer"
        >
            <a href="{{ $navigationUrls['products'] }}">
                {{ __('public.nav.products') }}
            </a>

            <a href="{{ $navigationUrls['benefits'] }}">
                {{ __('public.nav.benefits') }}
            </a>

            <a href="{{ $navigationUrls['specs'] }}">
                {{ __('public.nav.specs') }}
            </a>

            <a href="{{ $navigationUrls['contact'] }}">
                {{ __('public.nav.contact') }}
            </a>
        </div>

        <div class="nv-nav-actions">
            <a
                href="{{ $alternateLanguageUrl }}"
                class="nv-lang-switch"
                hreflang="{{ $alternateLocale }}"
                lang="{{ $alternateLocale }}"
            >
                {{ strtoupper($alternateLocale) }}
            </a>

            <a
                href="{{ $navigationUrls['contact'] }}"
                class="
                    nv-button
                    nv-button-primary
                    nv-header-cta
                "
            >
                {{ __('public.nav.quote') }}
            </a>
        </div>
    </nav>
</header>