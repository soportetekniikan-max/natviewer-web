<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(string $locale): View
    {
        abort_unless(
            in_array($locale, ['es', 'en'], true),
            404
        );

        App::setLocale($locale);

        $products = Product::query()
            ->with([
                'category',
                'brand',
                'primaryImage',
                'variants' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('id');
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
            ->orderByDesc('is_featured')
            ->orderBy('id')
            ->get();

        $contactSettings =
            ContactSetting::query()->first();

        $featuredProduct =
            $products->first();

        $featuredVariants =
            $featuredProduct
                ? $featuredProduct->variants
                : collect();

        $firstHeroVariant =
            $featuredVariants->get(0);

        $secondHeroVariant =
            $featuredVariants->get(1);

        $heroGlass =
            $firstHeroVariant
                ? data_get(
                    $firstHeroVariant->specifications,
                    'glass'
                )
                : null;

        $hero = [
            'product_name' =>
                $featuredProduct
                    ? $this->localized(
                        $featuredProduct,
                        'name',
                        $locale
                    )
                    : 'Falco UD',

            'short_description' =>
                $featuredProduct
                    ? $this->localized(
                        $featuredProduct,
                        'short_description',
                        $locale
                    )
                    : __('public.hero.text'),

            'first_variant_label' =>
                $firstHeroVariant
                    ? $this->variantShortLabel(
                        $firstHeroVariant,
                        $locale
                    )
                    : '8×42',

            'second_variant_label' =>
                $secondHeroVariant
                    ? $this->variantShortLabel(
                        $secondHeroVariant,
                        $locale
                    )
                    : '10×42',

            'glass' => $heroGlass,

            'default_currency' =>
                $contactSettings
                    ?->default_currency
                ?? 'COP',
        ];

        $catalogItems = $products
            ->flatMap(
                function ($product) use ($locale) {
                    return $product->variants->map(
                        function ($variant) use (
                            $product,
                            $locale
                        ) {
                            $variantName =
                                $this->localized(
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
                                    $this->localized(
                                        $product,
                                        'name',
                                        $locale
                                    ),

                                'variant_name' =>
                                    $variantName,

                                'variant_label' =>
                                    $this->variantShortLabel(
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
                                    $this->localized(
                                        $product->category,
                                        'name',
                                        $locale
                                    ),

                                'description' =>
                                    $this->localized(
                                        $product,
                                        'short_description',
                                        $locale
                                    ),

                                'price' =>
                                    $this->formatPrice(
                                        $variant
                                    ),

                                'stock' =>
                                    $this->formatStock(
                                        $variant,
                                        $locale
                                    ),

                                'detail_url' =>
                                    route(
                                        $locale === 'en'
                                            ? 'products.show.en'
                                            : 'products.show.es',
                                        [
                                            'product' =>
                                                $product->slug,
                                        ]
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

        return view(
            'home',
            [
                'locale' =>
                    $locale,

                'hero' =>
                    $hero,

                'catalogItems' =>
                    $catalogItems,

                'contactSettings' =>
                    $contactSettings,
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

    private function variantShortLabel(
        ProductVariant $variant,
        string $locale
    ): ?string {
        $specifications =
            $variant->specifications ?? [];

        $magnification =
            $specifications['magnification']
            ?? null;

        $objectiveDiameter =
            $specifications[
                'objective_diameter'
            ]
            ?? null;

        if (
            $magnification
            && $objectiveDiameter
        ) {
            $magnification =
                preg_replace(
                    '/x$/i',
                    '',
                    trim($magnification)
                );

            $objectiveDiameter =
                preg_replace(
                    '/\s*mm$/i',
                    '',
                    trim($objectiveDiameter)
                );

            return
                $magnification
                . '×'
                . $objectiveDiameter;
        }

        return $this->localized(
            $variant,
            'name',
            $locale
        );
    }

    private function formatPrice(
        ProductVariant $variant
    ): ?string {
        if ($variant->price === null) {
            return null;
        }

        return
            $variant->currency
            . ' '
            . number_format(
                (float) $variant->price,
                0,
                ',',
                '.'
            );
    }

    private function formatStock(
        ProductVariant $variant,
        string $locale
    ): ?string {
        if (
            $variant->manage_stock
            && $variant->stock_quantity
                !== null
        ) {
            if (
                $variant->stock_quantity > 0
            ) {
                return $locale === 'en'
                    ? $variant->stock_quantity
                        . ' units available'
                    : $variant->stock_quantity
                        . ' unidades disponibles';
            }

            return $locale === 'en'
                ? 'Out of stock'
                : 'Agotado';
        }

        return match (
            $variant->stock_status
        ) {
            'in_stock' =>
                $locale === 'en'
                    ? 'Available'
                    : 'Disponible',

            'out_of_stock' =>
                $locale === 'en'
                    ? 'Out of stock'
                    : 'Agotado',

            'backorder' =>
                $locale === 'en'
                    ? 'Available on request'
                    : 'Disponible bajo pedido',

            default => null,
        };
    }
}