@extends('layouts.app')
@section('title', 'Jenis Laporan')
@section('header_title', 'Kelola Jenis Laporan')
@section('content')
<div class="card">
    <div class="card-header"><h4 class="card-header-title">Daftar Jenis Laporan</h4><a href="{{ route('admin.report-types.create') }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> Tambah Jenis</a></div>
    @if(session('success'))<div style="margin:var(--sp-4) var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif
    <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Nama Jenis Laporan</th><th>Deskripsi</th><th>Jumlah Permintaan</th><th>Aksi</th></tr></thead><tbody>
    @forelse($types as $i => $type)
    <tr><td>{{ $i+1 }}</td><td style="font-weight:600;">{{ $type->name }}</td><td style="color:var(--color-text-muted);font-size:0.85rem;">{{ Str::limit($type->description, 80) }}</td><td><span class="badge badge-info">{{ $type->report_requests_count }}</span></td>
    <td><a href="{{ route('admin.report-types.edit', $type) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;"><i class="fa-solid fa-pen"></i></a>
    <form action="{{ route('admin.report-types.destroy', $type) }}" method="POST" style="display:inline-block;" class="form-delete"> @csrf @method('DELETE') <button type="submit" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;"><i class="fa-solid fa-trash"></i></button></form></td></tr>
    @empty <tr><td colspan="5" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Belum ada jenis laporan.</td></tr>@endforelse
    </tbody></table></div></div>
@endsection
