<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('creator')->latest()->paginate(15);
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_file' => 'required|file|max:10240', // max 10MB
        ]);

        $file = $request->file('document_file');
        
        $validated['file_path'] = $file->store('documents', 'public');
        $validated['file_type'] = $file->getClientOriginalExtension();
        $validated['file_size'] = $file->getSize();
        $validated['created_by'] = auth()->id();

        Document::create($validated);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function edit(Document $document)
    {
        return view('admin.documents.form', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('document_file')) {
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $file = $request->file('document_file');
            $validated['file_path'] = $file->store('documents', 'public');
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
        }

        $document->update($validated);

        return redirect()->route('admin.documents.index')->with('success', 'Informasi dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
