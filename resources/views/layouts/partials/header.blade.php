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
                    <a
                        href="{{ $navigationUrls['products'] }}"
                        class="nv-nav-link"
                    >
                        {{ __('public.nav.products') }}
                    </a>

                    <details class="nv-nav-cluster">
                        <summary
                            aria-label="{{
                                __('navigation.open_about_menu')
                            }}"
                        >
                            <span>
                                {{ __('navigation.about_group') }}
                            </span>

                            <span
                                class="nv-nav-cluster-chevron"
                                aria-hidden="true"
                            ></span>
                        </summary>

                        <div class="nv-nav-dropdown">
                            <a
                                href="{{
                                    route(
                                        'about.' . $currentLocale
                                    )
                                }}"
                                class="nv-nav-dropdown-item"
                            >
                                <span
                                    class="
                                        nv-nav-dropdown-icon
                                        nv-nav-dropdown-icon-about
                                    "
                                    aria-hidden="true"
                                ></span>

                                <span class="nv-nav-dropdown-copy">
                                    <strong>
                                        {{
                                            __(
                                                'navigation.about_title'
                                            )
                                        }}
                                    </strong>

                                    <small>
                                        {{
                                            __(
                                                'navigation.about_description'
                                            )
                                        }}
                                    </small>
                                </span>
                            </a>

                            <a
                                href="{{
                                    route(
                                        'faq.' . $currentLocale
                                    )
                                }}"
                                class="nv-nav-dropdown-item"
                            >
                                <span
                                    class="
                                        nv-nav-dropdown-icon
                                        nv-nav-dropdown-icon-faq
                                    "
                                    aria-hidden="true"
                                ></span>

                                <span class="nv-nav-dropdown-copy">
                                    <strong>
                                        {{
                                            __(
                                                'navigation.faq_title'
                                            )
                                        }}
                                    </strong>

                                    <small>
                                        {{
                                            __(
                                                'navigation.faq_description'
                                            )
                                        }}
                                    </small>
                                </span>
                            </a>
                        </div>
                    </details>

                    <a
                        href="{{ $navigationUrls['contact'] }}"
                        class="nv-nav-link"
                    >
                        {{ __('public.nav.contact') }}
                    </a>
                </div>

                <div class="nv-nav-actions">
                    @if ($showLanguageAlternates)
                        <a
                            href="{{ $alternateLanguageUrl }}"
                            class="nv-lang-switch"
                            hreflang="{{ $alternateLocale }}"
                            lang="{{ $alternateLocale }}"
                        >
                            {{ strtoupper($alternateLocale) }}
                        </a>
                    @endif

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
                aria-label="{{ __('navigation.open_menu') }}"
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
                aria-label="{{
                    __('navigation.mobile_navigation')
                }}"
            >
                <a href="{{ $navigationUrls['products'] }}">
                    {{ __('public.nav.products') }}
                </a>

                <details class="nv-mobile-nav-cluster">
                    <summary>
                        <span>
                            {{ __('navigation.about_group') }}
                        </span>

                        <span
                            class="nv-mobile-nav-chevron"
                            aria-hidden="true"
                        ></span>
                    </summary>

                    <div class="nv-mobile-nav-submenu">
                        <a
                            href="{{
                                route(
                                    'about.' . $currentLocale
                                )
                            }}"
                        >
                            <strong>
                                {{ __('navigation.about_title') }}
                            </strong>

                            <small>
                                {{
                                    __(
                                        'navigation.about_description'
                                    )
                                }}
                            </small>
                        </a>

                        <a
                            href="{{
                                route(
                                    'faq.' . $currentLocale
                                )
                            }}"
                        >
                            <strong>
                                {{ __('navigation.faq_title') }}
                            </strong>

                            <small>
                                {{
                                    __(
                                        'navigation.faq_description'
                                    )
                                }}
                            </small>
                        </a>
                    </div>
                </details>

                <a href="{{ $navigationUrls['contact'] }}">
                    {{ __('public.nav.contact') }}
                </a>
            </nav>

            <div class="nv-mobile-nav-actions">
                @if ($showLanguageAlternates)
                    <a
                        href="{{ $alternateLanguageUrl }}"
                        class="nv-lang-switch"
                        hreflang="{{ $alternateLocale }}"
                        lang="{{ $alternateLocale }}"
                    >
                        {{ strtoupper($alternateLocale) }}
                    </a>
                @endif

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