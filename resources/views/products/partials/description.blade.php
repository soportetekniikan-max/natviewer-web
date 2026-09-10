@if ($description)
    <section class="nv-detail-lower">
        <div class="nv-detail-description">
            <div>
                <span class="nv-eyebrow">
                    Natviewer
                </span>

                <h2>
                    {{
                        __(
                            'product.description_title'
                        )
                    }}
                </h2>
            </div>

            <p>
                {{ $description }}
            </p>
        </div>
    </section>
@endif