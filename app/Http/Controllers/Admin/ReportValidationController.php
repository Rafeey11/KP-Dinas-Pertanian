<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportSubmission;
use App\Models\ReportRequest;
use App\Models\Bpp;
use Illuminate\Http\Request;

class ReportValidationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $binaans = $user->role === 'admin_tim_kerja' ? $user->bpp_binaans->pluck('id')->toArray() : [];

        $query = ReportSubmission::with(['penyuluh', 'bpp', 'reportRequest.reportType', 'latestVersion', 'uploader']);

        if ($user->role === 'admin_tim_kerja') {
            $query->whereIn('bpp_id', $binaans);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bpp_id')) {
            $query->where('bpp_id', $request->bpp_id);
        }
        if ($request->filled('report_request_id')) {
            $query->where('report_request_id', $request->report_request_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('penyuluh', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $submissions = $query->latest()->paginate(20);
        $bppsQuery = Bpp::orderBy('name');
        if ($user->role === 'admin_tim_kerja') {
            $bppsQuery->whereIn('id', $binaans);
        }
        $bpps = $bppsQuery->get();
        $reportRequests = ReportRequest::with('reportType')->latest()->get();

        return view('admin.report-validation.index', compact('submissions', 'bpps', 'reportRequests'));
    }

    public function show(ReportSubmission $submission)
    {
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja' && !in_array($submission->bpp_id, $user->bpp_binaans->pluck('id')->toArray())) {
            abort(403, 'Unauthorized Access.');
        }

        $submission->load(['penyuluh', 'bpp', 'reportRequest.reportType', 'versions.uploader', 'uploader', 'validator']);
        return view('admin.report-validation.show', compact('submission'));
    }

    public function validate_report(Request $request, ReportSubmission $submission)
    {
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja' && !in_array($submission->bpp_id, $user->bpp_binaans->pluck('id')->toArray())) {
            abort(403, 'Unauthorized Access.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,revision,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        if (in_array($validated['status'], ['revision', 'rejected']) && empty($validated['admin_notes'])) {
            return back()->withErrors(['admin_notes' => 'Catatan admin wajib diisi jika laporan diminta revisi atau ditolak.'])->withInput();
        }

        $submission->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);

        $latestVersion = $submission->latestVersion;
        if ($latestVersion) {
            \App\Models\ReportValidationHistory::create([
                'report_submission_id' => $submission->id,
                'report_version_id' => $latestVersion->id,
                'validated_by' => auth()->id(),
                'status' => $validated['status'],
                'notes' => $validated['admin_notes'],
            ]);
        }

        $statusLabels = [
            'approved' => 'disetujui',
            'revision' => 'diminta revisi',
            'rejected' => 'ditolak',
        ];

        // Audit Log
        \App\Services\AuditLogService::log(
            $validated['status'] === 'revision' ? 'revision_requested' : $validated['status'],
            'Laporan',
            $submission,
            "Laporan {$submission->penyuluh->name} " . $statusLabels[$validated['status']] . " oleh {$user->name}.",
            ['status' => $submission->getOriginal('status')],
            ['status' => $validated['status'], 'admin_notes' => $validated['admin_notes']]
        );

        // Notify BPP User
        if ($submission->uploader) {
            $submission->uploader->notify(new \App\Notifications\ReportValidatedNotification($submission, $validated['status'], $user->name));
        }

        return redirect()->route('admin.report-validation.show', $submission->id)
            ->with('success', 'Laporan berhasil ' . ($statusLabels[$validated['status']] ?? 'diperbarui') . '.');
    }

    public function download(ReportSubmission $submission, $versionId)
    {
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja' && !in_array($submission->bpp_id, $user->bpp_binaans->pluck('id')->toArray())) {
            abort(403, 'Unauthorized Access.');
        }

        $version = $submission->versions()->findOrFail($versionId);
        $filePath = storage_path('app/' . $version->file_path);

        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'File tidak ditemukan.']);
        }

        return response()->download($filePath, $version->file_name);
    }
}
