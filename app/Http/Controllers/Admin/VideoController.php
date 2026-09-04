<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Bpp;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('bpp')
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $bpps = Bpp::orderBy('name')->get();
        return view('admin.videos.form', compact('bpps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bpp_id' => 'nullable|exists:bpps,id',
            'video_url' => 'required|url',
            'status' => 'required|in:active,inactive,pending,rejected',
            'show_on_homepage' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);
        $validated['created_by'] = auth()->id();
        $validated['source_type'] = 'youtube';

        if (isset($validated['sort_order'])) {
            $this->handleSortOrder($validated['sort_order']);
        }

        Video::create($validated);
        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil ditambahkan.');
    }

    public function edit(Video $video)
    {
        $bpps = Bpp::orderBy('name')->get();
        return view('admin.videos.form', compact('video', 'bpps'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bpp_id' => 'nullable|exists:bpps,id',
            'video_url' => 'required|url',
            'status' => 'required|in:active,inactive,pending,rejected',
            'show_on_homepage' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);
        $validated['source_type'] = 'youtube';

        if (isset($validated['sort_order']) && $validated['sort_order'] != $video->sort_order) {
            $this->handleSortOrder($validated['sort_order'], $video->id);
        }

        $video->update($validated);
        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil dihapus.');
    }

    public function approve(Video $video)
    {
        $video->update(['status' => 'active', 'show_on_homepage' => true]);
        return redirect()->route('admin.videos.index')->with('success', 'Video edukasi dari BPP berhasil disetujui.');
    }

    public function reject(Video $video)
    {
        $video->update(['status' => 'rejected', 'show_on_homepage' => false]);
        return redirect()->route('admin.videos.index')->with('success', 'Video edukasi dari BPP ditolak.');
    }

    protected function handleSortOrder($sortOrder, $excludeId = null)
    {
        $existing = Video::where('sort_order', $sortOrder);
        if ($excludeId) {
            $existing->where('id', '!=', $excludeId);
        }

        if ($existing->exists()) {
            Video::where('sort_order', '>=', $sortOrder)
                 ->when($excludeId, function($query) use ($excludeId) {
                     return $query->where('id', '!=', $excludeId);
                 })
                 ->increment('sort_order');
        }
    }
}
