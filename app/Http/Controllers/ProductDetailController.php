<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductDetailController extends Controller
{
    public function show(Product $product): View
    {
        $locale = (string) request()
            ->route('locale', 'es');

        abort_unless(
            in_array(
                $locale,
                ['es', 'en'],
                true
            ),
            404
        );

        App::setLocale($locale);

        abort_unless(
            $product->status === Product::STATUS_PUBLISHED,
            404
        );

        $product->load([
            'category',
            'brand',

            'variants' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderByDesc('is_default')
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },

            'images' => function ($query) {
                $query
                    ->where(function ($imageQuery) {
                        $imageQuery
                            ->whereNull('variant_id')
                            ->orWhereHas(
                                'variant',
                                function ($variantQuery) {
                                    $variantQuery->where(
                                        'is_active',
                                        true
                                    );
                                }
                            );
                    })
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);

        abort_unless(
            $product->category
                && $product->category->is_active,
            404
        );

        abort_unless(
            $product->brand
                && $product->brand->is_active,
            404
        );

        $productName = $this->localized(
            $product,
            'name',
            $locale
        ) ?: $product->name_es;

        $shortDescription = $this->localized(
            $product,
            'short_description',
            $locale
        );

        $description = $this->localized(
            $product,
            'description',
            $locale
        ) ?: $shortDescription;

        $categoryName = $this->localized(
            $product->category,
            'name',
            $locale
        );

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

        $primaryImage = $product->images
            ->firstWhere(
                'is_primary',
                true
            )
            ?? $product->images->first();

        $primaryImageUrl = $this->imageUrl(
            $primaryImage
        );

        $primaryImageAlt = $primaryImage
            ? (
                $this->localized(
                    $primaryImage,
                    'alt',
                    $locale
                )
                ?: $productName
            )
            : $productName;

        $gallery = $product->images
            ->map(
                function ($image) use (
                    $locale,
                    $productName,
                    $primaryImage
                ) {
                    return [
                        'id' => $image->id,

                        'url' => $this->imageUrl(
                            $image
                        ),

                        'alt' => $this->localized(
                            $image,
                            'alt',
                            $locale
                        ) ?: $productName,

                        'is_primary' =>
                            $primaryImage
                            && $primaryImage->id
                                === $image->id,
                    ];
                }
            )
            ->filter(
                fn (array $image) =>
                    ! empty($image['url'])
            )
            ->values();

        $defaultVariant = $product->variants
            ->firstWhere(
                'is_default',
                true
            )
            ?? $product->variants->first();

        $requestedVariantId = (int) request()->query(
            'variant',
            old(
                'product_variant_id',
                $defaultVariant?->id
            )
        );

        if (
            ! $product->variants->contains(
                'id',
                $requestedVariantId
            )
        ) {
            $requestedVariantId =
                $defaultVariant?->id;
        }

        $selectedVariant = $product->variants
            ->firstWhere(
                'id',
                $requestedVariantId
            )
            ?? $defaultVariant;

        $variants = $product->variants
            ->map(
                function (
                    ProductVariant $variant
                ) use (
                    $locale,
                    $selectedVariant
                ) {
                    return [
                        'id' => $variant->id,

                        'sku' => $variant->sku,

                        'name' => $this->localized(
                            $variant,
                            'name',
                            $locale
                        ) ?: $variant->sku,

                        'price' => $this->formatPrice(
                            $variant
                        ),

                        'stock' => $this->stockText(
                            $variant
                        ),

                        'is_default' =>
                            $variant->is_default,

                        'is_selected' =>
                            $selectedVariant
                            && $selectedVariant->id
                                === $variant->id,

                        'specifications' =>
                            collect(
                                $variant->specifications
                                ?? []
                            )
                                ->map(
                                    fn (
                                        $value,
                                        $key
                                    ) => [
                                        'label' =>
                                            $this
                                                ->formatSpecificationLabel(
                                                    (string) $key
                                                ),

                                        'value' =>
                                            (string) $value,
                                    ]
                                )
                                ->values()
                                ->all(),
                    ];
                }
            )
            ->values();

        $selectedVariantData =
            $selectedVariant
                ? $variants->firstWhere(
                    'id',
                    $selectedVariant->id
                )
                : null;

        $specificationGroups = $variants
            ->filter(
                fn (array $variant) =>
                    ! empty(
                        $variant['specifications']
                    )
            )
            ->values();

        $metaTitleField =
            'meta_title_' . $locale;

        $metaDescriptionField =
            'meta_description_' . $locale;

        $seoTitle =
            $product->{$metaTitleField}
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

        $productSchema =
            $this->buildProductSchema(
                $product,
                $productName,
                $seoDescription,
                $canonicalUrl
            );

        $schemaJson = json_encode(
            $productSchema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
        );

        return view(
            'products.show',
            [
                'locale' =>
                    $locale,

                'productId' =>
                    $product->id,

                'productName' =>
                    $productName,

                'shortDescription' =>
                    $shortDescription,

                'description' =>
                    $description,

                'categoryName' =>
                    $categoryName,

                'brandName' =>
                    $product->brand?->name,

                'gallery' =>
                    $gallery,

                'primaryImageUrl' =>
                    $primaryImageUrl,

                'primaryImageAlt' =>
                    $primaryImageAlt,

                'variants' =>
                    $variants,

                'selectedVariantId' =>
                    $selectedVariant?->id,

                'selectedVariant' =>
                    $selectedVariantData,

                'specificationGroups' =>
                    $specificationGroups,

                'seo' => [
                    'title' =>
                        $seoTitle,

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
                        $primaryImageUrl,

                    'image_alt' =>
                        $primaryImageAlt,
                ],

                'navigation' => [
                    'home' => route(
                        'home',
                        [
                            'locale' =>
                                $locale,
                        ]
                    ),

                    'catalog' => route(
                        'home',
                        [
                            'locale' =>
                                $locale,
                        ]
                    ) . '#products',
                ],

                'quoteAction' => route(
                    'quotes.store',
                    [
                        'locale' =>
                            $locale,
                    ]
                ),

                'utm' => [
                    'utm_source' =>
                        request()->query(
                            'utm_source'
                        ),

                    'utm_medium' =>
                        request()->query(
                            'utm_medium'
                        ),

                    'utm_campaign' =>
                        request()->query(
                            'utm_campaign'
                        ),

                    'utm_term' =>
                        request()->query(
                            'utm_term'
                        ),

                    'utm_content' =>
                        request()->query(
                            'utm_content'
                        ),
                ],

                'schemaJson' =>
                    $schemaJson ?: '{}',
            ]
        );
    }

    private function localized(
        ?Model $model,
        string $field,
        string $locale
    ): ?string {
        if (! $model) {
            return null;
        }

        $localizedField =
            $field . '_' . $locale;

        $fallbackField =
            $field . '_es';

        return $model->getAttribute(
            $localizedField
        )
            ?: $model->getAttribute(
                $fallbackField
            )
            ?: null;
    }

   private function imageUrl(
        ?Model $image
    ): ?string {
        if (! $image) {
            return null;
        }

        $diskName = (string) $image->getAttribute(
            'disk'
        );

        $path = (string) $image->getAttribute(
            'path'
        );

        if (
            $diskName === ''
            || $path === ''
        ) {
            return null;
        }

        /** @var FilesystemAdapter $filesystem */
        $filesystem = Storage::disk(
            $diskName
        );

        $imageUrl = $filesystem->url(
            $path
        );

        if (
            Str::startsWith(
                $imageUrl,
                [
                    'http://',
                    'https://',
                ]
            )
        ) {
            return $imageUrl;
        }

        return url($imageUrl);
    }

    private function formatPrice(
        ProductVariant $variant
    ): ?string {
        if ($variant->price === null) {
            return null;
        }

        return $variant->currency
            . ' '
            . number_format(
                (float) $variant->price,
                0,
                ',',
                '.'
            );
    }

    private function stockText(
        ProductVariant $variant
    ): string {
        if (
            $variant->manage_stock
            && $variant->stock_quantity
                !== null
        ) {
            if (
                $variant->stock_quantity > 0
            ) {
                return __(
                    'product.units_available',
                    [
                        'count' =>
                            $variant
                                ->stock_quantity,
                    ]
                );
            }

            return __(
                'product.stock_out'
            );
        }

        return match (
            $variant->stock_status
        ) {
            ProductVariant::STOCK_IN_STOCK =>
                __(
                    'product.stock_available'
                ),

            ProductVariant::STOCK_OUT_OF_STOCK =>
                __(
                    'product.stock_out'
                ),

            ProductVariant::STOCK_BACKORDER =>
                __(
                    'product.stock_backorder'
                ),

            default =>
                __(
                    'product.stock_pending'
                ),
        };
    }

    private function formatSpecificationLabel(
        string $key
    ): string {
        return Str::headline(
            str_replace(
                '_',
                ' ',
                $key
            )
        );
    }

    private function buildProductSchema(
        Product $product,
        string $productName,
        string $seoDescription,
        string $canonicalUrl
    ): array {
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

        $images = $product->images
            ->map(
                fn ($image) =>
                    $this->imageUrl(
                        $image
                    )
            )
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
                        $this->schemaAvailability(
                            $variant
                        );

                    if ($availability) {
                        $offer[
                            'availability'
                        ] = $availability;
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

        return $schema;
    }

    private function schemaAvailability(
        ProductVariant $variant
    ): ?string {
        if (
            $variant->manage_stock
            && $variant->stock_quantity
                !== null
        ) {
            return $variant->stock_quantity > 0
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock';
        }

        return match (
            $variant->stock_status
        ) {
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