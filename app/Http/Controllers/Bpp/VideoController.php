<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403, 'Anda tidak tertaut dengan BPP manapun.');

        $videos = Video::where('bpp_id', $bpp->id)->orderBy('created_at', 'desc')->get();
        return view('bpp.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('bpp.videos.form');
    }

    public function store(Request $request)
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403, 'Anda tidak tertaut dengan BPP manapun.');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
        ]);

        $validated['bpp_id'] = $bpp->id;
        $validated['created_by'] = auth()->id();
        $validated['source_type'] = 'youtube';
        $validated['status'] = 'pending';
        $validated['show_on_homepage'] = false; // default false for BPP submissions
        $validated['sort_order'] = 0;

        Video::create($validated);

        return redirect()->route('bpp.video.index')->with('success', 'Video edukasi berhasil ditambahkan dan sedang menunggu persetujuan Admin.');
    }

    public function edit(Video $video)
    {
        $bpp = auth()->user()->bpp;
        if ($video->bpp_id !== $bpp->id) abort(403, 'Anda tidak memiliki akses ke video ini.');

        return view('bpp.videos.form', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $bpp = auth()->user()->bpp;
        if ($video->bpp_id !== $bpp->id) abort(403, 'Anda tidak memiliki akses ke video ini.');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
        ]);

        // If they edit, return it to pending state
        $validated['status'] = 'pending';

        $video->update($validated);

        return redirect()->route('bpp.video.index')->with('success', 'Video edukasi berhasil diperbarui dan dikembalikan ke status Menunggu Persetujuan.');
    }

    public function destroy(Video $video)
    {
        $bpp = auth()->user()->bpp;
        if ($video->bpp_id !== $bpp->id) abort(403, 'Anda tidak memiliki akses ke video ini.');

        $video->delete();
        return redirect()->route('bpp.video.index')->with('success', 'Video edukasi berhasil dihapus.');
    }
}
