<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $bpp = auth()->user()->bpp;
        if (!$bpp) {
            abort(403, 'Akun Anda belum ditautkan ke profil BPP manapun.');
        }

        $query = Document::with('creator');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest()->paginate(12);

        return view('bpp.documents.index', compact('documents'));
    }
}
