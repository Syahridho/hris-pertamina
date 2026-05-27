<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Daftar semua submission yang ditujukan ke supervisor ini.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();

        $submissions = Submission::where('supervisor_id', $supervisor->id)
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->with('manpower')
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'pending'  => Submission::where('supervisor_id', $supervisor->id)->where('status', 'pending')->count(),
            'approved' => Submission::where('supervisor_id', $supervisor->id)->where('status', 'approved')->count(),
            'rejected' => Submission::where('supervisor_id', $supervisor->id)->where('status', 'rejected')->count(),
        ];

        return view('supervisor.submissions.index', compact('submissions', 'stats'));
    }

    /**
     * Detail submission.
     */
    public function show(Submission $submission)
    {
        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();
        abort_if($submission->supervisor_id !== $supervisor->id, 403);

        return view('supervisor.submissions.show', compact('submission'));
    }

    /**
     * Approve submission.
     */
    public function approve(Request $request, Submission $submission)
    {
        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();
        abort_if($submission->supervisor_id !== $supervisor->id, 403);
        abort_if($submission->status !== 'pending', 422, 'Submission sudah diproses.');

        $submission->update(['status' => 'approved']);

        // TODO: Kirim notifikasi ke manpower

        return redirect()->route('supervisor.submissions.index')
            ->with('success', "Pengajuan \"{$submission->name}\" telah disetujui.");
    }

    /**
     * Reject submission dengan alasan.
     */
    public function reject(Request $request, Submission $submission)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ], ['reason.required' => 'Alasan penolakan wajib diisi.']);

        /** @var \App\Models\Supervisor $supervisor */
        $supervisor = auth('supervisor')->user();
        abort_if($submission->supervisor_id !== $supervisor->id, 403);
        abort_if($submission->status !== 'pending', 422, 'Submission sudah diproses.');

        $submission->update([
            'status'      => 'rejected',
            'description' => $submission->description . "\n\n[DITOLAK] Alasan: " . $request->reason,
        ]);

        // TODO: Kirim notifikasi ke manpower

        return redirect()->route('supervisor.submissions.index')
            ->with('error', "Pengajuan \"{$submission->name}\" telah ditolak.");
    }
}
