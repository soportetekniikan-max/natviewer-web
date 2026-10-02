<?php

namespace App\Services\Localization;

use Illuminate\Database\Eloquent\Model;

class LocalizedValueResolver
{
    public function resolve(
        ?Model $model,
        string $field,
        string $locale
    ): ?string {
        if (! $model) {
            return null;
        }

        $localizedField = $field . '_' . $locale;
        $fallbackField = $field . '_es';

        return $model->getAttribute($localizedField)
            ?: $model->getAttribute($fallbackField)
            ?: null;
    }
}