<section
    class="
        nv-home-section
        nv-product-showcase-section
    "
    id="products"
>
    <div class="container">
        @if (session('quote_success'))
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

        <header class="nv-product-showcase-header">
            <div class="nv-product-showcase-intro">
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

            <div class="nv-product-showcase-description">
                <span
                    class="nv-product-showcase-line"
                    aria-hidden="true"
                ></span>

                <p>
                    {{
                        __(
                            'public.products.text'
                        )
                    }}
                </p>
            </div>
        </header>

        <div class="nv-product-showcase-list">
            @forelse (
                $catalogItems
                as $item
            )
                @include(
                    'home.partials.product-card',
                    [
                        'item' => $item,
                        'position' => $loop->index,
                    ]
                )
            @empty
                <div class="nv-product-empty">
                    <span class="nv-product-empty-mark">
                        Natviewer
                    </span>

                    <p>
                        {{
                            $locale === 'en'
                                ? 'No products are currently available.'
                                : 'No hay productos disponibles actualmente.'
                        }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</section>