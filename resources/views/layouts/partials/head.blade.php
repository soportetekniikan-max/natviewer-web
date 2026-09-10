@php
    $seoTitle = trim(
        $__env->yieldContent('title')
    );

    if ($seoTitle === '') {
        $seoTitle = __(
            'public.seo.home_title'
        );
    }

    $seoDescription = trim(
        $__env->yieldContent(
            'meta_description'
        )
    );

    if ($seoDescription === '') {
        $seoDescription = __(
            'public.seo.home_description'
        );
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
                'locale' => $currentLocale,
            ]
        );
    }

    $xDefaultUrl = trim(
        $__env->yieldContent(
            'alternate_default'
        )
    );

    if ($xDefaultUrl === '') {
        $xDefaultUrl = $alternateEsUrl;
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
        $seoImageAlt = 'Natviewer';
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
        $robots = 'index,follow,max-image-preview:large';
    }

    $ogLocale = $currentLocale === 'en'
        ? 'en_US'
        : 'es_CO';

    $alternateOgLocale = $currentLocale === 'en'
        ? 'es_CO'
        : 'en_US';

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Natviewer',
        'url' => url('/'),
        'logo' => asset(
            'images/logo-natviewer-white.png'
        ),
    ];

    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Natviewer',
        'url' => url('/'),
        'inLanguage' => [
            'es',
            'en',
        ],
    ];
@endphp

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

    <link
        rel="canonical"
        href="{{ $canonicalUrl }}"
    >

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

    <script type="application/ld+json">
        {!! json_encode(
            $organizationSchema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
        ) !!}
    </script>

    <script type="application/ld+json">
        {!! json_encode(
            $websiteSchema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
        ) !!}
    </script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    @stack('head')
</head>