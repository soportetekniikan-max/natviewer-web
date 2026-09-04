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
                'description_es' => 'Equipos ópticos para observación de naturaleza y avistamiento de aves.',
                'description_en' => 'Optical equipment for nature observation and birdwatching.',
                'logo_path' => 'images/logo-natviewer-white.png',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}