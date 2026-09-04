@extends('layouts.app')
@section('title', 'Manajemen Bank Data (E-Library)')
@section('header_title', 'Bank Data & SOP')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--sp-4) var(--sp-6);">
        <h4 class="card-header-title">Dokumen & Bank Data</h4>
        <a href="{{ route('admin.documents.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-upload"></i> Upload Dokumen
        </a>
    </div>
    
    @if(session('success'))
        <div style="margin: var(--sp-4) var(--sp-6); background: #DCFCE7; color: #166534; padding: var(--sp-3); border-radius: var(--rounded); font-size: 0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table" style="min-width: 800px;">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Judul Dokumen</th>
                    <th>Format & Ukuran</th>
                    <th>Tgl Upload</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $index => $doc)
                    <tr>
                        <td>{{ $documents->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $doc->title }}</div>
                            <div style="font-size: 0.8rem; color: var(--color-text-muted);">{{ Str::limit($doc->description, 60) }}</div>
                        </td>
                        <td>
                            <span class="badge badge-info" style="text-transform: uppercase;">{{ $doc->file_type }}</span>
                            <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 4px;">{{ $doc->formatted_size }}</div>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem;">{{ $doc->created_at->format('d M Y') }}</div>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.75rem;">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <a href="{{ route('admin.documents.edit', $doc) }}" class="btn btn-sm btn-primary" style="padding: 4px 10px; font-size: 0.75rem; margin: 0 4px;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus dokumen ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="padding: 4px 10px; font-size: 0.75rem; background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--color-text-muted); padding: var(--sp-6);">
                            Belum ada dokumen di Bank Data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($documents->hasPages())
        <div style="padding: var(--sp-4); border-top: 1px solid var(--color-border-light);">
            {{ $documents->links() }}
        </div>
    @endif
</div>
@endsection
