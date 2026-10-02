<?php

namespace App\Services\ProductDetail;

use App\Models\Product;
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
}