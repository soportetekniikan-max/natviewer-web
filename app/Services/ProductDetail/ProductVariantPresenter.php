<?php

namespace App\Services\ProductDetail;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Localization\LocalizedValueResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class ProductVariantPresenter
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue
    ) {
    }

    public function build(
        Product $product,
        Request $request,
        string $locale
    ): array {
        $defaultVariant = $product->variants
            ->firstWhere('is_default', true)
            ?? $product->variants->first();

        $requestedVariantId = (int) $request->query(
            'variant',
            $request->old(
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
                        'id' =>
                            $variant->id,

                        'sku' =>
                            $variant->sku,

                        'name' =>
                            $this->localizedValue->resolve(
                                $variant,
                                'name',
                                $locale
                            ) ?: $variant->sku,

                        'price' =>
                            $this->formatPrice(
                                $variant
                            ),

                        'stock' =>
                            $this->stockText(
                                $variant
                            ),

                        'is_default' =>
                            $variant->is_default,

                        'is_selected' =>
                            $selectedVariant
                            && $selectedVariant->id === $variant->id,

                        'specifications' =>
                            $this->specifications(
                                $variant
                            ),
                    ];
                }
            )
            ->values();

        $selectedVariantData = $selectedVariant
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

        return [
            'variants' =>
                $variants,

            'selectedVariantId' =>
                $selectedVariant?->id,

            'selectedVariant' =>
                $selectedVariantData,

            'specificationGroups' =>
                $specificationGroups,
        ];
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
            && $variant->stock_quantity !== null
        ) {
            if ($variant->stock_quantity > 0) {
                return __(
                    'product.units_available',
                    [
                        'count' =>
                            $variant->stock_quantity,
                    ]
                );
            }

            return __(
                'product.stock_out'
            );
        }

        return match ($variant->stock_status) {
            ProductVariant::STOCK_IN_STOCK =>
                __('product.stock_available'),

            ProductVariant::STOCK_OUT_OF_STOCK =>
                __('product.stock_out'),

            ProductVariant::STOCK_BACKORDER =>
                __('product.stock_backorder'),

            default =>
                __('product.stock_pending'),
        };
    }

    private function specifications(
        ProductVariant $variant
    ): array {
        return collect(
            $variant->specifications ?? []
        )
            ->map(
                fn ($value, $key) => [
                    'label' =>
                        $this->formatSpecificationLabel(
                            (string) $key
                        ),

                    'value' =>
                        $this->formatSpecificationValue(
                            (string) $value
                        ),
                ]
            )
            ->values()
            ->all();
    }

    private function formatSpecificationLabel(
        string $key
    ): string {
        $translationKey =
            'product.spec_labels.'
            . $key;

        if (Lang::has($translationKey)) {
            return __($translationKey);
        }

        return Str::headline(
            str_replace(
                '_',
                ' ',
                $key
            )
        );
    }

    private function formatSpecificationValue(
        string $value
    ): string {
        /*
         * Los códigos técnicos simples pueden
         * traducirse desde los archivos de idioma.
         *
         * Valores físicos como "42 mm",
         * "675 g" o "93 m" se muestran tal cual.
         */
        if (
            preg_match(
                '/^[a-z0-9_]+$/',
                $value
            ) === 1
        ) {
            $translationKey =
                'product.spec_values.'
                . $value;

            if (Lang::has($translationKey)) {
                return __($translationKey);
            }
        }

        return $value;
    }
}