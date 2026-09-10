<div class="col-lg-6">
    <article
        class="nv-product-card"
        data-product-id="{{ $item['product_id'] }}"
        data-variant-id="{{ $item['variant_id'] }}"
        data-sku="{{ $item['sku'] }}"
    >
        <div
            class="
                nv-product-media
                {{ $item['media_class'] }}
            "
        >
            <div class="nv-product-media-header">
                <span>
                    {{ $item['variant_label'] }}
                </span>

                <span>
                    {{ $item['product_name'] }}
                </span>
            </div>

            <div class="nv-product-media-content">
                <strong>
                    {{ $item['variant_name'] }}
                </strong>

                <p>
                    {{ $item['description'] }}
                </p>
            </div>
        </div>

        <div class="nv-product-body">
            <span class="nv-product-category">
                {{ $item['category_name'] }}
            </span>

            <h3>
                {{ $item['title'] }}
            </h3>

            <p>
                {{ $item['description'] }}
            </p>

            <div class="nv-product-data">
                <div>
                    <strong>
                        {{
                            $item['price']
                            ?: __(
                                'public.products.price_pending'
                            )
                        }}
                    </strong>

                    <span>
                        {{
                            __(
                                'public.products.price_note'
                            )
                        }}
                    </span>
                </div>

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

            <div
                class="
                    d-grid
                    gap-2
                "
            >
                <a
                    href="{{ $item['detail_url'] }}"
                    class="
                        nv-button
                        nv-button-outline
                    "
                >
                    {{ __('catalog.view_details') }}
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
        </div>
    </article>
</div>