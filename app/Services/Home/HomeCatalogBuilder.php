<?php

namespace App\Services\Home;

use App\Models\Product;
use App\Services\Localization\LocalizedValueResolver;
use Illuminate\Support\Collection;

class HomeCatalogBuilder
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue,
        private readonly HomeVariantFormatter $variantFormatter
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
                    return $product->variants->map(
                        function ($variant) use (
                            $product,
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
                                    $this->localizedValue->resolve(
                                        $product,
                                        'name',
                                        $locale
                                    ),

                                'variant_name' =>
                                    $variantName,

                                'variant_label' =>
                                    $this->variantFormatter->shortLabel(
                                        $variant,
                                        $locale
                                    ),

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

                                'price' =>
                                    $this->variantFormatter->price(
                                        $variant
                                    ),

                                'stock' =>
                                    $this->variantFormatter->stock(
                                        $variant,
                                        $locale
                                    ),

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
            ->values()
            ->map(
                function (
                    array $item,
                    int $index
                ) {
                    $item['media_class'] =
                        $index % 2 === 0
                            ? 'nv-product-media-green'
                            : 'nv-product-media-dark';

                    return $item;
                }
            );
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