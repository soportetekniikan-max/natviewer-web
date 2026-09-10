<div class="nv-detail-content">
    <span class="nv-detail-category">
        {{
            $categoryName
            ?: __('product.eyebrow')
        }}
    </span>

    <h1 class="nv-detail-title">
        {{ $productName }}
    </h1>

    @if ($brandName)
        <div class="nv-detail-brand">
            {{ $brandName }}
        </div>
    @endif

    @if ($shortDescription)
        <p class="nv-detail-lead">
            {{ $shortDescription }}
        </p>
    @endif

    @if ($selectedVariant)
        <div class="nv-detail-highlight">
            <div
                class="
                    nv-detail-highlight-grid
                "
            >
                <div>
                    <span>
                        {{
                            __(
                                'product.price'
                            )
                        }}
                    </span>

                    <strong
                        data-selected-price
                    >
                        {{
                            $selectedVariant[
                                'price'
                            ]
                            ?: __(
                                'product.price_pending'
                            )
                        }}
                    </strong>
                </div>

                <div>
                    <span>
                        {{
                            __(
                                'product.stock'
                            )
                        }}
                    </span>

                    <strong
                        data-selected-stock
                    >
                        {{
                            $selectedVariant[
                                'stock'
                            ]
                        }}
                    </strong>
                </div>
            </div>
        </div>
    @endif

    <div class="nv-detail-section-label">
        {{
            __(
                'product.variants_title'
            )
        }}
    </div>

    <h2 class="nv-detail-section-title">
        {{
            __(
                'product.variants_heading'
            )
        }}
    </h2>

    <p class="nv-detail-section-copy">
        {{
            __(
                'product.variants_text'
            )
        }}
    </p>

    <div class="nv-detail-variants">
        @forelse ($variants as $variant)
            <article
                class="
                    nv-detail-variant
                    {{
                        $variant[
                            'is_selected'
                        ]
                            ? 'is-selected'
                            : ''
                    }}
                "
                data-variant-card
                data-variant-id="{{
                    $variant['id']
                }}"
                data-variant-price="{{
                    $variant['price']
                    ?: __(
                        'product.price_pending'
                    )
                }}"
                data-variant-stock="{{
                    $variant['stock']
                }}"
            >
                <label
                    class="
                        nv-detail-variant-select
                    "
                >
                    <input
                        type="radio"
                        name="visual_variant"
                        value="{{ $variant['id'] }}"
                        class="
                            nv-detail-variant-radio
                        "
                        data-variant-radio
                        @checked(
                            $variant[
                                'is_selected'
                            ]
                        )
                    >

                    <span
                        class="
                            nv-detail-variant-indicator
                        "
                        aria-hidden="true"
                    ></span>

                    <span
                        class="
                            nv-detail-variant-content
                        "
                    >
                        <span
                            class="
                                nv-detail-variant-head
                            "
                        >
                            <span>
                                <span
                                    class="
                                        nv-detail-variant-name
                                    "
                                >
                                    {{
                                        $variant[
                                            'name'
                                        ]
                                    }}
                                </span>

                                <span
                                    class="
                                        nv-detail-variant-sku
                                    "
                                >
                                    {{
                                        __(
                                            'product.sku'
                                        )
                                    }}:
                                    {{
                                        $variant[
                                            'sku'
                                        ]
                                    }}
                                </span>
                            </span>

                            @if (
                                $variant[
                                    'is_default'
                                ]
                            )
                                <span
                                    class="
                                        nv-detail-recommended
                                    "
                                >
                                    {{
                                        __(
                                            'product.default_variant'
                                        )
                                    }}
                                </span>
                            @endif
                        </span>

                        <span
                            class="
                                nv-detail-variant-data
                            "
                        >
                            <span>
                                <span>
                                    {{
                                        __(
                                            'product.price'
                                        )
                                    }}
                                </span>

                                <strong>
                                    {{
                                        $variant[
                                            'price'
                                        ]
                                        ?: __(
                                            'product.price_pending'
                                        )
                                    }}
                                </strong>
                            </span>

                            <span>
                                <span>
                                    {{
                                        __(
                                            'product.stock'
                                        )
                                    }}
                                </span>

                                <strong>
                                    {{
                                        $variant[
                                            'stock'
                                        ]
                                    }}
                                </strong>
                            </span>
                        </span>
                    </span>
                </label>

                <a
                    href="#quote"
                    class="nv-detail-cta"
                    data-quote-variant="{{
                        $variant['id']
                    }}"
                >
                    {{
                        __(
                            'product.quote_button'
                        )
                    }}
                </a>
            </article>
        @empty
            <div
                class="
                    alert
                    alert-light
                    border
                    rounded-4
                "
            >
                {{
                    __(
                        'product.no_variants'
                    )
                }}
            </div>
        @endforelse
    </div>
</div>