<?php

namespace App\Services\Home;

use App\Models\ProductVariant;
use App\Services\Localization\LocalizedValueResolver;

class HomeVariantFormatter
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue
    ) {
    }

    public function shortLabel(
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
            $magnification = preg_replace(
                '/x$/i',
                '',
                trim(
                    (string) $magnification
                )
            );

            $objectiveDiameter = preg_replace(
                '/\s*mm$/i',
                '',
                trim(
                    (string) $objectiveDiameter
                )
            );

            return
                $magnification
                . '×'
                . $objectiveDiameter;
        }

        return $this->localizedValue->resolve(
            $variant,
            'name',
            $locale
        );
    }

    public function price(
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

    public function stock(
        ProductVariant $variant,
        string $locale
    ): ?string {
        if (
            $variant->manage_stock
            && $variant->stock_quantity !== null
        ) {
            if ($variant->stock_quantity > 0) {
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

        return match ($variant->stock_status) {
            ProductVariant::STOCK_IN_STOCK =>
                $locale === 'en'
                    ? 'Available'
                    : 'Disponible',

            ProductVariant::STOCK_OUT_OF_STOCK =>
                $locale === 'en'
                    ? 'Out of stock'
                    : 'Agotado',

            ProductVariant::STOCK_BACKORDER =>
                $locale === 'en'
                    ? 'Available on request'
                    : 'Disponible bajo pedido',

            default => null,
        };
    }
}