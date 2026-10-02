<header class="nv-site-header">
    <div class="container">
        <nav
            class="nv-navbar"
            aria-label="Natviewer"
        >
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

            <div class="nv-nav-desktop">
                <div class="nv-nav-links">
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
            </div>

            <button
                type="button"
                class="nv-nav-toggle"
                aria-label="Abrir menú"
                aria-controls="nvMobileNavigation"
                aria-expanded="false"
                data-public-nav-toggle
            >
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </button>
        </nav>

        <div
            class="nv-mobile-nav"
            id="nvMobileNavigation"
            data-public-nav-menu
            hidden
        >
            <nav
                class="nv-mobile-nav-links"
                aria-label="Navegación móvil"
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
            </nav>

            <div class="nv-mobile-nav-actions">
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
        </div>
    </div>
</header>