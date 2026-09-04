<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403, 'Anda tidak tertaut dengan BPP manapun.');

        return view('bpp.profile', compact('bpp'));
    }

    public function update(Request $request)
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) abort(403, 'Anda tidak tertaut dengan BPP manapun.');

        $validated = $request->validate([
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:150',
            'working_area' => 'nullable|string',
        ]);

        $bpp->update($validated);

        return redirect()->route('bpp.profil.edit')->with('success', 'Profil BPP berhasil diperbarui.');
    }
}
