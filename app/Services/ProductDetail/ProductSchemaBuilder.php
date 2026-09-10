<?php

namespace App\Services\ProductDetail;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class ProductSchemaBuilder
{
    public function buildJson(
        Product $product,
        string $productName,
        string $seoDescription,
        string $canonicalUrl,
        Collection $gallery
    ): string {
        $schema = [
            '@context' =>
                'https://schema.org',

            '@type' =>
                'Product',

            'name' =>
                $productName,

            'description' =>
                $seoDescription,

            'url' =>
                $canonicalUrl,
        ];

        if ($product->brand?->name) {
            $schema['brand'] = [
                '@type' =>
                    'Brand',

                'name' =>
                    $product->brand->name,
            ];
        }

        $images = $gallery
            ->pluck('url')
            ->filter()
            ->values()
            ->all();

        if (! empty($images)) {
            $schema['image'] =
                $images;
        }

        $offers = $product->variants
            ->filter(
                fn (
                    ProductVariant $variant
                ) =>
                    $variant->price !== null
            )
            ->map(
                function (
                    ProductVariant $variant
                ) use (
                    $canonicalUrl
                ) {
                    $offer = [
                        '@type' =>
                            'Offer',

                        'url' =>
                            $canonicalUrl
                            . '?variant='
                            . $variant->id,

                        'sku' =>
                            $variant->sku,

                        'price' =>
                            (string) $variant->price,

                        'priceCurrency' =>
                            $variant->currency,
                    ];

                    $availability =
                        $this->availability(
                            $variant
                        );

                    if ($availability) {
                        $offer['availability'] =
                            $availability;
                    }

                    return $offer;
                }
            )
            ->values()
            ->all();

        if (! empty($offers)) {
            $schema['offers'] =
                $offers;
        }

        return json_encode(
            $schema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
        ) ?: '{}';
    }

    private function availability(
        ProductVariant $variant
    ): ?string {
        if (
            $variant->manage_stock
            && $variant->stock_quantity !== null
        ) {
            return $variant->stock_quantity > 0
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock';
        }

        return match ($variant->stock_status) {
            ProductVariant::STOCK_IN_STOCK =>
                'https://schema.org/InStock',

            ProductVariant::STOCK_OUT_OF_STOCK =>
                'https://schema.org/OutOfStock',

            ProductVariant::STOCK_BACKORDER =>
                'https://schema.org/BackOrder',

            default => null,
        };
    }
}