<?php

namespace Database\Seeders;

use App\Models\Placement;
use App\Models\Project;
use App\Models\Supervisor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::where('name', 'RU-IV Cilacap')->first();
        $placement = Placement::where('name', 'Area Kilang Cilacap')->first();

        if (!$project || !$placement) {
            $this->command->warn('⚠️  Supervisor seed skipped: Project atau Placement belum ada.');
            return;
        }

        Supervisor::updateOrCreate(
            ['email' => 'supervisor@hris-pertamina.com'],
            [
                'username'     => 'spv_cilacap',
                'email'        => 'supervisor@hris-pertamina.com',
                'no_telp'      => '08110000002',
                'password'     => Hash::make('password'),
                'project_id'   => $project->id,
                'placement_id' => $placement->id,
                'status'       => 'active',
            ]
        );

        $this->command->info('✅ Supervisor seeded: supervisor@hris-pertamina.com / password');
    }
}
