@php
    $currentLocale = request()->route(
        'locale',
        'es'
    );

    $alternateLocale =
        $currentLocale === 'es'
            ? 'en'
            : 'es';

    /*
    |--------------------------------------------------------------------------
    | SEO base
    |--------------------------------------------------------------------------
    |
    | Las vistas pueden sobrescribir estos valores con @section().
    | Esto nos permitirá reutilizar el mismo layout cuando creemos
    | las páginas individuales de producto.
    |
    */

    $seoTitle = trim(
        $__env->yieldContent('title')
    );

    if ($seoTitle === '') {
        $seoTitle =
            __('public.seo.home_title');
    }

    $seoDescription = trim(
        $__env->yieldContent(
            'meta_description'
        )
    );

    if ($seoDescription === '') {
        $seoDescription =
            __('public.seo.home_description');
    }

    $canonicalUrl = trim(
        $__env->yieldContent(
            'canonical'
        )
    );

    if ($canonicalUrl === '') {
        $canonicalUrl = route(
            'home',
            [
                'locale' =>
                    $currentLocale,
            ]
        );
    }

    $alternateEsUrl = trim(
        $__env->yieldContent(
            'alternate_es'
        )
    );

    if ($alternateEsUrl === '') {
        $alternateEsUrl = route(
            'home',
            [
                'locale' => 'es',
            ]
        );
    }

    $alternateEnUrl = trim(
        $__env->yieldContent(
            'alternate_en'
        )
    );

    if ($alternateEnUrl === '') {
        $alternateEnUrl = route(
            'home',
            [
                'locale' => 'en',
            ]
        );
    }

    $xDefaultUrl = trim(
        $__env->yieldContent(
            'alternate_default'
        )
    );

    if ($xDefaultUrl === '') {
        $xDefaultUrl =
            $alternateEsUrl;
    }

    $seoImage = trim(
        $__env->yieldContent(
            'seo_image'
        )
    );

    if ($seoImage === '') {
        $seoImage = asset(
            'images/logo-natviewer-white.png'
        );
    }

    $seoImageAlt = trim(
        $__env->yieldContent(
            'seo_image_alt'
        )
    );

    if ($seoImageAlt === '') {
        $seoImageAlt =
            'Natviewer';
    }

    $ogType = trim(
        $__env->yieldContent(
            'og_type'
        )
    );

    if ($ogType === '') {
        $ogType = 'website';
    }

    $robots = trim(
        $__env->yieldContent(
            'robots'
        )
    );

    if ($robots === '') {
        $robots =
            'index,follow,max-image-preview:large';
    }

    $ogLocale =
        $currentLocale === 'en'
            ? 'en_US'
            : 'es_CO';

    $alternateOgLocale =
        $currentLocale === 'en'
            ? 'es_CO'
            : 'en_US';

    /*
    |--------------------------------------------------------------------------
    | Structured Data
    |--------------------------------------------------------------------------
    */

    $organizationSchema = [
        '@context' =>
            'https://schema.org',

        '@type' =>
            'Organization',

        'name' =>
            'Natviewer',

        'url' =>
            url('/'),

        'logo' =>
            asset(
                'images/logo-natviewer-white.png'
            ),
    ];

    $websiteSchema = [
        '@context' =>
            'https://schema.org',

        '@type' =>
            'WebSite',

        'name' =>
            'Natviewer',

        'url' =>
            url('/'),

        'inLanguage' => [
            'es',
            'en',
        ],
    ];
@endphp

