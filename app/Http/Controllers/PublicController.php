<?php

namespace App\Http\Controllers;

use App\Models\Bpp;
use App\Models\Penyuluh;
use App\Models\News;
use App\Models\Video;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $stats = [
            'bpp_count' => Bpp::where('status', 'active')->count(),
            'penyuluh_count' => Penyuluh::where('status', 'active')->count(),
            'news_count' => News::where('status', 'published')->count(),
            'district_count' => Bpp::where('status', 'active')->distinct('district')->count(),
        ];

        $latestNews = News::where('status', 'published')
            ->with(['bpp', 'penyuluh'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $previewBpps = Bpp::where('status', 'active')
            ->take(3)
            ->get();

        $previewVideos = Video::where('status', 'active')
            ->where('show_on_homepage', true)
            ->orderBy('sort_order')
            ->take(2)
            ->get();

        return view('public.home', compact('stats', 'latestNews', 'previewBpps', 'previewVideos'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function bppIndex(Request $request)
    {
        $query = Bpp::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%");
            });
        }

        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        $bpps = $query->paginate(12)->withQueryString();
        
        $districts = Bpp::select('district')->distinct()->orderBy('district')->pluck('district');

        return view('public.bpp.index', compact('bpps', 'districts'));
    }

    public function bppShow($slug)
    {
        $bpp = Bpp::where('slug', $slug)->with(['penyuluhs' => function($q) {
            $q->where('status', 'active')->where('is_public', true);
        }])->firstOrFail();

        return view('public.bpp.show', compact('bpp'));
    }

    public function penyuluhShow($slug)
    {
        $penyuluh = Penyuluh::where('slug', $slug)->where('status', 'active')->where('is_public', true)
            ->with('bpp')
            ->firstOrFail();

        return view('public.penyuluh.show', compact('penyuluh'));
    }

    public function newsIndex(Request $request)
    {
        $query = News::where('status', 'published')->with(['bpp', 'penyuluh', 'category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->latest('published_at')->paginate(9)->withQueryString();

        return view('public.news.index', compact('news'));
    }

    public function newsShow($slug)
    {
        $newsItem = News::where('slug', $slug)->where('status', 'published')
            ->with(['bpp', 'penyuluh', 'category'])
            ->firstOrFail();

        return view('public.news.show', compact('newsItem'));
    }

    public function videoIndex()
    {
        $videos = Video::where('status', 'active')
            ->orderBy('sort_order')
            ->orderByDesc('activity_date')
            ->paginate(12);

        return view('public.video.index', compact('videos'));
    }
}
