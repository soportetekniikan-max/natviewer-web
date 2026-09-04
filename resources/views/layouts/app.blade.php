@php
    $currentLocale = request()->route('locale', 'es');
    $alternateLocale = $currentLocale === 'es' ? 'en' : 'es';
@endphp

<!doctype html>
<html lang="{{ $currentLocale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Natviewer | Binoculares para avistamiento de aves')</title>
    <meta name="description" content="@yield('meta_description', 'Binoculares Natviewer para observación de aves, naturaleza y actividades outdoor.')">
    <meta name="theme-color" content="#122121">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="nv-site-header">
        <nav class="nv-navbar container">
            <a href="{{ route('home', ['locale' => $currentLocale]) }}" class="nv-brand" aria-label="Natviewer home">
                <img src="{{ asset('images/logo-natviewer-white.png') }}" alt="Natviewer" class="nv-brand-logo">
            </a>

            <div class="nv-nav-links" aria-label="Main navigation">
                <a href="#products">{{ __('public.nav.products') }}</a>
                <a href="#benefits">{{ __('public.nav.benefits') }}</a>
                <a href="#specs">{{ __('public.nav.specs') }}</a>
                <a href="#contact">{{ __('public.nav.contact') }}</a>
            </div>

            <div class="nv-nav-actions">
                <a href="{{ route('home', ['locale' => $alternateLocale]) }}" class="nv-lang-switch">
                    {{ strtoupper($alternateLocale) }}
                </a>

                <a href="#contact" class="nv-button nv-button-primary nv-header-cta">
                    {{ __('public.nav.quote') }}
                </a>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="nv-site-footer" id="contact">
        <div class="container">
            <div class="nv-footer-grid">
                <div>
                    <img src="{{ asset('images/logo-natviewer-white.png') }}" alt="Natviewer" class="nv-footer-logo">

                    <p>
                        {{ __('public.footer.text') }}
                    </p>
                </div>

                <div class="nv-footer-card">
                    <span>{{ __('public.footer.kicker') }}</span>

                    <h2>{{ __('public.footer.contact_title') }}</h2>

                    <p>{{ __('public.footer.contact_text') }}</p>

                    <a href="#" class="nv-button nv-button-primary">
                        {{ __('public.footer.quote_button') }}
                    </a>
                </div>
            </div>

            <div class="nv-footer-bottom">
                <span>© {{ date('Y') }} Natviewer. {{ __('public.footer.rights') }}</span>
                <span>{{ __('public.footer.version') }}</span>
            </div>
        </div>
    </footer>
</body>
</html>