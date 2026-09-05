<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with([
                'category',
                'brand',
            ])
            ->withCount([
                'variants',
                'images',
            ])
            ->orderByDesc('is_featured')
            ->orderBy('name_es')
            ->paginate(20);

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        $categories = Category::query()
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('name_es')
            ->get();

        $brands = Brand::query()
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.products.create', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store(
        StoreProductRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $storedPaths = [];

        try {
            $product = DB::transaction(
                function () use (
                    $request,
                    $validated,
                    &$storedPaths
                ): Product {
                    /*
                    |--------------------------------------------------------------------------
                    | Producto
                    |--------------------------------------------------------------------------
                    */

                    $product = new Product();

                    $product->category_id =
                        $validated['category_id'];

                    $product->brand_id =
                        $validated['brand_id'];

                    $product->slug =
                        $this->generateUniqueSlug(
                            $validated['slug']
                            ?? $validated['name_es']
                        );

                    $product->name_es =
                        $validated['name_es'];

                    $product->name_en =
                        $validated['name_en']
                        ?? null;

                    $product->short_description_es =
                        $validated[
                            'short_description_es'
                        ] ?? null;

                    $product->short_description_en =
                        $validated[
                            'short_description_en'
                        ] ?? null;

                    $product->description_es =
                        $validated['description_es']
                        ?? null;

                    $product->description_en =
                        $validated['description_en']
                        ?? null;

                    /*
                     * Siempre comienza como borrador.
                     * El administrador lo publica después
                     * de verificar el contenido.
                     */
                    $product->status =
                        Product::STATUS_DRAFT;

                    $product->is_featured =
                        (bool)
                        $validated['is_featured'];

                    $product->save();

                    /*
                    |--------------------------------------------------------------------------
                    | Variantes
                    |--------------------------------------------------------------------------
                    */

                    $variantMap = [];

                    $defaultAssigned = false;

                    foreach (
                        $validated['variants']
                        as $variantKey => $variantData
                    ) {
                        $variant =
                            new ProductVariant();

                        $variant->product_id =
                            $product->id;

                        $variant->sku =
                            trim(
                                $variantData['sku']
                            );

                        $variant->name_es =
                            $variantData['name_es'];

                        $variant->name_en =
                            $variantData['name_en']
                            ?? null;

                        $variant->price =
                            isset(
                                $variantData['price']
                            )
                            && $variantData['price']
                                !== ''
                                ? $variantData['price']
                                : null;

                        $variant->currency =
                            $variantData['currency'];

                        $variant->manage_stock =
                            (bool)
                            $variantData[
                                'manage_stock'
                            ];

                        $variant->stock_quantity =
                            $variant->manage_stock
                                ? (
                                    $variantData[
                                        'stock_quantity'
                                    ] ?? null
                                )
                                : null;

                        $variant->stock_status =
                            $variantData[
                                'stock_status'
                            ];

                        /*
                         * Solo puede existir una
                         * predeterminada durante
                         * este proceso de creación.
                         */
                        $requestedDefault =
                            (bool)
                            $variantData[
                                'is_default'
                            ];

                        $variant->is_default =
                            $requestedDefault
                            && ! $defaultAssigned;

                        if ($variant->is_default) {
                            $defaultAssigned = true;
                        }

                        $variant->is_active =
                            (bool)
                            $variantData[
                                'is_active'
                            ];

                        $variant->sort_order =
                            $variantData[
                                'sort_order'
                            ]
                            ?? 0;

                        $variant->specifications =
                            $this
                                ->normalizeSpecifications(
                                    $variantData[
                                        'specifications'
                                    ] ?? []
                                );

                        $variant->save();

                        $variantMap[
                            (string) $variantKey
                        ] = $variant;
                    }

                    /*
                     * Si ninguna fue marcada como
                     * predeterminada, usamos la primera.
                     */
                    if (! $defaultAssigned) {
                        $firstVariant =
                            $product
                                ->variants()
                                ->orderBy(
                                    'sort_order'
                                )
                                ->orderBy('id')
                                ->first();

                        if ($firstVariant) {
                            $firstVariant->update([
                                'is_default' => true,
                            ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Imágenes
                    |--------------------------------------------------------------------------
                    */

                    $imageRows =
                        $validated['images']
                        ?? [];

                    $primaryIndex = null;
                    $firstUploadedIndex = null;

                    foreach (
                        $imageRows
                        as $index => $imageData
                    ) {
                        $file = $request->file(
                            "images.$index.file"
                        );

                        if (! $file) {
                            continue;
                        }

                        if (
                            $firstUploadedIndex
                            === null
                        ) {
                            $firstUploadedIndex =
                                $index;
                        }

                        if (
                            $primaryIndex === null
                            && (bool)
                            $imageData['is_primary']
                        ) {
                            $primaryIndex =
                                $index;
                        }
                    }

                    if ($primaryIndex === null) {
                        $primaryIndex =
                            $firstUploadedIndex;
                    }

                    foreach (
                        $imageRows
                        as $index => $imageData
                    ) {
                        $file = $request->file(
                            "images.$index.file"
                        );

                        if (! $file) {
                            continue;
                        }

                        $path = $file->store(
                            'products/'.
                            $product->id,
                            'public'
                        );

                        $storedPaths[] =
                            $path;

                        $variantId = null;

                        $variantKey =
                            $imageData[
                                'variant_key'
                            ] ?? null;

                        if (
                            $variantKey !== null
                            && $variantKey !== ''
                        ) {
                            $variant =
                                $variantMap[
                                    (string)
                                    $variantKey
                                ] ?? null;

                            abort_unless(
                                $variant,
                                422
                            );

                            $variantId =
                                $variant->id;
                        }

                        $image =
                            $product
                                ->images()
                                ->make();

                        $image->variant_id =
                            $variantId;

                        $image->disk =
                            'public';

                        $image->path =
                            $path;

                        $image->alt_es =
                            $imageData['alt_es']
                            ?? null;

                        $image->alt_en =
                            $imageData['alt_en']
                            ?? null;

                        $image->is_primary =
                            (string) $index
                            === (string)
                                $primaryIndex;

                        $image->sort_order =
                            $imageData[
                                'sort_order'
                            ]
                            ?? (
                                (
                                    (int) $index
                                    + 1
                                ) * 10
                            );

                        $image->save();
                    }

                    return $product;
                }
            );
        } catch (Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')
                    ->delete($path);
            }

            throw $exception;
        }

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                'Producto creado correctamente. Quedó guardado como borrador para revisión antes de publicarlo.'
            );
    }

    public function edit(
        Product $product
    ): View {
        $product->load([
            'category',
            'brand',

            'variants' => function ($query) {
                $query
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },

            'images' => function ($query) {
                $query
                    ->orderByDesc(
                        'is_primary'
                    )
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);

        $categories = Category::query()
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('name_es')
            ->get();

        $brands = Brand::query()
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.edit',
            [
                'product' => $product,
                'categories' => $categories,
                'brands' => $brands,
            ]
        );
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {
        $validated =
            $request->validated();

        DB::transaction(
            function () use (
                $product,
                $validated
            ): void {
                $product->update([
                    'category_id' =>
                        $validated[
                            'category_id'
                        ],

                    'brand_id' =>
                        $validated[
                            'brand_id'
                        ],

                    'name_es' =>
                        $validated[
                            'name_es'
                        ],

                    'name_en' =>
                        $validated[
                            'name_en'
                        ] ?? null,

                    'short_description_es' =>
                        $validated[
                            'short_description_es'
                        ] ?? null,

                    'short_description_en' =>
                        $validated[
                            'short_description_en'
                        ] ?? null,

                    'description_es' =>
                        $validated[
                            'description_es'
                        ] ?? null,

                    'description_en' =>
                        $validated[
                            'description_en'
                        ] ?? null,

                    'status' =>
                        $validated[
                            'status'
                        ],

                    'is_featured' =>
                        (bool)
                        $validated[
                            'is_featured'
                        ],
                ]);

                foreach (
                    $validated['variants']
                    ?? []
                    as $variantData
                ) {
                    $variant = $product
                        ->variants()
                        ->whereKey(
                            $variantData['id']
                        )
                        ->firstOrFail();

                    $manageStock =
                        (bool)
                        $variantData[
                            'manage_stock'
                        ];

                    $specifications =
                        $this
                            ->normalizeSpecifications(
                                $variantData[
                                    'specifications'
                                ] ?? []
                            );

                    $variant->update([
                        'name_es' =>
                            $variantData[
                                'name_es'
                            ],

                        'name_en' =>
                            $variantData[
                                'name_en'
                            ] ?? null,

                        'price' =>
                            $variantData[
                                'price'
                            ] !== null
                            && $variantData[
                                'price'
                            ] !== ''
                                ? $variantData[
                                    'price'
                                ]
                                : null,

                        'currency' =>
                            $variantData[
                                'currency'
                            ],

                        'manage_stock' =>
                            $manageStock,

                        'stock_quantity' =>
                            $manageStock
                                ? (
                                    $variantData[
                                        'stock_quantity'
                                    ] ?? null
                                )
                                : null,

                        'stock_status' =>
                            $variantData[
                                'stock_status'
                            ],

                        'is_active' =>
                            (bool)
                            $variantData[
                                'is_active'
                            ],

                        'specifications' =>
                            $specifications,
                    ]);
                }
            }
        );

        return redirect()
            ->route(
                'admin.products.edit',
                $product
            )
            ->with(
                'success',
                'Producto actualizado correctamente.'
            );
    }

    public function archive(
        Product $product
    ): RedirectResponse {
        $product->update([
            'status' =>
                Product::STATUS_ARCHIVED,

            'is_featured' =>
                false,
        ]);

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Producto archivado correctamente. No se eliminó ningún dato histórico.'
            );
    }

    private function generateUniqueSlug(
        string $value
    ): string {
        $baseSlug =
            Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'producto';
        }

        $slug =
            $baseSlug;

        $counter = 2;

        while (
            Product::query()
                ->where(
                    'slug',
                    $slug
                )
                ->exists()
        ) {
            $slug =
                $baseSlug.
                '-'.
                $counter;

            $counter++;
        }

        return $slug;
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

            $specifications[
                $key
            ] = $value;
        }

        return $specifications;
    }
}