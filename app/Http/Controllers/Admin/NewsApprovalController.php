<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsApprovalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $binaans = $user->role === 'admin_tim_kerja' ? $user->bpp_binaans->pluck('id')->toArray() : [];
        $query = News::with(['bpp', 'category', 'creator']);

        if ($user->role === 'admin_tim_kerja') {
            $query->whereIn('bpp_id', $binaans);
        }

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
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja' && !in_array($news->bpp_id, $user->bpp_binaans->pluck('id')->toArray())) {
            abort(403, 'Unauthorized Access.');
        }

        $news->load(['bpp', 'category', 'creator', 'penyuluh']);
        return view('admin.news.show', compact('news'));
    }

    public function approve(News $news)
    {
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja' && !in_array($news->bpp_id, $user->bpp_binaans->pluck('id')->toArray())) {
            abort(403, 'Unauthorized Access.');
        }
        $news->update([
            'status' => 'published',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'published_at' => now(),
        ]);

        \App\Services\AuditLogService::log(
            'published',
            'Berita',
            $news,
            "Berita '{$news->title}' dipublikasikan oleh {$user->name}.",
            ['status' => 'pending_approval'],
            ['status' => 'published']
        );

        if ($news->creator) {
            $news->creator->notify(new \App\Notifications\NewsValidatedNotification($news, 'published'));
        }

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil disetujui dan dipublikasikan.');
    }

    public function reject(Request $request, News $news)
    {
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja' && !in_array($news->bpp_id, $user->bpp_binaans->pluck('id')->toArray())) {
            abort(403, 'Unauthorized Access.');
        }
        $request->validate(['admin_notes' => 'required|string|max:1000']);
        $news->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        \App\Services\AuditLogService::log(
            'rejected',
            'Berita',
            $news,
            "Berita '{$news->title}' ditolak oleh {$user->name}.",
            ['status' => 'pending_approval'],
            ['status' => 'rejected', 'admin_notes' => $request->admin_notes]
        );

        if ($news->creator) {
            $news->creator->notify(new \App\Notifications\NewsValidatedNotification($news, 'rejected'));
        }

        return redirect()->route('admin.news.index')->with('success', 'Berita telah ditolak.');
    }

    public function destroy(News $news)
    {
        if (auth()->user()->role === 'admin_tim_kerja') {
            abort(403, 'Unauthorized Access.');
        }
        if ($news->thumbnail) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
