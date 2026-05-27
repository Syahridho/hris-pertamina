<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'name'        => 'RU-IV Cilacap',
                'description' => 'Proyek operasional Refinery Unit IV Cilacap — unit pengolahan minyak terbesar di Indonesia.',
                'status'      => 'active',
            ],
            [
                'name'        => 'PHE ONWJ',
                'description' => 'Pertamina Hulu Energi Offshore North West Java — eksplorasi dan produksi minyak dan gas lepas pantai.',
                'status'      => 'active',
            ],
            [
                'name'        => 'TBBM Plumpang',
                'description' => 'Terminal Bahan Bakar Minyak Plumpang Jakarta — distribusi dan penyimpanan BBM nasional.',
                'status'      => 'active',
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['name' => $project['name']], $project);
        }

        $this->command->info('✅ Projects seeded: ' . count($projects) . ' records');
    }
}
