@extends('layouts.app')
@section('title', isset($video) ? 'Edit Video' : 'Tambah Video')
@section('header_title', 'Kelola Video YouTube')
@section('content')
<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);box-shadow:var(--shadow-sm);overflow:hidden;max-width:700px;">
    <div class="card-header"><h4 class="card-header-title">{{ isset($video) ? 'Edit' : 'Tambah' }} Video</h4><a href="{{ route('admin.videos.index') }}" class="btn btn-sm btn-outline">Kembali</a></div>
    <div style="padding:var(--sp-6);">
        @if($errors->any())<div style="background:#FEE2E2;color:#991B1B;padding:var(--sp-3);border-radius:var(--rounded);margin-bottom:var(--sp-6);"><ul style="margin:0;padding-left:1.5rem;font-size:0.85rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}" method="POST">@csrf @if(isset($video))@method('PUT')@endif
            
            <div style="margin-bottom:var(--sp-4);"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Judul Video *</label><input type="text" name="title" value="{{ old('title', $video->title ?? '') }}" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;"></div>
            <div style="margin-bottom:var(--sp-4);"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Link YouTube *</label><input type="url" name="video_url" value="{{ old('video_url', $video->video_url ?? '') }}" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;" placeholder="https://www.youtube.com/watch?v=..."></div>
            <div style="margin-bottom:var(--sp-4);"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Keterangan/Deskripsi Singkat</label><textarea name="description" rows="3" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;resize:vertical;">{{ old('description', $video->description ?? '') }}</textarea></div>
            
            <div class="grid-2" style="margin-bottom:var(--sp-4);">
                <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Terkait BPP? (Opsional)</label><select name="bpp_id" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;"><option value="">-- Tidak Terkait (Pusat) --</option>@foreach($bpps as $b)<option value="{{ $b->id }}" {{ old('bpp_id', $video->bpp_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>@endforeach</select></div>
                <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Urutan Tampil</label><input type="number" name="sort_order" value="{{ old('sort_order', $video->sort_order ?? 0) }}" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;"></div>
            </div>

            <div class="grid-2" style="margin-bottom:var(--sp-6);">
                <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Tampilkan di Beranda? *</label><select name="show_on_homepage" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;"><option value="1" {{ old('show_on_homepage', $video->show_on_homepage ?? 0) == 1 ? 'selected' : '' }}>Ya</option><option value="0" {{ old('show_on_homepage', $video->show_on_homepage ?? 0) == 0 ? 'selected' : '' }}>Tidak (Hanya di menu Video)</option></select></div>
                <div><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Status *</label><select name="status" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;"><option value="active" {{ old('status', $video->status ?? 'active') == 'active' ? 'selected' : '' }}>Aktif</option><option value="inactive" {{ old('status', $video->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option></select></div>
            </div>

            <div style="display:flex;gap:var(--sp-3);justify-content:flex-end;padding-top:var(--sp-4);border-top:1px solid var(--color-border-light);"><a href="{{ route('admin.videos.index') }}" class="btn btn-outline" style="padding:8px 16px;">Batal</a><button type="submit" class="btn btn-primary" style="padding:8px 24px;">Simpan</button></div>
        </form></div></div>
@endsection
