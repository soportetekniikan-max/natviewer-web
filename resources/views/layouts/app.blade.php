@php
    $currentLocale = app()->getLocale();

    if (! in_array($currentLocale, ['es', 'en'], true)) {
        $currentLocale = 'es';
    }

    $alternateLocale = $currentLocale === 'es'
        ? 'en'
        : 'es';

    $homeUrl = route(
        'home',
        ['locale' => $currentLocale]
    );

    $alternateEsUrl = trim(
        $__env->yieldContent('alternate_es')
    );

    if ($alternateEsUrl === '') {
        $alternateEsUrl = route(
            'home',
            ['locale' => 'es']
        );
    }

    $alternateEnUrl = trim(
        $__env->yieldContent('alternate_en')
    );

    if ($alternateEnUrl === '') {
        $alternateEnUrl = route(
            'home',
            ['locale' => 'en']
        );
    }

    $alternateLanguageUrl = $alternateLocale === 'en'
        ? $alternateEnUrl
        : $alternateEsUrl;

    $showLanguageAlternates =
        trim(
            $__env->yieldContent(
                'show_language_alternates'
            )
        ) !== 'false';

    $navigationUrls = [
        'home' => $homeUrl,
        'products' => $homeUrl . '#products',
        'benefits' => $homeUrl . '#benefits',
        'specs' => $homeUrl . '#specs',
        'contact' => $homeUrl . '#contact',
    ];
@endphp

<!doctype html>
<html lang="{{ $currentLocale }}">
    @include(
        'layouts.partials.head',
        [
            'currentLocale' => $currentLocale,
            'alternateEsUrl' => $alternateEsUrl,
            'alternateEnUrl' => $alternateEnUrl,
            'showLanguageAlternates' =>
                $showLanguageAlternates,
        ]
    )

    <body class="@yield('body_class')">
        @include(
            'layouts.partials.header',
            [
                'currentLocale' => $currentLocale,
                'alternateLocale' => $alternateLocale,
                'alternateLanguageUrl' => $alternateLanguageUrl,
                'navigationUrls' => $navigationUrls,
                'showLanguageAlternates' =>
                    $showLanguageAlternates,
            ]
        )

        <main id="main-content">
            @yield('content')
        </main>

        @include(
            'layouts.partials.footer',
            [
                'navigationUrls' => $navigationUrls,
                'currentLocale' => $currentLocale,
            ]
        )
    </body>
</html>