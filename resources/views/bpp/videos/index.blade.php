@extends('layouts.app')

@section('title', 'Video Edukasi')
@section('header_title', 'Video Edukasi BPP')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--sp-4) var(--sp-6);">
        <h4 class="card-header-title">Daftar Video Edukasi Anda</h4>
        <a href="{{ route('bpp.video.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Video
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
                    <th>Judul Video</th>
                    <th>URL / Link</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($videos as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: 600; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->title }}</td>
                        <td><a href="{{ $item->video_url }}" target="_blank" style="color: var(--color-primary); text-decoration: underline; font-size: 0.85rem;">Lihat Video</a></td>
                        <td>
                            @if($item->status === 'active')
                                <span class="badge badge-success">Disetujui & Tampil</span>
                            @elseif($item->status === 'pending')
                                <span class="badge badge-warning">Menunggu Persetujuan</span>
                            @elseif($item->status === 'rejected')
                                <span class="badge" style="background: #FEE2E2; color: #991B1B;">Ditolak</span>
                            @else
                                <span class="badge" style="background: #E5E7EB; color: #374151;">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--color-text-muted);">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <a href="{{ route('bpp.video.edit', $item->id) }}" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.75rem;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('bpp.video.destroy', $item->id) }}" method="POST" style="display: inline-block;" class="form-delete">
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
                            Belum ada video yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
