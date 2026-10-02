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
                '/(?:x|×)$/iu',
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
}