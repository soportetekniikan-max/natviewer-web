<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.brands.index',
            [
                'brands' => $brands,
            ]
        );
    }

    public function store(
        StoreBrandRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $storedLogo = null;

        try {
            $brand = DB::transaction(
                function () use (
                    $request,
                    $validated,
                    &$storedLogo
                ): Brand {
                    $brand = new Brand();

                    $brand->slug =
                        $this->generateUniqueSlug(
                            $validated['slug']
                            ?? $validated['name']
                        );

                    $brand->name =
                        $validated['name'];

                    $brand->description_es =
                        $validated['description_es']
                        ?? null;

                    $brand->description_en =
                        $validated['description_en']
                        ?? null;

                    $brand->logo_path = null;

                    $brand->is_active =
                        (bool)
                        $validated['is_active'];

                    $brand->sort_order =
                        $validated['sort_order'];

                    $brand->save();

                    if ($request->hasFile('logo')) {
                        $storedLogo = $request
                            ->file('logo')
                            ->store(
                                'brands/'.$brand->id,
                                'public'
                            );

                        $brand->logo_path =
                            $storedLogo;

                        $brand->save();
                    }

                    return $brand;
                }
            );
        } catch (Throwable $exception) {
            if ($storedLogo) {
                Storage::disk('public')
                    ->delete($storedLogo);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Marca creada correctamente.'
            );
    }

    public function edit(
        Brand $brand
    ): View {
        $brand->loadCount('products');

        return view(
            'admin.brands.edit',
            [
                'brand' => $brand,
            ]
        );
    }

    public function update(
        UpdateBrandRequest $request,
        Brand $brand
    ): RedirectResponse {
        $validated = $request->validated();

        $oldLogo = $brand->logo_path;
        $newLogo = null;

        try {
            if ($request->hasFile('logo')) {
                $newLogo = $request
                    ->file('logo')
                    ->store(
                        'brands/'.$brand->id,
                        'public'
                    );
            }

            DB::transaction(
                function () use (
                    $validated,
                    $brand,
                    $newLogo
                ): void {
                    $brand->slug =
                        $this->generateUniqueSlug(
                            $validated['slug']
                            ?? $validated['name'],
                            $brand->id
                        );

                    $brand->name =
                        $validated['name'];

                    $brand->description_es =
                        $validated['description_es']
                        ?? null;

                    $brand->description_en =
                        $validated['description_en']
                        ?? null;

                    $brand->is_active =
                        (bool)
                        $validated['is_active'];

                    $brand->sort_order =
                        $validated['sort_order'];

                    if ($newLogo !== null) {
                        $brand->logo_path =
                            $newLogo;
                    } elseif (
                        $validated['remove_logo']
                    ) {
                        $brand->logo_path =
                            null;
                    }

                    $brand->save();
                }
            );
        } catch (Throwable $exception) {
            if ($newLogo) {
                Storage::disk('public')
                    ->delete($newLogo);
            }

            throw $exception;
        }

        $logoChanged =
            $newLogo !== null
            || (
                $validated['remove_logo']
                && $oldLogo !== null
            );

        if (
            $logoChanged
            && $oldLogo
            && $oldLogo !== $brand->logo_path
        ) {
            Storage::disk('public')
                ->delete($oldLogo);
        }

        return redirect()
            ->route(
                'admin.brands.edit',
                $brand
            )
            ->with(
                'success',
                'Marca actualizada correctamente.'
            );
    }

    public function toggleStatus(
        Brand $brand
    ): RedirectResponse {
        $brand->is_active =
            ! $brand->is_active;

        $brand->save();

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                $brand->is_active
                    ? 'Marca activada.'
                    : 'Marca desactivada.'
            );
    }

    private function generateUniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'marca';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (true) {
            $query = Brand::query()
                ->where('slug', $slug);

            if ($ignoreId !== null) {
                $query->whereKeyNot($ignoreId);
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug =
                $baseSlug.'-'.$counter;

            $counter++;
        }
    }
}