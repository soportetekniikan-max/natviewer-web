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

        $product8 = Product::updateOrCreate(
            [
                'slug' =>
                    'natviewer-falco-8x42-ud',
            ],
            [
                'category_id' =>
                    $category->id,

                'brand_id' =>
                    $brand->id,

                'name_es' =>
                    'Natviewer Falco 8×42 UD',

                'name_en' =>
                    'Natviewer Falco 8×42 UD',

                'short_description_es' =>
                    'Binoculares 8×42 con lentes coated, prismas BAK-7 tipo techo, campo de visión de 93 m a 1000 m y enfoque mínimo de 3 m para observación de aves y naturaleza.',

                'short_description_en' =>
                    '8×42 binoculars with coated lenses, BAK-7 roof prisms, a 93 m field of view at 1000 m and a 3 m minimum focus for birdwatching and nature observation.',

                'description_es' =>
                    'Natviewer Falco 8×42 UD es un binocular para observación de aves, fauna y naturaleza. Ofrece 8× de aumento, objetivo de 42 mm, lentes coated, prismas BAK-7 tipo techo, campo de visión de 93 m a 1000 m, enfoque mínimo de 3 m, alivio ocular de 15 mm y pupila de salida de 5.25 mm. Su chasis es de policarbonato, pesa 675 g, incorpora oculares ajustables, ajuste dióptrico, enfoque central, resistencia a lluvia y niebla y compatibilidad con trípode.',

                'description_en' =>
                    'Natviewer Falco 8×42 UD is a binocular for birdwatching, wildlife and nature observation. It offers 8× magnification, a 42 mm objective, coated lenses, BAK-7 roof prisms, a 93 m field of view at 1000 m, 3 m minimum focus, 15 mm eye relief and a 5.25 mm exit pupil. It has a polycarbonate chassis, weighs 675 g, and includes adjustable eyecups, diopter adjustment, central focusing, rain and fog resistance, and tripod compatibility.',

                'status' =>
                    Product::STATUS_PUBLISHED,

                'is_featured' =>
                    true,

                'meta_title_es' =>
                    'Binoculares Natviewer Falco 8×42 UD',

                'meta_title_en' =>
                    'Natviewer Falco 8×42 UD Binoculars',

                'meta_description_es' =>
                    'Binoculares Natviewer Falco 8×42 UD con prismas BAK-7 tipo techo, campo de visión de 93 m a 1000 m y enfoque mínimo de 3 m.',

                'meta_description_en' =>
                    'Natviewer Falco 8×42 UD binoculars with BAK-7 roof prisms, a 93 m field of view at 1000 m and a 3 m minimum focus.',
            ]
        );

        $product10 = Product::updateOrCreate(
            [
                'slug' =>
                    'natviewer-falco-10x42-ud',
            ],
            [
                'category_id' =>
                    $category->id,

                'brand_id' =>
                    $brand->id,

                'name_es' =>
                    'Natviewer Falco 10×42 UD',

                'name_en' =>
                    'Natviewer Falco 10×42 UD',

                'short_description_es' =>
                    'Binoculares 10×42 con lentes coated, prismas BAK-7 tipo techo, campo de visión de 89 m a 1000 m y enfoque mínimo de 3 m para observación de aves y naturaleza.',

                'short_description_en' =>
                    '10×42 binoculars with coated lenses, BAK-7 roof prisms, an 89 m field of view at 1000 m and a 3 m minimum focus for birdwatching and nature observation.',

                'description_es' =>
                    'Natviewer Falco 10×42 UD es un binocular para observación de aves, fauna y naturaleza. Ofrece 10× de aumento, objetivo de 42 mm, lentes coated, prismas BAK-7 tipo techo, campo de visión de 89 m a 1000 m, enfoque mínimo de 3 m, alivio ocular de 15 mm y pupila de salida de 4.2 mm. Su chasis es de policarbonato, pesa 687 g, incorpora oculares ajustables, ajuste dióptrico, enfoque central, resistencia a salpicaduras, lluvia y niebla y compatibilidad con trípode.',

                'description_en' =>
                    'Natviewer Falco 10×42 UD is a binocular for birdwatching, wildlife and nature observation. It offers 10× magnification, a 42 mm objective, coated lenses, BAK-7 roof prisms, an 89 m field of view at 1000 m, 3 m minimum focus, 15 mm eye relief and a 4.2 mm exit pupil. It has a polycarbonate chassis, weighs 687 g, and includes adjustable eyecups, diopter adjustment, central focusing, splash, rain and fog resistance, and tripod compatibility.',

                'status' =>
                    Product::STATUS_PUBLISHED,

                'is_featured' =>
                    true,

                'meta_title_es' =>
                    'Binoculares Natviewer Falco 10×42 UD',

                'meta_title_en' =>
                    'Natviewer Falco 10×42 UD Binoculars',

                'meta_description_es' =>
                    'Binoculares Natviewer Falco 10×42 UD con prismas BAK-7 tipo techo, campo de visión de 89 m a 1000 m y enfoque mínimo de 3 m.',

                'meta_description_en' =>
                    'Natviewer Falco 10×42 UD binoculars with BAK-7 roof prisms, an 89 m field of view at 1000 m and a 3 m minimum focus.',
            ]
        );

        ProductVariant::updateOrCreate(
            [
                'sku' =>
                    'NV-FALCO-8X42-UD',
            ],
            [
                'product_id' =>
                    $product8->id,

                'name_es' =>
                    'Falco 8×42 UD',

                'name_en' =>
                    'Falco 8×42 UD',

                'price' =>
                    null,

                'currency' =>
                    'COP',

                'manage_stock' =>
                    true,

                'stock_quantity' =>
                    null,

                'stock_status' =>
                    ProductVariant::STOCK_UNKNOWN,

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
                    $product10->id,

                'name_es' =>
                    'Falco 10×42 UD',

                'name_en' =>
                    'Falco 10×42 UD',

                'price' =>
                    null,

                'currency' =>
                    'COP',

                'manage_stock' =>
                    true,

                'stock_quantity' =>
                    null,

                'stock_status' =>
                    ProductVariant::STOCK_UNKNOWN,

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
                    true,

                'is_active' =>
                    true,

                'sort_order' =>
                    1,
            ]
        );
    }
}