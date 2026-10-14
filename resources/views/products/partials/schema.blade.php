<script type="application/ld+json">{!! $schemaJson !!}</script>

@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('product.breadcrumb_home'), 'item' => $navigation['home']],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $categoryName, 'item' => $navigation['catalog']],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $productName, 'item' => $seo['canonical']],
        ],
    ];
@endphp

<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
