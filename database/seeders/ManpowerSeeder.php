<?php

namespace Database\Seeders;

use App\Models\Manpower;
use App\Models\Placement;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManpowerSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::where('name', 'RU-IV Cilacap')->first();
        $placement = Placement::where('name', 'Area Kilang Cilacap')->first();

        if (!$project || !$placement) {
            $this->command->warn('⚠️  Manpower seed skipped: Project atau Placement belum ada.');
            return;
        }

        $manpowers = [
            [
                'username'     => 'mp_budi',
                'email'        => 'budi@hris-pertamina.com',
                'no_telp'      => '08120000001',
                'password'     => Hash::make('password'),
                'project_id'   => $project->id,
                'placement_id' => $placement->id,
                'status'       => 'active',
            ],
            [
                'username'     => 'mp_sari',
                'email'        => 'sari@hris-pertamina.com',
                'no_telp'      => '08120000002',
                'password'     => Hash::make('password'),
                'project_id'   => $project->id,
                'placement_id' => $placement->id,
                'status'       => 'active',
            ],
        ];

        foreach ($manpowers as $manpower) {
            Manpower::updateOrCreate(['email' => $manpower['email']], $manpower);
        }

        $this->command->info('✅ Manpowers seeded: ' . count($manpowers) . ' records');
    }
}
