@extends('layouts.app')

@section('title', isset($newsItem) ? 'Edit Berita' : 'Tulis Berita')
@section('header_title', 'Manajemen Berita BPP')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden; max-width: 800px;">
    <div class="card-header">
        <h4 class="card-header-title">{{ isset($newsItem) ? 'Edit Berita' : 'Tulis Berita Baru' }}</h4>
        <a href="{{ route('bpp.berita.index') }}" class="btn btn-sm btn-outline">Kembali</a>
    </div>

    <div style="padding: var(--sp-6);">
        @if($errors->any())
            <div style="background: #FEE2E2; color: #991B1B; padding: var(--sp-3) var(--sp-4); border-radius: var(--rounded); margin-bottom: var(--sp-6);">
                <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.85rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($newsItem) ? route('bpp.berita.update', $newsItem->id) : route('bpp.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($newsItem))
                @method('PUT')
            @endif

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Thumbnail / Foto Berita</label>
                @if(isset($newsItem) && $newsItem->thumbnail)
                    <div style="margin-bottom: var(--sp-2);">
                        <img src="{{ Storage::url($newsItem->thumbnail) }}" alt="Thumbnail" style="max-height: 120px; border-radius: var(--rounded); border: 1px solid var(--color-border);">
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                <small style="color: var(--color-text-muted); font-size: 0.75rem;">Format JPG/PNG, maksimal 2MB. Boleh dikosongkan.</small>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Judul Berita *</label>
                <input type="text" name="title" value="{{ old('title', $newsItem->title ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Kategori *</label>
                    <select name="category_id" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $newsItem->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Status Publikasi *</label>
                    <select name="status" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="draft" {{ old('status', $newsItem->status ?? '') === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                        <option value="pending_approval" {{ old('status', $newsItem->status ?? '') === 'pending_approval' ? 'selected' : '' }}>Kirim untuk Persetujuan Admin</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Link YouTube (Opsional)</label>
                <input type="url" name="video_url" value="{{ old('video_url', $newsItem->video_url ?? '') }}" placeholder="Contoh: https://www.youtube.com/watch?v=..." style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                <small style="color: var(--color-text-muted); font-size: 0.75rem;">Tambahkan link video YouTube yang berkaitan dengan berita.</small>
            </div>

            <div style="margin-bottom: var(--sp-6);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Isi Berita / Kegiatan *</label>
                <textarea name="content" required rows="10" style="width: 100%; padding: var(--sp-3); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical; line-height: 1.6;">{{ old('content', $newsItem->content ?? '') }}</textarea>
                <small style="color: var(--color-text-muted); font-size: 0.75rem; display: block; margin-top: 4px;">Gunakan paragraf yang jelas untuk menjelaskan kegiatan BPP Anda.</small>
            </div>

            <div style="display: flex; gap: var(--sp-3); justify-content: flex-end; padding-top: var(--sp-4); border-top: 1px solid var(--color-border-light);">
                <a href="{{ route('bpp.berita.index') }}" class="btn btn-outline" style="padding: 8px 16px;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px;">Simpan Berita</button>
            </div>
        </form>
    </div>
</div>
@endsection
