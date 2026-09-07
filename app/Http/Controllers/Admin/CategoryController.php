<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name_es')
            ->get();

        return view(
            'admin.categories.index',
            [
                'categories' => $categories,
            ]
        );
    }

    public function store(
        StoreCategoryRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $category = new Category();

        $category->slug =
            $this->generateUniqueSlug(
                $validated['slug']
                ?? $validated['name_es']
            );

        $category->name_es =
            $validated['name_es'];

        $category->name_en =
            $validated['name_en']
            ?? null;

        $category->description_es =
            $validated['description_es']
            ?? null;

        $category->description_en =
            $validated['description_en']
            ?? null;

        $category->is_active =
            (bool) $validated['is_active'];

        $category->sort_order =
            $validated['sort_order'];

        $category->save();

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Categoría creada correctamente.'
            );
    }

    public function edit(
        Category $category
    ): View {
        $category->loadCount('products');

        return view(
            'admin.categories.edit',
            [
                'category' => $category,
            ]
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validated();

        $category->slug =
            $this->generateUniqueSlug(
                $validated['slug']
                ?? $validated['name_es'],
                $category->id
            );

        $category->name_es =
            $validated['name_es'];

        $category->name_en =
            $validated['name_en']
            ?? null;

        $category->description_es =
            $validated['description_es']
            ?? null;

        $category->description_en =
            $validated['description_en']
            ?? null;

        $category->is_active =
            (bool) $validated['is_active'];

        $category->sort_order =
            $validated['sort_order'];

        $category->save();

        return redirect()
            ->route(
                'admin.categories.edit',
                $category
            )
            ->with(
                'success',
                'Categoría actualizada correctamente.'
            );
    }

    public function toggleStatus(
        Category $category
    ): RedirectResponse {
        $category->is_active =
            ! $category->is_active;

        $category->save();

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                $category->is_active
                    ? 'Categoría activada.'
                    : 'Categoría desactivada.'
            );
    }

    private function generateUniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'categoria';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (true) {
            $query = Category::query()
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