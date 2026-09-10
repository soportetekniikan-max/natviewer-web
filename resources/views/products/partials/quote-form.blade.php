<section
    class="nv-detail-quote-section"
    id="quote"
>
    <div class="nv-detail-quote-card">
        <div class="nv-detail-quote-intro">
            <span>
                {{
                    __(
                        'product.quote_eyebrow'
                    )
                }}
            </span>

            <h2>
                {{
                    __(
                        'product.quote_title'
                    )
                }}
            </h2>

            <p>
                {{
                    __(
                        'product.quote_text'
                    )
                }}
            </p>

            <div class="nv-detail-trust">
                <span>
                    {{
                        __(
                            'product.trust_no_payment'
                        )
                    }}
                </span>

                <span>
                    {{
                        __(
                            'product.trust_personal_assistance'
                        )
                    }}
                </span>

                <span>
                    {{
                        __(
                            'product.trust_whatsapp'
                        )
                    }}
                </span>
            </div>
        </div>

        <div class="nv-detail-quote-form">
            @if ($variants->isNotEmpty())
                <form
                    method="POST"
                    action="{{ $quoteAction }}"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $productId }}"
                    >

                    @foreach (
                        $utm
                        as $utmField => $utmValue
                    )
                        <input
                            type="hidden"
                            name="{{ $utmField }}"
                            value="{{ $utmValue }}"
                        >
                    @endforeach

                    <div class="row g-3">
                        <div class="col-12">
                            <label
                                for="productVariant"
                                class="
                                    nv-detail-form-label
                                "
                            >
                                {{
                                    __(
                                        'product.variant'
                                    )
                                }}
                                *
                            </label>

                            <select
                                id="productVariant"
                                name="product_variant_id"
                                class="
                                    form-select
                                    @error(
                                        'product_variant_id'
                                    )
                                        is-invalid
                                    @enderror
                                "
                                required
                                data-product-variant-select
                            >
                                @foreach (
                                    $variants
                                    as $variant
                                )
                                    <option
                                        value="{{
                                            $variant[
                                                'id'
                                            ]
                                        }}"
                                        @selected(
                                            (int) old(
                                                'product_variant_id',
                                                $selectedVariantId
                                            )
                                            ===
                                            $variant[
                                                'id'
                                            ]
                                        )
                                    >
                                        {{
                                            $variant[
                                                'name'
                                            ]
                                        }}

                                        —

                                        {{
                                            $variant[
                                                'price'
                                            ]
                                            ?: __(
                                                'product.price_pending'
                                            )
                                        }}
                                    </option>
                                @endforeach
                            </select>

                            @error(
                                'product_variant_id'
                            )
                                <div
                                    class="
                                        invalid-feedback
                                    "
                                >
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label
                                for="detailCustomerName"
                                class="
                                    nv-detail-form-label
                                "
                            >
                                {{
                                    __(
                                        'product.name'
                                    )
                                }}
                                *
                            </label>

                            <input
                                type="text"
                                id="detailCustomerName"
                                name="customer_name"
                                value="{{ old(
                                    'customer_name'
                                ) }}"
                                maxlength="150"
                                autocomplete="name"
                                required
                                class="
                                    form-control
                                    @error(
                                        'customer_name'
                                    )
                                        is-invalid
                                    @enderror
                                "
                            >

                            @error('customer_name')
                                <div
                                    class="
                                        invalid-feedback
                                    "
                                >
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label
                                for="detailCustomerPhone"
                                class="
                                    nv-detail-form-label
                                "
                            >
                                {{
                                    __(
                                        'product.phone'
                                    )
                                }}
                                *
                            </label>

                            <input
                                type="tel"
                                id="detailCustomerPhone"
                                name="customer_phone"
                                value="{{ old(
                                    'customer_phone'
                                ) }}"
                                maxlength="40"
                                autocomplete="tel"
                                required
                                class="
                                    form-control
                                    @error(
                                        'customer_phone'
                                    )
                                        is-invalid
                                    @enderror
                                "
                            >

                            @error('customer_phone')
                                <div
                                    class="
                                        invalid-feedback
                                    "
                                >
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label
                                for="detailCustomerEmail"
                                class="
                                    nv-detail-form-label
                                "
                            >
                                {{
                                    __(
                                        'product.email'
                                    )
                                }}
                            </label>

                            <input
                                type="email"
                                id="detailCustomerEmail"
                                name="customer_email"
                                value="{{ old(
                                    'customer_email'
                                ) }}"
                                maxlength="255"
                                autocomplete="email"
                                class="
                                    form-control
                                    @error(
                                        'customer_email'
                                    )
                                        is-invalid
                                    @enderror
                                "
                            >

                            @error('customer_email')
                                <div
                                    class="
                                        invalid-feedback
                                    "
                                >
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label
                                for="detailQuantity"
                                class="
                                    nv-detail-form-label
                                "
                            >
                                {{
                                    __(
                                        'product.quantity'
                                    )
                                }}
                                *
                            </label>

                            <input
                                type="number"
                                id="detailQuantity"
                                name="quantity"
                                min="1"
                                max="99"
                                value="{{ old(
                                    'quantity',
                                    1
                                ) }}"
                                required
                                class="
                                    form-control
                                    @error(
                                        'quantity'
                                    )
                                        is-invalid
                                    @enderror
                                "
                            >

                            @error('quantity')
                                <div
                                    class="
                                        invalid-feedback
                                    "
                                >
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label
                                for="detailCustomerMessage"
                                class="
                                    nv-detail-form-label
                                "
                            >
                                {{
                                    __(
                                        'product.message'
                                    )
                                }}
                            </label>

                            <textarea
                                id="detailCustomerMessage"
                                name="customer_message"
                                maxlength="2000"
                                class="
                                    form-control
                                    @error(
                                        'customer_message'
                                    )
                                        is-invalid
                                    @enderror
                                "
                                placeholder="{{
                                    __(
                                        'product.message_placeholder'
                                    )
                                }}"
                            >{{ old('customer_message') }}</textarea>

                            @error('customer_message')
                                <div
                                    class="
                                        invalid-feedback
                                    "
                                >
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button
                                type="submit"
                                class="nv-detail-cta"
                            >
                                {{
                                    __(
                                        'product.submit_quote'
                                    )
                                }}
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div
                    class="
                        alert
                        alert-light
                    "
                >
                    {{
                        __(
                            'product.no_variants'
                        )
                    }}
                </div>
            @endif
        </div>
    </div>
</section>