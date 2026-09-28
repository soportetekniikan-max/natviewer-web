<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            [
                'slug' => 'binoculares-terrestres',
            ],
            [
                'name_es' => 'Binoculares terrestres',
                'name_en' => 'Terrestrial binoculars',

                'description_es' =>
                    'Binoculares para observación de aves, fauna, paisajes, naturaleza y actividades al aire libre.',

                'description_en' =>
                    'Binoculars for birdwatching, wildlife, landscapes, nature observation and outdoor activities.',

                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}