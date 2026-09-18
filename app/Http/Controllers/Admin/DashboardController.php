<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $binaans = $user->role === 'admin_tim_kerja' ? $user->bpp_binaans->pluck('id')->toArray() : [];

        $bppQuery = \App\Models\Bpp::query();
        $penyuluhQuery = \App\Models\Penyuluh::query();
        $newsQuery = \App\Models\News::query();
        $reportQuery = \App\Models\ReportSubmission::query();
        $videoQuery = \App\Models\Video::query();

        if ($user->role === 'admin_tim_kerja') {
            $bppQuery->whereIn('id', $binaans);
            $penyuluhQuery->whereIn('bpp_id', $binaans);
            $newsQuery->whereIn('bpp_id', $binaans);
            $reportQuery->whereIn('bpp_id', $binaans);
            $videoQuery->whereIn('bpp_id', $binaans);
        }

        $bppCount = $bppQuery->count();
        $penyuluhCount = $penyuluhQuery->count();
        $newsCount = $newsQuery->count();
        $pendingReports = $reportQuery->where('status', 'pending')->count();
        $pendingNews = $newsQuery->where('status', 'pending_approval')->count();
        $pendingVideos = $videoQuery->where('status', 'pending')->count();

        return view('admin.dashboard', compact('bppCount', 'penyuluhCount', 'newsCount', 'pendingReports', 'pendingNews', 'pendingVideos'));
    }
}
