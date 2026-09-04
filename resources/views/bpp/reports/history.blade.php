@extends('layouts.app')
@section('title', 'Riwayat Laporan')
@section('header_title', 'Riwayat Laporan BPP')
@section('content')
<div class="card card-filter">
    <form action="{{ route('bpp.laporan.history') }}" method="GET" class="grid-2" style="gap:var(--sp-4);align-items:end;">
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Filter Status Laporan</label><select name="status" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"><option value="">Semua Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Menunggu Validasi</option><option value="approved" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option><option value="revision" {{ request('status')=='revision'?'selected':'' }}>Perlu Revisi</option><option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option></select></div>
        <div><button type="submit" class="btn btn-primary">Filter Data</button></div>
    </form>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-header-title">Riwayat Seluruh Laporan</h4></div>
    <div class="table-responsive"><table class="table" style="min-width:900px;"><thead><tr><th>No</th><th>Penyuluh</th><th>Jenis & Judul Permintaan</th><th>Waktu Upload Terakhir</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($submissions as $i => $sub)
    <tr>
        <td>{{ $submissions->firstItem() + $i }}</td>
        <td style="font-weight:600;">{{ $sub->penyuluh->name }}</td>
        <td><div>{{ $sub->reportRequest->title }}</div><div style="font-size:0.8rem;color:var(--color-text-muted);">{{ $sub->reportRequest->reportType->name }}</div></td>
        <td><span style="font-size:0.85rem;">{{ $sub->latestVersion ? $sub->latestVersion->created_at->format('d M Y H:i') : '-' }}</span></td>
        <td>
            @if($sub->status==='pending')<span class="badge badge-warning">Menunggu</span>
            @elseif($sub->status==='approved')<span class="badge badge-success">Disetujui</span>
            @elseif($sub->status==='revision')<span class="badge badge-danger">Revisi</span>
            @elseif($sub->status==='rejected')<span class="badge badge-danger">Ditolak</span>@endif
        </td>
        <td><a href="{{ route('bpp.laporan.upload', [$sub->report_request_id, $sub->penyuluh_id]) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;">Detail</a></td>
    </tr>
    @empty <tr><td colspan="6" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Belum ada riwayat laporan yang diupload.</td></tr>@endforelse
    </tbody></table></div>
    @if($submissions->hasPages())<div style="padding:var(--sp-4);border-top:1px solid var(--color-border-light);">{{ $submissions->links() }}</div>@endif
</div>
@endsection
