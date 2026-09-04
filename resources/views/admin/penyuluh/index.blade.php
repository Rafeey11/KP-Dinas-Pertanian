@extends('layouts.app')
@section('title', 'Data Seluruh Penyuluh')
@section('header_title', 'Master Data Penyuluh')
@section('content')
<div class="card card-filter">
    <form action="{{ route('admin.penyuluh.index') }}" method="GET" class="grid-4" style="gap:var(--sp-4);align-items:end;">
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Asal BPP</label><select name="bpp_id" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"><option value="">Semua BPP</option>@foreach($bpps as $bpp)<option value="{{ $bpp->id }}" {{ request('bpp_id')==$bpp->id?'selected':'' }}>{{ $bpp->name }}</option>@endforeach</select></div>
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Status</label><select name="status" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"><option value="">Semua Status</option><option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option><option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Nonaktif</option></select></div>
        <div><label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Pencarian</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIP..." style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);"></div>
        <div><button type="submit" class="btn btn-primary" style="width:100%;">Filter Data</button></div>
    </form>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--sp-4) var(--sp-6);">
        <h4 class="card-header-title">Data Seluruh Penyuluh</h4>
        <div style="display: flex; gap: var(--sp-2);">
            <a href="{{ route('admin.penyuluh.export') }}" class="btn btn-sm btn-outline">
                <i class="fa-solid fa-file-csv"></i> Download CSV
            </a>
            <a href="{{ route('admin.penyuluh.create') }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Penyuluh
            </a>
        </div>
    </div>
    @if(session('success'))<div style="margin:var(--sp-4) var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif
    <div class="table-responsive"><table class="table" style="min-width:1000px;"><thead><tr><th>No</th><th>BPP</th><th>Nama / NIP</th><th>Jabatan</th><th>Status Pegawai</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($penyuluhs as $i => $p)
    <tr>
        <td>{{ $penyuluhs->firstItem() + $i }}</td>
        <td>{{ $p->bpp->name ?? '-' }}</td>
        <td><div style="font-weight:600;">{{ $p->name }}</div><div style="font-size:0.8rem;color:var(--color-text-muted);">{{ $p->nip ?? 'Tanpa NIP' }}</div></td>
        <td>{{ $p->position }}</td>
        <td>{{ $p->employment_status }}</td>
        <td>@if($p->status==='active')<span class="badge badge-success">Aktif</span>@else<span class="badge" style="background:#FEE2E2;color:#991B1B;">Nonaktif</span>@endif</td>
        <td>
            <a href="{{ route('admin.penyuluh.edit', $p) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;"><i class="fa-solid fa-pen"></i></a>
            <form action="{{ route('admin.penyuluh.destroy', $p) }}" method="POST" style="display:inline-block;" class="form-delete"> @csrf @method('DELETE') <button type="submit" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;"><i class="fa-solid fa-trash"></i></button></form>
        </td>
    </tr>
    @empty <tr><td colspan="7" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Tidak ada data penyuluh.</td></tr>@endforelse
    </tbody></table></div>
    @if($penyuluhs->hasPages())<div style="padding:var(--sp-4);border-top:1px solid var(--color-border-light);">{{ $penyuluhs->links() }}</div>@endif
</div>
@endsection
