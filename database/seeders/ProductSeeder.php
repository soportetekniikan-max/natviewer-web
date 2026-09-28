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
        $category = Category::where(
            'slug',
            'binoculares-terrestres'
        )->first();

        $brand = Brand::where(
            'slug',
            'natviewer'
        )->first();

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

                'short_description_es' =>
                    'Binoculares Natviewer Falco con objetivo de 42 mm y especificación UD para observación de aves, fauna, paisajes y naturaleza.',

                'short_description_en' =>
                    'Natviewer Falco binoculars with a 42 mm objective and UD specification for birdwatching, wildlife, landscapes and nature observation.',

                'description_es' =>
                    'Natviewer Falco es una línea de binoculares para observación de aves, fauna, paisajes y naturaleza. Las configuraciones disponibles actualmente combinan un objetivo de 42 mm con especificación UD y permiten elegir diferentes niveles de aumento según las preferencias de observación.',

                'description_en' =>
                    'Natviewer Falco is a binocular line for birdwatching, wildlife, landscapes and nature observation. The currently available configurations combine a 42 mm objective with UD specification and provide different magnification options according to observation preferences.',

                'status' => Product::STATUS_PUBLISHED,
                'is_featured' => true,

                'meta_title_es' =>
                    'Binoculares Natviewer Falco UD',

                'meta_title_en' =>
                    'Natviewer Falco UD Binoculars',

                'meta_description_es' =>
                    'Conoce Natviewer Falco, binoculares con objetivo de 42 mm y especificación UD para observación de aves, fauna, paisajes y naturaleza.',

                'meta_description_en' =>
                    'Discover Natviewer Falco binoculars with a 42 mm objective and UD specification for birdwatching, wildlife, landscapes and nature observation.',
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
                 * Precio pendiente de confirmación comercial.
                 */
                'price' => null,
                'currency' => 'COP',

                /*
                 * Stock pendiente de confirmación comercial.
                 */
                'manage_stock' => true,
                'stock_quantity' => null,
                'stock_status' =>
                    ProductVariant::STOCK_UNKNOWN,

                /*
                 * Solo se incluyen especificaciones
                 * actualmente confirmadas.
                 */
                'specifications' => [
                    'magnification' => '8×',
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
                 * Precio pendiente de confirmación comercial.
                 */
                'price' => null,
                'currency' => 'COP',

                /*
                 * Stock pendiente de confirmación comercial.
                 */
                'manage_stock' => true,
                'stock_quantity' => null,
                'stock_status' =>
                    ProductVariant::STOCK_UNKNOWN,

                /*
                 * Solo se incluyen especificaciones
                 * actualmente confirmadas.
                 */
                'specifications' => [
                    'magnification' => '10×',
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