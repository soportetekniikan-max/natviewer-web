<nav
    class="nv-detail-breadcrumb"
    aria-label="Breadcrumb"
>
    <a href="{{ $navigation['home'] }}">
        {{
            __(
                'product.breadcrumb_home'
            )
        }}
    </a>

    <span
        class="nv-detail-breadcrumb-separator"
        aria-hidden="true"
    >
        /
    </span>

    <a href="{{ $navigation['catalog'] }}">
        {{
            $categoryName
            ?: __(
                'public.nav.products'
            )
        }}
    </a>

    <span
        class="nv-detail-breadcrumb-separator"
        aria-hidden="true"
    >
        /
    </span>

    <span aria-current="page">
        {{ $productName }}
    </span>
</nav>