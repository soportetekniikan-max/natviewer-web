<div class="col-12 col-xl-6">
    <article
        class="nv-product-card"
        data-product-id="{{ $item['product_id'] }}"
        data-variant-id="{{ $item['variant_id'] }}"
        data-sku="{{ $item['sku'] }}"
    >
        <div class="nv-product-media">
            <div class="nv-product-badges">
                @if ($item['is_featured'])
                    <span
                        class="
                            nv-product-badge
                            nv-product-badge-featured
                        "
                    >
                        {{
                            $locale === 'en'
                                ? 'Featured'
                                : 'Destacado'
                        }}
                    </span>
                @endif

                <span
                    class="
                        nv-product-badge
                        nv-product-badge-brand
                    "
                >
                    {{ $item['brand_name'] }}
                </span>
            </div>

            <a
                href="{{ $item['detail_url'] }}"
                class="nv-product-image-link"
                aria-label="{{
                    $locale === 'en'
                        ? 'View details for ' . $item['title']
                        : 'Ver detalles de ' . $item['title']
                }}"
            >
                @if ($item['image_url'])
                    <img
                        src="{{ $item['image_url'] }}"
                        alt="{{ $item['image_alt'] }}"
                        class="nv-product-image"
                        loading="lazy"
                        decoding="async"
                    >
                @else
                    <div
                        class="nv-product-image-placeholder"
                        aria-hidden="true"
                    >
                        <span>
                            NATVIEWER
                        </span>

                        <strong>
                            {{
                                $item[
                                    'variant_label'
                                ]
                            }}
                        </strong>
                    </div>
                @endif
            </a>

            <div class="nv-product-media-footer">
                <span class="nv-product-media-category">
                    {{ $item['category_name'] }}
                </span>

                <span
                    class="
                        nv-product-stock-pill
                        {{
                            $item['is_available']
                                ? 'is-available'
                                : 'is-unavailable'
                        }}
                    "
                >
                    <i aria-hidden="true"></i>

                    {{
                        $item['stock']
                        ?: __(
                            'public.products.stock_pending'
                        )
                    }}
                </span>
            </div>
        </div>

        <div class="nv-product-body">
            <div class="nv-product-heading">
                <div>
                    <span class="nv-product-brand">
                        {{ $item['brand_name'] }}
                    </span>

                    <h3>
                        {{ $item['title'] }}
                    </h3>
                </div>

                <span class="nv-product-sku">
                    {{ $item['sku'] }}
                </span>
            </div>

            <p class="nv-product-description">
                {{ $item['description'] }}
            </p>

            @if (
                count(
                    $item['variant_options']
                ) > 0
            )
                <div class="nv-product-variants">
                    <span class="nv-product-variants-label">
                        {{
                            $locale === 'en'
                                ? 'Available versions'
                                : 'Versiones disponibles'
                        }}
                    </span>

                    <div class="nv-product-variant-list">
                        @foreach (
                            $item['variant_options']
                            as $variantOption
                        )
                            <span
                                class="
                                    nv-product-variant-chip
                                    {{
                                        $variantOption['id']
                                        === $item['variant_id']
                                            ? 'is-active'
                                            : ''
                                    }}
                                "
                            >
                                {{
                                    $variantOption[
                                        'label'
                                    ]
                                }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="nv-product-commerce">
                <div class="nv-product-price-block">
                    <span>
                        {{
                            $locale === 'en'
                                ? 'Price'
                                : 'Precio'
                        }}
                    </span>

                    <strong>
                        {{
                            $item['price']
                            ?: __(
                                'public.products.price_pending'
                            )
                        }}
                    </strong>

                    <small>
                        {{
                            __(
                                'public.products.price_note'
                            )
                        }}
                    </small>
                </div>

                <div class="nv-product-availability">
                    <span
                        class="
                            nv-product-availability-dot
                            {{
                                $item['is_available']
                                    ? 'is-available'
                                    : 'is-unavailable'
                            }}
                        "
                        aria-hidden="true"
                    ></span>

                    <div>
                        <strong>
                            {{
                                $item['stock']
                                ?: __(
                                    'public.products.stock_pending'
                                )
                            }}
                        </strong>

                        <span>
                            {{
                                __(
                                    'public.products.stock_note'
                                )
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="nv-product-actions">
                <a
                    href="{{ $item['detail_url'] }}"
                    class="
                        nv-button
                        nv-button-outline
                    "
                >
                    {{ __('catalog.view_details') }}

                    <span aria-hidden="true">
                        →
                    </span>
                </a>

                <button
                    type="button"
                    class="
                        nv-button
                        nv-button-primary
                        nv-quote-trigger
                    "
                    data-bs-toggle="modal"
                    data-bs-target="#quoteModal"
                    data-quote-product="{{ $item['product_id'] }}"
                    data-quote-variant="{{ $item['variant_id'] }}"
                    data-quote-sku="{{ $item['sku'] }}"
                    data-quote-product-name="{{ $item['product_name'] }}"
                    data-quote-variant-name="{{ $item['variant_name'] }}"
                >
                    {{
                        __(
                            'public.products.quote_button'
                        )
                    }}
                </button>
            </div>

            <div class="nv-product-confidence">
                <span>
                    <i aria-hidden="true">
                        ✓
                    </i>

                    {{
                        $locale === 'en'
                            ? 'Personalized advice'
                            : 'Asesoría personalizada'
                    }}
                </span>

                <span>
                    <i aria-hidden="true">
                        ✓
                    </i>

                    {{
                        $locale === 'en'
                            ? 'Direct quotation'
                            : 'Cotización directa'
                    }}
                </span>
            </div>
        </div>
    </article>
</div>