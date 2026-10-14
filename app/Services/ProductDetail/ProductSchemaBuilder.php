<?php

namespace App\Services\ProductDetail;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Describes quotation-only product pages without advertising a commercial offer.
 */
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
            '@context' => 'https://schema.org',

            '@type' => 'WebPage',

            '@id' => $canonicalUrl.'#webpage',

            'name' => $productName,

            'description' => $seoDescription,

            'url' => $canonicalUrl,
        ];

        if ($product->variants->count() === 1) {
            $schema['identifier'] = $product->variants->first()->sku;
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
