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
                'description_es' => 'Binoculares diseñados para observación de naturaleza, aves y fauna.',
                'description_en' => 'Binoculars designed for nature, bird and wildlife observation.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}