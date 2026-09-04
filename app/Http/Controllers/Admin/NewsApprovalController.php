<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['bpp', 'category', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending_approval', 'published', 'rejected']);
        }

        $news = $query->latest()->paginate(20);
        return view('admin.news.index', compact('news'));
    }

    public function show(News $news)
    {
        $news->load(['bpp', 'category', 'creator', 'penyuluh']);
        return view('admin.news.show', compact('news'));
    }

    public function approve(News $news)
    {
        $news->update([
            'status' => 'published',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'published_at' => now(),
        ]);
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil disetujui dan dipublikasikan.');
    }

    public function reject(Request $request, News $news)
    {
        $request->validate(['admin_notes' => 'nullable|string']);
        $news->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);
        return redirect()->route('admin.news.index')->with('success', 'Berita telah ditolak.');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
