<?php

namespace App\Http\Controllers\Manpower;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\GpsValidationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private GpsValidationService $gps) {}

    /**
     * Halaman clock in/out dengan GPS detector.
     */
    public function clock(Request $request)
    {
        /** @var \App\Models\Manpower $manpower */
        $manpower  = auth('manpower')->user();
        $placement = $manpower->placement;
        $today     = Carbon::today();

        $todayAttendance = Attendance::where('manpower_id', $manpower->id)
            ->whereDate('date', $today)
            ->first();

        return view('manpower.clock', compact('manpower', 'placement', 'todayAttendance', 'today'));
    }

    /**
     * Proses Clock In — validasi GPS.
     */
    public function clockIn(Request $request): JsonResponse
    {
        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        /** @var \App\Models\Manpower $manpower */
        $manpower  = auth('manpower')->user();
        $placement = $manpower->placement;
        $today     = Carbon::today();

        // Cek sudah clock in hari ini?
        $existing = Attendance::where('manpower_id', $manpower->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->clock_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan clock in hari ini pada ' . $existing->clock_in->format('H:i'),
            ], 422);
        }

        // Validasi GPS
        if (!$placement || !$placement->coordinate) {
            return response()->json(['success' => false, 'message' => 'Lokasi kerja belum dikonfigurasi. Hubungi supervisor.'], 422);
        }

        $gpsResult = $this->gps->validate(
            $request->latitude,
            $request->longitude,
            $placement->coordinate,
            $placement->radius
        );

        if (!$gpsResult['valid']) {
            return response()->json([
                'success' => false,
                'message' => $gpsResult['message'],
                'distance' => $gpsResult['distance'],
                'radius'   => $gpsResult['radius'],
            ], 422);
        }

        // Tentukan status: tepat waktu atau terlambat
        $schedule = $placement->schedule;
        $status   = 'present';

        if ($schedule && isset($schedule->datetimes['clock_in'])) {
            $scheduledIn    = Carbon::parse($today->format('Y-m-d') . ' ' . $schedule->datetimes['clock_in']);
            $toleranceEnd   = $scheduledIn->copy()->addMinutes($schedule->datetimes['tolerance_minutes'] ?? 15);
            if (Carbon::now()->gt($toleranceEnd)) {
                $status = 'late';
            }
        }

        // Simpan attendance
        Attendance::create([
            'manpower_id' => $manpower->id,
            'clock_in'    => now(),
            'date'        => $today,
            'status'      => $status,
        ]);

        return response()->json([
            'success'  => true,
            'status'   => $status,
            'time'     => now()->format('H:i'),
            'message'  => $status === 'late'
                ? '⚠️ Clock in berhasil, namun Anda terlambat.'
                : '✅ Clock in berhasil! Selamat bekerja.',
        ]);
    }

    /**
     * Proses Clock Out — validasi GPS.
     */
    public function clockOut(Request $request): JsonResponse
    {
        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        /** @var \App\Models\Manpower $manpower */
        $manpower  = auth('manpower')->user();
        $placement = $manpower->placement;
        $today     = Carbon::today();

        $attendance = Attendance::where('manpower_id', $manpower->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            return response()->json(['success' => false, 'message' => 'Anda belum melakukan clock in hari ini.'], 422);
        }

        if ($attendance->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan clock out hari ini pada ' . $attendance->clock_out->format('H:i'),
            ], 422);
        }

        // Validasi GPS
        $gpsResult = $this->gps->validate(
            $request->latitude,
            $request->longitude,
            $placement->coordinate,
            $placement->radius
        );

        if (!$gpsResult['valid']) {
            return response()->json([
                'success'  => false,
                'message'  => $gpsResult['message'],
                'distance' => $gpsResult['distance'],
                'radius'   => $gpsResult['radius'],
            ], 422);
        }

        $attendance->update(['clock_out' => now()]);
        $duration = $attendance->clock_in->diffInMinutes(now());
        $hours    = floor($duration / 60);
        $minutes  = $duration % 60;

        return response()->json([
            'success'  => true,
            'time'     => now()->format('H:i'),
            'duration' => "{$hours}j {$minutes}m",
            'message'  => "✅ Clock out berhasil! Durasi kerja: {$hours}j {$minutes}m.",
        ]);
    }

    /**
     * Halaman riwayat absensi.
     */
    public function history(Request $request)
    {
        /** @var \App\Models\Manpower $manpower */
        $manpower = auth('manpower')->user();

        $attendances = Attendance::where('manpower_id', $manpower->id)
            ->when($request->month, fn ($q) => $q->whereMonth('date', $request->month))
            ->when($request->year, fn ($q) => $q->whereYear('date', $request->year))
            ->orderByDesc('date')
            ->paginate(20);

        return view('manpower.attendance.history', compact('manpower', 'attendances'));
    }
}
