<?php

namespace Database\Seeders;

use App\Models\Placement;
use App\Models\Project;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class PlacementSeeder extends Seeder
{
    public function run(): void
    {
        $projectCilacap = Project::where('name', 'RU-IV Cilacap')->first();
        $projectPlumpang = Project::where('name', 'TBBM Plumpang')->first();
        $shiftPagi = Schedule::where('name', 'Shift Pagi')->first();
        $shiftSiang = Schedule::where('name', 'Shift Siang')->first();

        if (!$projectCilacap || !$projectPlumpang || !$shiftPagi || !$shiftSiang) {
            $this->command->warn('⚠️  Placement seed skipped: Project atau Schedule belum ada.');
            return;
        }

        $placements = [
            [
                'name'        => 'Area Kilang Cilacap',
                'description' => 'Lokasi kerja di kawasan kilang minyak RU-IV Cilacap.',
                'coordinate'  => '-7.716667,109.000000',
                'radius'      => 200,
                'schedule_id' => $shiftPagi->id,
                'project_id'  => $projectCilacap->id,
            ],
            [
                'name'        => 'Terminal Plumpang Jakarta',
                'description' => 'Lokasi kerja di Terminal BBM Plumpang, Jakarta Utara.',
                'coordinate'  => '-6.133333,106.883333',
                'radius'      => 150,
                'schedule_id' => $shiftSiang->id,
                'project_id'  => $projectPlumpang->id,
            ],
        ];

        foreach ($placements as $placement) {
            Placement::updateOrCreate(['name' => $placement['name']], $placement);
        }

        $this->command->info('✅ Placements seeded: ' . count($placements) . ' records');
    }
}
