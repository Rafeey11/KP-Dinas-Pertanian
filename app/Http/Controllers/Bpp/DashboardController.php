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

        return view('bpp.dashboard', compact('bpp', 'penyuluhCount', 'newsCount', 'activeRequests', 'pendingNews', 'rejectedNews', 'pendingVideos', 'rejectedVideos'));
    }
}
