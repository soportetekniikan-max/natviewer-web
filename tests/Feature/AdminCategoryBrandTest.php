<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryBrandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            BrandSeeder::class,
        ]);
    }

    public function test_admin_can_access_categories_and_brands(): void
    {
        $admin = $this->createAdmin();

        $this
            ->actingAs($admin)
            ->get(
                route('admin.categories.index')
            )
            ->assertOk();

        $this
            ->actingAs($admin)
            ->get(
                route('admin.brands.index')
            )
            ->assertOk();
    }

    public function test_admin_can_create_category_with_automatic_slug(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.categories.store'),
                [
                    'name_es' =>
                        'Telescopios terrestres',

                    'name_en' =>
                        'Spotting scopes',

                    'slug' => '',

                    'description_es' =>
                        'Categoría de prueba.',

                    'description_en' =>
                        'Test category.',

                    'sort_order' => 20,

                    'is_active' => 1,
                ]
            );

        $response->assertRedirect(
            route('admin.categories.index')
        );

        $category = Category::query()
            ->where(
                'name_es',
                'Telescopios terrestres'
            )
            ->firstOrFail();

        $this->assertSame(
            'telescopios-terrestres',
            $category->slug
        );

        $this->assertSame(
            20,
            $category->sort_order
        );

        $this->assertTrue(
            $category->is_active
        );
    }

    public function test_admin_can_update_and_toggle_category(): void
    {
        $admin = $this->createAdmin();

        $category = Category::query()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.categories.update',
                    $category
                ),
                [
                    'name_es' =>
                        'Categoría actualizada',

                    'name_en' =>
                        'Updated category',

                    'slug' =>
                        'categoria-actualizada',

                    'description_es' =>
                        'Descripción actualizada.',

                    'description_en' =>
                        'Updated description.',

                    'sort_order' => 50,

                    'is_active' => 1,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.categories.edit',
                $category
            )
        );

        $category->refresh();

        $this->assertSame(
            'categoria-actualizada',
            $category->slug
        );

        $this->assertSame(
            50,
            $category->sort_order
        );

        $this->assertTrue(
            $category->is_active
        );

        $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.categories.toggle',
                    $category
                )
            )
            ->assertRedirect(
                route('admin.categories.index')
            );

        $this->assertFalse(
            $category->fresh()->is_active
        );
    }

    public function test_duplicate_category_slug_is_rejected(): void
    {
        $admin = $this->createAdmin();

        $existing = Category::query()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.categories.store'),
                [
                    'name_es' =>
                        'Otra categoría',

                    'name_en' =>
                        'Another category',

                    'slug' =>
                        $existing->slug,

                    'sort_order' => 30,

                    'is_active' => 1,
                ]
            );

        $response->assertSessionHasErrors(
            'slug'
        );
    }

    public function test_admin_can_create_brand_with_logo(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.brands.store'),
                [
                    'name' =>
                        'Marca Test',

                    'slug' => '',

                    'description_es' =>
                        'Marca de prueba.',

                    'description_en' =>
                        'Test brand.',

                    'logo' =>
                        UploadedFile::fake()
                            ->image(
                                'brand-logo.png',
                                600,
                                300
                            ),

                    'sort_order' => 20,

                    'is_active' => 1,
                ]
            );

        $response->assertRedirect(
            route('admin.brands.index')
        );

        $brand = Brand::query()
            ->where(
                'name',
                'Marca Test'
            )
            ->firstOrFail();

        $this->assertSame(
            'marca-test',
            $brand->slug
        );

        $this->assertNotNull(
            $brand->logo_path
        );

        $this->assertTrue(
            $brand->is_active
        );

        Storage::disk('public')
            ->assertExists(
                $brand->logo_path
            );
    }

    public function test_admin_can_replace_brand_logo(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $brand = Brand::query()
            ->firstOrFail();

        $oldLogo =
            'brands/'.$brand->id.'/old-logo.png';

        Storage::disk('public')
            ->put(
                $oldLogo,
                'old-logo'
            );

        $brand->logo_path =
            $oldLogo;

        $brand->save();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.brands.update',
                    $brand
                ),
                [
                    'name' =>
                        $brand->name,

                    'slug' =>
                        $brand->slug,

                    'description_es' =>
                        $brand->description_es,

                    'description_en' =>
                        $brand->description_en,

                    'logo' =>
                        UploadedFile::fake()
                            ->image(
                                'new-logo.png',
                                600,
                                300
                            ),

                    'remove_logo' => 0,

                    'sort_order' =>
                        $brand->sort_order,

                    'is_active' =>
                        $brand->is_active
                            ? 1
                            : 0,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.brands.edit',
                $brand
            )
        );

        $brand->refresh();

        $this->assertNotNull(
            $brand->logo_path
        );

        $this->assertNotSame(
            $oldLogo,
            $brand->logo_path
        );

        Storage::disk('public')
            ->assertMissing(
                $oldLogo
            );

        Storage::disk('public')
            ->assertExists(
                $brand->logo_path
            );
    }

    public function test_admin_can_remove_brand_logo(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $brand = Brand::query()
            ->firstOrFail();

        $logoPath =
            'brands/'.$brand->id.'/logo.png';

        Storage::disk('public')
            ->put(
                $logoPath,
                'logo'
            );

        $brand->logo_path =
            $logoPath;

        $brand->save();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.brands.update',
                    $brand
                ),
                [
                    'name' =>
                        $brand->name,

                    'slug' =>
                        $brand->slug,

                    'description_es' =>
                        $brand->description_es,

                    'description_en' =>
                        $brand->description_en,

                    'remove_logo' => 1,

                    'sort_order' =>
                        $brand->sort_order,

                    'is_active' =>
                        $brand->is_active
                            ? 1
                            : 0,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.brands.edit',
                $brand
            )
        );

        $brand->refresh();

        $this->assertNull(
            $brand->logo_path
        );

        Storage::disk('public')
            ->assertMissing(
                $logoPath
            );
    }

    public function test_admin_can_update_and_toggle_brand(): void
    {
        $admin = $this->createAdmin();

        $brand = Brand::query()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.brands.update',
                    $brand
                ),
                [
                    'name' =>
                        'Marca actualizada',

                    'slug' =>
                        'marca-actualizada',

                    'description_es' =>
                        'Descripción actualizada.',

                    'description_en' =>
                        'Updated description.',

                    'remove_logo' => 0,

                    'sort_order' => 40,

                    'is_active' => 1,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.brands.edit',
                $brand
            )
        );

        $brand->refresh();

        $this->assertSame(
            'Marca actualizada',
            $brand->name
        );

        $this->assertSame(
            'marca-actualizada',
            $brand->slug
        );

        $this->assertSame(
            40,
            $brand->sort_order
        );

        $this->assertTrue(
            $brand->is_active
        );

        $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.brands.toggle',
                    $brand
                )
            )
            ->assertRedirect(
                route('admin.brands.index')
            );

        $this->assertFalse(
            $brand->fresh()->is_active
        );
    }

    public function test_duplicate_brand_slug_is_rejected(): void
    {
        $admin = $this->createAdmin();

        $existing = Brand::query()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.brands.store'),
                [
                    'name' =>
                        'Otra marca',

                    'slug' =>
                        $existing->slug,

                    'sort_order' => 30,

                    'is_active' => 1,
                ]
            );

        $response->assertSessionHasErrors(
            'slug'
        );
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'name' =>
                'Administrador Test',

            'email' =>
                'admin@example.com',

            'password' =>
                'password-seguro',

            'is_admin' =>
                true,
        ]);
    }
}