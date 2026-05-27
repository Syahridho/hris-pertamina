<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan seeding mengikuti dependency FK:
     * superadmins → projects → schedules → placements → supervisors → manpowers
     */
    public function run(): void
    {
        $this->call([
            SuperadminSeeder::class,
            ProjectSeeder::class,
            ScheduleSeeder::class,
            PlacementSeeder::class,
            SupervisorSeeder::class,
            ManpowerSeeder::class,
        ]);
    }
}
