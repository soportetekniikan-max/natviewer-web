{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
>
    <url>
        <loc>{{ $spanishUrl }}</loc>

        <xhtml:link
            rel="alternate"
            hreflang="es"
            href="{{ $spanishUrl }}"
        />

        <xhtml:link
            rel="alternate"
            hreflang="en"
            href="{{ $englishUrl }}"
        />

        <xhtml:link
            rel="alternate"
            hreflang="x-default"
            href="{{ $spanishUrl }}"
        />
    </url>

    <url>
        <loc>{{ $englishUrl }}</loc>

        <xhtml:link
            rel="alternate"
            hreflang="es"
            href="{{ $spanishUrl }}"
        />

        <xhtml:link
            rel="alternate"
            hreflang="en"
            href="{{ $englishUrl }}"
        />

        <xhtml:link
            rel="alternate"
            hreflang="x-default"
            href="{{ $spanishUrl }}"
        />
    </url>
</urlset>