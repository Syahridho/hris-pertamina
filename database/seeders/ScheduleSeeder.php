<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'name'        => 'Shift Pagi',
                'description' => 'Jadwal kerja shift pagi, Senin–Jumat.',
                'datetimes'   => [
                    'days'      => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    'clock_in'  => '07:00',
                    'clock_out' => '15:00',
                    'tolerance_minutes' => 15,
                ],
            ],
            [
                'name'        => 'Shift Siang',
                'description' => 'Jadwal kerja shift siang, Senin–Jumat.',
                'datetimes'   => [
                    'days'      => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    'clock_in'  => '15:00',
                    'clock_out' => '23:00',
                    'tolerance_minutes' => 15,
                ],
            ],
            [
                'name'        => 'Shift Malam',
                'description' => 'Jadwal kerja shift malam, termasuk akhir pekan.',
                'datetimes'   => [
                    'days'      => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                    'clock_in'  => '23:00',
                    'clock_out' => '07:00',
                    'tolerance_minutes' => 15,
                ],
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::updateOrCreate(['name' => $schedule['name']], $schedule);
        }

        $this->command->info('✅ Schedules seeded: ' . count($schedules) . ' records');
    }
}
