<div
    class="nv-detail-gallery"
    aria-label="{{ __('product.gallery') }}"
>
    <div class="nv-detail-main-image">
        @if ($primaryImageUrl)
            <img
                src="{{ $primaryImageUrl }}"
                alt="{{ $primaryImageAlt }}"
                data-main-product-image
            >
        @else
            <div
                class="
                    nv-detail-main-image-placeholder
                "
            >
                <img
                    src="{{ asset(
                        'images/logo-natviewer-white.png'
                    ) }}"
                    alt="Natviewer"
                >

                <strong>
                    {{ $productName }}
                </strong>
            </div>
        @endif
    </div>

    @if ($gallery->count() > 1)
        <div class="nv-detail-thumbnails">
            @foreach ($gallery as $image)
                <button
                    type="button"
                    class="
                        nv-detail-thumb
                        {{
                            $image['is_primary']
                                ? 'is-active'
                                : ''
                        }}
                    "
                    data-gallery-image
                    data-image-url="{{ $image['url'] }}"
                    data-image-alt="{{ $image['alt'] }}"
                    aria-label="{{ $image['alt'] }}"
                    aria-pressed="{{
                        $image['is_primary']
                            ? 'true'
                            : 'false'
                    }}"
                >
                    <img
                        src="{{ $image['url'] }}"
                        alt="{{ $image['alt'] }}"
                        loading="lazy"
                    >
                </button>
            @endforeach
        </div>
    @endif
</div>