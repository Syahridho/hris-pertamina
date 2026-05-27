<?php

namespace App\Http\Controllers\Supervisor;

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
     * Monitor kehadiran manpower di placement supervisor hari ini.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\Supervisor $supervisor */
        $supervisor  = auth('supervisor')->user();
        $placement   = $supervisor->placement;
        $today       = Carbon::today();
        $manpowerIds = $placement?->manpowers()->pluck('id') ?? collect();

        $attendances = Attendance::whereIn('manpower_id', $manpowerIds)
            ->whereDate('date', $today)
            ->with('manpower')
            ->orderByDesc('clock_in')
            ->get();

        $notPresent = $placement?->manpowers()
            ->whereNotIn('id', $attendances->pluck('manpower_id'))
            ->get() ?? collect();

        return view('supervisor.attendance.index', compact(
            'supervisor', 'attendances', 'notPresent', 'today', 'placement'
        ));
    }

    /**
     * Riwayat absensi dengan filter.
     */
    public function history(Request $request)
    {
        /** @var \App\Models\Supervisor $supervisor */
        $supervisor  = auth('supervisor')->user();
        $placement   = $supervisor->placement;
        $manpowerIds = $placement?->manpowers()->pluck('id') ?? collect();

        $attendances = Attendance::whereIn('manpower_id', $manpowerIds)
            ->when($request->manpower_id, fn ($q) => $q->where('manpower_id', $request->manpower_id))
            ->when($request->date_from, fn ($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('date', '<=', $request->date_to))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->with('manpower')
            ->orderByDesc('date')
            ->paginate(20);

        $manpowers = $placement?->manpowers()->get() ?? collect();

        return view('supervisor.attendance.history', compact('attendances', 'manpowers'));
    }

    /**
     * Halaman clock supervisor.
     */
    public function clock()
    {
        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();
        $today      = Carbon::today();
        $placement  = $supervisor->placement;

        $todayAttendance = Attendance::where('supervisor_id', $supervisor->id)
            ->whereDate('date', $today)
            ->first();

        return view('supervisor.clock', compact('supervisor', 'placement', 'todayAttendance', 'today'));
    }

    public function clockIn(Request $request): JsonResponse
    {
        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();
        $placement  = $supervisor->placement;
        $today      = Carbon::today();

        $existing = Attendance::where('supervisor_id', $supervisor->id)->whereDate('date', $today)->first();
        if ($existing?->clock_in) {
            return response()->json(['success' => false, 'message' => 'Sudah clock in pada ' . $existing->clock_in->format('H:i')], 422);
        }

        $gpsResult = $this->gps->validate($request->latitude, $request->longitude, $placement->coordinate, $placement->radius);
        if (!$gpsResult['valid']) {
            return response()->json(['success' => false, 'message' => $gpsResult['message'], 'distance' => $gpsResult['distance']], 422);
        }

        Attendance::create(['supervisor_id' => $supervisor->id, 'clock_in' => now(), 'date' => $today, 'status' => 'present']);
        return response()->json(['success' => true, 'time' => now()->format('H:i'), 'message' => '✅ Clock in berhasil!']);
    }

    public function clockOut(Request $request): JsonResponse
    {
        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();
        $placement  = $supervisor->placement;
        $today      = Carbon::today();

        $attendance = Attendance::where('supervisor_id', $supervisor->id)->whereDate('date', $today)->first();
        if (!$attendance?->clock_in) {
            return response()->json(['success' => false, 'message' => 'Belum clock in hari ini.'], 422);
        }
        if ($attendance->clock_out) {
            return response()->json(['success' => false, 'message' => 'Sudah clock out pada ' . $attendance->clock_out->format('H:i')], 422);
        }

        $gpsResult = $this->gps->validate($request->latitude, $request->longitude, $placement->coordinate, $placement->radius);
        if (!$gpsResult['valid']) {
            return response()->json(['success' => false, 'message' => $gpsResult['message'], 'distance' => $gpsResult['distance']], 422);
        }

        $attendance->update(['clock_out' => now()]);
        $duration = $attendance->clock_in->diffInMinutes(now());
        return response()->json(['success' => true, 'time' => now()->format('H:i'), 'duration' => floor($duration / 60) . 'j ' . ($duration % 60) . 'm', 'message' => '✅ Clock out berhasil!']);
    }
}
