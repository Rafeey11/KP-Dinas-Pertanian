<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportRequest;
use App\Models\ReportType;
use App\Models\Bpp;
use App\Models\Penyuluh;
use App\Models\ReportSubmission;
use Illuminate\Http\Request;

class ReportRequestController extends Controller
{
    public function index()
    {
        $requests = ReportRequest::with('reportType', 'creator')
            ->withCount('submissions')
            ->latest()
            ->get();
        return view('admin.report-requests.index', compact('requests'));
    }

    public function create()
    {
        $types = ReportType::orderBy('name')->get();
        return view('admin.report-requests.form', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type_id' => 'required|exists:report_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period' => 'required|string|max:50',
            'period_year' => 'required|integer|min:2020|max:2099',
            'start_date' => 'nullable|date',
            'deadline' => 'required|date',
            'allowed_formats' => 'nullable|string',
            'max_file_size' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['allowed_formats'] = $validated['allowed_formats']
            ? array_map('trim', explode(',', $validated['allowed_formats']))
            : ['pdf'];

        ReportRequest::create($validated);
        return redirect()->route('admin.report-requests.index')->with('success', 'Permintaan laporan berhasil dibuat.');
    }

    public function show(ReportRequest $reportRequest)
    {
        $reportRequest->load('reportType', 'creator');

        // Get progress per BPP
        $bpps = Bpp::where('status', 'active')->withCount('penyuluhs')->orderBy('name')->get();

        foreach ($bpps as $bpp) {
            $bpp->submitted_count = ReportSubmission::where('report_request_id', $reportRequest->id)
                ->where('bpp_id', $bpp->id)
                ->count();
            $bpp->approved_count = ReportSubmission::where('report_request_id', $reportRequest->id)
                ->where('bpp_id', $bpp->id)
                ->where('status', 'approved')
                ->count();
        }

        $totalPenyuluh = Penyuluh::where('status', 'active')->count();
        $totalSubmitted = $reportRequest->submissions()->count();
        $totalApproved = $reportRequest->submissions()->where('status', 'approved')->count();
        $totalRevision = $reportRequest->submissions()->where('status', 'revision')->count();
        $totalPending = $reportRequest->submissions()->where('status', 'pending')->count();

        return view('admin.report-requests.show', compact(
            'reportRequest', 'bpps', 'totalPenyuluh', 'totalSubmitted',
            'totalApproved', 'totalRevision', 'totalPending'
        ));
    }

    public function edit(ReportRequest $reportRequest)
    {
        $types = ReportType::orderBy('name')->get();
        return view('admin.report-requests.form', compact('reportRequest', 'types'));
    }

    public function update(Request $request, ReportRequest $reportRequest)
    {
        $validated = $request->validate([
            'report_type_id' => 'required|exists:report_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'period' => 'required|string|max:50',
            'period_year' => 'required|integer|min:2020|max:2099',
            'start_date' => 'nullable|date',
            'deadline' => 'required|date',
            'allowed_formats' => 'nullable|string',
            'max_file_size' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,closed',
        ]);

        $validated['allowed_formats'] = $validated['allowed_formats']
            ? array_map('trim', explode(',', $validated['allowed_formats']))
            : ['pdf'];

        $reportRequest->update($validated);
        return redirect()->route('admin.report-requests.index')->with('success', 'Permintaan laporan berhasil diperbarui.');
    }

    public function destroy(ReportRequest $reportRequest)
    {
        // Secara eksplisit menghapus submissions dan versions agar event deleting() berjalan dan file terhapus
        foreach ($reportRequest->submissions()->with('versions')->get() as $submission) {
            foreach ($submission->versions as $version) {
                $version->delete();
            }
            $submission->delete();
        }
        $reportRequest->delete();
        return redirect()->route('admin.report-requests.index')->with('success', 'Permintaan laporan berhasil dihapus.');
    }
}
