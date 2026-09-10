<?php

namespace App\Services\ProductDetail;

use App\Models\Product;
use App\Services\Localization\LocalizedValueResolver;
use Illuminate\Http\Request;

class ProductDetailBuilder
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue,
        private readonly ProductMediaPresenter $mediaPresenter,
        private readonly ProductVariantPresenter $variantPresenter,
        private readonly ProductSeoBuilder $seoBuilder,
        private readonly ProductSchemaBuilder $schemaBuilder
    ) {
    }

    public function build(
        Product $product,
        Request $request,
        string $locale
    ): array {
        $this->loadProduct(
            $product
        );

        $this->ensureProductIsPublic(
            $product
        );

        $productName =
            $this->localizedValue->resolve(
                $product,
                'name',
                $locale
            ) ?: $product->name_es;

        $shortDescription =
            $this->localizedValue->resolve(
                $product,
                'short_description',
                $locale
            );

        $description =
            $this->localizedValue->resolve(
                $product,
                'description',
                $locale
            ) ?: $shortDescription;

        $categoryName =
            $this->localizedValue->resolve(
                $product->category,
                'name',
                $locale
            );

        $media = $this->mediaPresenter->build(
            $product,
            $locale,
            $productName
        );

        $variantData =
            $this->variantPresenter->build(
                $product,
                $request,
                $locale
            );

        $seo = $this->seoBuilder->build(
            $product,
            $locale,
            $productName,
            $shortDescription,
            $description,
            $media['primaryImageUrl'],
            $media['primaryImageAlt']
        );

        $schemaJson =
            $this->schemaBuilder->buildJson(
                $product,
                $productName,
                $seo['description'],
                $seo['canonical'],
                $media['gallery']
            );

        return [
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
                $media['gallery'],

            'primaryImageUrl' =>
                $media['primaryImageUrl'],

            'primaryImageAlt' =>
                $media['primaryImageAlt'],

            'variants' =>
                $variantData['variants'],

            'selectedVariantId' =>
                $variantData['selectedVariantId'],

            'selectedVariant' =>
                $variantData['selectedVariant'],

            'specificationGroups' =>
                $variantData['specificationGroups'],

            'seo' =>
                $seo,

            'navigation' =>
                $this->navigation(
                    $locale
                ),

            'quoteAction' =>
                route(
                    'quotes.store',
                    [
                        'locale' =>
                            $locale,
                    ]
                ),

            'utm' =>
                $this->utm(
                    $request
                ),

            'schemaJson' =>
                $schemaJson,
        ];
    }

    private function loadProduct(
        Product $product
    ): void {
        $product->load([
            'category',
            'brand',

            'variants' => function ($query) {
                $query
                    ->where(
                        'is_active',
                        true
                    )
                    ->orderByDesc(
                        'is_default'
                    )
                    ->orderBy(
                        'sort_order'
                    )
                    ->orderBy(
                        'id'
                    );
            },

            'images' => function ($query) {
                $query
                    ->where(
                        function ($imageQuery) {
                            $imageQuery
                                ->whereNull(
                                    'variant_id'
                                )
                                ->orWhereHas(
                                    'variant',
                                    function (
                                        $variantQuery
                                    ) {
                                        $variantQuery
                                            ->where(
                                                'is_active',
                                                true
                                            );
                                    }
                                );
                        }
                    )
                    ->orderByDesc(
                        'is_primary'
                    )
                    ->orderBy(
                        'sort_order'
                    )
                    ->orderBy(
                        'id'
                    );
            },
        ]);
    }

    private function ensureProductIsPublic(
        Product $product
    ): void {
        abort_unless(
            $product->status
                === Product::STATUS_PUBLISHED,
            404
        );

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
    }

    private function navigation(
        string $locale
    ): array {
        $homeUrl = route(
            'home',
            [
                'locale' =>
                    $locale,
            ]
        );

        return [
            'home' =>
                $homeUrl,

            'catalog' =>
                $homeUrl . '#products',
        ];
    }

    private function utm(
        Request $request
    ): array {
        return [
            'utm_source' =>
                $request->query(
                    'utm_source'
                ),

            'utm_medium' =>
                $request->query(
                    'utm_medium'
                ),

            'utm_campaign' =>
                $request->query(
                    'utm_campaign'
                ),

            'utm_term' =>
                $request->query(
                    'utm_term'
                ),

            'utm_content' =>
                $request->query(
                    'utm_content'
                ),
        ];
    }
}