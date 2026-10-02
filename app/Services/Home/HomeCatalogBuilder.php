<?php

namespace App\Services\Home;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Localization\LocalizedValueResolver;
use App\Services\ProductDetail\ProductMediaPresenter;
use Illuminate\Support\Collection;

class HomeCatalogBuilder
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue,
        private readonly HomeVariantFormatter $variantFormatter,
        private readonly ProductMediaPresenter $mediaPresenter
    ) {
    }

    public function build(
        Collection $products,
        string $locale
    ): Collection {
        return $products
            ->flatMap(
                function (
                    Product $product
                ) use (
                    $locale
                ) {
                    $productName =
                        $this->localizedValue->resolve(
                            $product,
                            'name',
                            $locale
                        );

                    $media =
                        $this->mediaPresenter->build(
                            $product,
                            $locale,
                            $productName
                        );

                    $variantOptions =
                        $product->variants
                            ->map(
                                function (
                                    ProductVariant $variant
                                ) use (
                                    $locale
                                ) {
                                    return [
                                        'id' =>
                                            $variant->id,

                                        'label' =>
                                            $this->variantFormatter
                                                ->shortLabel(
                                                    $variant,
                                                    $locale
                                                ),
                                    ];
                                }
                            )
                            ->values()
                            ->all();

                    return $product->variants->map(
                        function (
                            ProductVariant $variant
                        ) use (
                            $product,
                            $productName,
                            $media,
                            $variantOptions,
                            $locale
                        ) {
                            $variantName =
                                $this->localizedValue->resolve(
                                    $variant,
                                    'name',
                                    $locale
                                );

                            return [
                                'product_id' =>
                                    $product->id,

                                'variant_id' =>
                                    $variant->id,

                                'sku' =>
                                    $variant->sku,

                                'product_name' =>
                                    $productName,

                                'variant_name' =>
                                    $variantName,

                                'variant_label' =>
                                    $this->variantFormatter
                                        ->shortLabel(
                                            $variant,
                                            $locale
                                        ),

                                'variant_options' =>
                                    $variantOptions,

                                'title' =>
                                    trim(
                                        (
                                            $product
                                                ->brand
                                                ?->name
                                            ?? 'Natviewer'
                                        )
                                        . ' '
                                        . (
                                            $variantName
                                            ?? ''
                                        )
                                    ),

                                'brand_name' =>
                                    $product
                                        ->brand
                                        ?->name
                                    ?? 'Natviewer',

                                'category_name' =>
                                    $this->localizedValue->resolve(
                                        $product->category,
                                        'name',
                                        $locale
                                    ),

                                'description' =>
                                    $this->localizedValue->resolve(
                                        $product,
                                        'short_description',
                                        $locale
                                    ),

                                'is_featured' =>
                                    (bool) $product
                                        ->is_featured,

                                'image_url' =>
                                    $media[
                                        'primaryImageUrl'
                                    ],

                                'image_alt' =>
                                    $media[
                                        'primaryImageAlt'
                                    ],

                                'detail_url' =>
                                    $this->detailUrl(
                                        $product,
                                        $locale
                                    ),
                            ];
                        }
                    );
                }
            )
            ->values();
    }

    private function detailUrl(
        Product $product,
        string $locale
    ): string {
        return route(
            $locale === 'en'
                ? 'products.show.en'
                : 'products.show.es',
            [
                'product' =>
                    $product->slug,
            ]
        );
    }
}