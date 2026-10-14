<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_pages_have_localized_metadata_and_crawlable_product_links(): void
    {
        $this->seedCatalog();
        $category = Category::query()->firstOrFail();
        $product = Product::query()->firstOrFail();

        foreach (['es', 'en'] as $locale) {
            $url = route('categories.show.'.$locale, ['category' => $category->slug]);
            $response = $this->get($url);

            $response->assertOk()->assertViewIs('pages.category')
                ->assertSee('<h1>'.$category->{'name_'.$locale}.'</h1>', false)
                ->assertSee('href="'.$url.'"', false)
                ->assertSee(route('products.show.'.$locale, ['product' => $product->slug]), false)
                ->assertSee('"@type":"CollectionPage"', false)
                ->assertSee('"numberOfItems":2', false);
        }
    }

    public function test_home_and_product_breadcrumb_link_to_the_category(): void
    {
        $this->seedCatalog();
        $product = Product::query()->firstOrFail();
        $categoryUrl = route('categories.show.es', ['category' => $product->category->slug]);

        $home = $this->get('/es');
        $detail = $this->get('/es/productos/'.$product->slug);

        $home->assertOk()->assertSee('href="'.$categoryUrl.'"', false);
        $detail->assertOk()->assertSee('href="'.$categoryUrl.'"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"sku":"'.$product->variants->first()->sku.'"', false);
    }

    public function test_sitemap_includes_nonempty_categories_and_privacy_pages_in_both_languages(): void
    {
        $this->seedCatalog();
        $category = Category::query()->firstOrFail();

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        foreach (['es', 'en'] as $locale) {
            $response->assertSee('<loc>'.route('categories.show.'.$locale, ['category' => $category->slug]).'</loc>', false)
                ->assertSee('<loc>'.route('privacy-policy.'.$locale).'</loc>', false);
        }
    }

    public function test_draft_products_are_not_listed_in_a_category(): void
    {
        $this->seedCatalog();
        $hidden = Product::query()->orderByDesc('id')->firstOrFail();
        $hidden->update(['status' => Product::STATUS_DRAFT]);

        $response = $this->get('/es/categorias/binoculares-terrestres');

        $response->assertOk()->assertDontSee($hidden->slug, false)
            ->assertSee('"numberOfItems":1', false);
    }

    public function test_products_from_inactive_brands_are_not_listed(): void
    {
        $this->seedCatalog();
        $brand = Brand::create(['slug' => 'hidden', 'name' => 'Hidden', 'is_active' => false]);
        $hidden = Product::query()->orderByDesc('id')->firstOrFail();
        $hidden->update(['brand_id' => $brand->id]);

        $response = $this->get('/es/categorias/binoculares-terrestres');

        $response->assertOk()->assertDontSee($hidden->slug, false)
            ->assertSee('"numberOfItems":1', false);
    }

    public function test_products_without_active_variants_are_not_listed(): void
    {
        $this->seedCatalog();
        $hidden = Product::query()->orderByDesc('id')->firstOrFail();
        $hidden->variants()->update(['is_active' => false]);

        $response = $this->get('/es/categorias/binoculares-terrestres');

        $response->assertOk()->assertDontSee($hidden->slug, false)
            ->assertSee('"numberOfItems":1', false);
    }

    public function test_category_does_not_include_products_assigned_to_another_category(): void
    {
        $this->seedCatalog();
        $otherCategory = Category::create(['slug' => 'other', 'name_es' => 'Otra categoría', 'is_active' => true]);
        $otherProduct = Product::query()->orderByDesc('id')->firstOrFail();
        $otherProduct->update(['category_id' => $otherCategory->id]);

        $this->get('/es/categorias/binoculares-terrestres')->assertOk()
            ->assertDontSee($otherProduct->slug, false)
            ->assertSee('"numberOfItems":1', false);
    }

    public function test_category_with_no_available_models_is_excluded_from_the_sitemap(): void
    {
        $this->seedCatalog();
        foreach (Product::query()->get() as $product) {
            $product->variants()->update(['is_active' => false]);
        }

        $this->get('/es/categorias/binoculares-terrestres')->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('/categorias/binoculares-terrestres', false);
    }

    public function test_inactive_and_empty_categories_are_not_public_or_in_the_sitemap(): void
    {
        $this->seedCatalog();
        Category::query()->update(['is_active' => false]);
        Category::create(['slug' => 'empty', 'name_es' => 'Vacía', 'is_active' => true]);

        $this->get('/es/categorias/binoculares-terrestres')->assertNotFound();
        $this->get('/en/categories/empty')->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()
            ->assertDontSee('/categorias/binoculares-terrestres', false)
            ->assertDontSee('/categories/empty', false);
    }

    public function test_category_content_is_escaped_in_html_and_structured_data(): void
    {
        $this->seedCatalog();
        $name = '<script>alert("category")</script>';
        Category::query()->update(['name_es' => $name]);

        $response = $this->get('/es/categorias/binoculares-terrestres');

        $response->assertOk()->assertSee($name)->assertDontSee($name, false)
            ->assertSee('"@type":"CollectionPage"', false);
    }

    private function seedCatalog(): void
    {
        $this->seed([CategorySeeder::class, BrandSeeder::class, ProductSeeder::class]);
    }
}
