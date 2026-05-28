<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Konversi data lama ke format baru sebelum ditampilkan di form.
     *
     * Format lama:
     *   {"days":["monday","tuesday",...], "clock_in":"08:00", "clock_out":"17:00", "tolerance_minutes":15}
     *
     * Format baru:
     *   {"tolerance_minutes":15, "Senin":{"aktif":true,"jam_mulai":"08:00","jam_selesai":"17:00"}, ...}
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $datetimes = $data['datetimes'] ?? [];

        if (! is_array($datetimes)) {
            return $data;
        }

        // Deteksi format lama: ada key 'days' atau 'clock_in'
        if (isset($datetimes['days']) || isset($datetimes['clock_in'])) {
            $data['datetimes'] = $this->convertOldFormat($datetimes);
        }

        return $data;
    }

    private function convertOldFormat(array $old): array
    {
        $hariMap = [
            'monday'    => 'Senin',
            'tuesday'   => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday'  => 'Kamis',
            'friday'    => 'Jumat',
            'saturday'  => 'Sabtu',
            'sunday'    => 'Minggu',
            'senin'     => 'Senin',
            'selasa'    => 'Selasa',
            'rabu'      => 'Rabu',
            'kamis'     => 'Kamis',
            'jumat'     => 'Jumat',
            'sabtu'     => 'Sabtu',
            'minggu'    => 'Minggu',
        ];

        $allHari     = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $activeDays  = collect($old['days'] ?? [])
            ->map(fn ($d) => $hariMap[strtolower($d)] ?? null)
            ->filter()
            ->values()
            ->toArray();

        $clockIn     = $old['clock_in']  ?? '08:00';
        $clockOut    = $old['clock_out'] ?? '17:00';
        $tolerance   = $old['tolerance_minutes'] ?? 15;

        $new = ['tolerance_minutes' => $tolerance];

        foreach ($allHari as $hari) {
            $aktif = in_array($hari, $activeDays);
            $new[$hari] = $aktif
                ? ['aktif' => true,  'jam_mulai' => $clockIn, 'jam_selesai' => $clockOut]
                : ['aktif' => false];
        }

        return $new;
    }
}
