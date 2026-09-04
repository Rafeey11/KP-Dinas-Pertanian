@extends('layouts.app')

@section('title', isset($video) ? 'Edit Video Edukasi' : 'Tambah Video Edukasi')
@section('header_title', 'Manajemen Video Edukasi')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden; max-width: 800px;">
    <div class="card-header">
        <h4 class="card-header-title">{{ isset($video) ? 'Edit Video Edukasi' : 'Tambah Video Edukasi Baru' }}</h4>
        <a href="{{ route('bpp.video.index') }}" class="btn btn-sm btn-outline">Kembali</a>
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

        <div style="background: #EFF6FF; color: #1E3A8A; padding: var(--sp-4); border-radius: var(--rounded-md); margin-bottom: var(--sp-6); border: 1px solid #BFDBFE;">
            <strong>Informasi:</strong> Video yang Anda kirimkan tidak akan langsung tampil di halaman depan (publik). Video akan masuk dalam status <em>Menunggu Persetujuan (Pending)</em> hingga Admin Kabupaten meninjau kelayakannya.
        </div>

        <form action="{{ isset($video) ? route('bpp.video.update', $video->id) : route('bpp.video.store') }}" method="POST">
            @csrf
            @if(isset($video))
                @method('PUT')
            @endif

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Judul Video *</label>
                <input type="text" name="title" value="{{ old('title', $video->title ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;" placeholder="Contoh: Tutorial Pembuatan Pupuk Organik">
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Link YouTube (URL) *</label>
                <input type="url" name="video_url" value="{{ old('video_url', $video->video_url ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;" placeholder="https://www.youtube.com/watch?v=...">
                <small style="color: var(--color-text-muted); font-size: 0.75rem; display: block; margin-top: 4px;">Pastikan link video dapat diakses oleh publik.</small>
            </div>

            <div style="margin-bottom: var(--sp-6);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Deskripsi Video</label>
                <textarea name="description" rows="4" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical;">{{ old('description', $video->description ?? '') }}</textarea>
            </div>

            <div style="display: flex; gap: var(--sp-3); justify-content: flex-end; padding-top: var(--sp-4); border-top: 1px solid var(--color-border-light);">
                <a href="{{ route('bpp.video.index') }}" class="btn btn-outline" style="padding: 8px 16px;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px;">Kirim Video</button>
            </div>
        </form>
    </div>
</div>
@endsection
