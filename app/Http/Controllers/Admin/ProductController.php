<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with([
                'category',
                'brand',
            ])
            ->withCount('variants')
            ->orderByDesc('is_featured')
            ->orderBy('name_es')
            ->paginate(20);

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function edit(Product $product): View
    {
        $product->load([
            'category',
            'brand',
            'variants',
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

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use (
            $product,
            $validated
        ): void {
            $product->update([
                'category_id' =>
                    $validated['category_id'],

                'brand_id' =>
                    $validated['brand_id'],

                'name_es' =>
                    $validated['name_es'],

                'name_en' =>
                    $validated['name_en'] ?? null,

                'short_description_es' =>
                    $validated['short_description_es'] ?? null,

                'short_description_en' =>
                    $validated['short_description_en'] ?? null,

                'description_es' =>
                    $validated['description_es'] ?? null,

                'description_en' =>
                    $validated['description_en'] ?? null,

                'status' =>
                    $validated['status'],

                'is_featured' =>
                    (bool) $validated['is_featured'],
            ]);

            foreach ($validated['variants'] as $variantData) {
                $variant = $product
                    ->variants()
                    ->whereKey($variantData['id'])
                    ->firstOrFail();

                $manageStock = (bool)
                    $variantData['manage_stock'];

                $variant->update([
                    'name_es' =>
                        $variantData['name_es'],

                    'name_en' =>
                        $variantData['name_en'] ?? null,

                    'price' =>
                        $variantData['price'] !== null
                        && $variantData['price'] !== ''
                            ? $variantData['price']
                            : null,

                    'currency' =>
                        $variantData['currency'],

                    'manage_stock' =>
                        $manageStock,

                    'stock_quantity' =>
                        $manageStock
                            ? ($variantData['stock_quantity'] ?? null)
                            : null,

                    'stock_status' =>
                        $variantData['stock_status'],

                    'is_active' =>
                        (bool) $variantData['is_active'],
                ]);
            }
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with(
                'success',
                'Producto actualizado correctamente.'
            );
    }
}