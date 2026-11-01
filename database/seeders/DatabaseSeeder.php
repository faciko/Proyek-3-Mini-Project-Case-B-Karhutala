<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        User::updateOrCreate(
            ['email' => 'pelapor@example.test'],
            [
                'name' => 'Demo Pelapor',
                'role' => 'reporter',
                'password' => Hash::make('demo12345'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'monitor@example.test'],
            [
                'name' => 'Demo Monitor',
                'role' => 'monitor',
                'password' => Hash::make('demo12345'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'koordinator@example.test'],
            [
                'name' => 'Demo Koordinator',
                'role' => 'coordinator',
                'password' => Hash::make('demo12345'),
            ]
        );

        $this->call([
            AreaSeeder::class,
            FireReportSeeder::class,
        ]);
    }
}
