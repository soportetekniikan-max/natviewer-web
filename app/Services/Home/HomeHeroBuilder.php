<?php

namespace App\Services\Home;

use App\Models\ContactSetting;
use App\Models\Product;
use App\Services\Localization\LocalizedValueResolver;
use App\Services\ProductDetail\ProductMediaPresenter;

class HomeHeroBuilder
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue,
        private readonly HomeVariantFormatter $variantFormatter,
        private readonly ProductMediaPresenter $mediaPresenter
    ) {
    }

    public function build(
        ?Product $featuredProduct,
        ?ContactSetting $contactSettings,
        string $locale
    ): array {
        $featuredVariants =
            $featuredProduct
                ? $featuredProduct->variants
                : collect();

        $firstHeroVariant =
            $featuredVariants->first();

        $productName =
            $featuredProduct
                ? $this->localizedValue->resolve(
                    $featuredProduct,
                    'name',
                    $locale
                )
                : 'Falco UD';

        $media = [
            'primaryImageUrl' => null,
            'primaryImageAlt' => $productName,
        ];

        if ($featuredProduct) {
            $media =
                $this->mediaPresenter->build(
                    $featuredProduct,
                    $locale,
                    $productName
                );
        }

        return [
            'product_name' =>
                $productName,

            'short_description' =>
                $featuredProduct
                    ? $this->localizedValue->resolve(
                        $featuredProduct,
                        'short_description',
                        $locale
                    )
                    : __('public.hero.text'),

            'variant_label' =>
                $firstHeroVariant
                    ? $this->variantFormatter->shortLabel(
                        $firstHeroVariant,
                        $locale
                    )
                    : '8×42',

            'image_url' =>
                $media[
                    'primaryImageUrl'
                ],

            'image_alt' =>
                $media[
                    'primaryImageAlt'
                ],

            'detail_url' =>
                $featuredProduct
                    ? route(
                        $locale === 'en'
                            ? 'products.show.en'
                            : 'products.show.es',
                        [
                            'product' =>
                                $featuredProduct->slug,
                        ]
                    )
                    : null,

            'default_currency' =>
                $contactSettings
                    ?->default_currency
                ?? 'COP',
        ];
    }
}