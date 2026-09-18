<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = \App\Models\Bpp::orderBy('code');
        
        if ($user->role === 'admin_tim_kerja') {
            $query->whereIn('id', $user->bpp_binaans->pluck('id')->toArray());
        }
        
        $bpps = $query->get();
        return view('admin.bpp.index', compact('bpps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role === 'admin_tim_kerja') {
            abort(403, 'Unauthorized Access.');
        }
        return view('admin.bpp.form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin_tim_kerja') {
            abort(403, 'Unauthorized Access.');
        }

        $validated = $request->validate([
            'code' => 'required|unique:bpps,code',
            'name' => 'required|string|max:150',
            'district' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'contact_person' => 'nullable|string|max:150',
            'working_area' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        
        \App\Models\Bpp::create($validated);

        return redirect()->route('admin.bpp.index')->with('success', 'Data BPP berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $bpp = \App\Models\Bpp::findOrFail($id);
        
        if (auth()->user()->role === 'admin_tim_kerja') {
            if (!in_array($bpp->id, auth()->user()->bpp_binaans->pluck('id')->toArray())) {
                abort(403, 'Unauthorized Access. BPP ini bukan binaan Anda.');
            }
        }

        return view('admin.bpp.form', compact('bpp'));
    }

    public function update(Request $request, string $id)
    {
        $bpp = \App\Models\Bpp::findOrFail($id);

        if (auth()->user()->role === 'admin_tim_kerja') {
            if (!in_array($bpp->id, auth()->user()->bpp_binaans->pluck('id')->toArray())) {
                abort(403, 'Unauthorized Access. BPP ini bukan binaan Anda.');
            }
        }

        $isAdmin = auth()->user()->role === 'admin';

        $rules = [
            'address' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'contact_person' => 'nullable|string|max:150',
            'working_area' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'google_maps_url' => 'nullable|url',
        ];

        if ($isAdmin) {
            $rules['code'] = 'required|unique:bpps,code,'.$id;
            $rules['name'] = 'required|string|max:150';
            $rules['district'] = 'required|string|max:100';
            $rules['status'] = 'required|in:active,inactive';
        }

        $validated = $request->validate($rules);

        if (!$isAdmin) {
            $validated['code'] = $bpp->code;
            $validated['name'] = $bpp->name;
            $validated['district'] = $bpp->district;
            $validated['status'] = $bpp->status;
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        
        $bpp->update($validated);

        return redirect()->route('admin.bpp.index')->with('success', 'Data BPP berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        if (auth()->user()->role === 'admin_tim_kerja') {
            abort(403, 'Unauthorized Access. Anda tidak dapat menghapus BPP.');
        }

        $bpp = \App\Models\Bpp::findOrFail($id);
        
        // Hide BPP
        $bpp->update(['status' => 'inactive']);

        // Hide Penyuluh
        $bpp->penyuluhs()->update(['status' => 'inactive']);

        // Hide News
        \App\Models\News::where('bpp_id', $bpp->id)
            ->whereIn('status', ['published', 'pending_approval'])
            ->update(['status' => 'rejected']);

        // Hide Videos
        \App\Models\Video::where('bpp_id', $bpp->id)
            ->whereIn('status', ['active', 'pending'])
            ->update(['status' => 'rejected', 'show_on_homepage' => false]);

        return redirect()->route('admin.bpp.index')->with('success', 'Data BPP dan seluruh data terkait berhasil disembunyikan (dinonaktifkan).');
    }

    public function export()
    {
        $user = auth()->user();
        $query = \App\Models\Bpp::orderBy('code');
        if ($user->role === 'admin_tim_kerja') {
            $query->whereIn('id', $user->bpp_binaans->pluck('id')->toArray());
        }
        $bpps = $query->get();
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Data_BPP_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Kode BPP', 'Nama BPP', 'Kecamatan', 'Alamat', 'Telepon', 'Email', 'Kontak Person', 'Status'];

        $callback = function() use($bpps, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bpps as $bpp) {
                fputcsv($file, [
                    $bpp->code,
                    $bpp->name,
                    $bpp->district,
                    $bpp->address,
                    $bpp->phone,
                    $bpp->email,
                    $bpp->contact_person,
                    $bpp->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
