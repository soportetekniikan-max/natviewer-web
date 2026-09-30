<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductImageRequest;
use App\Http\Requests\Admin\UpdateProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductImageController extends Controller
{
    public function store(
        StoreProductImageRequest $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validated();

        $files = $request->file(
            'images',
            []
        );

        $storedPaths = [];

        try {
            foreach ($files as $file) {
                $storedPaths[] = $file->store(
                    'products/'.$product->id,
                    'public'
                );
            }

            DB::transaction(
                function () use (
                    $product,
                    $validated,
                    $storedPaths
                ): void {
                    $hasImages = $product
                        ->images()
                        ->exists();

                    $makeFirstPrimary =
                        ! $hasImages
                        || (bool) $validated[
                            'make_primary'
                        ];

                    if ($makeFirstPrimary) {
                        $product
                            ->images()
                            ->update([
                                'is_primary' => false,
                            ]);
                    }

                    $currentMaxSortOrder =
                        $product
                            ->images()
                            ->max('sort_order')
                        ?? 0;

                    $initialSortOrder =
                        $validated['sort_order']
                        ?? (
                            $currentMaxSortOrder
                            + 10
                        );

                    foreach (
                        $storedPaths
                        as $index => $path
                    ) {
                        $product
                            ->images()
                            ->create([
                                'variant_id' =>
                                    $validated[
                                        'variant_id'
                                    ]
                                    ?? null,

                                'disk' =>
                                    'public',

                                'path' =>
                                    $path,

                                'alt_es' =>
                                    $validated[
                                        'alt_es'
                                    ]
                                    ?? null,

                                'alt_en' =>
                                    $validated[
                                        'alt_en'
                                    ]
                                    ?? null,

                                'is_primary' =>
                                    $makeFirstPrimary
                                    && $index === 0,

                                'sort_order' =>
                                    $initialSortOrder
                                    + ($index * 10),
                            ]);
                    }
                }
            );
        } catch (Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')
                    ->delete($path);
            }

            throw $exception;
        }

        $imageCount = count(
            $storedPaths
        );

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                $imageCount === 1
                    ? 'Imagen subida correctamente.'
                    : $imageCount
                        . ' imágenes subidas correctamente.'
            );
    }

    public function update(
        UpdateProductImageRequest $request,
        Product $product,
        ProductImage $image
    ): RedirectResponse {
        $this->ensureImageBelongsToProduct(
            $product,
            $image
        );

        $validated = $request->validated();

        $image->update([
            'variant_id' =>
                $validated['variant_id']
                    ?? null,

            'alt_es' =>
                $validated['alt_es']
                    ?? null,

            'alt_en' =>
                $validated['alt_en']
                    ?? null,

            'sort_order' =>
                $validated['sort_order'],
        ]);

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                'Datos de la imagen actualizados.'
            );
    }

    public function setPrimary(
        Product $product,
        ProductImage $image
    ): RedirectResponse {
        $this->ensureImageBelongsToProduct(
            $product,
            $image
        );

        DB::transaction(
            function () use (
                $product,
                $image
            ): void {
                $product
                    ->images()
                    ->update([
                        'is_primary' => false,
                    ]);

                $image->update([
                    'is_primary' => true,
                ]);
            }
        );

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                'Imagen principal actualizada.'
            );
    }

    public function destroy(
        Product $product,
        ProductImage $image
    ): RedirectResponse {
        $this->ensureImageBelongsToProduct(
            $product,
            $image
        );

        $disk = $image->disk;
        $path = $image->path;
        $wasPrimary = $image->is_primary;

        DB::transaction(
            function () use (
                $product,
                $image,
                $wasPrimary
            ): void {
                $image->delete();

                if ($wasPrimary) {
                    $nextImage = $product
                        ->images()
                        ->orderBy(
                            'sort_order'
                        )
                        ->orderBy('id')
                        ->first();

                    if ($nextImage) {
                        $nextImage->update([
                            'is_primary' => true,
                        ]);
                    }
                }
            }
        );

        Storage::disk($disk)
            ->delete($path);

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                'Imagen eliminada correctamente.'
            );
    }

    private function ensureImageBelongsToProduct(
        Product $product,
        ProductImage $image
    ): void {
        abort_unless(
            $image->product_id === $product->id,
            404
        );
    }
}