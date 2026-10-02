<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductVariantRequest;
use App\Http\Requests\Admin\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(
        Product $product
    ): View {
        $product->load([
            'variants' => function ($query) {
                $query
                    ->orderByDesc('is_default')
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);

        return view(
            'admin.products.variants.index',
            [
                'product' => $product,
            ]
        );
    }

    public function store(
        StoreProductVariantRequest $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validated();

        $variant = DB::transaction(
            function () use (
                $product,
                $validated
            ): ProductVariant {
                $existingCount = $product
                    ->variants()
                    ->count();

                $hasDefault = $product
                    ->variants()
                    ->where(
                        'is_default',
                        true
                    )
                    ->exists();

                $isDefault =
                    (bool) $validated['is_default']
                    || $existingCount === 0
                    || ! $hasDefault;

                if ($isDefault) {
                    $product
                        ->variants()
                        ->update([
                            'is_default' => false,
                        ]);
                }

                $variant =
                    new ProductVariant();

                $variant->product_id =
                    $product->id;

                $variant->sku =
                    trim($validated['sku']);

                $variant->name_es =
                    $validated['name_es'];

                $variant->name_en =
                    $validated['name_en']
                    ?? null;

                $variant->price =
                    isset($validated['price'])
                    && $validated['price'] !== ''
                        ? $validated['price']
                        : null;

                $variant->currency =
                    $validated['currency'];

                $variant->manage_stock =
                    (bool)
                    $validated['manage_stock'];

                $variant->stock_quantity =
                    $variant->manage_stock
                        ? (
                            $validated[
                                'stock_quantity'
                            ] ?? null
                        )
                        : null;

                $variant->stock_status =
                    $validated['stock_status'];

                $variant->is_default =
                    $isDefault;

                $variant->is_active =
                    (bool)
                    $validated['is_active'];

                $variant->sort_order =
                    $validated['sort_order']
                    ?? (
                        (
                            $product
                                ->variants()
                                ->max('sort_order')
                            ?? 0
                        ) + 10
                    );

                $variant->specifications =
                    $this->normalizeSpecifications(
                        $validated[
                            'specifications'
                        ] ?? []
                    );

                $variant->save();

                return $variant;
            }
        );

        return redirect()
            ->route(
                'admin.products.variants.edit',
                [
                    $product,
                    $variant,
                ]
            )
            ->with(
                'success',
                'Variante creada correctamente.'
            );
    }

    public function edit(
        Product $product,
        ProductVariant $variant
    ): View {
        $this->ensureBelongsToProduct(
            $product,
            $variant
        );

        return view(
            'admin.products.variants.edit',
            [
                'product' => $product,
                'variant' => $variant,
            ]
        );
    }

    public function update(
        UpdateProductVariantRequest $request,
        Product $product,
        ProductVariant $variant
    ): RedirectResponse {
        $this->ensureBelongsToProduct(
            $product,
            $variant
        );

        $validated =
            $request->validated();

        DB::transaction(function () use (
            $product,
            $variant,
            $validated
        ): void {
            $isActive =
                (bool)
                $validated['is_active'];

            $requestedDefault =
                (bool)
                $validated['is_default'];

            /*
             * Si se marca esta variante como
             * predeterminada, retiramos el estado
             * de todas las demás.
             */
            if ($requestedDefault) {
                $product
                    ->variants()
                    ->whereKeyNot(
                        $variant->id
                    )
                    ->update([
                        'is_default' => false,
                    ]);
            }

            /*
             * Una variante que ya era predeterminada
             * conserva ese estado aunque el checkbox
             * se desmarque accidentalmente.
             *
             * Para cambiar la predeterminada,
             * se marca otra variante.
             */
            $isDefault =
                $requestedDefault
                || (
                    $variant->is_default
                    && $isActive
                );

            if (! $isActive) {
                $isDefault = false;
            }

            $variant->sku =
                trim($validated['sku']);

            $variant->name_es =
                $validated['name_es'];

            $variant->name_en =
                $validated['name_en']
                ?? null;

            $variant->price =
                isset($validated['price'])
                && $validated['price'] !== ''
                    ? $validated['price']
                    : null;

            $variant->currency =
                $validated['currency'];

            $variant->manage_stock =
                (bool)
                $validated['manage_stock'];

            $variant->stock_quantity =
                $variant->manage_stock
                    ? (
                        $validated[
                            'stock_quantity'
                        ] ?? null
                    )
                    : null;

            $variant->stock_status =
                $validated['stock_status'];

            $variant->is_default =
                $isDefault;

            $variant->is_active =
                $isActive;

            $variant->sort_order =
                $validated['sort_order']
                ?? 0;

            $variant->specifications =
                $this->normalizeSpecifications(
                    $validated[
                        'specifications'
                    ] ?? []
                );

            $variant->save();

            /*
             * Si acabamos de desactivar la
             * predeterminada, promovemos la
             * primera variante activa disponible.
             */
            $hasActiveDefault = $product
                ->variants()
                ->where(
                    'is_active',
                    true
                )
                ->where(
                    'is_default',
                    true
                )
                ->exists();

            if (! $hasActiveDefault) {
                $replacement = $product
                    ->variants()
                    ->where(
                        'is_active',
                        true
                    )
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->first();

                if ($replacement) {
                    $replacement->update([
                        'is_default' => true,
                    ]);
                }
            }
        });

        return redirect()
            ->route(
                'admin.products.variants.edit',
                [
                    $product,
                    $variant,
                ]
            )
            ->with(
                'success',
                'Variante actualizada correctamente.'
            );
    }

    private function ensureBelongsToProduct(
        Product $product,
        ProductVariant $variant
    ): void {
        abort_unless(
            $variant->product_id
            === $product->id,
            404
        );
    }

    private function normalizeSpecifications(
        array $rows
    ): array {
        $specifications = [];

        foreach ($rows as $row) {
            $key = trim(
                (string)
                ($row['key'] ?? '')
            );

            $value = trim(
                (string)
                ($row['value'] ?? '')
            );

            if (
                $key === ''
                || $value === ''
            ) {
                continue;
            }

            $specifications[$key] =
                $value;
        }

        return $specifications;
    }
}