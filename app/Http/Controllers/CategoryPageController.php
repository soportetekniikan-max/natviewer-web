<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\Home\HomeCatalogBuilder;
use App\Services\Localization\LocalizedValueResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryPageController extends Controller
{
    public function show(
        Category $category,
        Request $request,
        HomeCatalogBuilder $catalogBuilder,
        LocalizedValueResolver $localizedValue
    ): View {
        $locale = (string) $request->route('locale', 'es');
        abort_unless(in_array($locale, ['es', 'en'], true) && $category->is_active, 404);
        App::setLocale($locale);

        $products = $category->products()
            ->with([
                'category',
                'brand',
                'images',
                'variants' => fn ($query) => $query->where('is_active', true),
            ])
            ->where('status', Product::STATUS_PUBLISHED)
            ->whereHas('brand', fn ($query) => $query->where('is_active', true))
            ->whereHas('variants', fn ($query) => $query->where('is_active', true))
            ->orderByDesc('is_featured')
            ->orderBy('id')
            ->get();

        abort_if($products->isEmpty(), 404);

        $categoryName = $localizedValue->resolve($category, 'name', $locale) ?: $category->slug;
        $categoryDescription = $localizedValue->resolve($category, 'description', $locale)
            ?: __('catalog.category_description', ['category' => $categoryName]);
        $canonicalUrl = route('categories.show.'.$locale, ['category' => $category->slug]);
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $categoryName,
            'description' => strip_tags($categoryDescription),
            'url' => $canonicalUrl,
            'inLanguage' => $locale,
            'mainEntity' => [
                '@type' => 'ItemList',
                'numberOfItems' => $products->count(),
                'itemListElement' => $products->values()->map(fn (Product $product, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $localizedValue->resolve($product, 'name', $locale),
                    'url' => route('products.show.'.$locale, ['product' => $product->slug]),
                ])->all(),
            ],
        ];

        return view('pages.category', [
            'locale' => $locale,
            'category' => $category,
            'categoryName' => $categoryName,
            'categoryDescription' => $categoryDescription,
            'metaDescription' => Str::limit(strip_tags($categoryDescription), 160, ''),
            'canonicalUrl' => $canonicalUrl,
            'productCount' => $products->count(),
            'catalogItems' => $catalogBuilder->build($products, $locale),
            'schemaJson' => json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
        ]);
    }
}
