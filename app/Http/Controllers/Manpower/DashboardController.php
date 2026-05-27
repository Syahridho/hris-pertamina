<?php

namespace App\Http\Controllers\Manpower;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\Manpower $manpower */
        $manpower = auth('manpower')->user();
        $today    = Carbon::today();

        // Rekord absensi hari ini
        $todayAttendance = Attendance::where('manpower_id', $manpower->id)
            ->whereDate('date', $today)
            ->first();

        // Rekap bulan ini
        $monthStats = Attendance::where('manpower_id', $manpower->id)
            ->whereYear('date', $today->year)
            ->whereMonth('date', $today->month)
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'present') as present,
                SUM(status = 'late') as late,
                SUM(status = 'absent') as absent
            ")
            ->first();

        // Riwayat 7 hari terakhir
        $recentAttendances = Attendance::where('manpower_id', $manpower->id)
            ->orderByDesc('date')
            ->limit(7)
            ->get();

        // Pengajuan terbaru
        $recentSubmissions = $manpower->submissions()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('manpower.dashboard', compact(
            'manpower',
            'todayAttendance',
            'monthStats',
            'recentAttendances',
            'recentSubmissions',
            'today'
        ));
    }
}
