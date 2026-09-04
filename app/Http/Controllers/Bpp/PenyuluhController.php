<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenyuluhController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403, 'User tidak terkait dengan BPP manapun.');

        $penyuluhs = \App\Models\Penyuluh::where('bpp_id', $bpp->id)->orderBy('name')->get();
        return view('bpp.penyuluh.index', compact('penyuluhs', 'bpp'));
    }

    public function create()
    {
        return view('bpp.penyuluh.form');
    }

    public function store(Request $request)
    {
        $bpp = auth()->user()->bpp;
        
        $validated = $request->validate([
            'nip' => 'nullable|unique:penyuluhs,nip',
            'name' => 'required|string|max:150',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string',
            'working_area' => 'nullable|string',
            'village_area' => 'nullable|string',
            'farmer_group' => 'nullable|string',
            'is_public' => 'required|boolean',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:1024',
        ]);

        $validated['bpp_id'] = $bpp->id;
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']) . '-' . rand(100, 999);
        
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('penyuluhs', 'public');
        }
        
        \App\Models\Penyuluh::create($validated);

        return redirect()->route('bpp.penyuluh.index')->with('success', 'Data Penyuluh berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $penyuluh = \App\Models\Penyuluh::where('bpp_id', auth()->user()->bpp_id)->findOrFail($id);
        return view('bpp.penyuluh.form', compact('penyuluh'));
    }

    public function update(Request $request, string $id)
    {
        $penyuluh = \App\Models\Penyuluh::where('bpp_id', auth()->user()->bpp_id)->findOrFail($id);

        $validated = $request->validate([
            'nip' => 'nullable|unique:penyuluhs,nip,'.$id,
            'name' => 'required|string|max:150',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string',
            'working_area' => 'nullable|string',
            'village_area' => 'nullable|string',
            'farmer_group' => 'nullable|string',
            'is_public' => 'required|boolean',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:1024',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']) . '-' . rand(100, 999);
        
        if ($request->hasFile('photo')) {
            if ($penyuluh->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($penyuluh->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($penyuluh->photo);
            }
            $validated['photo'] = $request->file('photo')->store('penyuluhs', 'public');
        }
        
        $penyuluh->update($validated);

        return redirect()->route('bpp.penyuluh.index')->with('success', 'Data Penyuluh berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $penyuluh = \App\Models\Penyuluh::where('bpp_id', auth()->user()->bpp_id)->findOrFail($id);
        
        if ($penyuluh->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($penyuluh->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($penyuluh->photo);
        }
        
        $penyuluh->delete();

        return redirect()->route('bpp.penyuluh.index')->with('success', 'Data Penyuluh berhasil dihapus.');
    }
}
