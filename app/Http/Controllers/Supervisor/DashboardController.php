<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();
        $today      = Carbon::today();
        $placement  = $supervisor->placement;

        // Statistik kehadiran manpower di placement ini hari ini
        $manpowerIds = $placement?->manpowers()->pluck('id') ?? collect();

        $todayStats = [
            'present' => Attendance::whereIn('manpower_id', $manpowerIds)->whereDate('date', $today)->where('status', 'present')->count(),
            'late'    => Attendance::whereIn('manpower_id', $manpowerIds)->whereDate('date', $today)->where('status', 'late')->count(),
            'absent'  => $manpowerIds->count() - Attendance::whereIn('manpower_id', $manpowerIds)->whereDate('date', $today)->count(),
            'total'   => $manpowerIds->count(),
        ];

        // Pending submissions yang ditujukan ke supervisor ini
        $pendingCount = Submission::where('supervisor_id', $supervisor->id)
            ->where('status', 'pending')
            ->count();

        $pendingSubmissions = Submission::where('supervisor_id', $supervisor->id)
            ->where('status', 'pending')
            ->with('manpower')
            ->latest()
            ->limit(5)
            ->get();

        // Daftar manpower + status kehadiran hari ini
        $manpowers = $placement?->manpowers()
            ->with(['attendances' => fn ($q) => $q->whereDate('date', $today)])
            ->limit(10)
            ->get() ?? collect();

        return view('supervisor.dashboard', compact(
            'supervisor', 'todayStats', 'pendingCount', 'pendingSubmissions', 'manpowers', 'today'
        ));
    }
}
