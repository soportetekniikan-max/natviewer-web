@if ($specificationGroups->isNotEmpty())
    <section class="nv-detail-specs">
        <div
            class="
                nv-section-header
                nv-detail-specs-heading
            "
        >
            <span class="nv-eyebrow">
                {{
                    __(
                        'product.technical_eyebrow'
                    )
                }}
            </span>

            <h2>
                {{
                    __(
                        'product.specifications'
                    )
                }}
            </h2>
        </div>

        @foreach (
            $specificationGroups
            as $variant
        )
            <article
                class="nv-detail-spec-card"
            >
                <div
                    class="
                        nv-detail-spec-header
                    "
                >
                    <h3>
                        {{ $variant['name'] }}
                    </h3>

                    <small>
                        {{
                            __(
                                'product.sku'
                            )
                        }}:
                        {{ $variant['sku'] }}
                    </small>
                </div>

                <dl class="nv-detail-spec-list">
                    @foreach (
                        $variant[
                            'specifications'
                        ]
                        as $specification
                    )
                        <div
                            class="
                                nv-detail-spec-row
                            "
                        >
                            <dt>
                                {{
                                    $specification[
                                        'label'
                                    ]
                                }}
                            </dt>

                            <dd>
                                {{
                                    $specification[
                                        'value'
                                    ]
                                }}
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </article>
        @endforeach
    </section>
@endif