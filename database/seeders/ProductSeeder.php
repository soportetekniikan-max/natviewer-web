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
                'category_id' =>
                    $category->id,

                'brand_id' =>
                    $brand->id,

                'name_es' =>
                    'Natviewer Falco',

                'name_en' =>
                    'Natviewer Falco',

                'short_description_es' =>
                    'Binoculares con lentes coated, prismas BAK-7 tipo techo y objetivo de 42 mm para observación de aves y naturaleza.',

                'short_description_en' =>
                    'Binoculars with coated lenses, BAK-7 roof prisms and a 42 mm objective for birdwatching and nature observation.',

                'description_es' =>
                    'Natviewer Falco es una línea de binoculares para observación de aves, fauna y naturaleza. Sus configuraciones actuales incorporan lentes coated, prismas BAK-7 tipo techo, objetivo de 42 mm, enfoque mínimo de 3 metros y alivio ocular de 15 mm. Las variantes disponibles permiten elegir diferentes niveles de aumento manteniendo una configuración orientada al uso en campo.',

                'description_en' =>
                    'Natviewer Falco is a binocular line for birdwatching, wildlife and nature observation. Its current configurations feature coated lenses, BAK-7 roof prisms, a 42 mm objective, 3 m minimum focus and 15 mm eye relief. Available variants provide different magnification levels while maintaining a configuration intended for field observation.',

                'status' =>
                    Product::STATUS_PUBLISHED,

                'is_featured' =>
                    true,

                'meta_title_es' =>
                    'Binoculares Natviewer Falco 8×42 y 10×42 UD',

                'meta_title_en' =>
                    'Natviewer Falco 8×42 and 10×42 UD Binoculars',

                'meta_description_es' =>
                    'Conoce los binoculares Natviewer Falco con lentes coated, prismas BAK-7 tipo techo y objetivo de 42 mm para observación de aves y naturaleza.',

                'meta_description_en' =>
                    'Discover Natviewer Falco binoculars with coated lenses, BAK-7 roof prisms and a 42 mm objective for birdwatching and nature observation.',
            ]
        );

        ProductVariant::updateOrCreate(
            [
                'sku' =>
                    'NV-FALCO-8X42-UD',
            ],
            [
                'product_id' =>
                    $product->id,

                'name_es' =>
                    'Falco 8×42 UD',

                'name_en' =>
                    'Falco 8×42 UD',

                /*
                 * Pendiente de definición
                 * comercial para Natviewer.
                 */
                'price' =>
                    null,

                'currency' =>
                    'COP',

                /*
                 * Pendiente de confirmación
                 * de inventario Natviewer.
                 */
                'manage_stock' =>
                    true,

                'stock_quantity' =>
                    null,

                'stock_status' =>
                    ProductVariant::STOCK_UNKNOWN,

                /*
                 * Especificaciones verificadas
                 * con la ficha del producto.
                 */
                'specifications' => [
                    'magnification' =>
                        '8×',

                    'objective_diameter' =>
                        '42 mm',

                    'lenses' =>
                        'coated',

                    'prism' =>
                        'bak7_roof',

                    'field_of_view' =>
                        '93 m',

                    'minimum_focus' =>
                        '3 m',

                    'eye_relief' =>
                        '15 mm',

                    'exit_pupil' =>
                        '5.25 mm',

                    'chassis' =>
                        'polycarbonate',

                    'weight' =>
                        '675 g',

                    'adjustable_eyecups' =>
                        'yes',

                    'diopter_adjustment' =>
                        'yes',

                    'focus_system' =>
                        'central_wheel',

                    'weather_resistance' =>
                        'rain_fog_resistant',

                    'tripod_compatible' =>
                        'yes',

                    'included_accessories' =>
                        'lens_caps_neck_strap_case',

                    'warranty' =>
                        'manufacturing_defects_3_months',
                ],

                'is_default' =>
                    true,

                'is_active' =>
                    true,

                'sort_order' =>
                    1,
            ]
        );

        ProductVariant::updateOrCreate(
            [
                'sku' =>
                    'NV-FALCO-10X42-UD',
            ],
            [
                'product_id' =>
                    $product->id,

                'name_es' =>
                    'Falco 10×42 UD',

                'name_en' =>
                    'Falco 10×42 UD',

                /*
                 * Pendiente de definición
                 * comercial para Natviewer.
                 */
                'price' =>
                    null,

                'currency' =>
                    'COP',

                /*
                 * Pendiente de confirmación
                 * de inventario Natviewer.
                 */
                'manage_stock' =>
                    true,

                'stock_quantity' =>
                    null,

                'stock_status' =>
                    ProductVariant::STOCK_UNKNOWN,

                /*
                 * Especificaciones verificadas
                 * con la ficha del producto.
                 */
                'specifications' => [
                    'magnification' =>
                        '10×',

                    'objective_diameter' =>
                        '42 mm',

                    'lenses' =>
                        'coated',

                    'prism' =>
                        'bak7_roof',

                    'field_of_view' =>
                        '89 m',

                    'minimum_focus' =>
                        '3 m',

                    'eye_relief' =>
                        '15 mm',

                    'exit_pupil' =>
                        '4.2 mm',

                    'chassis' =>
                        'polycarbonate',

                    'weight' =>
                        '687 g',

                    'adjustable_eyecups' =>
                        'yes',

                    'diopter_adjustment' =>
                        'yes',

                    'focus_system' =>
                        'central_wheel',

                    'weather_resistance' =>
                        'splash_rain_fog_resistant',

                    'tripod_compatible' =>
                        'yes',

                    'included_accessories' =>
                        'lens_caps_neck_strap_case',

                    'warranty' =>
                        'manufacturing_defects_3_months',
                ],

                'is_default' =>
                    false,

                'is_active' =>
                    true,

                'sort_order' =>
                    2,
            ]
        );
    }
}