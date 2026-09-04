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
        $query = ReportSubmission::with(['penyuluh', 'bpp', 'reportRequest.reportType', 'latestVersion', 'uploader']);

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
        $bpps = Bpp::orderBy('name')->get();
        $reportRequests = ReportRequest::with('reportType')->latest()->get();

        return view('admin.report-validation.index', compact('submissions', 'bpps', 'reportRequests'));
    }

    public function show(ReportSubmission $submission)
    {
        $submission->load(['penyuluh', 'bpp', 'reportRequest.reportType', 'versions.uploader', 'uploader', 'validator']);
        return view('admin.report-validation.show', compact('submission'));
    }

    public function validate_report(Request $request, ReportSubmission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,revision,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $submission->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);

        $statusLabels = [
            'approved' => 'disetujui',
            'revision' => 'diminta revisi',
            'rejected' => 'ditolak',
        ];

        return redirect()->route('admin.report-validation.show', $submission->id)
            ->with('success', 'Laporan berhasil ' . ($statusLabels[$validated['status']] ?? 'diperbarui') . '.');
    }

    public function download(ReportSubmission $submission, $versionId)
    {
        $version = $submission->versions()->findOrFail($versionId);
        $filePath = storage_path('app/' . $version->file_path);

        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'File tidak ditemukan.']);
        }

        return response()->download($filePath, $version->file_name);
    }
}
