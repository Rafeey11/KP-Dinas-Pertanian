@extends('layouts.app')
@section('title', 'Persetujuan Berita')
@section('header_title', 'Kelola Publikasi Berita')
@section('content')
<div class="card card-filter">
    <form action="{{ route('admin.news.index') }}" method="GET" class="grid-2" style="gap:var(--sp-4);align-items:end;max-width:500px;">
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Status Persetujuan</label><select name="status" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"><option value="">Semua Status</option><option value="pending_approval" {{ request('status')=='pending_approval'?'selected':'' }}>Menunggu Persetujuan</option><option value="published" {{ request('status')=='published'?'selected':'' }}>Disetujui & Dipublikasikan</option><option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option></select></div>
        <div><button type="submit" class="btn btn-primary">Filter Data</button></div>
    </form>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-header-title">Daftar Kiriman Berita BPP</h4></div>
    @if(session('success'))<div style="margin:var(--sp-4) var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif
    <div class="table-responsive"><table class="table" style="min-width:900px;"><thead><tr><th>No</th><th>Judul Berita</th><th>Kategori</th><th>BPP Pengirim</th><th>Tanggal Kirim</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($news as $i => $item)
    <tr>
        <td>{{ $news->firstItem() + $i }}</td>
        <td style="font-weight:600;">{{ Str::limit($item->title, 50) }}</td>
        <td>{{ $item->category->name ?? '-' }}</td>
        <td>{{ $item->bpp->name ?? '-' }}</td>
        <td><span style="font-size:0.85rem;">{{ $item->created_at->format('d M Y') }}</span></td>
        <td style="white-space: nowrap;">
            @if($item->status==='pending_approval')<span class="badge badge-warning">Menunggu</span>
            @elseif($item->status==='published')<span class="badge badge-success">Dipublikasikan</span>
            @elseif($item->status==='rejected')<span class="badge badge-danger">Ditolak</span>@endif
        </td>
        <td style="display: flex; gap: 6px; align-items: center; white-space: nowrap;">
            <a href="{{ route('admin.news.show', $item) }}" class="btn btn-sm btn-primary" style="padding:4px 10px;font-size:0.75rem;">Review</a>
            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" style="margin: 0; display: inline-flex;" class="form-delete" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');"> 
                @csrf 
                @method('DELETE') 
                <button type="submit" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5; display: inline-flex; align-items: center; height: 26px;"><i class="fa-solid fa-trash"></i></button>
            </form>
        </td>
    </tr>
    @empty <tr><td colspan="7" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Belum ada kiriman berita.</td></tr>@endforelse
    </tbody></table></div>
    @if($news->hasPages())<div style="padding:var(--sp-4);border-top:1px solid var(--color-border-light);">{{ $news->links() }}</div>@endif
</div>
@endsection
