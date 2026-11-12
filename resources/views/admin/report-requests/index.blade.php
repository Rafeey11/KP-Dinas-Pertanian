@extends('layouts.app')
@section('title', 'Permintaan Laporan')
@section('header_title', 'Kelola Permintaan Laporan')
@section('content')
<div class="card">
    <div class="card-header"><h4 class="card-header-title">Daftar Permintaan Laporan</h4><a href="{{ route('admin.report-requests.create') }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> Buat Permintaan</a></div>
    @if(session('success'))<div style="margin:var(--sp-4) var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif
    <div class="table-responsive"><table class="table" style="min-width:900px;"><thead><tr><th>No</th><th>Judul</th><th>Jenis</th><th>Periode</th><th>Deadline</th><th>Status</th><th>Laporan Masuk</th><th>Aksi</th></tr></thead><tbody>
    @forelse($requests as $i => $req)
    <tr>
        <td>{{ $i+1 }}</td>
        <td style="font-weight:600;"><a href="{{ route('admin.report-requests.show', $req) }}" style="color:var(--color-primary);text-decoration:none;">{{ $req->title }}</a></td>
        <td>{{ $req->reportType->name ?? '-' }}</td>
        <td>{{ $req->period }} {{ $req->period_year }}</td>
        <td>
            <span style="font-size:0.85rem;{{ $req->isOverdue() ? 'color:#DC2626;font-weight:600;' : '' }}">{{ $req->deadline->format('d M Y') }}</span>
            @if($req->isOverdue())<br><span class="badge" style="background:#FEE2E2;color:#991B1B;font-size:0.7rem;">Lewat Deadline</span>@endif
        </td>
        <td>@if($req->status==='active')<span class="badge badge-success">Aktif</span>@else<span class="badge" style="background:#E5E7EB;color:#374151;">Ditutup</span>@endif</td>
        <td><span class="badge badge-info">{{ $req->submissions_count }}</span></td>
        <td>
            <div style="display:flex;gap:4px;">
                <a href="{{ route('admin.report-requests.show', $req) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;" title="Lihat Progress"><i class="fa-solid fa-chart-bar"></i></a>
                <a href="{{ route('admin.report-requests.edit', $req) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;"><i class="fa-solid fa-pen"></i></a>
                <form action="{{ route('admin.report-requests.destroy', $req) }}" method="POST" style="margin:0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan laporan ini? Semua data laporan yang masuk juga akan terhapus!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;color:var(--color-danger);border-color:var(--color-danger);" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </td>
    </tr>
    @empty <tr><td colspan="8" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Belum ada permintaan laporan.</td></tr>@endforelse
    </tbody></table></div></div>
@endsection
