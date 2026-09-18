<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $bpp = auth()->user()->bpp;
        
        $penyuluhCount = $bpp ? $bpp->penyuluhs()->count() : 0;
        $newsCount = $bpp ? \App\Models\News::where('bpp_id', $bpp->id)->count() : 0;
        $activeRequests = \App\Models\ReportRequest::where('status', 'active')->count();

        $pendingNews = $bpp ? \App\Models\News::where('bpp_id', $bpp->id)->where('status', 'pending_approval')->count() : 0;
        $rejectedNews = $bpp ? \App\Models\News::where('bpp_id', $bpp->id)->where('status', 'rejected')->count() : 0;
        
        $pendingVideos = $bpp ? \App\Models\Video::where('bpp_id', $bpp->id)->where('status', 'pending')->count() : 0;
        $rejectedVideos = $bpp ? \App\Models\Video::where('bpp_id', $bpp->id)->where('status', 'rejected')->count() : 0;

        $reportStats = [
            'pending' => 0,
            'revision' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        if ($bpp) {
            $reportStats['pending'] = \App\Models\ReportSubmission::where('bpp_id', $bpp->id)->where('status', 'pending')->count();
            $reportStats['revision'] = \App\Models\ReportSubmission::where('bpp_id', $bpp->id)->where('status', 'revision')->count();
            $reportStats['approved'] = \App\Models\ReportSubmission::where('bpp_id', $bpp->id)->where('status', 'approved')->count();
            $reportStats['rejected'] = \App\Models\ReportSubmission::where('bpp_id', $bpp->id)->where('status', 'rejected')->count();
        }
        
        $unsubmittedReports = 0;
        if ($bpp) {
            $activeRequestIds = \App\Models\ReportRequest::where('status', 'active')->pluck('id')->toArray();
            $submittedRequestIds = \App\Models\ReportSubmission::where('bpp_id', $bpp->id)->whereIn('report_request_id', $activeRequestIds)->pluck('report_request_id')->toArray();
            $unsubmittedReports = count($activeRequestIds) - count($submittedRequestIds);
            if ($unsubmittedReports < 0) $unsubmittedReports = 0;
        }

        $latestNews = \App\Models\News::where('status', 'published')
            ->with(['bpp', 'category'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('bpp.dashboard', compact('bpp', 'penyuluhCount', 'newsCount', 'activeRequests', 'pendingNews', 'rejectedNews', 'pendingVideos', 'rejectedVideos', 'latestNews', 'reportStats', 'unsubmittedReports'));
    }
}
