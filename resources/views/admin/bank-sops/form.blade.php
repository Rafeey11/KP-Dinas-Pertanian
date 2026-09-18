@extends('layouts.app')

@section('title', isset($bankSop) ? 'Edit Bank SOP' : 'Tambah Bank SOP')
@section('header_title', isset($bankSop) ? 'Edit Bank SOP' : 'Tambah Bank SOP')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h4 class="card-title m-0">{{ isset($bankSop) ? 'Edit' : 'Tambah' }} Bank SOP</h4>
    </div>
    <div class="card-body">
        <form action="{{ isset($bankSop) ? route('admin.bank-sops.update', $bankSop->id) : route('admin.bank-sops.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($bankSop)) @method('PUT') @endif
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $bankSop->title ?? '') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $bankSop->category ?? '') }}" placeholder="Contoh: SOP Administrasi, Surat Keputusan" required>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe SOP <span class="text-danger">*</span></label>
                        <select name="type" id="typeSelect" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="document" {{ old('type', $bankSop->type ?? '') == 'document' ? 'selected' : '' }}>Dokumen (PDF/Word/Excel)</option>
                            <option value="link" {{ old('type', $bankSop->type ?? '') == 'link' ? 'selected' : '' }}>Link Eksternal (GForm/Drive/YouTube)</option>
                            <option value="template" {{ old('type', $bankSop->type ?? '') == 'template' ? 'selected' : '' }}>Template Dokumen</option>
                            <option value="internal_form" {{ old('type', $bankSop->type ?? '') == 'internal_form' ? 'selected' : '' }}>Form Isian Internal (Website)</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="fileInputWrapper" style="display: none;">
                        <label class="form-label">Upload File @if(!isset($bankSop)) <span class="text-danger">*</span> @endif</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                        @if(isset($bankSop) && $bankSop->file_path)
                            <small class="text-muted d-block mt-1">File saat ini: {{ $bankSop->file_name }}</small>
                        @endif
                        @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="urlInputWrapper" style="display: none;">
                        <label class="form-label">URL Eksternal <span class="text-danger">*</span></label>
                        <input type="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $bankSop->url ?? '') }}" placeholder="https://...">
                        @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $bankSop->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', $bankSop->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $bankSop->status ?? '') == 'published' ? 'selected' : '' }}>Published (Aktif)</option>
                            <option value="inactive" {{ old('status', $bankSop->status ?? '') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="archived" {{ old('status', $bankSop->status ?? '') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batas Waktu (Deadline) (Opsional)</label>
                        <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline', isset($bankSop) && $bankSop->deadline ? $bankSop->deadline->format('Y-m-d\TH:i') : '') }}">
                        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target BPP <span class="text-danger">*</span></label>
                        <select name="target_type" id="targetTypeSelect" class="form-select @error('target_type') is-invalid @enderror" required>
                            <option value="all_bpp" {{ old('target_type', $bankSop->target_type ?? '') == 'all_bpp' ? 'selected' : '' }}>Seluruh BPP</option>
                            <option value="selected_bpp" {{ old('target_type', $bankSop->target_type ?? '') == 'selected_bpp' ? 'selected' : '' }}>BPP Tertentu</option>
                        </select>
                        @error('target_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="bppSelectWrapper" style="display: none;">
                        <label class="form-label">Pilih BPP <span class="text-danger">*</span></label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                            @php
                                $selectedBpps = [];
                                if (isset($bankSop)) {
                                    $selectedBpps = collect($bankSop->targets)->pluck('bpp_id')->toArray();
                                }
                                $oldBpps = old('bpp_ids', $selectedBpps);
                            @endphp
                            @foreach($bpps as $bpp)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bpp_ids[]" value="{{ $bpp->id }}" id="bpp_{{ $bpp->id }}" {{ in_array($bpp->id, $oldBpps) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bpp_{{ $bpp->id }}">
                                    {{ $bpp->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('bpp_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.bank-sops.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('typeSelect');
    const fileWrapper = document.getElementById('fileInputWrapper');
    const urlWrapper = document.getElementById('urlInputWrapper');
    const fileInput = fileWrapper.querySelector('input');
    const urlInput = urlWrapper.querySelector('input');

    const targetTypeSelect = document.getElementById('targetTypeSelect');
    const bppSelectWrapper = document.getElementById('bppSelectWrapper');

    function toggleTypeFields() {
        const val = typeSelect.value;
        if (val === 'document' || val === 'template') {
            fileWrapper.style.display = 'block';
            urlWrapper.style.display = 'none';
            @if(!isset($bankSop)) fileInput.required = true; @endif
            urlInput.required = false;
        } else if (val === 'link') {
            fileWrapper.style.display = 'none';
            urlWrapper.style.display = 'block';
            fileInput.required = false;
            urlInput.required = true;
        } else {
            fileWrapper.style.display = 'none';
            urlWrapper.style.display = 'none';
            fileInput.required = false;
            urlInput.required = false;
        }
    }

    function toggleTargetFields() {
        if (targetTypeSelect.value === 'selected_bpp') {
            bppSelectWrapper.style.display = 'block';
        } else {
            bppSelectWrapper.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', toggleTypeFields);
    targetTypeSelect.addEventListener('change', toggleTargetFields);

    toggleTypeFields();
    toggleTargetFields();
});
</script>
@endsection
