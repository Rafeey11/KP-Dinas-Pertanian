<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $bppCount = \App\Models\Bpp::count();
        $penyuluhCount = \App\Models\Penyuluh::count();
        $newsCount = \App\Models\News::count();
        $pendingReports = \App\Models\ReportSubmission::where('status', 'pending')->count();
        $pendingNews = \App\Models\News::where('status', 'pending_approval')->count();
        $pendingVideos = \App\Models\Video::where('status', 'pending')->count();

        return view('admin.dashboard', compact('bppCount', 'penyuluhCount', 'newsCount', 'pendingReports', 'pendingNews', 'pendingVideos'));
    }
}
