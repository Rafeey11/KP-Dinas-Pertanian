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
        ]);

        $validated['bpp_id'] = $bpp->id;
        $validated['created_by'] = auth()->id();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . rand(100, 999);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }
        \App\Models\News::create($validated);

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
        $newsItem = \App\Models\News::where('bpp_id', auth()->user()->bpp_id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:news_categories,id',
            'status' => 'required|in:draft,pending_approval',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . rand(100, 999);

        if ($request->hasFile('thumbnail')) {
            if ($newsItem->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($newsItem->thumbnail)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($newsItem->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }
        $newsItem->update($validated);

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
