<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::updateOrCreate(
            [
                'slug' => 'natviewer',
            ],
            [
                'name' => 'Natviewer',

                'description_es' =>
                    'Natviewer presenta productos ópticos para observación de aves, fauna, paisajes y naturaleza.',

                'description_en' =>
                    'Natviewer presents optical products for birdwatching, wildlife, landscapes and nature observation.',

                'logo_path' =>
                    'images/logo-natviewer-white.png',

                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}