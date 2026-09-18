<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403, 'User tidak terkait dengan BPP manapun.');

        $news = \App\Models\News::where('bpp_id', $bpp->id)->with('category')->latest()->get();
        return view('bpp.news.index', compact('news'));
    }

    public function create()
    {
        $categories = \App\Models\NewsCategory::all();
        return view('bpp.news.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $bpp = auth()->user()->bpp;
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:news_categories,id',
            'status' => 'required|in:draft,pending_approval',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
        ]);

        $validated['bpp_id'] = $bpp->id;
        $validated['created_by'] = auth()->id();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['content'] = strip_tags($validated['content'], '<p><br><b><strong><i><em><u><ul><ol><li><a><img><h1><h2><h3><h4><h5><h6><blockquote>');

        if (!empty($validated['video_url'])) {
            $validated['video_type'] = str_contains($validated['video_url'], 'youtube') || str_contains($validated['video_url'], 'youtu.be') ? 'youtube' : 'external';
        } else {
            $validated['video_type'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }
        $news = \App\Models\News::create($validated);

        if ($news->status === 'pending_approval') {
            \App\Services\AuditLogService::log(
                'submitted',
                'Berita',
                $news,
                "BPP {$bpp->name} mengirim berita '{$news->title}' untuk review."
            );

            $admins = $bpp->admin_tim_kerjas;
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\NewsSubmittedNotification($news, $bpp));
            }
        }

        return redirect()->route('bpp.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $newsItem = \App\Models\News::where('bpp_id', auth()->user()->bpp_id)->findOrFail($id);
        $categories = \App\Models\NewsCategory::all();
        return view('bpp.news.form', compact('newsItem', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $bpp = auth()->user()->bpp;
        $newsItem = \App\Models\News::where('bpp_id', $bpp->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:news_categories,id',
            'status' => 'required|in:draft,pending_approval',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['content'] = strip_tags($validated['content'], '<p><br><b><strong><i><em><u><ul><ol><li><a><img><h1><h2><h3><h4><h5><h6><blockquote>');

        if (!empty($validated['video_url'])) {
            $validated['video_type'] = str_contains($validated['video_url'], 'youtube') || str_contains($validated['video_url'], 'youtu.be') ? 'youtube' : 'external';
        } else {
            $validated['video_type'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            if ($newsItem->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($newsItem->thumbnail)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($newsItem->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }
        
        $oldStatus = $newsItem->status;
        $newsItem->update($validated);

        if ($validated['status'] === 'pending_approval' && $oldStatus !== 'pending_approval') {
            \App\Services\AuditLogService::log(
                'submitted',
                'Berita',
                $newsItem,
                "BPP {$bpp->name} mengirim berita '{$newsItem->title}' untuk review."
            );

            $admins = $bpp->admin_tim_kerjas;
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\NewsSubmittedNotification($newsItem, $bpp));
            }
        }

        return redirect()->route('bpp.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $newsItem = \App\Models\News::where('bpp_id', auth()->user()->bpp_id)->findOrFail($id);
        
        if ($newsItem->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($newsItem->thumbnail)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($newsItem->thumbnail);
        }

        $newsItem->delete();

        return redirect()->route('bpp.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
