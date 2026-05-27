<?php

namespace App\Http\Controllers\Manpower;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Daftar semua pengajuan milik manpower.
     */
    public function index()
    {
        /** @var \App\Models\Manpower $manpower */
        $manpower    = auth('manpower')->user();
        $submissions = Submission::where('manpower_id', $manpower->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('manpower.submissions.index', compact('submissions'));
    }

    /**
     * Form buat pengajuan baru.
     */
    public function create()
    {
        return view('manpower.submissions.create');
    }

    /**
     * Simpan pengajuan baru + upload file lampiran.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50'],
            'type'        => ['required', 'in:cuti,sakit,izin'],
            'description' => ['required', 'string', 'max:1000'],
            'file'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB
        ], [
            'name.required'        => 'Nama pengajuan wajib diisi.',
            'type.required'        => 'Jenis pengajuan wajib dipilih.',
            'type.in'              => 'Jenis pengajuan tidak valid.',
            'description.required' => 'Keterangan wajib diisi.',
            'file.mimes'           => 'File harus berformat PDF, JPG, atau PNG.',
            'file.max'             => 'Ukuran file maksimum 5MB.',
        ]);

        /** @var \App\Models\Manpower $manpower */
        $manpower = auth('manpower')->user();

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions/' . $manpower->id, 'public');
        }

        Submission::create([
            'name'         => $validated['name'],
            'type'         => $validated['type'],
            'description'  => $validated['description'],
            'manpower_id'  => $manpower->id,
            'supervisor_id' => $manpower->placement->supervisors()->first()?->id,
            'file'         => $filePath,
            'status'       => 'pending',
        ]);

        return redirect()->route('manpower.submissions.index')
            ->with('success', 'Pengajuan berhasil dikirim dan menunggu persetujuan supervisor.');
    }

    /**
     * Detail pengajuan.
     */
    public function show(Submission $submission)
    {
        /** @var \App\Models\Manpower $manpower */
        $manpower = auth('manpower')->user();

        // Pastikan hanya bisa lihat pengajuan sendiri
        abort_if($submission->manpower_id !== $manpower->id, 403);

        return view('manpower.submissions.show', compact('submission'));
    }
}
