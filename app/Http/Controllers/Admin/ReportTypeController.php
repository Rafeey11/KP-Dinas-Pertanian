<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportTypeController extends Controller
{
    public function index()
    {
        $types = ReportType::withCount('reportRequests')->orderBy('name')->get();
        return view('admin.report-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.report-types.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:report_types,name',
            'description' => 'nullable|string',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        ReportType::create($validated);
        return redirect()->route('admin.report-types.index')->with('success', 'Jenis laporan berhasil ditambahkan.');
    }

    public function edit(ReportType $reportType)
    {
        return view('admin.report-types.form', compact('reportType'));
    }

    public function update(Request $request, ReportType $reportType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:report_types,name,' . $reportType->id,
            'description' => 'nullable|string',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $reportType->update($validated);
        return redirect()->route('admin.report-types.index')->with('success', 'Jenis laporan berhasil diperbarui.');
    }

    public function destroy(ReportType $reportType)
    {
        $reportType->delete();
        return redirect()->route('admin.report-types.index')->with('success', 'Jenis laporan berhasil dihapus.');
    }
}
