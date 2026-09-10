<?php

namespace App\Services\Home;

use App\Models\ContactSetting;
use App\Models\Product;
use App\Services\Localization\LocalizedValueResolver;

class HomeHeroBuilder
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue,
        private readonly HomeVariantFormatter $variantFormatter
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
            $featuredVariants->get(0);

        $secondHeroVariant =
            $featuredVariants->get(1);

        $heroGlass = $firstHeroVariant
            ? data_get(
                $firstHeroVariant->specifications,
                'glass'
            )
            : null;

        return [
            'product_name' =>
                $featuredProduct
                    ? $this->localizedValue->resolve(
                        $featuredProduct,
                        'name',
                        $locale
                    )
                    : 'Falco UD',

            'short_description' =>
                $featuredProduct
                    ? $this->localizedValue->resolve(
                        $featuredProduct,
                        'short_description',
                        $locale
                    )
                    : __('public.hero.text'),

            'first_variant_label' =>
                $firstHeroVariant
                    ? $this->variantFormatter->shortLabel(
                        $firstHeroVariant,
                        $locale
                    )
                    : '8×42',

            'second_variant_label' =>
                $secondHeroVariant
                    ? $this->variantFormatter->shortLabel(
                        $secondHeroVariant,
                        $locale
                    )
                    : '10×42',

            'glass' =>
                $heroGlass,

            'default_currency' =>
                $contactSettings
                    ?->default_currency
                ?? 'COP',
        ];
    }
}