<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use App\Models\ReportRequest;
use App\Models\ReportSubmission;
use App\Models\ReportVersion;
use App\Models\Penyuluh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * List all report requests visible to BPP.
     */
    public function index()
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403);

        $requests = ReportRequest::with('reportType')
            ->where('status', 'active')
            ->latest()
            ->get();

        // Attach progress info per request
        $penyuluhCount = Penyuluh::where('bpp_id', $bpp->id)->where('status', 'active')->count();

        foreach ($requests as $req) {
            $req->submitted = ReportSubmission::where('report_request_id', $req->id)
                ->where('bpp_id', $bpp->id)->count();
            $req->total_penyuluh = $penyuluhCount;
        }

        return view('bpp.reports.index', compact('requests', 'bpp'));
    }

    /**
     * Show a specific report request with penyuluh list + status.
     */
    public function show(ReportRequest $reportRequest)
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403);

        $reportRequest->load('reportType');

        $penyuluhs = Penyuluh::where('bpp_id', $bpp->id)->where('status', 'active')->orderBy('name')->get();

        // Get existing submissions for this request + bpp
        $submissions = ReportSubmission::where('report_request_id', $reportRequest->id)
            ->where('bpp_id', $bpp->id)
            ->with('latestVersion')
            ->get()
            ->keyBy('penyuluh_id');

        return view('bpp.reports.show', compact('reportRequest', 'penyuluhs', 'submissions', 'bpp'));
    }

    /**
     * Upload form for a specific penyuluh + request.
     */
    public function upload(ReportRequest $reportRequest, Penyuluh $penyuluh)
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp || $penyuluh->bpp_id !== $bpp->id) abort(403);

        if ($reportRequest->status === 'closed') {
            return redirect()->route('bpp.laporan.index')->with('error', 'Permintaan laporan ini sudah ditutup.');
        }

        $submission = ReportSubmission::where('report_request_id', $reportRequest->id)
            ->where('penyuluh_id', $penyuluh->id)
            ->with('versions')
            ->first();

        return view('bpp.reports.upload', compact('reportRequest', 'penyuluh', 'submission'));
    }

    /**
     * Store or revise an upload.
     */
    public function storeUpload(Request $request, ReportRequest $reportRequest, Penyuluh $penyuluh)
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp || $penyuluh->bpp_id !== $bpp->id) abort(403);

        if ($reportRequest->status === 'closed') {
            return redirect()->route('bpp.laporan.index')->with('error', 'Permintaan laporan ini sudah ditutup.');
        }

        $maxSize = $reportRequest->max_file_size; // KB
        $allowedFormats = $reportRequest->allowed_formats ?? ['pdf'];
        $mimes = implode(',', $allowedFormats);

        $request->validate([
            'file' => "required|file|max:{$maxSize}|mimes:{$mimes}",
            'notes' => 'nullable|string|max:500',
        ]);

        // Find or create submission
        $submission = ReportSubmission::firstOrCreate(
            [
                'report_request_id' => $reportRequest->id,
                'penyuluh_id' => $penyuluh->id,
            ],
            [
                'bpp_id' => $bpp->id,
                'uploaded_by' => auth()->id(),
                'status' => 'pending',
            ]
        );

        // If revision, update status back to pending
        if ($submission->status === 'revision' || $submission->status === 'rejected') {
            $submission->update([
                'status' => 'pending',
                'uploaded_by' => auth()->id(),
            ]);
        }

        // Determine version number
        $lastVersion = $submission->versions()->max('version_number') ?? 0;
        $newVersion = $lastVersion + 1;

        // Store file
        $file = $request->file('file');
        $path = $file->storeAs(
            "reports/{$bpp->id}/{$reportRequest->id}/{$penyuluh->id}",
            "v{$newVersion}_" . time() . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        ReportVersion::create([
            'report_submission_id' => $submission->id,
            'version_number' => $newVersion,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'notes' => $request->notes,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('bpp.laporan.show', $reportRequest->id)
            ->with('success', 'Laporan untuk ' . $penyuluh->name . ' berhasil diupload (Versi ' . $newVersion . ').');
    }

    /**
     * History of all reports for this BPP.
     */
    public function history(Request $request)
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403);

        $query = ReportSubmission::where('bpp_id', $bpp->id)
            ->with(['penyuluh', 'reportRequest.reportType', 'latestVersion']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest()->paginate(20);

        return view('bpp.reports.history', compact('submissions'));
    }
}
