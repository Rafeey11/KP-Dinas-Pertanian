@extends('layouts.app')

@section('title', 'Kelola Berita')
@section('header_title', 'Manajemen Berita BPP')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--sp-4) var(--sp-6);">
        <h4 class="card-header-title">Daftar Berita & Kegiatan</h4>
        <a href="{{ route('bpp.berita.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-plus"></i> Tulis Berita
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
                    <th>No</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: flex; align-items: center; gap: 10px;">
                            @if($item->thumbnail)
                                <img src="{{ Storage::url($item->thumbnail) }}" alt="Thumbnail" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; flex-shrink: 0;">
                            @else
                                <div style="width: 40px; height: 40px; background: var(--color-bg); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); flex-shrink: 0;"><i class="fa-solid fa-image"></i></div>
                            @endif
                            <span style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->title }}</span>
                        </td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>
                            @if($item->status === 'published')
                                <span class="badge badge-success">Dipublikasi</span>
                            @elseif($item->status === 'draft')
                                <span class="badge badge-warning">Draft</span>
                            @else
                                <span class="badge" style="background: #E5E7EB; color: #374151;">Diarsipkan</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--color-text-muted);">
                            {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : '-' }}
                        </td>
                        <td>
                            <a href="{{ route('bpp.berita.edit', $item->id) }}" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.75rem;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('bpp.berita.destroy', $item->id) }}" method="POST" style="display: inline-block;" class="form-delete">
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
                        <td colspan="6" style="text-align: center; color: var(--color-text-muted); padding: var(--sp-6);">
                            Belum ada berita yang ditulis.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
