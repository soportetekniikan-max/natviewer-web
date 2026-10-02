<?php

namespace App\Services\ProductDetail;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeoBuilder
{
    public function build(
        Product $product,
        string $locale,
        string $productName,
        ?string $shortDescription,
        ?string $description,
        ?string $imageUrl,
        string $imageAlt
    ): array {
        $spanishUrl = route(
            'products.show.es',
            [
                'product' => $product->slug,
            ]
        );

        $englishUrl = route(
            'products.show.en',
            [
                'product' => $product->slug,
            ]
        );

        $canonicalUrl = $locale === 'en'
            ? $englishUrl
            : $spanishUrl;

        $metaTitleField =
            'meta_title_' . $locale;

        $metaDescriptionField =
            'meta_description_' . $locale;

        $title = $product->{$metaTitleField}
            ?: $productName . ' | Natviewer';

        $seoDescription =
            $product->{$metaDescriptionField}
            ?: $shortDescription
            ?: Str::limit(
                strip_tags(
                    (string) $description
                ),
                160,
                ''
            );

        return [
            'title' =>
                $title,

            'description' =>
                $seoDescription,

            'canonical' =>
                $canonicalUrl,

            'alternate_es' =>
                $spanishUrl,

            'alternate_en' =>
                $englishUrl,

            'alternate_default' =>
                $spanishUrl,

            'image' =>
                $imageUrl,

            'image_alt' =>
                $imageAlt,
        ];
    }
}