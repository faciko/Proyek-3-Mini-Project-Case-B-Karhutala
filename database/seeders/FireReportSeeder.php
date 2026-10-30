<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Area;
use App\Models\FireReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class FireReportSeeder extends Seeder
{
    public function run(): void
    {
        $reporter = User::where('role', 'reporter')->firstOrFail();
        $area = Area::firstOrFail();

        FireReport::create([
            'user_id' => $reporter->id,
            'area_id' => $area->id,
            'title' => 'Laporan asap di area utara',
            'description' => 'Data dummy untuk pembelajaran.',
            'occurred_at' => now()->subHours(3),
            'latitude' => -6.2000000,
            'longitude' => 106.8166660,
            'condition_level' => 'medium',
            'status' => 'under_review',
        ]);
    }
}
