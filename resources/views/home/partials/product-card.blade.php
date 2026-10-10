<div class="nv-product-story-item">
    <article
        @class([
            'nv-product-card',
            'nv-product-story',
            'is-reverse' =>
                (($position ?? 0) % 2) === 1,
            'is-featured' =>
                $item['is_featured'],
        ])
        data-product-id="{{ $item['product_id'] }}"
        data-variant-id="{{ $item['variant_id'] }}"
        data-sku="{{ $item['sku'] }}"
    >
        {{-- =================================================
             PRODUCT MEDIA
             ================================================= --}}
        <div class="nv-product-story-media">
            <div class="nv-product-story-media-top">
                <span class="nv-product-story-number">
                    {{
                        str_pad(
                            (string) (
                                ($position ?? 0) + 1
                            ),
                            2,
                            '0',
                            STR_PAD_LEFT
                        )
                    }}
                </span>

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
                            {{ $item['variant_label'] }}
                        </strong>
                    </div>
                @endif
            </a>

            <div class="nv-product-story-media-footer">
                <span>
                    {{ $item['category_name'] }}
                </span>

                <span>
                    {{ $item['sku'] }}
                </span>
            </div>
        </div>


        {{-- =================================================
             PRODUCT CONTENT
             ================================================= --}}
        <div class="nv-product-story-content">
            <div class="nv-product-story-heading">
                <div class="nv-product-story-heading-main">
                    <span class="nv-product-brand">
                        {{ $item['brand_name'] }}
                    </span>

                    <h3>
                        {{ $item['title'] }}
                    </h3>
                </div>

                <span class="nv-product-story-variant">
                    {{ $item['variant_label'] }}
                </span>
            </div>

            <p class="nv-product-description">
                {{ $item['description'] }}
            </p>


            {{-- =================================================
                 VARIANTS
                 Kept for future multi-variant products
                 ================================================= --}}
            @if (
                count(
                    $item['variant_options']
                ) > 1
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
                                @class([
                                    'nv-product-variant-chip',
                                    'is-active' =>
                                        $variantOption['id']
                                        === $item['variant_id'],
                                ])
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


            {{-- =================================================
                 COMMERCIAL SUPPORT
                 ================================================= --}}
            <div class="nv-product-story-support">
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


            {{-- =================================================
                 ACTIONS
                 ================================================= --}}
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


            {{-- =================================================
                 EDITORIAL FOOTER
                 ================================================= --}}
            <div class="nv-product-story-footer">
                <span>
                    Natviewer
                </span>

                <span
                    class="nv-product-story-footer-line"
                    aria-hidden="true"
                ></span>

                <span>
                    {{ $item['sku'] }}
                </span>
            </div>
        </div>
    </article>
</div>