<!doctype html>
<html lang="{{ $currentLocale }}">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>{{ $seoTitle }}</title>

    <meta
        name="description"
        content="{{ $seoDescription }}"
    >

    <meta
        name="robots"
        content="{{ $robots }}"
    >

    <meta
        name="theme-color"
        content="#122121"
    >

    {{-- Canonical --}}
    <link
        rel="canonical"
        href="{{ $canonicalUrl }}"
    >

    {{-- Internacionalización --}}
    <link
        rel="alternate"
        hreflang="es"
        href="{{ $alternateEsUrl }}"
    >

    <link
        rel="alternate"
        hreflang="en"
        href="{{ $alternateEnUrl }}"
    >

    <link
        rel="alternate"
        hreflang="x-default"
        href="{{ $xDefaultUrl }}"
    >

    {{-- Open Graph --}}
    <meta
        property="og:type"
        content="{{ $ogType }}"
    >

    <meta
        property="og:site_name"
        content="Natviewer"
    >

    <meta
        property="og:title"
        content="{{ $seoTitle }}"
    >

    <meta
        property="og:description"
        content="{{ $seoDescription }}"
    >

    <meta
        property="og:url"
        content="{{ $canonicalUrl }}"
    >

    <meta
        property="og:image"
        content="{{ $seoImage }}"
    >

    <meta
        property="og:image:alt"
        content="{{ $seoImageAlt }}"
    >

    <meta
        property="og:locale"
        content="{{ $ogLocale }}"
    >

    <meta
        property="og:locale:alternate"
        content="{{ $alternateOgLocale }}"
    >

    {{-- Twitter / X --}}
    <meta
        name="twitter:card"
        content="summary"
    >

    <meta
        name="twitter:title"
        content="{{ $seoTitle }}"
    >

    <meta
        name="twitter:description"
        content="{{ $seoDescription }}"
    >

    <meta
        name="twitter:image"
        content="{{ $seoImage }}"
    >

    {{-- Structured Data --}}
    <script type="application/ld+json">
        {!! json_encode(
            $organizationSchema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
        ) !!}
    </script>

    <script type="application/ld+json">
        {!! json_encode(
            $websiteSchema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
        ) !!}
    </script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    @stack('head')
</head>

<body>
    <header class="nv-site-header">
        <nav class="nv-navbar container">
            <a
                href="{{ route(
                    'home',
                    [
                        'locale' =>
                            $currentLocale,
                    ]
                ) }}"
                class="nv-brand"
                aria-label="Natviewer home"
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
                aria-label="Main navigation"
            >
                <a href="#products">
                    {{ __('public.nav.products') }}
                </a>

                <a href="#benefits">
                    {{ __('public.nav.benefits') }}
                </a>

                <a href="#specs">
                    {{ __('public.nav.specs') }}
                </a>

                <a href="#contact">
                    {{ __('public.nav.contact') }}
                </a>
            </div>

            <div class="nv-nav-actions">
                <a
                    href="{{ route(
                        'home',
                        [
                            'locale' =>
                                $alternateLocale,
                        ]
                    ) }}"
                    class="nv-lang-switch"
                    hreflang="{{ $alternateLocale }}"
                >
                    {{ strtoupper(
                        $alternateLocale
                    ) }}
                </a>

                <a
                    href="#contact"
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

    <main>
        @yield('content')
    </main>

    <footer
        class="nv-site-footer"
        id="contact"
    >
        <div class="container">
            <div class="nv-footer-grid">
                <div>
                    <img
                        src="{{ asset(
                            'images/logo-natviewer-white.png'
                        ) }}"
                        alt="Natviewer"
                        class="nv-footer-logo"
                    >

                    <p>
                        {{ __('public.footer.text') }}
                    </p>
                </div>

                <div class="nv-footer-card">
                    <span>
                        {{ __('public.footer.kicker') }}
                    </span>

                    <h2>
                        {{ __('public.footer.contact_title') }}
                    </h2>

                    <p>
                        {{ __('public.footer.contact_text') }}
                    </p>

                    <a
                        href="#products"
                        class="nv-button nv-button-primary"
                    >
                        {{ __('public.footer.quote_button') }}
                    </a>
                </div>
            </div>

            <div class="nv-footer-bottom">
                <span>
                    © {{ date('Y') }} Natviewer.
                    {{ __('public.footer.rights') }}
                </span>

                <span>
                    {{ __('public.footer.version') }}
                </span>
            </div>
        </div>
    </footer>
</body>
</html>