<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        Area::updateOrCreate([
            'name' => 'Sukamaju',
        ]);

        Area::updateOrCreate([
            'name' => 'Harapan Jaya',
        ]);
    }
}