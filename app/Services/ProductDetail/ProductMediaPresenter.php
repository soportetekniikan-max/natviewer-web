<?php

namespace App\Services\ProductDetail;

use App\Models\Product;
use App\Services\Localization\LocalizedValueResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductMediaPresenter
{
    public function __construct(
        private readonly LocalizedValueResolver $localizedValue
    ) {
    }

    public function build(
        Product $product,
        string $locale,
        string $productName
    ): array {
        $primaryImage = $product->images
            ->firstWhere('is_primary', true)
            ?? $product->images->first();

        $primaryImageUrl = $this->imageUrl(
            $primaryImage
        );

        $primaryImageAlt = $primaryImage
            ? (
                $this->localizedValue->resolve(
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

                        'alt' => $this->localizedValue->resolve(
                            $image,
                            'alt',
                            $locale
                        ) ?: $productName,

                        'is_primary' =>
                            $primaryImage
                            && $primaryImage->id === $image->id,
                    ];
                }
            )
            ->filter(
                fn (array $image) =>
                    ! empty($image['url'])
            )
            ->values();

        return [
            'primaryImageUrl' =>
                $primaryImageUrl,

            'primaryImageAlt' =>
                $primaryImageAlt,

            'gallery' =>
                $gallery,
        ];
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
}