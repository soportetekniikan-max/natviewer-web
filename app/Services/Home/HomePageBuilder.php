<?php

namespace App\Services\Home;

use App\Models\ContactSetting;
use App\Models\Product;

class HomePageBuilder
{
    public function __construct(
        private readonly HomeHeroBuilder $heroBuilder,
        private readonly HomeCatalogBuilder $catalogBuilder
    ) {
    }

    public function build(
        string $locale
    ): array {
        $products = $this->products();

        $contactSettings =
            ContactSetting::query()
                ->first();

        $featuredProduct =
            $products->first();

        return [
            'locale' =>
                $locale,

            'hero' =>
                $this->heroBuilder->build(
                    $featuredProduct,
                    $contactSettings,
                    $locale
                ),

            'catalogItems' =>
                $this->catalogBuilder->build(
                    $products,
                    $locale
                ),

            'contactSettings' =>
                $contactSettings,
        ];
    }

    private function products()
    {
        return Product::query()
            ->with([
                'category',
                'brand',
                'primaryImage',

                'variants' => function ($query) {
                    $query
                        ->where(
                            'is_active',
                            true
                        )
                        ->orderBy(
                            'sort_order'
                        )
                        ->orderBy(
                            'id'
                        );
                },
            ])
            ->where(
                'status',
                Product::STATUS_PUBLISHED
            )
            ->whereHas(
                'category',
                fn ($query) =>
                    $query->where(
                        'is_active',
                        true
                    )
            )
            ->whereHas(
                'brand',
                fn ($query) =>
                    $query->where(
                        'is_active',
                        true
                    )
            )
            ->orderByDesc(
                'is_featured'
            )
            ->orderBy(
                'id'
            )
            ->get();
    }
}