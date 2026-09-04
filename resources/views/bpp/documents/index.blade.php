@extends('layouts.app')
@section('title', 'Bank Data / E-Library')
@section('header_title', 'Perpustakaan Digital (Bank Data)')

@section('content')
<div class="card card-filter">
    <form action="{{ route('bpp.documents.index') }}" method="GET" style="display: flex; gap: var(--sp-4); align-items: flex-end; max-width: 500px;">
        <div style="flex: 1;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">Pencarian Dokumen</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Juknis, SOP, atau Materi..." style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded);">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Cari Data</button>
        </div>
    </form>
</div>

<div class="grid-3">
    @forelse($documents as $doc)
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div style="padding: var(--sp-5);">
                <div style="display: flex; align-items: center; gap: var(--sp-3); margin-bottom: var(--sp-4);">
                    <div style="width: 48px; height: 48px; background: #DBEAFE; color: #2563EB; border-radius: var(--rounded); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        @if(in_array(strtolower($doc->file_type), ['pdf']))
                            <i class="fa-solid fa-file-pdf"></i>
                        @elseif(in_array(strtolower($doc->file_type), ['doc', 'docx']))
                            <i class="fa-solid fa-file-word"></i>
                        @elseif(in_array(strtolower($doc->file_type), ['xls', 'xlsx']))
                            <i class="fa-solid fa-file-excel"></i>
                        @else
                            <i class="fa-solid fa-file-lines"></i>
                        @endif
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; line-height: 1.3;">{{ $doc->title }}</h3>
                        <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 4px;">{{ strtoupper($doc->file_type) }} • {{ $doc->formatted_size }}</div>
                    </div>
                </div>
                
                @if($doc->description)
                    <p style="font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.5; margin: 0;">{{ Str::limit($doc->description, 100) }}</p>
                @endif
                
                <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: var(--sp-4); padding-top: var(--sp-4); border-top: 1px solid var(--color-border-light);">
                    <i class="fa-regular fa-clock"></i> Diupload pada {{ $doc->created_at->format('d M Y') }}
                </div>
            </div>
            <div style="padding: var(--sp-4); background: var(--color-bg); border-top: 1px solid var(--color-border-light); text-align: center;">
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-download"></i> Download File
                </a>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: var(--sp-12); background: #fff; border-radius: var(--rounded-lg); border: 1px dashed var(--color-border);">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: var(--color-border); margin-bottom: var(--sp-4);"></i>
            <h3 style="margin: 0 0 var(--sp-2) 0;">Belum Ada Dokumen</h3>
            <p style="margin: 0; color: var(--color-text-muted);">Admin belum mengunggah file Juknis atau SOP ke dalam Bank Data.</p>
        </div>
    @endforelse
</div>

@if($documents->hasPages())
    <div style="padding-top: var(--sp-6);">
        {{ $documents->links() }}
    </div>
@endif

@endsection
