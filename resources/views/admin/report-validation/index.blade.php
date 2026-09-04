@extends('layouts.app')
@section('title', 'Validasi Laporan')
@section('header_title', 'Validasi Laporan Penyuluh')
@section('content')
<div class="card card-filter">
    <form action="{{ route('admin.report-validation.index') }}" method="GET" class="grid-4" style="gap:var(--sp-4);align-items:end;">
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Status Laporan</label><select name="status" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"><option value="">Semua Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Menunggu</option><option value="approved" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option><option value="revision" {{ request('status')=='revision'?'selected':'' }}>Revisi</option><option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option></select></div>
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">BPP</label><select name="bpp_id" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"><option value="">Semua BPP</option>@foreach($bpps as $bpp)<option value="{{ $bpp->id }}" {{ request('bpp_id')==$bpp->id?'selected':'' }}>{{ $bpp->name }}</option>@endforeach</select></div>
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Penyuluh</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"></div>
        <div><button type="submit" class="btn btn-primary" style="width:100%;">Filter Data</button></div>
    </form>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-header-title">Daftar Laporan Masuk</h4></div>
    <div class="table-responsive"><table class="table" style="min-width:1000px;"><thead><tr><th>No</th><th>Penyuluh & BPP</th><th>Laporan & Periode</th><th>Versi</th><th>Waktu Upload</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($submissions as $i => $sub)
    <tr>
        <td>{{ $submissions->firstItem() + $i }}</td>
        <td><div style="font-weight:600;">{{ $sub->penyuluh->name }}</div><div style="font-size:0.8rem;color:var(--color-text-muted);">{{ $sub->bpp->name }}</div></td>
        <td><div>{{ $sub->reportRequest->title }}</div><div style="font-size:0.8rem;color:var(--color-text-muted);">{{ $sub->reportRequest->reportType->name }}</div></td>
        <td><span class="badge" style="background:#E5E7EB;color:#374151;">v{{ $sub->latestVersion->version_number ?? '-' }}</span></td>
        <td><span style="font-size:0.85rem;">{{ $sub->latestVersion ? $sub->latestVersion->created_at->format('d M Y H:i') : '-' }}</span></td>
        <td>
            @if($sub->status==='pending')<span class="badge badge-warning">Menunggu</span>
            @elseif($sub->status==='approved')<span class="badge badge-success">Disetujui</span>
            @elseif($sub->status==='revision')<span class="badge badge-danger">Revisi</span>
            @elseif($sub->status==='rejected')<span class="badge badge-danger">Ditolak</span>@endif
        </td>
        <td><a href="{{ route('admin.report-validation.show', $sub) }}" class="btn btn-sm btn-primary" style="padding:4px 10px;font-size:0.75rem;">Validasi</a></td>
    </tr>
    @empty <tr><td colspan="7" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Tidak ada data laporan.</td></tr>@endforelse
    </tbody></table></div>
    @if($submissions->hasPages())<div style="padding:var(--sp-4);border-top:1px solid var(--color-border-light);">{{ $submissions->links() }}</div>@endif
</div>
@endsection
