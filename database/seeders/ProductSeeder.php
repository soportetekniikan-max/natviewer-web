<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'binoculares-terrestres')->first();
        $brand = Brand::where('slug', 'natviewer')->first();

        if (! $category || ! $brand) {
            throw new RuntimeException(
                'CategorySeeder y BrandSeeder deben ejecutarse antes de ProductSeeder.'
            );
        }

        $product = Product::updateOrCreate(
            [
                'slug' => 'natviewer-falco',
            ],
            [
                'category_id' => $category->id,
                'brand_id' => $brand->id,

                'name_es' => 'Natviewer Falco',
                'name_en' => 'Natviewer Falco',

                'short_description_es' => 'Binoculares premium para observación de aves y naturaleza.',
                'short_description_en' => 'Premium binoculars for birdwatching and nature observation.',

                'description_es' => 'La línea Natviewer Falco está diseñada para ofrecer una experiencia óptica de alta calidad en observación de aves, fauna y naturaleza.',
                'description_en' => 'The Natviewer Falco line is designed to provide a high-quality optical experience for birdwatching, wildlife and nature observation.',

                'status' => Product::STATUS_PUBLISHED,
                'is_featured' => true,

                'meta_title_es' => 'Binoculares Natviewer Falco',
                'meta_title_en' => 'Natviewer Falco Binoculars',

                'meta_description_es' => 'Descubre los binoculares Natviewer Falco para observación de aves y naturaleza.',
                'meta_description_en' => 'Discover Natviewer Falco binoculars for birdwatching and nature observation.',
            ]
        );

        ProductVariant::updateOrCreate(
            [
                'sku' => 'NV-FALCO-8X42-UD',
            ],
            [
                'product_id' => $product->id,

                'name_es' => 'Falco 8×42 UD',
                'name_en' => 'Falco 8×42 UD',

                /*
                 * Precio pendiente de confirmación.
                 */
                'price' => null,
                'currency' => 'COP',

                /*
                 * Stock pendiente de confirmación.
                 */
                'manage_stock' => true,
                'stock_quantity' => null,
                'stock_status' => ProductVariant::STOCK_UNKNOWN,

                'specifications' => [
                    'magnification' => '8x',
                    'objective_diameter' => '42 mm',
                    'glass' => 'UD',
                ],

                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        ProductVariant::updateOrCreate(
            [
                'sku' => 'NV-FALCO-10X42-UD',
            ],
            [
                'product_id' => $product->id,

                'name_es' => 'Falco 10×42 UD',
                'name_en' => 'Falco 10×42 UD',

                /*
                 * Precio pendiente de confirmación.
                 */
                'price' => null,
                'currency' => 'COP',

                /*
                 * Stock pendiente de confirmación.
                 */
                'manage_stock' => true,
                'stock_quantity' => null,
                'stock_status' => ProductVariant::STOCK_UNKNOWN,

                'specifications' => [
                    'magnification' => '10x',
                    'objective_diameter' => '42 mm',
                    'glass' => 'UD',
                ],

                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );
    }
}