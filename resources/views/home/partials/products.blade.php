<section
    class="
        nv-home-section
        nv-product-showcase-section
    "
    id="products"
>
    <div class="container">
        @if (
            session('quote_success')
        )
            <div
                class="
                    alert
                    nv-quote-alert
                    nv-quote-alert-success
                "
                role="alert"
            >
                {{
                    session(
                        'quote_success'
                    )
                }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="
                    alert
                    nv-quote-alert
                    nv-quote-alert-error
                "
                role="alert"
            >
                {{
                    $locale === 'en'
                        ? 'Please review the quote form fields.'
                        : 'Revisa los campos del formulario de cotización.'
                }}
            </div>
        @endif

        <div class="nv-section-heading">
            <div class="nv-section-heading-mark">
                <span>
                    02
                </span>

                <i aria-hidden="true"></i>
            </div>

            <div class="nv-section-heading-main">
                <span class="nv-eyebrow">
                    {{
                        __(
                            'public.products.eyebrow'
                        )
                    }}
                </span>

                <h2>
                    {{
                        __(
                            'public.products.title'
                        )
                    }}
                </h2>
            </div>

            <p class="nv-section-heading-copy">
                {{
                    __(
                        'public.products.text'
                    )
                }}
            </p>
        </div>

        <div class="nv-product-grid row g-4">
            @forelse (
                $catalogItems
                as $item
            )
                @include(
                    'home.partials.product-card',
                    [
                        'item' => $item,
                    ]
                )
            @empty
                <div class="col-12">
                    <div
                        class="
                            alert
                            alert-light
                            border
                        "
                    >
                        {{
                            $locale === 'en'
                                ? 'No products are currently available.'
                                : 'No hay productos disponibles actualmente.'
                        }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>