<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh;
use App\Models\Bpp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PenyuluhController extends Controller
{
    public function index(Request $request)
    {
        $query = Penyuluh::with('bpp');

        if ($request->filled('bpp_id')) {
            $query->where('bpp_id', $request->bpp_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penyuluhs = $query->orderBy('name')->paginate(25);
        $bpps = Bpp::orderBy('name')->get();

        return view('admin.penyuluh.index', compact('penyuluhs', 'bpps'));
    }

    public function create()
    {
        $bpps = Bpp::orderBy('name')->get();
        return view('admin.penyuluh.form', compact('bpps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bpp_id' => 'required|exists:bpps,id',
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
        $validated['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);
        
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('penyuluhs', 'public');
        }

        Penyuluh::create($validated);
        return redirect()->route('admin.penyuluh.index')->with('success', 'Data penyuluh berhasil ditambahkan.');
    }

    public function edit(Penyuluh $penyuluh)
    {
        $bpps = Bpp::orderBy('name')->get();
        return view('admin.penyuluh.form', compact('penyuluh', 'bpps'));
    }

    public function update(Request $request, Penyuluh $penyuluh)
    {
        $validated = $request->validate([
            'bpp_id' => 'required|exists:bpps,id',
            'nip' => 'nullable|unique:penyuluhs,nip,' . $penyuluh->id,
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
        $validated['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);
        
        if ($request->hasFile('photo')) {
            if ($penyuluh->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($penyuluh->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($penyuluh->photo);
            }
            $validated['photo'] = $request->file('photo')->store('penyuluhs', 'public');
        }

        $penyuluh->update($validated);
        return redirect()->route('admin.penyuluh.index')->with('success', 'Data penyuluh berhasil diperbarui.');
    }

    public function destroy(Penyuluh $penyuluh)
    {
        if ($penyuluh->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($penyuluh->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($penyuluh->photo);
        }
        $penyuluh->delete();
        return redirect()->route('admin.penyuluh.index')->with('success', 'Data penyuluh berhasil dihapus.');
    }

    public function export()
    {
        $penyuluhs = Penyuluh::with('bpp')->orderBy('name')->get();
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Data_Penyuluh_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['NIP', 'Nama Lengkap', 'Unit BPP', 'Jabatan', 'Status Pegawai', 'Wilayah Kerja (Desa/Kelurahan)', 'Kelompok Tani Binaan', 'Telepon', 'Email', 'Status Aktif'];

        $callback = function() use($penyuluhs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($penyuluhs as $p) {
                fputcsv($file, [
                    $p->nip,
                    $p->name,
                    $p->bpp ? $p->bpp->name : '-',
                    $p->position,
                    $p->employment_status,
                    $p->village_area,
                    $p->farmer_group,
                    $p->phone,
                    $p->email,
                    $p->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
