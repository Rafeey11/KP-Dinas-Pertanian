@extends('layouts.app')
@section('title', isset($document) ? 'Edit Dokumen' : 'Upload Dokumen Baru')
@section('header_title', isset($document) ? 'Edit Informasi Dokumen' : 'Upload Dokumen Bank Data')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h4 class="card-header-title">{{ isset($document) ? 'Edit Dokumen' : 'Form Upload Dokumen' }}</h4>
    </div>
    
    <div class="card-body" style="padding: var(--sp-6);">
        @if($errors->any())
            <div style="background: #FEE2E2; color: #991B1B; padding: var(--sp-3); border-radius: var(--rounded); margin-bottom: var(--sp-4); font-size: 0.85rem;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($document) ? route('admin.documents.update', $document) : route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($document)) @method('PUT') @endif

            <div class="form-group mb-4">
                <label class="form-label">Judul Dokumen <span style="color:red;">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $document->title ?? '') }}" required placeholder="Contoh: Juknis Penyuluhan 2026">
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Opsional: Keterangan mengenai dokumen ini">{{ old('description', $document->description ?? '') }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">File Dokumen {!! !isset($document) ? '<span style="color:red;">*</span>' : '' !!}</label>
                @if(isset($document))
                    <div style="margin-bottom: 8px; font-size: 0.85rem;">
                        File saat ini: <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">Lihat File ({{ $document->formatted_size }})</a><br>
                        <span style="color: var(--color-text-muted);">Abaikan input ini jika tidak ingin mengubah file.</span>
                    </div>
                @endif
                <input type="file" name="document_file" class="form-control" {{ !isset($document) ? 'required' : '' }}>
                <div class="form-text" style="margin-top: 4px; font-size: 0.8rem; color: var(--color-text-muted);">Maksimal ukuran file: 10MB.</div>
            </div>

            <div style="display: flex; gap: var(--sp-2); justify-content: flex-end; margin-top: var(--sp-6);">
                <a href="{{ route('admin.documents.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> {{ isset($document) ? 'Simpan Perubahan' : 'Upload Dokumen' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
